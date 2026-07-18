<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gestão Combate — Sistema de Gestão para Academias de Artes Marciais</title>
    <meta name="description" content="Sistema completo de gestão para academias de Jiu-Jitsu, MMA e artes marciais. Controle de alunos, pagamentos, presenças e muito mais. Experimente grátis."/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --accent: #f59e0b;
            --dark: #0f172a;
            --dark-card: #1e293b;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --border: rgba(255,255,255,0.08);
            --radius: 16px;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Lexend', sans-serif;
            background: var(--dark);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── HERO ──────────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6rem 1.5rem 4rem;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(37,99,235,.35) 0%, transparent 70%),
                        radial-gradient(ellipse 50% 40% at 80% 70%, rgba(245,158,11,.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(37,99,235,.15);
            border: 1px solid rgba(37,99,235,.35);
            color: #93c5fd;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .35rem 1rem;
            border-radius: 999px;
            margin-bottom: 1.5rem;
            animation: fadeUp .6s ease both;
        }

        .hero h1 {
            font-size: clamp(2.4rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -.025em;
            max-width: 860px;
            animation: fadeUp .7s .1s ease both;
        }

        .hero h1 span { color: #60a5fa; }

        .hero p {
            margin-top: 1.25rem;
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: var(--text-muted);
            max-width: 560px;
            line-height: 1.7;
            font-weight: 300;
            animation: fadeUp .7s .2s ease both;
        }

        .hero-cta {
            margin-top: 2.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            animation: fadeUp .7s .3s ease both;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            background: var(--primary);
            color: #fff;
            font-family: 'Lexend', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            padding: .85rem 2rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 24px rgba(37,99,235,.4);
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 8px 32px rgba(37,99,235,.5); }

        .btn-outline {
            display: inline-flex; align-items: center; gap: .5rem;
            background: transparent;
            color: var(--text-muted);
            font-family: 'Lexend', sans-serif;
            font-weight: 500;
            font-size: 1rem;
            padding: .85rem 2rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            cursor: pointer;
            text-decoration: none;
            transition: color .2s, border-color .2s, transform .15s;
        }
        .btn-outline:hover { color: var(--text); border-color: rgba(255,255,255,.2); transform: translateY(-2px); }

        /* ─── TRUST BAR ─────────────────────────────────── */
        .trust-bar {
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2.5rem;
            flex-wrap: wrap;
            background: rgba(255,255,255,.02);
        }
        .trust-item {
            display: flex; align-items: center; gap: .5rem;
            color: var(--text-muted);
            font-size: .85rem;
            font-weight: 500;
        }
        .trust-item .material-symbols-outlined { font-size: 1.1rem; color: var(--accent); }

        /* ─── FEATURES ──────────────────────────────────── */
        .section { padding: 5rem 1.5rem; max-width: 1100px; margin: 0 auto; }
        .section-tag {
            display: inline-block;
            background: rgba(37,99,235,.12);
            color: #93c5fd;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .3rem .8rem;
            border-radius: 6px;
            margin-bottom: .75rem;
        }
        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -.02em;
        }
        .section-title span { color: #60a5fa; }
        .section-sub {
            margin-top: .75rem;
            color: var(--text-muted);
            font-size: 1.05rem;
            font-weight: 300;
            max-width: 540px;
            line-height: 1.7;
        }

        .features-grid {
            margin-top: 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .feature-card {
            background: var(--dark-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.75rem;
            overflow: hidden;
            transition: transform .2s, border-color .2s, box-shadow .2s;
        }
        .feature-card:hover { transform: translateY(-4px); border-color: rgba(37,99,235,.35); box-shadow: 0 12px 40px rgba(0,0,0,.3); }

        .feature-icon {
            width: 48px; height: 48px;
            background: rgba(37,99,235,.12);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.1rem;
        }
        .feature-icon .material-symbols-outlined { color: #60a5fa; font-size: 1.5rem; }

        .feature-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: .4rem; word-break: break-word; overflow-wrap: break-word; }
        .feature-card p { font-size: .875rem; color: var(--text-muted); line-height: 1.65; }

        /* ─── STATS ─────────────────────────────────────── */
        .stats-section {
            padding: 4rem 1.5rem;
            background: linear-gradient(135deg, rgba(37,99,235,.08) 0%, rgba(245,158,11,.05) 100%);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .stats-grid {
            max-width: 900px; margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        .stat-number { font-size: 2.75rem; font-weight: 900; color: #60a5fa; letter-spacing: -.03em; }
        .stat-label { font-size: .85rem; color: var(--text-muted); margin-top: .25rem; font-weight: 400; }

        /* ─── DEMO CTA ──────────────────────────────────── */
        .demo-cta {
            padding: 5rem 1.5rem;
            text-align: center;
        }
        .demo-box {
            max-width: 680px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(37,99,235,.15), rgba(29,78,216,.08));
            border: 1px solid rgba(37,99,235,.3);
            border-radius: 24px;
            padding: 3rem 2rem;
        }
        .demo-box h2 { font-size: clamp(1.6rem, 3.5vw, 2.4rem); font-weight: 800; margin-bottom: .75rem; }
        .demo-box p { color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 2rem; }
        .demo-credentials {
            display: inline-flex;
            flex-direction: column;
            gap: .4rem;
            background: rgba(0,0,0,.3);
            border-radius: 12px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            text-align: left;
            border: 1px solid var(--border);
        }
        .demo-credentials span { font-size: .85rem; color: var(--text-muted); }
        .demo-credentials strong { color: #60a5fa; font-size: .95rem; }

        /* ─── FOOTER ────────────────────────────────────── */
        .footer {
            border-top: 1px solid var(--border);
            padding: 2rem 1.5rem;
            text-align: center;
            color: var(--text-muted);
            font-size: .8rem;
        }

        /* ─── ANIMATIONS ────────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── NAVBAR ────────────────────────────────────── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 1rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            backdrop-filter: blur(16px);
            background: rgba(15,23,42,.7);
            border-bottom: 1px solid var(--border);
        }
        .navbar-brand { font-size: 1.1rem; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: .5rem; }
        .navbar-brand .material-symbols-outlined { color: #60a5fa; }
        .navbar-btn {
            background: var(--primary); color: #fff;
            font-family: 'Lexend', sans-serif; font-weight: 600; font-size: .85rem;
            padding: .55rem 1.25rem; border-radius: 10px; border: none;
            cursor: pointer; text-decoration: none;
            transition: background .2s;
        }
        .navbar-btn:hover { background: var(--primary-dark); }

        /* ─── MATERIAL ICONS ────────────────────────────── */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block; line-height: 1; vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-brand">
        <span class="material-symbols-outlined">sports_kabaddi</span>
        Gestão Combate
    </div>
    <a href="{{ route('demo.login') }}" class="navbar-btn" id="navbar-demo-btn">
        Acessar Demo
    </a>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-badge">
        <span class="material-symbols-outlined" style="font-size:.9rem">auto_awesome</span>
        Sistema profissional para academias
    </div>
    <h1>Gerencie sua academia<br/><span>com inteligência</span></h1>
    <p>Controle de alunos, pagamentos, chamadas com IA e relatórios completos — tudo em um só lugar, pensado para academias de artes marciais.</p>
    <div class="hero-cta">
        <a href="{{ route('demo.login') }}" class="btn-primary" id="hero-demo-btn">
            <span class="material-symbols-outlined" style="font-size:1.1rem">play_circle</span>
            Experimentar Grátis Agora
        </a>
        <a href="#features" class="btn-outline" id="hero-features-btn">
            <span class="material-symbols-outlined" style="font-size:1.1rem">expand_more</span>
            Ver Funcionalidades
        </a>
    </div>
</section>

<!-- TRUST BAR -->
<div class="trust-bar">
    <div class="trust-item">
        <span class="material-symbols-outlined">verified</span>
        Dados seguros e criptografados
    </div>
    <div class="trust-item">
        <span class="material-symbols-outlined">bolt</span>
        Sistema rápido e responsivo
    </div>
    <div class="trust-item">
        <span class="material-symbols-outlined">support_agent</span>
        Suporte dedicado
    </div>
    <div class="trust-item">
        <span class="material-symbols-outlined">smartphone</span>
        100% adaptado para celular
    </div>
</div>

<!-- FEATURES -->
<section class="section" id="features">
    <div class="section-tag">Funcionalidades</div>
    <h2 class="section-title">Tudo que sua academia<br/><span>precisa em um sistema</span></h2>
    <p class="section-sub">Do cadastro do aluno ao controle financeiro, passando pelo reconhecimento facial na chamada.</p>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">group</span></div>
            <h3>Gestão de Alunos</h3>
            <p>Cadastro completo com faixa, grau, histórico médico, responsáveis e foto. Pesquisa rápida e filtros avançados.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">payments</span></div>
            <h3>Controle Financeiro</h3>
            <p>Mensalidades, inadimplência, planos flexíveis e histórico de pagamentos. Relatórios mensais automáticos.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">photo_camera</span></div>
            <h3>Chamada por IA</h3>
            <p>Tire uma foto do tatame e a inteligência artificial identifica os alunos presentes automaticamente.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">event_available</span></div>
            <h3>Controle de Presença</h3>
            <p>Registro rápido de chamada com marcação em lote. Histórico completo de frequência por aluno.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">bar_chart</span></div>
            <h3>Relatórios e Analytics</h3>
            <p>Gráficos de inadimplência, frequência e crescimento. Tome decisões baseadas em dados reais.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">person_pin</span></div>
            <h3>Portal do Aluno</h3>
            <p>O aluno acessa seu próprio portal para ver presenças, pagamentos e fazer check-in no treino.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">settings</span></div>
            <h3>Personalização da Academia</h3>
            <p>Cores, logo, nome da academia e configurações próprias. Cada academia tem sua identidade.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">workspace_premium</span></div>
            <h3>Graduação e Faixas</h3>
            <p>Controle de faixas e graus de cada aluno, com histórico de progressão na jornada do atleta.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><span class="material-symbols-outlined">phone_iphone</span></div>
            <h3>Mobile-First</h3>
            <p>Funciona perfeitamente em celulares e tablets. Faça a chamada do treino direto do seu smartphone.</p>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="stats-section">
    <div class="stats-grid">
        <div>
            <div class="stat-number">15+</div>
            <div class="stat-label">Funcionalidades prontas</div>
        </div>
        <div>
            <div class="stat-number">IA</div>
            <div class="stat-label">Reconhecimento facial</div>
        </div>
        <div>
            <div class="stat-number">360°</div>
            <div class="stat-label">Visão do aluno</div>
        </div>
        <div>
            <div class="stat-number">24/7</div>
            <div class="stat-label">Online na nuvem</div>
        </div>
    </div>
</div>

<!-- DEMO CTA -->
<section class="demo-cta">
    <div class="demo-box">
        <h2>Explore o sistema agora mesmo 🥋</h2>
        <p>Acesse o ambiente de demonstração completo com dados reais de exemplo. Nenhum cadastro necessário.</p>

        <div class="demo-credentials">
            <span>📧 Login de acesso</span>
            <strong>demo@gestao.com</strong>
            <span>🔑 Senha</span>
            <strong>demo1234</strong>
        </div>

        <br/>

        <a href="{{ route('demo.login') }}" class="btn-primary" id="cta-demo-btn" style="font-size:1.05rem; padding:1rem 2.5rem;">
            <span class="material-symbols-outlined" style="font-size:1.2rem">rocket_launch</span>
            Entrar no Ambiente de Demo
        </a>

        <p style="margin-top:1.25rem; font-size:.8rem; color: #475569;">
            ⚠️ Os dados são resetados toda madrugada. Fique à vontade para explorar tudo!
        </p>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© {{ date('Y') }} Gestão Combate. Desenvolvido para academias de artes marciais.</p>
</footer>

</body>
</html>
