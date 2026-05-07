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
}
