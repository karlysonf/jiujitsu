<!DOCTYPE html>
<html class="dark" lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Acesso do Instrutor - {{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        obsidian: "#090d16",
                        card: "#111726",
                        cardLight: "#182234",
                        crimson: "#e11d48",
                        cyanTech: "#06b6d4",
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        body {
            background-color: #090d16;
            font-family: 'Inter', sans-serif;
            color: #f8fafc;
        }
    </style>
</head>

<body class="bg-[#090d16] font-sans text-slate-100 min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden selection:bg-rose-500 selection:text-white">
    <!-- Ambient Glow Effects -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-rose-600/15 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-cyan-500/15 rounded-full blur-[120px] pointer-events-none"></div>

    <main class="w-full max-w-[1100px] grid grid-cols-1 md:grid-cols-2 bg-[#111726] rounded-3xl overflow-hidden shadow-2xl min-h-[640px] border border-white/10 relative z-10">
        <!-- Left Side: Visual Narrative -->
        <section class="hidden md:flex flex-col relative bg-[#111726] p-8 md:p-12 justify-between border-r border-white/10 overflow-hidden">
            <!-- Background image with gradient overlay -->
            <div class="absolute inset-0 z-0">
                <img alt="Luta de Jiu-Jitsu no Tatame" class="w-full h-full object-cover brightness-50 contrast-125 hover:scale-105 transition-transform duration-700" src="{{ asset('images/jiujitsu_login_bg.jpg') }}" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#111726] via-[#111726]/75 to-[#111726]/30"></div>
            </div>

            <!-- Content Top -->
            <div class="z-10 relative">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-600 to-rose-700 flex items-center justify-center text-white shadow-lg shadow-rose-600/30">
                        @if(isset($currentTenant))
                        <span class="material-symbols-outlined text-xl">sports_martial_arts</span>
                        @else
                        <span class="material-symbols-outlined text-xl">admin_panel_settings</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-cyan-400 block">Gestão Combate</span>
                        <h1 class="text-white font-['Outfit'] font-black text-xl tracking-tight leading-none">{{ isset($currentTenant) ? $currentTenant->name : 'CT Combate' }}</h1>
                    </div>
                </div>

                <div class="mt-8">
                    @if(isset($currentTenant))
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-bold uppercase tracking-wider mb-3">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        Alta Performance
                    </div>
                    <h2 class="text-white font-['Outfit'] font-black text-3xl md:text-4xl leading-tight mb-3">Domine o fluxo da sua <span class="bg-gradient-to-r from-rose-500 to-rose-400 bg-clip-text text-transparent">Academia</span>.</h2>
                    <p class="text-slate-400 text-xs md:text-sm leading-relaxed max-w-md">A precisão de um faixa preta, agora para suas operações. Experimente a gestão de elite em combates e modalidades.</p>
                    @else
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-3">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        Global Admin
                    </div>
                    <h2 class="text-white font-['Outfit'] font-black text-3xl md:text-4xl leading-tight mb-3">Painel Administrativo <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">Global</span>.</h2>
                    <p class="text-slate-400 text-xs md:text-sm leading-relaxed max-w-md">Central de controle do Gestão Combate. Monitore inquilinos, planos e faturamento global.</p>
                    @endif
                </div>
            </div>

            <!-- Content Bottom: Bento Widgets -->
            <div class="z-10 relative pt-8">
                <div class="grid grid-cols-2 gap-3">
                    @if(isset($currentTenant))
                    <div class="bg-white/5 backdrop-blur-md p-3.5 rounded-2xl border border-white/10">
                        <span class="text-cyan-400 font-['Outfit'] font-extrabold text-xl block">1.2k+</span>
                        <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Alunos Matriculados</span>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-3.5 rounded-2xl border border-white/10">
                        <span class="text-rose-400 font-['Outfit'] font-extrabold text-xl block">98%</span>
                        <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Taxa de Retenção</span>
                    </div>
                    @else
                    <div class="bg-white/5 backdrop-blur-md p-3.5 rounded-2xl border border-white/10">
                        <span class="text-cyan-400 font-['Outfit'] font-extrabold text-xl block">SaaS Multi</span>
                        <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Multi-Inquilino</span>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-3.5 rounded-2xl border border-white/10">
                        <span class="text-rose-400 font-['Outfit'] font-extrabold text-xl block">Seguro</span>
                        <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Painel Criptografado</span>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Right Side: Login Form -->
        <section class="flex flex-col justify-center p-6 sm:p-10 md:p-12 lg:p-14 bg-[#111726] relative">
            <div class="max-w-[400px] mx-auto w-full">
                <!-- Mobile Logo -->
                <div class="md:hidden flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-600 to-rose-700 flex items-center justify-center text-white shadow-lg shadow-rose-600/30">
                        <span class="material-symbols-outlined text-xl">sports_martial_arts</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-cyan-400 block">Gestão Combate</span>
                        <h1 class="text-white font-['Outfit'] font-black text-lg">{{ isset($currentTenant) ? $currentTenant->name : 'CT Combate' }}</h1>
                    </div>
                </div>

                <header class="mb-8">
                    <h3 class="text-white font-['Outfit'] font-black text-2xl md:text-3xl tracking-tight mb-1">Acesso do Instrutor</h3>
                    <p class="text-slate-400 text-xs sm:text-sm">Insira suas credenciais para acessar o painel de gerenciamento.</p>
                </header>

                @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-rose-400">error</span>
                        {{ $errors->first() }}
                    </div>
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- CPF Field -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300 font-semibold text-xs block" for="login_identity">CPF ou Usuário</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-rose-400 transition-colors">
                                <span class="material-symbols-outlined text-sm">badge</span>
                            </div>
                            <input class="block w-full pl-10 pr-4 py-3 bg-[#090d16] border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none" id="login_identity" name="login_identity" placeholder="CPF ou Usuário" type="text" value="{{ old('login_identity') }}" required autofocus />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300 font-semibold text-xs block" for="password">Senha</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-rose-400 transition-colors">
                                <span class="material-symbols-outlined text-sm">lock</span>
                            </div>
                            <input class="block w-full pl-10 pr-10 py-3 bg-[#090d16] border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none" id="password" name="password" placeholder="••••••••••••" type="password" required />
                            <button class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-white transition-colors" type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined text-sm" id="password-toggle-icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input class="w-4 h-4 rounded border-white/20 bg-[#090d16] text-rose-600 focus:ring-rose-500 cursor-pointer transition-all" type="checkbox" name="remember" />
                            <span class="text-slate-400 text-xs font-medium group-hover:text-white transition-colors">Lembrar-me</span>
                        </label>
                        <a class="text-xs text-rose-400 font-semibold hover:text-rose-300 transition-colors" href="{{ route('password.request') }}">Esqueceu a senha?</a>
                    </div>

                    <!-- Login Button -->
                    <button class="w-full bg-gradient-to-r from-rose-600 to-rose-700 text-white py-3.5 rounded-xl font-['Outfit'] font-bold text-sm hover:shadow-lg hover:shadow-rose-600/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-2" type="submit">
                        Entrar no Sistema
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </button>
                </form>

                <!-- Footer Actions -->
                <footer class="mt-8 pt-6 border-t border-white/10 flex flex-col items-center gap-3">
                    <div class="flex flex-wrap justify-center gap-3">
                        <button onclick="toggleSupportModal(true)" type="button" class="flex items-center gap-1.5 text-slate-400 hover:text-white text-xs font-semibold px-4 py-2 rounded-xl bg-[#182234] border border-white/10 hover:bg-white/10 transition-all">
                            <span class="material-symbols-outlined text-base text-cyan-400">support_agent</span>
                            Suporte
                        </button>
                        <!--
                        <button onclick="toggleDemoModal(true)" type="button" class="flex items-center gap-1.5 text-slate-400 hover:text-white text-xs font-semibold px-4 py-2 rounded-xl bg-[#182234] border border-white/10 hover:bg-white/10 transition-all">
                            <span class="material-symbols-outlined text-base text-rose-400">play_circle</span>
                            Demonstração
                        </button>
                        -->
                    </div>
                </footer>
            </div>
        </section>
    </main>

    <!-- Support Modal Overlay -->
    <div id="support-modal" class="fixed inset-0 bg-[#090d16]/80 backdrop-blur-md z-50 overflow-y-auto p-4 hidden opacity-0 transition-opacity duration-300 flex justify-center items-start sm:items-center">
        <div class="bg-[#111726] rounded-2xl max-w-2xl w-full p-6 md:p-8 shadow-2xl relative border border-white/10 transform scale-95 transition-transform duration-300 my-8 sm:my-auto text-white">
            <!-- Close Button -->
            <button onclick="toggleSupportModal(false)" type="button" class="absolute top-4 right-4 p-2 rounded-xl hover:bg-white/10 text-slate-400 hover:text-white transition-colors" aria-label="Fechar">
                <span class="material-symbols-outlined">close</span>
            </button>

            <!-- Header -->
            <div class="flex items-center gap-3 mb-6">
                <button onclick="toggleSupportModal(false)" type="button" class="flex items-center justify-center p-2 rounded-xl bg-[#182234] border border-white/10 text-slate-300 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </button>
                <h2 class="text-xl font-['Outfit'] font-bold text-white">Suporte ao Usuário</h2>
            </div>

            <!-- Canais de Atendimento Section -->
            <div class="mb-6">
                <h3 class="text-base font-['Outfit'] font-bold text-white">Canais de Atendimento</h3>
                <p class="text-xs text-slate-400 mt-1">Selecione o canal mais adequado para sua dúvida</p>
            </div>

            <!-- Grid of Email and Phone Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Email Card -->
                <a href="mailto:karlysonsantosdev@gmail.com" class="flex flex-col items-center justify-center p-5 bg-[#182234] border border-white/10 rounded-2xl hover:border-cyan-500/50 transition-all text-center group">
                    <span class="material-symbols-outlined text-cyan-400 text-3xl mb-2 group-hover:scale-110 transition-transform">mail</span>
                    <span class="text-xs font-bold text-white">E-mail</span>
                    <span class="text-xs text-slate-400 mt-1 font-mono">karlysonsantosdev@gmail.com</span>
                </a>

                <!-- Phone Card -->
                <a href="tel:82987532852" class="flex flex-col items-center justify-center p-5 bg-[#182234] border border-white/10 rounded-2xl hover:border-cyan-500/50 transition-all text-center group">
                    <span class="material-symbols-outlined text-cyan-400 text-3xl mb-2 group-hover:scale-110 transition-transform">call</span>
                    <span class="text-xs font-bold text-white">Telefone</span>
                    <span class="text-xs text-slate-400 mt-1 font-mono">(82) 98753-2852</span>
                </a>
            </div>

            <!-- WhatsApp Card -->
            <div class="p-6 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex flex-col md:flex-row items-center gap-6 justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-emerald-400">chat</span>
                        <h4 class="text-sm font-['Outfit'] font-bold text-emerald-300">Atendimento via WhatsApp</h4>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">
                        Clique no botão abaixo para falar com nossa equipe diretamente no WhatsApp ou aponte a câmera do celular para o QR Code.
                    </p>
                    <a href="https://wa.me/5582987532852" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all shadow-lg text-xs">
                        <span class="material-symbols-outlined text-sm">chat</span>
                        Falar no WhatsApp
                    </a>
                </div>

                <!-- QR Code Section -->
                <div class="flex flex-col items-center justify-center p-3 bg-white rounded-xl shadow-md shrink-0">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https%3A%2F%2Fwa.me%2F5582987532852" alt="WhatsApp QR Code" class="w-28 h-28 object-contain" />
                    <span class="text-[9px] font-bold text-slate-700 uppercase mt-1 tracking-wider">Escaneie o QR Code</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Modal Overlay -->
    <dialog id="demo-modal" class="bg-[#111726] rounded-2xl max-w-5xl w-[calc(100%-2rem)] md:w-full p-0 shadow-2xl border border-white/10 backdrop:bg-[#090d16]/80 backdrop:backdrop-blur-md transform scale-95 transition-all duration-300 opacity-0 outline-none m-auto text-white">
        <div class="flex flex-col md:flex-row h-full min-h-[350px] md:min-h-[500px] md:h-[600px] overflow-hidden">
            <!-- Sidebar (Steps) -->
            <div class="hidden md:flex w-full md:w-72 bg-[#0d1320] p-6 border-r border-white/10 flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-rose-400">smart_display</span>
                            <h2 class="text-base font-['Outfit'] font-bold text-white">Tour do Sistema</h2>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button onclick="goToSlide(0)" id="demo-step-0" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-white/5 transition-all flex items-start gap-3 group">
                            <div class="p-2 rounded-lg bg-rose-500/10 text-rose-400">
                                <span class="material-symbols-outlined text-lg">dashboard</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-white">Painel Principal</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Indicadores em tempo real.</p>
                                <div class="w-full bg-[#182234] h-1 rounded-full mt-2 overflow-hidden relative">
                                    <div id="demo-progress-0" class="bg-rose-500 h-full w-0 transition-all duration-100 ease-linear"></div>
                                </div>
                            </div>
                        </button>

                        <button onclick="goToSlide(1)" id="demo-step-1" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-white/5 transition-all flex items-start gap-3 group">
                            <div class="p-2 rounded-lg bg-rose-500/10 text-rose-400">
                                <span class="material-symbols-outlined text-lg">group</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-white">Gestão de Alunos</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Listagem visual e faixas.</p>
                                <div class="w-full bg-[#182234] h-1 rounded-full mt-2 overflow-hidden relative">
                                    <div id="demo-progress-1" class="bg-rose-500 h-full w-0 transition-all duration-100 ease-linear"></div>
                                </div>
                            </div>
                        </button>

                        <button onclick="goToSlide(2)" id="demo-step-2" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-white/5 transition-all flex items-start gap-3 group">
                            <div class="p-2 rounded-lg bg-rose-500/10 text-rose-400">
                                <span class="material-symbols-outlined text-lg">person_add</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-white">Cadastro de Aluno</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Formulário inteligente.</p>
                                <div class="w-full bg-[#182234] h-1 rounded-full mt-2 overflow-hidden relative">
                                    <div id="demo-progress-2" class="bg-rose-500 h-full w-0 transition-all duration-100 ease-linear"></div>
                                </div>
                            </div>
                        </button>

                        <button onclick="goToSlide(3)" id="demo-step-3" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-white/5 transition-all flex items-start gap-3 group">
                            <div class="p-2 rounded-lg bg-rose-500/10 text-rose-400">
                                <span class="material-symbols-outlined text-lg">checklist</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-white">Controle de Presença</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Chamada inteligente por IA.</p>
                                <div class="w-full bg-[#182234] h-1 rounded-full mt-2 overflow-hidden relative">
                                    <div id="demo-progress-3" class="bg-rose-500 h-full w-0 transition-all duration-100 ease-linear"></div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                    <button onclick="toggleAutoplay()" type="button" class="flex items-center gap-1 bg-[#182234] px-3 py-1.5 rounded-full text-xs font-semibold text-white">
                        <span class="material-symbols-outlined text-sm" id="autoplay-icon">pause</span>
                        <span id="autoplay-text">Autoplay</span>
                    </button>
                    <span class="font-mono text-[10px]">Setas ➔ ⬅</span>
                </div>
            </div>

            <!-- Showcase Area -->
            <div class="flex-1 bg-[#111726] flex flex-col justify-between relative">
                <button onclick="toggleDemoModal(false)" type="button" class="hidden md:flex absolute top-4 right-4 z-10 items-center justify-center w-9 h-9 rounded-full bg-[#182234] border border-white/10 text-slate-400 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>

                <div class="flex-1 flex items-center justify-center p-6">
                    <div class="relative w-full h-full max-h-[420px] overflow-hidden rounded-xl border border-white/10 bg-[#090d16]">
                        <img src="{{ asset('images/demo/step1.png') }}" id="demo-img-0" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-100 transition-all duration-500" alt="Painel Principal">
                        <img src="{{ asset('images/demo/step3.png') }}" id="demo-img-1" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-0 transition-all duration-500 pointer-events-none" alt="Gestão de Alunos">
                        <img src="{{ asset('images/demo/step2.png') }}" id="demo-img-2" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-0 transition-all duration-500 pointer-events-none" alt="Cadastro de Aluno">
                        <img src="{{ asset('images/demo/step4.png') }}" id="demo-img-3" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-0 transition-all duration-500 pointer-events-none" alt="Controle de Presença">
                    </div>
                </div>

                <div class="p-4 md:p-6 bg-[#0d1320] border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="text-xs font-bold text-white" id="slide-title">Painel Principal</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5" id="slide-desc">Indicadores financeiros e de frequência em tempo real.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="prevSlide()" type="button" class="p-2 rounded-xl bg-[#182234] border border-white/10 text-slate-300 hover:text-white transition-all">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </button>
                        <button onclick="nextSlide()" type="button" class="p-2 rounded-xl bg-[#182234] border border-white/10 text-slate-300 hover:text-white transition-all">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </dialog>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerText = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerText = 'visibility';
            }
        }

        function toggleSupportModal(show) {
            const modal = document.getElementById('support-modal');
            const modalContent = modal.querySelector('div');
            if (show) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                }, 10);
            } else {
                modal.classList.add('opacity-0');
                modalContent.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        document.getElementById('support-modal').addEventListener('click', (e) => {
            const modal = document.getElementById('support-modal');
            if (e.target === modal) {
                toggleSupportModal(false);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('support-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    toggleSupportModal(false);
                }
            }
        });

        /* Demo Modal Scripts */
        let demoInterval = null;
        let currentDemoSlide = 0;
        const totalDemoSlides = 4;
        const demoSlideDuration = 5000;
        let demoAutoplayActive = true;
        let demoProgressInterval = null;

        const demoSlidesData = [
            {
                title: "Painel Principal",
                desc: "Indicadores financeiros e de frequência em tempo real."
            },
            {
                title: "Gestão de Alunos",
                desc: "Listagem visual de alunos ativos, inativos e graduações."
            },
            {
                title: "Cadastro de Aluno",
                desc: "Formulário inteligente com seleção de plano e graduação."
            },
            {
                title: "Controle de Presença",
                desc: "Chamada eletrônica rápida com status de presença."
            }
        ];

        function toggleDemoModal(show) {
            const dialog = document.getElementById('demo-modal');
            if (show) {
                dialog.showModal();
                setTimeout(() => {
                    dialog.classList.remove('scale-95', 'opacity-0');
                    dialog.classList.add('scale-100', 'opacity-100');
                }, 10);
                goToSlide(0);
                startAutoplay();
            } else {
                dialog.classList.remove('scale-100', 'opacity-100');
                dialog.classList.add('scale-95', 'opacity-0');
                stopAutoplay();
                setTimeout(() => {
                    dialog.close();
                }, 300);
            }
        }

        document.getElementById('demo-modal').addEventListener('click', (e) => {
            const dialog = document.getElementById('demo-modal');
            const dialogDimensions = dialog.getBoundingClientRect();
            if (
                e.clientX < dialogDimensions.left ||
                e.clientX > dialogDimensions.right ||
                e.clientY < dialogDimensions.top ||
                e.clientY > dialogDimensions.bottom
            ) {
                toggleDemoModal(false);
            }
        });

        document.getElementById('demo-modal').addEventListener('cancel', (e) => {
            e.preventDefault();
            toggleDemoModal(false);
        });

        document.addEventListener('keydown', (e) => {
            const dialog = document.getElementById('demo-modal');
            if (dialog && dialog.open) {
                if (e.key === 'ArrowRight') {
                    nextSlide();
                } else if (e.key === 'ArrowLeft') {
                    prevSlide();
                }
            }
        });

        function goToSlide(slideIndex) {
            for (let i = 0; i < totalDemoSlides; i++) {
                const btn = document.getElementById(`demo-step-${i}`);
                if (btn) {
                    btn.classList.remove('bg-white/10', 'border-white/10');
                    btn.classList.add('border-transparent');
                }
                
                const progress = document.getElementById(`demo-progress-${i}`);
                if (progress) {
                    progress.style.transition = 'none';
                    progress.style.width = '0%';
                }
                
                const img = document.getElementById(`demo-img-${i}`);
                if (img) {
                    img.classList.remove('opacity-100', 'scale-100');
                    img.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                }
            }

            currentDemoSlide = slideIndex;
            
            const activeBtn = document.getElementById(`demo-step-${slideIndex}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-white/10', 'border-white/10');
                activeBtn.classList.remove('border-transparent');
            }
            
            const activeImg = document.getElementById(`demo-img-${slideIndex}`);
            if (activeImg) {
                activeImg.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                activeImg.classList.add('opacity-100', 'scale-100');
            }

            document.getElementById('slide-title').innerText = `${demoSlidesData[slideIndex].title} (${slideIndex + 1}/${totalDemoSlides})`;
            document.getElementById('slide-desc').innerText = demoSlidesData[slideIndex].desc;

            if (demoAutoplayActive) {
                resetAndStartProgressBar(slideIndex);
            }
        }

        function nextSlide() {
            let next = (currentDemoSlide + 1) % totalDemoSlides;
            goToSlide(next);
        }

        function prevSlide() {
            let prev = (currentDemoSlide - 1 + totalDemoSlides) % totalDemoSlides;
            goToSlide(prev);
        }

        function toggleAutoplay() {
            demoAutoplayActive = !demoAutoplayActive;
            const icon = document.getElementById('autoplay-icon');
            const text = document.getElementById('autoplay-text');
            
            if (demoAutoplayActive) {
                icon.innerText = 'pause';
                text.innerText = 'Autoplay';
                resetAndStartProgressBar(currentDemoSlide);
            } else {
                icon.innerText = 'play_arrow';
                text.innerText = 'Pausado';
                stopAutoplay();
                document.getElementById(`demo-progress-${currentDemoSlide}`).style.width = '0%';
            }
        }

        function startAutoplay() {
            demoAutoplayActive = true;
            document.getElementById('autoplay-icon').innerText = 'pause';
            document.getElementById('autoplay-text').innerText = 'Autoplay';
            resetAndStartProgressBar(currentDemoSlide);
        }

        function stopAutoplay() {
            if (demoInterval) {
                clearInterval(demoInterval);
                demoInterval = null;
            }
            if (demoProgressInterval) {
                clearInterval(demoProgressInterval);
                demoProgressInterval = null;
            }
        }

        function resetAndStartProgressBar(slideIndex) {
            stopAutoplay();
            
            const progress = document.getElementById(`demo-progress-${slideIndex}`);
            if (!progress) return;
            
            progress.style.transition = 'none';
            progress.style.width = '0%';
            
            let startTime = Date.now();
            
            demoProgressInterval = setInterval(() => {
                let elapsed = Date.now() - startTime;
                let percentage = Math.min((elapsed / demoSlideDuration) * 100, 100);
                progress.style.width = `${percentage}%`;
                
                if (elapsed >= demoSlideDuration) {
                    clearInterval(demoProgressInterval);
                    nextSlide();
                }
            }, 30);
        }
    </script>
</body>

</html>