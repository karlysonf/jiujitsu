<!DOCTYPE html>
<html class="dark" lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? (isset($currentTenant) ? $currentTenant->name : 'Gestão Combate') }}</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                "on-secondary-fixed-variant": "#574500",
                "outline-variant": "#334155",
                "surface-container-low": "#111726",
                "primary-fixed-dim": "#f43f5e",
                "tertiary-fixed-dim": "#38bdf8",
                "on-secondary-fixed": "#241a00",
                "surface": "#090d16",
                "tertiary-container": "#0c4a6e",
                "on-primary-fixed": "#131b2e",
                "surface-container-high": "#1e293b",
                "outline": "#475569",
                "surface-container-lowest": "#090d16",
                "surface-dim": "#0f172a",
                "on-secondary-container": "#745c00",
                "on-secondary": "#ffffff",
                "inverse-on-surface": "#090d16",
                "on-background": "#f8fafc",
                "on-tertiary-fixed-variant": "#0284c7",
                "secondary-container": "#06b6d4",
                "primary": "var(--primary-color)",
                "on-tertiary-fixed": "#0c4a6e",
                "secondary-fixed-dim": "#06b6d4",
                "on-tertiary": "#ffffff",
                "error-container": "#450a0a",
                "on-error": "#ffffff",
                "inverse-surface": "#f8fafc",
                "secondary-fixed": "#38bdf8",
                "surface-container-highest": "#334155",
                "error": "#f43f5e",
                "on-surface-variant": "#94a3b8",
                "tertiary-fixed": "#7dd3fc",
                "surface-bright": "#1e293b",
                "surface-container": "#182234",
                "tertiary": "#06b6d4",
                "primary-container": "#182234",
                "on-error-container": "#fca5a5",
                "surface-variant": "#1e293b",
                "on-tertiary-container": "#38bdf8",
                "on-surface": "#f8fafc",
                "on-primary": "#ffffff",
                "background": "#090d16",
                "primary-fixed": "#fda4af",
                "on-primary-fixed-variant": "#9f1239",
                "secondary": "var(--secondary-color)",
                "inverse-primary": "#f43f5e",
                "surface-tint": "#e11d48",
                "on-primary-container": "#f43f5e"
            },
            "fontFamily": {
                "label-sm": ["Inter"],
                "label-bold": ["Inter"],
                "body-lg": ["Inter"],
                "headline-md": ["Outfit"],
                "body-md": ["Inter"],
                "headline-lg": ["Outfit"],
                "display-xl": ["Outfit"]
            }
          }
        }
      }
    </script>
    <style>
        :root {
            --primary-color: {{ isset($currentTenant) ? $currentTenant->primary_color : '#e11d48' }};
            --secondary-color: {{ isset($currentTenant) ? $currentTenant->secondary_color : '#06b6d4' }};
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        body { font-family: 'Inter', sans-serif; background-color: #090d16; color: #f8fafc; }
        h1, h2, h3, h4, .font-outfit { font-family: 'Outfit', sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#090d16] text-slate-100 min-h-screen antialiased">
    <!-- TopAppBar -->
    <header class="bg-[#111726]/90 backdrop-blur-md text-white font-['Outfit'] font-semibold border-b border-white/10 flex justify-between items-center h-16 px-4 md:px-6 w-full sticky top-0 z-50">
        <div class="flex items-center gap-2 md:gap-gutter">
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-btn" class="md:hidden flex items-center justify-center p-2 -ml-2 rounded-lg hover:bg-white/10 text-slate-300 focus:outline-none transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <span class="text-xl font-extrabold text-white tracking-tight whitespace-nowrap overflow-hidden text-ellipsis flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-500">shield</span>
                {{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}
            </span>
        </div>
        <div class="flex items-center gap-md">
            <div class="flex items-center gap-4">
                <div class="h-9 w-9 rounded-full overflow-hidden border-2 border-rose-500/40 bg-slate-800 flex items-center justify-center shadow-md shadow-rose-500/20">
                    @if(auth()->user()->photo)
                        <img alt="Perfil" class="h-full w-full object-cover" src="{{ Storage::disk('public')->url(auth()->user()->photo) }}"/>
                    @else
                        <span class="material-symbols-outlined text-slate-400 text-sm">person</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="flex min-h-[calc(100vh-64px)] relative">
        <!-- Backdrop for mobile -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 z-40 hidden md:hidden transition-opacity opacity-0 backdrop-blur-md"></div>

        <!-- SideNavBar -->
        <aside id="main-sidebar" class="bg-[#0d1320] text-slate-200 font-['Inter'] text-sm antialiased fixed left-0 top-16 h-[calc(100vh-64px)] w-[280px] border-r border-white/10 flex flex-col py-6 px-4 gap-2 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none">
            <div class="px-4 mb-6">
                <div class="flex items-center gap-3">
                    @if(isset($currentTenant) && $currentTenant->logo)
                        <img src="{{ Storage::disk('public')->url($currentTenant->logo) }}" alt="Logo" class="h-10 w-10 object-contain rounded-lg shadow-sm">
                    @else
                        <div class="bg-gradient-to-br from-rose-600 to-rose-700 p-2.5 rounded-xl shadow-lg shadow-rose-600/30">
                            <span class="material-symbols-outlined text-white">sports_kabaddi</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-base font-bold text-white leading-tight font-['Outfit']">{{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</h2>
                        <p class="text-xs text-rose-400/90 font-medium">Gestão de Elite para Tatames</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 flex flex-col gap-1.5">
                @if(auth()->user()->hasRole('root'))
                    <a class="{{ request()->routeIs('root.tenants.*') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('root.tenants.index') }}">
                        <span class="material-symbols-outlined text-rose-500">domain</span>
                        <span>Gerenciar Academias</span>
                    </a>
                @else
                    <a class="{{ request()->routeIs('dashboard') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('dashboard') }}">
                        <span class="material-symbols-outlined text-rose-500">dashboard</span>
                        <span>Dashboard</span>
                    </a>

                    @can('manage-users')
                    <a class="{{ request()->routeIs('users.*') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('users.index') }}">
                        <span class="material-symbols-outlined text-rose-500">group</span>
                        <span>Alunos</span>
                    </a>
                    @endcan

                    @can('manage-attendance')
                    <a class="{{ request()->routeIs('attendances.*') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('attendances.index') }}">
                        <span class="material-symbols-outlined text-cyan-400">how_to_reg</span>
                        <span>Presença</span>
                    </a>
                    @endcan

                    @if(auth()->user()->hasRole('aluno'))
                    <a class="{{ request()->routeIs('portal.payments.*') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('portal.payments.index') }}">
                        <span class="material-symbols-outlined text-rose-500">payments</span>
                        <span>Pagamentos</span>
                    </a>
                    @else
                        @can('manage-finance')
                        <a class="{{ request()->routeIs('payments.*') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('payments.index') }}">
                            <span class="material-symbols-outlined text-rose-500">payments</span>
                            <span>Pagamentos</span>
                        </a>
                        @endcan
                    @endif

                    @can('view-reports')
                    <a class="{{ request()->routeIs('reports.*') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('reports.index') }}">
                        <span class="material-symbols-outlined text-cyan-400">analytics</span>
                        <span>Relatórios</span>
                    </a>
                    @endcan

                    @can('manage-settings')
                    <a class="{{ request()->routeIs('settings.*') ? 'bg-rose-500/10 text-rose-400 font-semibold border-r-4 border-rose-500 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200" href="{{ route('settings.index') }}">
                        <span class="material-symbols-outlined text-slate-400">settings</span>
                        <span>Configurações</span>
                    </a>
                    @endcan
                @endif
            </nav>

            <div class="mt-auto pt-6 border-t border-white/10">
                @can('manage-finance')
                <button onclick="window.location='{{ route('payments.index') }}'" class="w-full bg-gradient-to-r from-rose-600 to-rose-700 text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2 mb-4 hover:shadow-lg hover:shadow-rose-600/30 active:scale-95 transition-all">
                    <span class="material-symbols-outlined">add_card</span>
                    Registrar Pagamento
                </button>
                @endcan
                
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-slate-400 hover:text-rose-400 flex items-center gap-3 px-4 py-2 text-sm transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                        Sair do Sistema
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="w-full md:w-auto md:ml-[280px] flex-1 overflow-y-auto overflow-x-hidden bg-[#090d16]">
            @if(auth()->check() && auth()->user()->email === 'demo@gestao.com')
            <div class="sticky top-0 z-30 bg-amber-500/20 border-b border-amber-500/40 text-amber-200 text-sm font-semibold px-4 py-2.5 flex items-center justify-between gap-3 shadow-md backdrop-blur-md">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-amber-400">science</span>
                    <span>🧪 <strong>Modo Demonstração</strong> — Você está navegando no ambiente de demo.</span>
                </div>
                <a href="{{ route('demo.landing') }}" class="text-amber-300 underline underline-offset-2 whitespace-nowrap text-xs hover:text-amber-100">← Voltar à apresentação</a>
            </div>
            @endif
            <div class="p-4 md:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mobile Menu Toggle
            const menuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            function toggleSidebar() {
                if (!sidebar) return;
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    sidebar.classList.add('-translate-x-full');
                    if (backdrop) backdrop.classList.add('opacity-0');
                    setTimeout(() => { if (backdrop) backdrop.classList.add('hidden') }, 300);
                    document.body.style.overflow = '';
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    if (backdrop) {
                        backdrop.classList.remove('hidden');
                        setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
                    }
                    document.body.style.overflow = 'hidden';
                }
            }

            if (menuBtn) menuBtn.addEventListener('click', toggleSidebar);
            if (backdrop) backdrop.addEventListener('click', toggleSidebar);

            @if(session('success'))
            Swal.fire({
                title: 'Sucesso!',
                text: "{{ session('success') }}",
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                background: '#111726',
                color: '#fff'
            });
            @endif

            @if(session('error') || (isset($errors) && $errors->any()))
            let errMsg = "{{ session('error') ?? 'Ocorreu um erro.' }}";
            @if(isset($errors) && $errors->any())
            errMsg = "{{ implode('\\n', $errors->all()) }}";
            @endif
            Swal.fire({
                title: 'Atenção!',
                text: errMsg,
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                background: '#111726',
                color: '#fff'
            });
            @endif
        });
    </script>
</body>
</html>