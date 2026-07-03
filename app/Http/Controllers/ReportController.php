<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        Gate::authorize('view-reports');
        return view('reports.index');
    }

    public function monthly(Request $request)
    {
        Gate::authorize('view-reports');

        $validated = $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $month = $validated['month'] ?? now()->format('Y-m');

        $payments = Payment::with('user')
            ->where('reference_month', $month)
            ->where('status', 'paid')
            ->get();

        $total = $payments->sum('amount');

        return view('reports.monthly', compact('payments', 'total', 'month'));
    }
    public function delinquency(Request $request)
    {
        Gate::authorize('view-reports');

        $overduePayments = Payment::with('user')
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();

        $totalOverdue = $overduePayments->sum('amount');
        $overdueCount = $overduePayments->unique('user_id')->count();

        return view('reports.delinquency', compact('overduePayments', 'totalOverdue', 'overdueCount'));
    }

    public function attendance(Request $request)
    {
        Gate::authorize('view-reports');

        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $startDate = $validated['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $validated['end_date'] ?? now()->toDateString();

        // 1. Get attendances in date range
        $attendances = \App\Models\Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // 2. Group by user
        $attendanceByUser = $attendances->groupBy('user_id');

        // 3. Get all active tenant users who can check in
        $users = \App\Models\User::role(['aluno', 'professor', 'instrutor'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $reportData = [];
        foreach ($users as $user) {
            $userAttendances = $attendanceByUser->get($user->id, collect());
            $reportData[] = [
                'user' => $user,
                'presence_count' => $userAttendances->count(),
                'last_presence' => $userAttendances->first()?->date?->format('d/m/Y') ?? 'Nenhuma',
                'presence_dates' => $userAttendances->pluck('date')->map(fn($d) => $d->format('d/m/Y'))->toArray(),
            ];
        }

        // Sort by presence count descending
        usort($reportData, fn($a, $b) => $b['presence_count'] <=> $a['presence_count']);

        // Calculate consolidation metrics
        $totalPresences = $attendances->count();

        $dailyGroup = $attendances->groupBy(fn($a) => $a->date->toDateString());
        $daysCount = $dailyGroup->count() ?: 1;
        $avgPresencesPerDay = round($totalPresences / $daysCount, 1);

        $peakDay = 'Nenhum';
        $peakDayCount = 0;
        if ($dailyGroup->isNotEmpty()) {
            $peakDate = $dailyGroup->sortByDesc->count()->keys()->first();
            $peakDayCount = $dailyGroup->get($peakDate)->count();
            $peakDay = Carbon::parse($peakDate)->format('d/m/Y');
        }

        $mostFrequentUser = 'Nenhum';
        $mostFrequentCount = 0;
        if (!empty($reportData) && $reportData[0]['presence_count'] > 0) {
            $mostFrequentUser = $reportData[0]['user']->name;
            $mostFrequentCount = $reportData[0]['presence_count'];
        }

        return view('reports.attendance', compact(
            'reportData',
            'startDate',
            'endDate',
            'totalPresences',
            'avgPresencesPerDay',
            'peakDay',
            'peakDayCount',
            'mostFrequentUser',
            'mostFrequentCount'
        ));
    }
}

