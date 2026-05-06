<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Aluno - CT Denyson Anderson</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Usando Tailwind CSS via CDN para rápida prototipação de interface modular e responsiva -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="text-gray-800">

    <!-- Header / Navbar -->
    <nav class="bg-blue-800 text-white shadow-md">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-ninja text-xl"></i>
                    <span class="font-bold text-lg">Portal do Aluno</span>
                </div>
                <div>
                     <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white hover:text-gray-200 font-medium text-sm flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <!-- Saudação -->
        <h1 class="text-2xl font-bold mb-6 text-gray-900">Olá, {{ explode(' ', $user->name)[0] }}! 🥋</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative flex items-center gap-3 mb-6" role="alert">
                <i class="fas fa-check-circle"></i>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative flex items-center gap-3 mb-6" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span class="block sm:inline font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card de Frequência -->
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Frequência</h2>
                <p class="text-gray-500 text-sm mb-6">Registre sua presença diária nos treinos da academia. Oss!</p>
                
                <form action="{{ route('portal.checkin') }}" method="POST" class="w-full">
                    @csrf
                    @if($hasCheckedInToday)
                        <button type="button" disabled class="w-full bg-gray-300 text-gray-600 font-bold py-3 px-4 rounded-lg cursor-not-allowed flex items-center justify-center gap-2 transition">
                            <i class="fas fa-check"></i> Presença Confirmada ✅
                        </button>
                    @else
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow transform hover:-translate-y-0.5 transition flex items-center justify-center gap-2">
                            Marcar Presença no Treino de Hoje
                        </button>
                    @endif
                </form>
            </div>

            <!-- Resumo Perfil -->
            <div class="bg-white rounded-xl shadow p-6 border border-gray-100 flex flex-col justify-center">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">Seus Dados</h2>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-gray-500"><i class="fas fa-envelope mr-2"></i> E-mail:</span>
                        <span class="font-medium text-gray-800">{{ $user->email ?? 'Não cadastrado' }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-gray-500"><i class="fas fa-medal mr-2"></i> Faixa:</span>
                        <span class="font-medium text-gray-800">{{ $user->belt }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-gray-500"><i class="fas fa-calendar-alt mr-2"></i> Início:</span>
                        <span class="font-medium text-gray-800">{{ $user->start_date?->format('d/m/Y') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-gray-500"><i class="fas fa-info-circle mr-2"></i> Status:</span>
                        @if($user->status === 'active')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold uppercase tracking-wide">Ativo</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold uppercase tracking-wide">Inativo</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <!-- Card Financeiro -->
        <div class="mt-8 bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="bg-gray-50 p-4 border-b flex items-center gap-3">
                <i class="fas fa-wallet text-gray-600 text-xl"></i>
                <h2 class="text-lg font-bold text-gray-800">Mensalidades</h2>
            </div>
            
            <div class="p-6">
                <!-- Seção A Vencer / Pendentes -->
                <div class="mb-8">
                    <h3 class="text-md font-bold text-orange-600 mb-3 flex items-center gap-2 border-b border-orange-100 pb-2">
                        <i class="fas fa-exclamation-circle"></i> A Vencer / Pendentes
                    </h3>
                    
                    @if($pendingPayments->isEmpty())
                        <p class="text-sm text-gray-500 italic">Nenhuma fatura pendente.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($pendingPayments as $payment)
                                <li class="bg-orange-50 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-orange-100">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-lg">R$ {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500 uppercase font-medium">Ref: {{ \Carbon\Carbon::parse($payment->reference_month)->format('m/Y') }}</p>
                                    </div>
                                    <div class="text-left sm:text-right flex flex-col sm:items-end gap-1">
                                        <span class="text-orange-700 text-sm font-semibold flex items-center gap-1 sm:justify-end">
                                            <i class="far fa-calendar-alt"></i> Vence em: {{ \Carbon\Carbon::parse($payment->due_date)->format('d/m/Y') }}
                                        </span>
                                        @if(\Carbon\Carbon::parse($payment->due_date)->isPast() && $payment->status != 'paid')
                                            <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded font-bold">ATRASADA</span>
                                        @else
                                            <span class="bg-orange-200 text-orange-800 text-xs px-2 py-0.5 rounded font-bold uppercase">{{ $payment->status }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Seção Histórico Pagas -->
                <div>
                    <h3 class="text-md font-bold text-green-600 mb-3 flex items-center gap-2 border-b border-green-100 pb-2">
                        <i class="fas fa-history"></i> Histórico de Pagamentos
                    </h3>

                    @if($paidPayments->isEmpty())
                        <p class="text-sm text-gray-500 italic">Nenhum histórico encontrado.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($paidPayments as $payment)
                                <li class="bg-gray-50 hover:bg-gray-100 transition rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-gray-100">
                                    <div>
                                        <p class="font-semibold text-gray-700">R$ {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500 uppercase font-medium">Ref: {{ \Carbon\Carbon::parse($payment->reference_month)->format('m/Y') }}</p>
                                    </div>
                                    <div class="text-left sm:text-right flex flex-col sm:items-end gap-1">
                                        <span class="text-green-600 text-sm font-semibold flex items-center gap-1 sm:justify-end">
                                            <i class="fas fa-check"></i> Pago em: {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                        <span class="text-gray-400 text-xs flex items-center gap-1 sm:justify-end">
                                            <i class="fas fa-credit-card"></i> {{ $payment->payment_method ?? 'Transferência/Dinheiro' }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="text-center text-gray-400 text-xs mt-8 pb-8">
        Portal do Aluno &copy; {{ date('Y') }} - Todos os direitos reservados.
    </footer>

</body>
</html>
