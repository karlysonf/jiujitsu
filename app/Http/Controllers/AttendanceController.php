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

        $apiKey = config('services.gemini.key');
        $fallbackMessage = 'Modo Simulação: Adicione GEMINI_API_KEY no .env para reconhecimento facial real por IA.';

        if ($apiKey) {
            $fallbackMessage = 'Modo Simulação: Nenhum aluno ativo possui foto de perfil salva no servidor para usar como referência no reconhecimento.';
            try {
                $parts = [];
                $parts[] = ['text' => "Você é um assistente de reconhecimento facial para uma academia de Jiu-Jitsu. Vou lhe enviar as fotos individuais dos alunos (com seus respectivos IDs) e uma foto do grupo no tatame. Sua tarefa é identificar quais dos alunos cadastrados estão presentes na foto do grupo. Retorne estritamente um array JSON contendo os IDs dos alunos que você reconheceu na foto do grupo, no formato: [ID1, ID2, ID3]. Não retorne mais nada além do array JSON bruto (sem marcações markdown ```json ou explicações)."];

                $hasReferencePhotos = false;
                foreach ($users as $user) {
                    if ($user->photo) {
                        $photoPath = storage_path('app/public/' . $user->photo);
                        if (file_exists($photoPath)) {
                            $mime = mime_content_type($photoPath) ?: 'image/jpeg';
                            $data = base64_encode(file_get_contents($photoPath));
                            
                            $parts[] = ['text' => "Aluno ID: " . $user->id . ", Nome: " . $user->name];
                            $parts[] = [
                                'inlineData' => [
                                    'mimeType' => $mime,
                                    'data' => $data
                                ]
                            ];
                            $hasReferencePhotos = true;
                        }
                    }
                }

                if ($hasReferencePhotos) {
                    // Add the uploaded group photo
                    $groupPhoto = $request->file('photo');
                    $groupMime = $groupPhoto->getMimeType();
                    $groupData = base64_encode(file_get_contents($groupPhoto->getRealPath()));

                    $parts[] = ['text' => "Esta é a foto do grupo no tatame. Analise e identifique quais dos alunos listados anteriormente estão presentes nesta foto. Retorne estritamente o array JSON contendo os IDs dos alunos presentes."];
                    $parts[] = [
                        'inlineData' => [
                            'mimeType' => $groupMime,
                            'data' => $groupData
                        ]
                    ];

                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Content-Type' => 'application/json'
                    ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey, [
                        'contents' => [
                            [
                                'parts' => $parts
                            ]
                        ]
                    ]);

                    if ($response->successful()) {
                        $result = $response->json();
                        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                        
                        // Clean markdown blocks if any
                        $text = preg_replace('/```json\s*|```\s*/', '', trim($text));
                        $identifiedIds = json_decode($text, true);

                        if (is_array($identifiedIds)) {
                            $validIds = $users->pluck('id')->toArray();
                            $identifiedIds = array_intersect($identifiedIds, $validIds);

                            return response()->json([
                                'success' => true,
                                'identified_ids' => array_values($identifiedIds),
                                'simulation' => false
                            ]);
                        }
                    }
                    
                    \Illuminate\Support\Facades\Log::error('Gemini API Error: ' . $response->body());
                    $fallbackMessage = 'Modo Simulação: A API do Gemini retornou um erro ao processar as fotos.';
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Face Identification Exception: ' . $e->getMessage());
                $fallbackMessage = 'Modo Simulação: Ocorreu um erro ao processar o reconhecimento facial: ' . $e->getMessage();
            }
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
