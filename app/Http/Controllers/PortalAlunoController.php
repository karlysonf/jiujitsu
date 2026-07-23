<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;


class PortalAlunoController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('portal.login')->withErrors(['email' => 'Acesso negado.']);
        }

        // Frequência de Hoje
        $hasCheckedInToday = $user->attendances()->whereDate('date', Carbon::today())->exists();

        // Financeiro - Separar Pagas e A Vencer
        $pendingPayments = $user->payments()
            ->whereIn('status', ['pending', 'late'])
            ->orderBy('due_date', 'asc')
            ->get();

        $paidPayments = $user->payments()
            ->where('status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('portal.dashboard', compact('user', 'hasCheckedInToday', 'pendingPayments', 'paidPayments'));
    }

    public function checkIn()
    {
        $user = auth()->user();

        // Verifica se é Segunda (1), Quarta (3) ou Sexta (5)
        $dayOfWeek = Carbon::today()->dayOfWeekIso; // 1 = Monday, 7 = Sunday
        if (!in_array($dayOfWeek, [1, 3, 5])) {
            return back()->with('error', 'O check-in só é permitido às Segundas, Quartas e Sextas-feiras.');
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

    public function payments()
    {
        $user = auth()->user();
        $payments = $user->payments()->orderBy('due_date', 'desc')->get();
        return view('portal.payments', compact('user', 'payments'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('users/photos', 'public');
        
        $user->update(['photo' => $path]);

        app(\App\Services\FaceRecognitionService::class)->extractAndSaveEmbedding($user);

        return back()->with('success', 'Foto de perfil atualizada com sucesso!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}
