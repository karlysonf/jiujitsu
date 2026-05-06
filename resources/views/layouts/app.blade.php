<!DOCTYPE html>
<html class="light" lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'CT Denyson Anderson' }}</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                "on-secondary-fixed-variant": "#574500",
                "outline-variant": "#c6c6cd",
                "surface-container-low": "#f2f4f6",
                "primary-fixed-dim": "#bec6e0",
                "tertiary-fixed-dim": "#b4c5ff",
                "on-secondary-fixed": "#241a00",
                "surface": "#f7f9fb",
                "tertiary-container": "#00174b",
                "on-primary-fixed": "#131b2e",
                "surface-container-high": "#e6e8ea",
                "outline": "#76777d",
                "surface-container-lowest": "#ffffff",
                "surface-dim": "#d8dadc",
                "on-secondary-container": "#745c00",
                "on-secondary": "#ffffff",
                "inverse-on-surface": "#eff1f3",
                "on-background": "#191c1e",
                "on-tertiary-fixed-variant": "#003ea8",
                "secondary-container": "#fed65b",
                "primary": "#000000",
                "on-tertiary-fixed": "#00174b",
                "secondary-fixed-dim": "#e9c349",
                "on-tertiary": "#ffffff",
                "error-container": "#ffdad6",
                "on-error": "#ffffff",
                "inverse-surface": "#2d3133",
                "secondary-fixed": "#ffe088",
                "surface-container-highest": "#e0e3e5",
                "error": "#ba1a1a",
                "on-surface-variant": "#45464d",
                "tertiary-fixed": "#dbe1ff",
                "surface-bright": "#f7f9fb",
                "surface-container": "#eceef0",
                "tertiary": "#000000",
                "primary-container": "#131b2e",
                "on-error-container": "#93000a",
                "surface-variant": "#e0e3e5",
                "on-tertiary-container": "#497cff",
                "on-surface": "#191c1e",
                "on-primary": "#ffffff",
                "background": "#f7f9fb",
                "primary-fixed": "#dae2fd",
                "on-primary-fixed-variant": "#3f465c",
                "secondary": "#735c00",
                "inverse-primary": "#bec6e0",
                "surface-tint": "#565e74",
                "on-primary-container": "#7c839b"
            },
            "borderRadius": {
                "DEFAULT": "0.125rem",
                "lg": "0.25rem",
                "xl": "0.5rem",
                "full": "0.75rem"
            },
            "spacing": {
                "xs": "4px",
                "margin": "32px",
                "xl": "64px",
                "sm": "12px",
                "base": "8px",
                "lg": "40px",
                "gutter": "24px",
                "md": "24px"
            },
            "fontFamily": {
                "label-sm": ["Lexend"],
                "label-bold": ["Lexend"],
                "body-lg": ["Lexend"],
                "headline-md": ["Lexend"],
                "body-md": ["Lexend"],
                "headline-lg": ["Lexend"],
                "display-xl": ["Lexend"]
            },
            "fontSize": {
                "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                "label-bold": ["14px", {"lineHeight": "20px", "fontWeight": "600"}],
                "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                "display-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "800"}]
            }
          }
        }
      }
    </script>
    <style>
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
        body { font-family: 'Lexend', sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface">
    <!-- TopAppBar -->
    <header class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-50 font-['Lexend'] font-medium border-b border-slate-200 dark:border-slate-800 flex justify-between items-center h-16 px-4 md:px-6 w-full sticky top-0 z-50">
        <div class="flex items-center gap-2 md:gap-gutter">
            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-btn" class="md:hidden flex items-center justify-center p-2 -ml-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors">
                <span class="material-symbols-outlined text-slate-700 dark:text-slate-300">menu</span>
            </button>
            <span class="text-xl md:text-xl font-black text-slate-900 dark:text-slate-50 tracking-tight whitespace-nowrap overflow-hidden text-ellipsis">CT Denyson Anderson</span>
        </div>
        <div class="flex items-center gap-md">
            <div class="flex items-center gap-4">
                <div class="h-8 w-8 rounded-full overflow-hidden border border-outline-variant">
                    <img alt="Perfil do Instrutor" class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkAM_pl4Pncb9GfmjqFvZ3mu_dvgjBsM1ZiTK8sqFuGaFX29F4xqLIVhUP_Ja23ia2crZ1UuuX1zzxSAyxY4JyPLBSvsV_rG-gU38e0GZ66PY1VkdFAGd7UImAkETMB_LLz4FHtioys3d4AWlk_Y1Q0Y5MVvsPqhA8gUrzJC0L1WSAhceTjGquhprsSnjD1OCJQKsJZ1lLZ2br8b5Ljd90LEY2qygH2qWyXKdUgFlFAYbuWAq6pTDIjWxv30Fg8v_fM8XkOttk4_mf"/>
                </div>
            </div>
        </div>
    </header>

    <div class="flex min-h-[calc(100vh-64px)] relative">
        <!-- Backdrop for mobile -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 dark:bg-slate-900/80 z-40 hidden md:hidden transition-opacity opacity-0 backdrop-blur-sm"></div>

        <!-- SideNavBar -->
        <aside id="main-sidebar" class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-50 font-['Lexend'] text-sm antialiased fixed left-0 top-16 h-[calc(100vh-64px)] w-[280px] border-r border-slate-200 dark:border-slate-800 flex flex-col py-6 px-4 gap-2 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl md:shadow-none">
            <div class="px-4 mb-xl">
                <div class="flex items-center gap-3">
                    <div class="bg-primary p-2 rounded-lg">
                        <span class="material-symbols-outlined text-white">sports_kabaddi</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-50 leading-tight">CT Denyson Anderson</h2>
                        <p class="text-xs text-slate-500">Gestão de Elite para Academias</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 flex flex-col gap-1">
                <a class="{{ request()->routeIs('dashboard') ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 font-semibold shadow-sm border-r-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Dashboard</span>
                </a>

                @can('manage-users')
                <a class="{{ request()->routeIs('users.*') ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 font-semibold shadow-sm border-r-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" href="{{ route('users.index') }}">
                    <span class="material-symbols-outlined">group</span>
                    <span>Alunos</span>
                </a>
                @endcan

                @can('manage-attendance')
                <a class="{{ request()->routeIs('attendances.*') ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 font-semibold shadow-sm border-r-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" href="{{ route('attendances.index') }}">
                    <span class="material-symbols-outlined">how_to_reg</span>
                    <span>Presença</span>
                </a>
                @endcan

                @if(auth()->user()->hasRole('aluno'))
                <a class="{{ request()->routeIs('portal.payments.*') ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 font-semibold shadow-sm border-r-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" href="{{ route('portal.payments.index') }}">
                    <span class="material-symbols-outlined">payments</span>
                    <span>Pagamentos</span>
                </a>
                @else
                    @can('manage-finance')
                    <a class="{{ request()->routeIs('payments.*') ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 font-semibold shadow-sm border-r-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" href="{{ route('payments.index') }}">
                        <span class="material-symbols-outlined">payments</span>
                        <span>Pagamentos</span>
                    </a>
                    @endcan
                @endif

                @can('view-reports')
                <a class="{{ request()->routeIs('reports.*') ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 font-semibold shadow-sm border-r-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" href="{{ route('reports.index') }}">
                    <span class="material-symbols-outlined">analytics</span>
                    <span>Relatórios</span>
                </a>
                @endcan
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-200 dark:border-slate-800">
                @can('manage-finance')
                <button onclick="window.location='{{ route('payments.index') }}'" class="w-full bg-primary text-white py-3 rounded-xl font-label-bold flex items-center justify-center gap-2 mb-4 hover:opacity-90 active:scale-95 transition-all">
                    <span class="material-symbols-outlined">add_card</span>
                    Registrar Pagamento
                </button>
                @endcan
                
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-slate-600 dark:text-slate-400 hover:text-slate-900 flex items-center gap-3 px-4 py-2 text-sm">
                        <span class="material-symbols-outlined">logout</span>
                        Sair do Sistema
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="w-full md:w-auto md:ml-[280px] flex-1 p-4 md:p-margin overflow-y-auto overflow-x-hidden">
            @yield('content')
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
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
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
                timerProgressBar: true
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
                timerProgressBar: true
            });
            @endif
        });
    </script>
</body>
</html>