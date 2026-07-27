<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Combate — Demonstração Interativa</title>
    <meta name="description" content="Demonstração do sistema de gestão para academias de artes marciais. Teste o reconhecimento facial por IA e ambiente administrativo.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🥋</text></svg>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --gc-bg:          #090d16;
            --gc-surf:        #111726;
            --gc-card:        #182234;
            --gc-card-hover:  #1e2c42;
            
            --gc-red:         #e11d48;
            --gc-red-bright:  #f43f5e;
            --gc-red-glow:    rgba(225, 29, 72, 0.35);
            
            --gc-cyan:        #06b6d4;
            --gc-cyan-bright: #38bdf8;
            
            --gc-txt:         #f8fafc;
            --gc-muted:       #94a3b8;
            --gc-border:      rgba(255, 255, 255, 0.08);
            --gc-border-bright: rgba(255, 255, 255, 0.15);
            
            --gc-font-head: 'Outfit', sans-serif;
            --gc-font-body: 'Inter', sans-serif;
        }

        body {
            background: var(--gc-bg);
            color: var(--gc-txt);
            font-family: var(--gc-font-body);
            font-size: 15px;
            line-height: 1.7;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        .gc-wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        
        .gc-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1500;
            padding: 16px 0; background: rgba(9, 13, 22, 0.92);
            backdrop-filter: blur(20px); border-bottom: 1px solid var(--gc-border);
        }
        .gc-nav__inner { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .gc-nav__logo { display: flex; align-items: center; gap: 10px; font-family: var(--gc-font-head); font-size: 24px; font-weight: 800; color: var(--gc-txt); }
        .gc-nav__logo i { color: var(--gc-red-bright); }
        .gc-nav__logo span { color: var(--gc-red-bright); }

        .gc-hero {
            min-height: 90vh; display: flex; align-items: center; justify-content: center;
            text-align: center; overflow: hidden; padding: 140px 24px 80px; position: relative;
        }
        .gc-hero__bg { position: absolute; inset: 0; background: var(--gc-bg); }
        .gc-hero__mesh {
            position: absolute; inset: 0;
            background-image: 
                radial-gradient(at 50% 30%, rgba(225, 29, 72, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 70%, rgba(6, 182, 212, 0.15) 0px, transparent 50%);
            filter: blur(40px);
        }
        .gc-hero__body { position: relative; z-index: 2; max-width: 860px; }
        .gc-hero__h1 { font-family: var(--gc-font-head); font-size: clamp(42px, 7vw, 84px); font-weight: 900; line-height: 0.98; margin-bottom: 20px; }
        .gc-hero__h1 span { color: var(--gc-red-bright); }

        .demo-card {
            background: var(--gc-card); border: 1px solid var(--gc-border-bright); border-radius: 24px;
            padding: 40px; max-width: 660px; margin: 30px auto 0; text-align: left;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
        .demo-card h3 { font-family: var(--gc-font-head); font-size: 30px; font-weight: 800; color: var(--gc-red-bright); margin-bottom: 10px; }
        .demo-card p { color: var(--gc-muted); font-size: 14.5px; margin-bottom: 24px; }
        
        .btn-demo-action {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            background: linear-gradient(135deg, var(--gc-red), #be123c); color: #fff; font-weight: 700; padding: 16px 28px;
            border-radius: 12px; font-size: 14.5px; width: 100%; transition: all .3s;
            box-shadow: 0 0 25px var(--gc-red-glow);
        }
        .btn-demo-action:hover { transform: translateY(-2px); box-shadow: 0 8px 30px var(--gc-red-glow); }

        .btn-demo-secondary {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            background: transparent; color: var(--gc-txt); font-weight: 600; padding: 15px 28px;
            border-radius: 12px; font-size: 14.5px; width: 100%; border: 1px solid var(--gc-border-bright); margin-top: 12px;
        }
        .btn-demo-secondary:hover { background: rgba(255,255,255,.06); }
    </style>
</head>
<body>

    <nav class="gc-nav">
        <div class="gc-wrap">
            <div class="gc-nav__inner">
                <a href="{{ route('home') }}" class="gc-nav__logo">
                    <i class="fa-solid fa-shield-halved"></i>
                    GESTÃO <span>COMBATE</span>
                </a>
                <a class="btn-demo-action" style="width: auto; padding: 8px 18px;" href="{{ route('login') }}">
                    <i class="fa-solid fa-lock"></i> Entrar
                </a>
            </div>
        </div>
    </nav>

    <section class="gc-hero">
        <div class="gc-hero__bg"><div class="gc-hero__mesh"></div></div>
        <div class="gc-hero__body">
            <h1 class="gc-hero__h1">AMBIENTE DE <span>DEMONSTRAÇÃO</span></h1>
            <p style="color: var(--gc-muted); font-size: 18px;">Explore o sistema de testes com permissões administrativas e recursos pré-configurados.</p>
            
            <div class="demo-card">
                <h3><i class="fa-solid fa-rocket"></i> Painel de Testes</h3>
                <p>Neste ambiente você pode navegar pelas telas de alunos, pagamentos, turmas e testar a biometria facial simulada.</p>
                
                <a href="{{ route('demo.login') }}" class="btn-demo-action">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Acessar Como Gestor Demo
                </a>
                
                <a href="{{ route('demo.reset') }}" class="btn-demo-secondary">
                    <i class="fa-solid fa-rotate"></i> Resetar Dados da Demonstração
                </a>
                
                <a href="{{ route('home') }}" style="display: block; text-align: center; margin-top: 20px; font-size: 13.5px; color: var(--gc-muted);">
                    <i class="fa-solid fa-arrow-left"></i> Voltar para a Página Inicial
                </a>
            </div>
        </div>
    </section>

</body>
</html>
