<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        Gate::authorize('manage-finance');
        
        $payments = Payment::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('due_date', 'desc')
            ->paginate(15);

        $users = User::role('aluno')->orderBy('name')->get();
        
        // Simple stats for the dashboard
        $stats = [
            'total_today' => Payment::whereDate('payment_date', now())->sum('amount'),
            'total_pending' => Payment::where('status', 'pending')->sum('amount'),
            'adimplencia' => 94.2, // Placeholder or calculated if needed
        ];

        return view('payments.index', compact('payments', 'users', 'stats'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-finance');
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_id' => 'nullable|exists:payments,id',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'payment_method' => 'required|string',
            'reference_month' => 'required|string',
            'notes' => 'nullable|string',
            'gateway_transaction_id' => 'nullable|string',
            'idempotency_key' => 'nullable|string',
        ]);

        try {
            $this->paymentService->registerPayment($data);
            return redirect()->back()->with('success', 'Pagamento registrado com sucesso!');
        } catch (UniqueConstraintViolationException $e) {
            Log::warning("Tentativa de registro de pagamento duplicado: " . $e->getMessage());
            return redirect()->back()->with('warning', 'Este pagamento já foi processado anteriormente.');
        } catch (\Exception $e) {
            Log::error("Erro ao registrar pagamento: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o pagamento. Tente novamente.');
        }
    }

    public function getOpenPaymentsByUser(User $user)
    {
        Gate::authorize('manage-finance');
        
        $payments = $user->payments()
            ->whereIn('status', ['pending', 'late'])
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'due_date' => $payment->due_date->format('Y-m-d'),
                    'due_date_formatted' => $payment->due_date->format('d/m/Y'),
                    'reference_month' => $payment->reference_month,
                    'reference_month_formatted' => ucfirst(\Carbon\Carbon::parse($payment->reference_month . '-01')->translatedFormat('F/Y')),
                ];
            });

        return response()->json($payments);
    }

    public function userHistory(User $user)
    {
        Gate::authorize('manage-finance');
        $payments = $user->payments()->orderBy('reference_month', 'desc')->get();
        return view('payments.student_history', compact('user', 'payments'));
    }

    public function generateBilling()
    {
        Gate::authorize('manage-finance');
        \App\Jobs\GenerateMonthlyPayments::dispatch();
        return redirect()->back()->with('success', 'Geração de mensalidades iniciada em background!');
    }

    public function destroy(Request $request, Payment $payment)
    {
        abort_unless($request->user() && $request->user()->hasAnyRole(['root', 'admin']), 403, 'Acesso negado.');

        $payment->delete();

        return redirect()->back()->with('success', 'Pagamento/Mensalidade excluído com sucesso!');
    }
}
