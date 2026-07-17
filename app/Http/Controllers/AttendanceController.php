<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        Gate::authorize('manage-attendance');
        $date = $request->date ?? now()->toDateString();
        $attendances = $this->attendanceService->getDailyAttendance($date);
        $users = User::role(['aluno', 'professor', 'instrutor'])->where('status', 'active')->orderBy('name')->get();

        return view('attendances.index', compact('attendances', 'users', 'date'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-attendance');
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'nullable|date',
        ]);

        $this->attendanceService->recordAttendance($request->user_id, $request->date);

        return redirect()->back()->with('success', 'Presença registrada com sucesso!');
    }

    public function destroy(Request $request)
    {
        Gate::authorize('manage-attendance');
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'nullable|date',
        ]);

        $this->attendanceService->removeAttendance($request->user_id, $request->date);

        return redirect()->back()->with('success', 'Presença removida com sucesso!');
    }

    public function bulkStore(Request $request)
    {
        Gate::authorize('manage-attendance');
        $request->validate([
            'date' => 'required|date',
            'present_users' => 'nullable|array',
            'present_users.*' => 'exists:users,id',
        ]);

        $date = $request->date;
        $presentUsers = $request->input('present_users', []);

        $activeUserIds = User::role(['aluno', 'professor', 'instrutor'])
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        foreach ($activeUserIds as $userId) {
            if (in_array($userId, $presentUsers)) {
                $this->attendanceService->recordAttendance($userId, $date);
            } else {
                $this->attendanceService->removeAttendance($userId, $date);
            }
        }

        return redirect()->back()->with('success', 'Presenças atualizadas com sucesso!');
    }

    public function identifyFaces(Request $request)
    {
        Gate::authorize('manage-attendance');
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240'
        ]);

        $users = User::role(['aluno', 'professor', 'instrutor'])
            ->where('status', 'active')
            ->get();

        $fallbackMessage = 'Modo Simulação: Nenhum aluno ativo possui foto de perfil salva no servidor para usar como referência no reconhecimento.';
            try {
                $referenceData = [];
                $hasReferencePhotos = false;
                
                foreach ($users as $user) {
                    if ($user->photo) {
                        $photoPath = storage_path('app/public/' . $user->photo);
                        if (file_exists($photoPath)) {
                            $data = base64_encode(file_get_contents($photoPath));
                            $referenceData[] = [
                                'id' => $user->id,
                                'image_base64' => $data
                            ];
                            $hasReferencePhotos = true;
                        }
                    }
                }

                if ($hasReferencePhotos) {
                    $groupPhoto = $request->file('photo');
                    
                    $faceServiceUrl = env('FACE_SERVICE_URL', 'http://127.0.0.1:8002') . '/recognize';
                    
                    $response = \Illuminate\Support\Facades\Http::attach(
                        'group_photo', file_get_contents($groupPhoto->getRealPath()), $groupPhoto->getClientOriginalName()
                    )->post($faceServiceUrl, [
                        'reference_data' => json_encode($referenceData)
                    ]);

                    if ($response->successful()) {
                        $result = $response->json();
                        \Illuminate\Support\Facades\Log::info('FaceAPI Success Response: ' . json_encode($result));

                        $identifiedIds = $result['identified_ids'] ?? [];

                        if (is_array($identifiedIds)) {
                            $validIds = $users->pluck('id')->toArray();
                            $identifiedIds = array_intersect($identifiedIds, $validIds);

                            return response()->json([
                                'success' => true,
                                'identified_ids' => array_values($identifiedIds),
                                'simulation' => false
                            ]);
                        } else {
                            \Illuminate\Support\Facades\Log::error('FaceAPI Parse Error: ' . json_encode($result));
                            $fallbackMessage = 'Modo Simulação: Falha ao processar resposta do microsserviço.';
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::error('FaceAPI Error Status ' . $response->status() . ': ' . $response->body());
                        $fallbackMessage = 'Modo Simulação: Erro no microsserviço de face (Status ' . $response->status() . ').';
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Face Identification Exception: ' . $e->getMessage());
                $fallbackMessage = 'Modo Simulação: Ocorreu um erro de comunicação com o microsserviço Python: ' . $e->getMessage();
            }
        // Fallback to Simulation Mode if API key is missing or failed
        $identifiedIds = [];
        if ($users->isNotEmpty()) {
            $count = min(rand(3, 5), $users->count());
            $identifiedIds = $users->random($count)->pluck('id')->toArray();
        }

        return response()->json([
            'success' => true,
            'identified_ids' => $identifiedIds,
            'simulation' => true,
            'message' => $fallbackMessage
        ]);
    }
}
