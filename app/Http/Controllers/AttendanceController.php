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
}
