<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('aluno')) {
            $hasCheckedInToday = $user->attendances()->whereDate('date', Carbon::today())->exists();
            $recentAttendances = $user->attendances()->orderBy('date', 'desc')->take(3)->get();
            $attendancesCount = $user->attendances()->count();
            $attendancesThisMonth = $user->attendances()->whereMonth('date', Carbon::now()->month)->count();
            
            $nextPayment = $user->payments()->where('status', 'pending')->orderBy('due_date', 'asc')->first();
            $isFinancialOk = !$user->payments()->where('status', 'pending')->where('due_date', '<', Carbon::today())->exists();

            return view('dashboard-aluno', compact(
                'user', 
                'hasCheckedInToday', 
                'recentAttendances', 
                'attendancesCount', 
                'attendancesThisMonth',
                'nextPayment',
                'isFinancialOk'
            ));
        }

        $data = $this->dashboardService->getDashboardData();
        return view('dashboard', $data);
    }

    public function checkIn()
    {
        $user = auth()->user();
        if (!$user->hasRole('aluno')) {
            abort(403);
        }

        $alreadyCheckedIn = $user->attendances()->whereDate('date', Carbon::today())->exists();

        if ($alreadyCheckedIn) {
            return back()->with('error', 'Você já marcou presença hoje!');
        }

        $user->attendances()->create([
            'date' => Carbon::today(),
        ]);

        return back()->with('success', 'Presença confirmada no treino de hoje! Bom treino!');
    }
}
