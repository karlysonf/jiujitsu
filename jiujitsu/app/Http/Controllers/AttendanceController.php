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
        $users = User::role(['aluno', 'professor'])->where('status', 'active')->orderBy('name')->get();

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
}
