<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;

class DashboardService
{
    public function getDashboardData()
    {
        $currentMonth = now()->format('Y-m');
        $today = now()->toDateString();

        // Financial Metrics
        $totalReceived = (float) Payment::where('status', 'paid')
            ->where(function ($q) use ($currentMonth) {
                $q->where('reference_month', $currentMonth)
                  ->orWhere(function ($sub) {
                      $sub->whereYear('payment_date', now()->year)
                          ->whereMonth('payment_date', now()->month);
                  });
            })->sum('amount');

        $totalPending = (float) Payment::where('status', 'pending')
            ->where(function ($q) use ($currentMonth) {
                $q->where('reference_month', $currentMonth)
                  ->orWhere(function ($sub) {
                      $sub->whereYear('due_date', now()->year)
                          ->whereMonth('due_date', now()->month);
                  });
            })
            ->where('due_date', '>=', $today)
            ->sum('amount');

        $totalLate = (float) Payment::where(function ($q) use ($today) {
            $q->where('status', 'late')
              ->orWhere(function ($q2) use ($today) {
                  $q2->where('status', 'pending')
                     ->where('due_date', '<', $today);
              });
        })->sum('amount');

        $pendingCount = Payment::where('status', 'pending')
            ->where(function ($q) use ($currentMonth) {
                $q->where('reference_month', $currentMonth)
                  ->orWhere(function ($sub) {
                      $sub->whereYear('due_date', now()->year)
                          ->whereMonth('due_date', now()->month);
                  });
            })
            ->where('due_date', '>=', $today)
            ->count();

        $lateCount = Payment::where(function ($q) use ($today) {
            $q->where('status', 'late')
              ->orWhere(function ($q2) use ($today) {
                  $q2->where('status', 'pending')
                     ->where('due_date', '<', $today);
              });
        })->count();

        // User Metrics
        $activeUsersCount = User::role(['aluno', 'professor', 'instrutor'])->where('status', 'active')->count();
        
        // Simplified Attendance Rate (Classes attended / total possible classes - hypothetical 12/month)
        $avgAttendance = \App\Models\Attendance::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count() / max($activeUsersCount, 1);
        $attendanceRate = min(round(($avgAttendance / 12) * 100), 100);

        // Monthly Flow (Last 6 months)
        $monthlyFlow = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStr = $month->format('Y-m');

            $monthReceived = (float) Payment::where('status', 'paid')
                ->where(function ($query) use ($monthStr, $month) {
                    $query->where('reference_month', $monthStr)
                          ->orWhere(function ($sub) use ($month) {
                              $sub->whereYear('payment_date', $month->year)
                                  ->whereMonth('payment_date', $month->month);
                          });
                })->sum('amount');

            $monthPending = (float) Payment::where('status', '!=', 'paid')
                ->where(function ($query) use ($monthStr, $month) {
                    $query->where('reference_month', $monthStr)
                          ->orWhere(function ($sub) use ($month) {
                              $sub->whereYear('due_date', $month->year)
                                  ->whereMonth('due_date', $month->month);
                          });
                })->sum('amount');

            $monthlyFlow[] = [
                'label'        => ucfirst(rtrim($month->translatedFormat('M'), '.')),
                'month_full'   => ucfirst($month->translatedFormat('F Y')),
                'year_month'   => $monthStr,
                'value'        => $monthReceived,
                'pending'      => $monthPending,
                'total_billed' => $monthReceived + $monthPending,
            ];
        }

        // Graduation Candidates (Students with most attendances this month)
        $graduationCandidates = User::role('aluno')
            ->where('status', 'active')
            ->withCount(['attendances' => function($q) {
                $q->whereMonth('date', now()->month);
            }])
            ->orderBy('attendances_count', 'desc')
            ->take(3)
            ->get();

        return [
            'total_received'        => $totalReceived ?? 0,
            'total_pending'         => $totalPending ?? 0,
            'total_late'            => $totalLate ?? 0,
            'pending_count'         => $pendingCount ?? 0,
            'late_count'            => $lateCount ?? 0,
            'active_users'          => $activeUsersCount,
            'attendance_rate'       => $attendanceRate,
            'monthly_flow'          => $monthlyFlow,
            'graduation_candidates' => $graduationCandidates,
            'recent_payments'       => Payment::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'all_students'          => User::role('aluno')->orderBy('name')->take(10)->get(),
        ];
    }
}
