<!DOCTYPE html>
<html class="light" lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Acesso do Instrutor - {{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#000000",
                        "primary-container": "#131b2e",
                        "secondary-container": "#fed65b",
                        "on-primary-container": "#7c839b",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e6e8ea",
                        "surface-bright": "#f7f9fb",
                        "outline-variant": "#c6c6cd",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#45464d",
                        "tertiary-container": "#00174b",
                        "on-tertiary-fixed": "#00174b",
                        "background": "#f7f9fb",
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "xs": "4px",
                        "xl": "64px",
                        "lg": "40px",
                        "base": "8px",
                        "gutter": "24px",
                        "margin": "32px",
                        "sm": "12px",
                        "md": "24px"
                    },
                    "fontFamily": {
                        "body-md": ["Lexend"],
                        "headline-lg": ["Lexend"],
                        "label-sm": ["Lexend"],
                        "label-bold": ["Lexend"],
                        "body-lg": ["Lexend"],
                        "headline-md": ["Lexend"],
                        "display-xl": ["Lexend"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }],
                        "headline-lg": ["32px", {
                            "lineHeight": "40px",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "700"
                        }],
                        "label-sm": ["12px", {
                            "lineHeight": "16px",
                            "fontWeight": "500"
                        }],
                        "label-bold": ["14px", {
                            "lineHeight": "20px",
                            "fontWeight": "600"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "28px",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "32px",
                            "fontWeight": "700"
                        }],
                        "display-xl": ["48px", {
                            "lineHeight": "56px",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "800"
                        }]
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

        .bg-login-texture {
            background-color: #f7f9fb;
            background-image: radial-gradient(#e0e3e5 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="bg-login-texture font-body-md text-on-background min-h-screen flex items-center justify-center p-md">
    <main class="w-full max-w-[1200px] grid grid-cols-1 md:grid-cols-2 bg-surface-container-lowest rounded-xl overflow-hidden shadow-2xl min-h-[700px] border border-outline-variant">
        <!-- Left Side: Visual Narrative -->
        <section class="hidden md:flex flex-col relative bg-primary-container p-lg justify-between">
            <div class="z-10">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-10 h-10 bg-secondary-container rounded flex items-center justify-center">
                        @if(isset($currentTenant))
                        <span class="material-symbols-outlined text-on-secondary-container">sports_martial_arts</span>
                        @else
                        <span class="material-symbols-outlined text-on-secondary-container">admin_panel_settings</span>
                        @endif
                    </div>
                    <h1 class="text-white font-headline-md text-headline-md tracking-tight">{{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</h1>
                </div>
                <div class="mt-xl">
                    @if(isset($currentTenant))
                    <h2 class="text-white font-display-xl text-display-xl mb-md">Domine o fluxo da sua <span class="text-secondary-container">Academia</span>.</h2>
                    <p class="text-on-primary-container font-body-lg text-body-lg max-w-[400px]">A precisão de um faixa preta, agora para suas operações. Experimente a gestão de elite.</p>
                    @else
                    <h2 class="text-white font-display-xl text-display-xl mb-md">Painel Administrativo <span class="text-secondary-container">Global</span>.</h2>
                    <p class="text-on-primary-container font-body-lg text-body-lg max-w-[400px]">Central de controle do Gestão Combate. Monitore inquilinos, planos e faturamento global.</p>
                    @endif
                </div>
            </div>
            <div class="absolute inset-0 z-0 opacity-40">
                @if(isset($currentTenant))
                <img alt="Martial arts training environment" class="w-full h-full object-cover grayscale brightness-50" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBM07F_jJUvlHvLVXx8StEv_Wq15sjdWylwYOUF7n-Pk9C1yPZr9KNVMhnQO-mMr3yukvDzl1dS4XLdcegZvJbxgLJgK79-MepGh90fk8U_qLvOGYPNLld0sqateslLitaH__Y5Si2cgH2i2sNI-fgZEzfP4vndKrNZq9vQyXNF3X6p0tni0XIc9ql4txIAz-Bbt9ap7JCOeuH9y72crfv2QnSxKBBV5QnCSnUunHvb2OXhhyjjLDi5HsWSUVi5CvwTzNQ19s6IkcRL" />
                @else
                <img alt="Technology dashboard overview" class="w-full h-full object-cover grayscale brightness-50" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80" />
                @endif
            </div>
            <div class="z-10">
                <div class="flex gap-md">
                    @if(isset($currentTenant))
                    <div class="bg-white/10 backdrop-blur-md p-sm rounded border border-white/20">
                        <span class="text-secondary-container font-headline-md block">1.2k+</span>
                        <span class="text-white/60 font-label-sm uppercase">Alunos Matriculados</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-sm rounded border border-white/20">
                        <span class="text-secondary-container font-headline-md block">98%</span>
                        <span class="text-white/60 font-label-sm uppercase">Taxa de Retenção</span>
                    </div>
                    @else
                    <div class="bg-white/10 backdrop-blur-md p-sm rounded border border-white/20">
                        <span class="text-secondary-container font-headline-md block">SaaS</span>
                        <span class="text-white/60 font-label-sm uppercase">Multi-Inquilino</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-sm rounded border border-white/20">
                        <span class="text-secondary-container font-headline-md block">Seguro</span>
                        <span class="text-white/60 font-label-sm uppercase">Painel Criptografado</span>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Right Side: Login Form -->
        <section class="flex flex-col justify-center p-8 md:p-12 lg:p-16 bg-surface-container-lowest">
            <div class="max-w-[440px] mx-auto w-full">
                <!-- Mobile Logo -->
                <div class="md:hidden flex items-center gap-sm mb-lg">
                    <div class="w-10 h-10 bg-primary-container rounded flex items-center justify-center">
                        <span class="material-symbols-outlined text-white">sports_martial_arts</span>
                    </div>
                    <h1 class="text-primary font-headline-md text-headline-md">{{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</h1>
                </div>
                <header class="mb-lg">
                    <h3 class="text-primary font-headline-lg text-headline-lg mb-xs">Acesso do Instrutor</h3>
                    <p class="text-on-surface-variant font-body-md text-body-md">Insira suas credenciais para acessar o painel de gerenciamento.</p>
                </header>

                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">error</span>
                        {{ $errors->first() }}
                    </div>
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-md">
                    @csrf
                    <!-- CPF Field -->
                    <div class="space-y-xs">
                        <label class="text-on-surface font-label-bold text-label-bold block" for="login_identity">CPF ou Usuário</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none text-outline group-focus-within:text-tertiary-container transition-colors">
                                <span class="material-symbols-outlined">badge</span>
                            </div>
                            <input class="block w-full pl-[56px] pr-md py-md bg-surface-bright border border-outline-variant rounded-lg font-body-md text-on-surface placeholder:text-outline focus:ring-2 focus:ring-tertiary-container/20 focus:border-tertiary-container transition-all outline-none" id="login_identity" name="login_identity" placeholder="CPF ou Usuário" type="text" value="{{ old('login_identity') }}" required autofocus />
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div class="space-y-xs">
                        <label class="text-on-surface font-label-bold text-label-bold block" for="password">Senha</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none text-outline group-focus-within:text-tertiary-container transition-colors">
                                <span class="material-symbols-outlined">lock</span>
                            </div>
                            <input class="block w-full pl-[56px] pr-md py-md bg-surface-bright border border-outline-variant rounded-lg font-body-md text-on-surface placeholder:text-outline focus:ring-2 focus:ring-tertiary-container/20 focus:border-tertiary-container transition-all outline-none" id="password" name="password" placeholder="••••••••••••" type="password" required />
                            <button class="absolute inset-y-0 right-0 pr-md flex items-center text-outline hover:text-on-surface transition-colors" type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined" id="password-toggle-icon">visibility</span>
                            </button>
                        </div>
                    </div>
                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-xs cursor-pointer group">
                            <input class="w-5 h-5 rounded border-outline-variant text-primary-container focus:ring-tertiary-container cursor-pointer transition-all" type="checkbox" name="remember" />
                            <span class="text-on-surface-variant font-label-bold text-label-bold group-hover:text-primary transition-colors ml-2">Lembrar-me</span>
                        </label>
                        <a class="text-tertiary-container font-label-bold text-label-bold hover:underline" href="{{ route('password.request') }}">Esqueceu a senha?</a>
                    </div>
                    <!-- Login Button -->
                    <button class="w-full bg-primary-container text-white py-md rounded-lg font-label-bold text-body-md hover:bg-black transition-all active:scale-[0.98] shadow-lg shadow-primary-container/10 flex items-center justify-center gap-sm mt-md" type="submit">
                        Entrar
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>

                <!-- Footer Actions -->
                <footer class="mt-xl pt-lg border-t border-surface-container-high flex flex-col items-center gap-md">
                    <div class="flex flex-wrap justify-center gap-md">
                        <button onclick="toggleSupportModal(true)" type="button" class="flex items-center gap-xs text-on-surface-variant font-label-bold text-label-bold hover:bg-surface-container-high px-md py-xs rounded-full transition-colors">
                            <span class="material-symbols-outlined text-[20px]">support_agent</span>
                            Suporte
                        </button>
                        <!--
                        <button onclick="toggleDemoModal(true)" type="button" class="flex items-center gap-xs text-on-surface-variant font-label-bold text-label-bold hover:bg-surface-container-high px-md py-xs rounded-full transition-colors">
                            <span class="material-symbols-outlined text-[20px]">play_circle</span>
                            Demonstração
                        </button>
                        -->
                    </div>
                </footer>
            </div>
        </section>
    </main>

    <!-- Support Modal Overlay -->
    <div id="support-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 overflow-y-auto p-4 hidden opacity-0 transition-opacity duration-300 flex justify-center items-start sm:items-center">
        <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-3xl w-full p-6 md:p-8 shadow-2xl relative border border-slate-200 dark:border-slate-800 transform scale-95 transition-transform duration-300 my-8 sm:my-auto">
            <!-- Close Button -->
            <button onclick="toggleSupportModal(false)" type="button" class="absolute top-4 right-4 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 hover:text-slate-850 transition-colors" aria-label="Fechar">
                <span class="material-symbols-outlined">close</span>
            </button>

            <!-- Header -->
            <div class="flex items-center gap-4 mb-6">
                <button onclick="toggleSupportModal(false)" type="button" class="flex items-center justify-center p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 hover:text-slate-850 transition-colors">
                    <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">arrow_back</span>
                </button>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Suporte ao Usuário</h2>
            </div>

            <!-- Canais de Atendimento Section -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Canais de Atendimento</h3>
                <p class="text-sm text-slate-500 mt-1">Selecione o canal mais adequado para sua dúvida</p>
            </div>

            <!-- Grid of Email and Phone Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Email Card -->
                <a href="mailto:karlysonsantosdev@gmail.com" class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl hover:border-slate-400 hover:shadow-md transition-all text-center group">
                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-3xl mb-2 group-hover:scale-110 transition-transform">mail</span>
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">E-mail</span>
                    <span class="text-xs text-slate-500 mt-1 font-mono">karlysonsantosdev@gmail.com</span>
                </a>

                <!-- Phone Card -->
                <a href="tel:82987532852" class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl hover:border-slate-400 hover:shadow-md transition-all text-center group">
                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-3xl mb-2 group-hover:scale-110 transition-transform">call</span>
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Telefone</span>
                    <span class="text-xs text-slate-500 mt-1 font-mono">(82) 98753-2852</span>
                </a>
            </div>

            <!-- WhatsApp Card -->
            <div class="p-6 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900 rounded-2xl flex flex-col md:flex-row items-center gap-6 justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">chat</span>
                        <h4 class="text-base font-bold text-emerald-800 dark:text-emerald-300">Atendimento via WhatsApp</h4>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-450 leading-relaxed mb-4">
                        Clique no botão abaixo para falar com nossa equipe diretamente no WhatsApp ou aponte a câmera do seu celular para o QR Code ao lado para iniciar no seu dispositivo móvel.
                    </p>
                    <a href="https://wa.me/5582987532852" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-600/10">
                        <span class="material-symbols-outlined text-sm">chat</span>
                        Falar no WhatsApp
                    </a>
                </div>

            <!-- QR Code Section -->
            <div class="flex flex-col items-center justify-center p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 rounded-xl shadow-sm min-w-[170px]">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https%3A%2F%2Fwa.me%2F5582987532852" alt="WhatsApp QR Code" class="w-32 h-32 object-contain" />
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-2 tracking-wider">Escaneie o QR Code</span>
            </div>
        </div>

    </div>
</div>

<!-- Demo Modal Overlay (using native dialog) -->
<dialog id="demo-modal" class="bg-white dark:bg-slate-900 rounded-2xl max-w-6xl w-[calc(100%-2rem)] md:w-full max-h-[calc(100vh-2rem)] md:max-h-[85vh] p-0 shadow-2xl border border-slate-200 dark:border-slate-800 backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm transform scale-95 transition-all duration-300 opacity-0 outline-none m-auto">
    <!-- Content Container -->
    <div class="flex flex-col md:flex-row h-full min-h-[350px] md:min-h-[500px] md:h-[650px] overflow-hidden">
        <!-- Sidebar (Steps/Tabs) -->
        <div class="hidden md:flex w-full md:w-80 bg-slate-50 dark:bg-slate-950 p-6 border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-850 flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container dark:text-white">smart_display</span>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-50">Tour do Sistema</h2>
                    </div>
                    <button onclick="toggleDemoModal(false)" type="button" class="md:hidden flex items-center justify-center p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Steps List -->
                <div class="space-y-4">
                    <!-- Step 1 Button -->
                    <button onclick="goToSlide(0)" id="demo-step-0" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-slate-200/50 dark:hover:bg-slate-800/50 transition-all flex items-start gap-3 group">
                        <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[22px]">dashboard</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Painel Principal</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-450 mt-0.5">Indicadores financeiros e de frequência em tempo real.</p>
                            <!-- Progress bar -->
                            <div class="w-full bg-slate-200 dark:bg-slate-800 h-1 rounded-full mt-2 overflow-hidden relative">
                                <div id="demo-progress-0" class="bg-indigo-650 h-full w-0 transition-all duration-100 ease-linear"></div>
                            </div>
                        </div>
                    </button>

                    <!-- Step 2 Button -->
                    <button onclick="goToSlide(1)" id="demo-step-1" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-slate-200/50 dark:hover:bg-slate-800/50 transition-all flex items-start gap-3 group">
                        <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[22px]">group</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Gestão de Alunos</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-450 mt-0.5">Listagem visual de alunos ativos, inativos e graduações.</p>
                            <div class="w-full bg-slate-200 dark:bg-slate-800 h-1 rounded-full mt-2 overflow-hidden relative">
                                <div id="demo-progress-1" class="bg-indigo-650 h-full w-0 transition-all duration-100 ease-linear"></div>
                            </div>
                        </div>
                    </button>

                    <!-- Step 3 Button -->
                    <button onclick="goToSlide(2)" id="demo-step-2" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-slate-200/50 dark:hover:bg-slate-800/50 transition-all flex items-start gap-3 group">
                        <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[22px]">person_add</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Cadastro de Aluno</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-450 mt-0.5">Formulário inteligente com seleção de plano e graduação.</p>
                            <div class="w-full bg-slate-200 dark:bg-slate-800 h-1 rounded-full mt-2 overflow-hidden relative">
                                <div id="demo-progress-2" class="bg-indigo-650 h-full w-0 transition-all duration-100 ease-linear"></div>
                            </div>
                        </div>
                    </button>

                    <!-- Step 4 Button -->
                    <button onclick="goToSlide(3)" id="demo-step-3" class="w-full text-left p-3 rounded-xl border border-transparent hover:bg-slate-200/50 dark:hover:bg-slate-800/50 transition-all flex items-start gap-3 group">
                        <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[22px]">checklist</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Controle de Presença</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-450 mt-0.5">Chamada eletrônica rápida com status de presença.</p>
                            <div class="w-full bg-slate-200 dark:bg-slate-800 h-1 rounded-full mt-2 overflow-hidden relative">
                                <div id="demo-progress-3" class="bg-indigo-650 h-full w-0 transition-all duration-100 ease-linear"></div>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Footer Autoplay Controls & Keyboard Helper -->
            <div class="mt-8 pt-4 border-t border-slate-200 dark:border-slate-850 flex items-center justify-between text-xs text-slate-450">
                <button onclick="toggleAutoplay()" type="button" class="flex items-center gap-1 hover:text-slate-900 dark:hover:text-white transition-colors bg-slate-200/50 dark:bg-slate-800/50 px-3 py-1.5 rounded-full font-semibold">
                    <span class="material-symbols-outlined text-sm" id="autoplay-icon">pause</span>
                    <span id="autoplay-text">Autoplay</span>
                </button>
                <span class="hidden md:inline font-mono">Use ➔ ou ⬅</span>
            </div>
        </div>

        <!-- Main Showcase Area (Image display) -->
        <div class="flex-1 bg-slate-100 dark:bg-slate-900 flex flex-col justify-between relative group/showcase">
            <!-- Mobile Header -->
            <div class="md:hidden flex items-center justify-between px-6 py-4 bg-white/50 dark:bg-slate-950/50 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container dark:text-white">smart_display</span>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-slate-50">Tour do Sistema</h2>
                </div>
                <button onclick="toggleDemoModal(false)" type="button" class="flex items-center justify-center p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 active:scale-95 transition-all">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Close Button (Desktop) -->
            <button onclick="toggleDemoModal(false)" type="button" class="hidden md:flex absolute top-6 right-6 z-10 items-center justify-center w-10 h-10 rounded-full bg-white/90 dark:bg-slate-950/90 hover:bg-white dark:hover:bg-slate-950 text-slate-750 hover:text-slate-900 dark:text-slate-350 dark:hover:text-white transition-all shadow-md active:scale-95 border border-slate-200 dark:border-slate-800">
                <span class="material-symbols-outlined">close</span>
            </button>

            <!-- Slides Container -->
            <div class="flex-1 flex items-center justify-center p-4 md:p-10">
                <div class="relative w-full h-full max-h-[450px] overflow-hidden rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <!-- Step 1 Image -->
                    <img src="{{ asset('images/demo/step1.png') }}" id="demo-img-0" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-100 transition-all duration-500 scale-100" alt="Painel Principal (Dashboard)">
                    <!-- Step 2 Image -->
                    <img src="{{ asset('images/demo/step3.png') }}" id="demo-img-1" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-0 transition-all duration-500 scale-95 pointer-events-none" alt="Gestão de Alunos">
                    <!-- Step 3 Image -->
                    <img src="{{ asset('images/demo/step2.png') }}" id="demo-img-2" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-0 transition-all duration-500 scale-95 pointer-events-none" alt="Cadastro de Aluno">
                    <!-- Step 4 Image -->
                    <img src="{{ asset('images/demo/step4.png') }}" id="demo-img-3" class="demo-slide w-full h-full object-contain absolute inset-0 opacity-0 transition-all duration-500 scale-95 pointer-events-none" alt="Controle de Presença">
                </div>
            </div>

            <!-- Navigation Controls Footer -->
            <div class="p-5 md:p-6 bg-white/50 dark:bg-slate-950/50 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="w-full md:flex-1 md:min-w-0">
                    <h4 class="text-sm font-bold text-slate-850 dark:text-slate-100" id="slide-title">Painel Principal</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-450 mt-0.5" id="slide-desc">Indicadores financeiros e de frequência em tempo real.</p>
                </div>
                <div class="flex items-center justify-between md:justify-end gap-2 w-full md:w-auto shrink-0">
                    <a href="{{ route('demo.login') }}" id="demo-modal-cta-btn"
                       class="flex-1 md:flex-none flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-md shadow-blue-600/30">
                        <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                        <span class="whitespace-nowrap">Experimentar agora</span>
                    </a>
                    <button onclick="prevSlide()" type="button" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 transition-all active:scale-95">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button onclick="nextSlide()" type="button" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 transition-all active:scale-95">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</dialog>

        </div>
    </div>

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

        // Close Support Modal on backdrop click
        document.getElementById('support-modal').addEventListener('click', (e) => {
            const modal = document.getElementById('support-modal');
            if (e.target === modal) {
                toggleSupportModal(false);
            }
        });

        // Close Support Modal on ESC key
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

        // Close on backdrop click
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

        // Close on ESC key
        document.getElementById('demo-modal').addEventListener('cancel', (e) => {
            e.preventDefault();
            toggleDemoModal(false);
        });

        // Keyboard navigation (left/right arrow keys)
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
                    btn.classList.remove('bg-white', 'dark:bg-slate-900', 'border-slate-200', 'dark:border-slate-800', 'shadow-sm');
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
                activeBtn.classList.add('bg-white', 'dark:bg-slate-900', 'border-slate-200', 'dark:border-slate-800', 'shadow-sm');
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