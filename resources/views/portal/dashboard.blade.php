<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Aluno - {{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #090d16; color: #f8fafc; }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="text-slate-100 bg-[#090d16] min-h-screen">

    <!-- Header / Navbar -->
    <nav class="bg-[#111726]/90 backdrop-blur-md text-white border-b border-white/10 shadow-lg sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-rose-600 to-rose-700 flex items-center justify-center shadow-md shadow-rose-600/30">
                        <i class="fas fa-shield-halved text-sm"></i>
                    </div>
                    <span class="font-['Outfit'] font-bold text-lg tracking-tight">Portal do Aluno</span>
                </div>
                <div>
                     <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-slate-300 hover:text-rose-400 font-semibold text-sm flex items-center gap-2 transition-colors">
                            <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <!-- Saudação com Foto -->
        <div class="flex items-center gap-4 mb-6">
            <form id="photo-form" action="{{ route('portal.update-photo') }}" method="POST" enctype="multipart/form-data" class="relative group cursor-pointer" onclick="document.getElementById('portal_photo_input').click()">
                @csrf
                <input type="file" id="portal_photo_input" name="photo" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden" onchange="document.getElementById('photo-form').submit()">
                
                @if($user->photo)
                    <img src="{{ Storage::disk('public')->url($user->photo) }}" alt="Foto do perfil" class="w-16 h-16 rounded-full object-cover shadow-lg border-2 border-rose-500/40 group-hover:opacity-75 transition">
                @else
                    <div class="w-16 h-16 rounded-full bg-[#182234] border-2 border-rose-500/40 flex items-center justify-center text-rose-400 text-2xl shadow-lg group-hover:bg-rose-500/20 transition">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                
                <!-- Ícone de câmera no hover -->
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/60 rounded-full backdrop-blur-sm">
                    <i class="fas fa-camera text-white text-sm"></i>
                </div>
            </form>

            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white">Olá, {{ explode(' ', $user->name)[0] }}! 🥋</h1>
                <p class="text-xs text-cyan-400 font-semibold tracking-wider uppercase mt-1">Atleta em Desenvolvimento</p>
            </div>
        </div>
        
        @error('photo')
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-xl flex items-center gap-3 mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span class="font-medium text-sm">{{ $message }}</span>
            </div>
        @enderror
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3 mb-6">
                <i class="fas fa-check-circle"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-xl flex items-center gap-3 mb-6">
                <i class="fas fa-exclamation-circle"></i>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card de Frequência -->
            <div class="bg-[#111726] rounded-2xl shadow-xl p-6 border border-white/10 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-cyan-500/10 border border-cyan-500/30 rounded-2xl flex items-center justify-center text-cyan-400 mb-4 shadow-lg shadow-cyan-500/10">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold mb-2 text-white">Frequência</h2>
                <p class="text-slate-400 text-xs mb-6">Registre sua presença diária nos treinos da academia. Oss!</p>
                
                <form action="{{ route('portal.checkin') }}" method="POST" class="w-full">
                    @csrf
                    @php
                        $dayOfWeek = \Carbon\Carbon::today()->dayOfWeekIso;
                        $canCheckIn = in_array($dayOfWeek, [1, 3, 5]); // Segunda, Quarta, Sexta
                    @endphp

                    @if($hasCheckedInToday)
                        <button type="button" disabled class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold py-3.5 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i> Presença Confirmada ✅
                        </button>
                    @elseif(!$canCheckIn)
                        <button type="button" disabled class="w-full bg-[#182234] border border-white/10 text-slate-500 font-bold py-3.5 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2" title="Treinos apenas às Segundas, Quartas e Sextas">
                            <i class="fas fa-ban"></i> Check-in Indisponível Hoje
                        </button>
                    @else
                        <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-rose-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-rose-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            Marcar Presença no Treino
                        </button>
                    @endif
                </form>
            </div>

            <!-- Resumo Perfil -->
            <div class="bg-[#111726] rounded-2xl shadow-xl p-6 border border-white/10 flex flex-col justify-center">
                <h2 class="text-lg font-bold mb-4 border-b border-white/10 pb-2 text-white">Seus Dados Cadastrais</h2>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-slate-400"><i class="fas fa-envelope mr-2 text-rose-500"></i> E-mail:</span>
                        <span class="font-medium text-slate-200">{{ $user->email ?? 'Não cadastrado' }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-slate-400"><i class="fas fa-medal mr-2 text-cyan-400"></i> Faixa:</span>
                        <span class="font-bold text-rose-400 uppercase">FAIXA {{ $user->belt }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-slate-400"><i class="fas fa-calendar-alt mr-2 text-cyan-400"></i> Início:</span>
                        <span class="font-medium text-slate-200">{{ $user->start_date?->format('d/m/Y') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-slate-400"><i class="fas fa-info-circle mr-2 text-rose-500"></i> Status:</span>
                        @if($user->status === 'active')
                            <span class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Ativo</span>
                        @else
                            <span class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">Inativo</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <!-- Alterar Senha -->
        <div class="mt-6 bg-[#111726] rounded-2xl shadow-xl p-6 border border-white/10 flex flex-col justify-center">
            <h2 class="text-lg font-bold mb-4 border-b border-white/10 pb-2 text-white"><i class="fas fa-lock mr-2 text-rose-500"></i> Alterar Senha</h2>
            
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-xl mb-4">
                    <ul class="list-disc pl-5 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('portal.change-password') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nova Senha</label>
                    <input type="password" name="password" id="password" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition" required minlength="8" placeholder="No mínimo 8 caracteres">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase mb-1">Confirmar Nova Senha</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition" required minlength="8" placeholder="Digite a senha novamente">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-rose-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-rose-600/30 transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Atualizar Senha
                    </button>
                </div>
            </form>
        </div>

        <!-- Card Financeiro (Oculto para Professores) -->
        @if(!$user->hasAnyRole(['professor', 'instrutor']))
        <div class="mt-8 bg-[#111726] rounded-2xl shadow-xl overflow-hidden border border-white/10">
            <div class="bg-[#182234] p-4 border-b border-white/10 flex items-center gap-3">
                <i class="fas fa-wallet text-rose-400 text-xl"></i>
                <h2 class="text-lg font-bold text-white">Mensalidades</h2>
            </div>
            
            <div class="p-6">
                <!-- Seção A Vencer / Pendentes -->
                <div class="mb-8">
                    <h3 class="text-md font-bold text-amber-400 mb-3 flex items-center gap-2 border-b border-white/10 pb-2">
                        <i class="fas fa-exclamation-circle"></i> A Vencer / Pendentes
                    </h3>
                    
                    @if($pendingPayments->isEmpty())
                        <p class="text-xs text-slate-500 italic">Nenhuma fatura pendente.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($pendingPayments as $payment)
                                <li class="bg-[#182234] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-amber-500/20">
                                    <div>
                                        <p class="font-extrabold text-amber-400 text-lg font-['Outfit']">R$ {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                        <p class="text-xs text-slate-400 uppercase font-medium">Ref: {{ \Carbon\Carbon::parse($payment->reference_month)->format('m/Y') }}</p>
                                    </div>
                                    <div class="text-left sm:text-right flex flex-col sm:items-end gap-1">
                                        <span class="text-amber-300 text-xs font-semibold flex items-center gap-1 sm:justify-end">
                                            <i class="far fa-calendar-alt"></i> Vence em: {{ \Carbon\Carbon::parse($payment->due_date)->format('d/m/Y') }}
                                        </span>
                                        @if(\Carbon\Carbon::parse($payment->due_date)->isPast() && $payment->status != 'paid')
                                            <span class="bg-rose-500/20 text-rose-400 text-xs px-2 py-0.5 rounded-full font-bold border border-rose-500/30">ATRASADA</span>
                                        @else
                                            <span class="bg-amber-500/20 text-amber-300 text-xs px-2 py-0.5 rounded-full font-bold border border-amber-500/30 uppercase">{{ $payment->status }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Seção Histórico Pagas -->
                <div>
                    <h3 class="text-md font-bold text-emerald-400 mb-3 flex items-center gap-2 border-b border-white/10 pb-2">
                        <i class="fas fa-history"></i> Histórico de Pagamentos
                    </h3>

                    @if($paidPayments->isEmpty())
                        <p class="text-xs text-slate-500 italic">Nenhum histórico encontrado.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($paidPayments as $payment)
                                <li class="bg-[#182234] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-white/5">
                                    <div>
                                        <p class="font-extrabold text-emerald-400 text-base font-['Outfit']">R$ {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                        <p class="text-xs text-slate-400 uppercase font-medium">Ref: {{ \Carbon\Carbon::parse($payment->reference_month)->format('m/Y') }}</p>
                                    </div>
                                    <div class="text-left sm:text-right flex flex-col sm:items-end gap-1">
                                        <span class="text-emerald-400 text-xs font-semibold flex items-center gap-1 sm:justify-end">
                                            <i class="fas fa-check"></i> Pago em: {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                        <span class="text-slate-400 text-xs flex items-center gap-1 sm:justify-end">
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
        @endif

    </main>

    <!-- Footer -->
    <footer class="text-center text-slate-500 text-xs mt-8 pb-8">
        Portal do Aluno &copy; {{ date('Y') }} - {{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}. Todos os direitos reservados.
    </footer>

</body>
</html>
