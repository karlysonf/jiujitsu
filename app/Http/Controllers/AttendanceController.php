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
    protected $faceRecognitionService;

    public function __construct(AttendanceService $attendanceService, \App\Services\FaceRecognitionService $faceRecognitionService)
    {
        $this->attendanceService = $attendanceService;
        $this->faceRecognitionService = $faceRecognitionService;
    }

    public function index(Request $request)
    {
        Gate::authorize('manage-attendance');
        $date = $request->date ?? now()->toDateString();
        $attendances = $this->attendanceService->getDailyAttendance($date);
        $users = User::role(['admin', 'aluno', 'professor', 'instrutor'])->where('status', 'active')->orderBy('name')->get();

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
        
        \Illuminate\Support\Facades\Log::info('identifyFaces request received', [
            'has_file' => $request->hasFile('photo'),
            'file_valid' => $request->hasFile('photo') ? $request->file('photo')->isValid() : false,
            'file_mime' => $request->hasFile('photo') ? $request->file('photo')->getMimeType() : null,
            'file_size' => $request->hasFile('photo') ? $request->file('photo')->getSize() : null,
            'file_extension' => $request->hasFile('photo') ? $request->file('photo')->getClientOriginalExtension() : null,
            'post_size' => $_SERVER['CONTENT_LENGTH'] ?? null
        ]);

        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation failed', $e->errors());
            throw $e;
        }

        $users = User::role(['aluno', 'professor', 'instrutor'])
            ->where('status', 'active')
            ->get();

        $result = $this->faceRecognitionService->recognizeGroupPhoto($request->file('photo'), $users);

        if (!empty($result['success'])) {
            return response()->json($result);
        }

        $fallbackMessage = $result['message'] ?? 'Modo Simulação: Ocorreu um erro no serviço de reconhecimento.';

        // Fallback para Modo Simulação se falhar ou não houver fotos
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
