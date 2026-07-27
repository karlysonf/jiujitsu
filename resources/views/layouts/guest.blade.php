<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? (isset($currentTenant) ? $currentTenant->name : 'Gestão Combate') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/design_system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <style>
        :root {
            --primary: #e11d48;
            --primary-dark: #be123c;
            --secondary: #06b6d4;
            --bg-main: #090d16;
            --bg-card: #111726;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-card: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
            background-image: 
                radial-gradient(at 30% 20%, rgba(225, 29, 72, 0.15) 0px, transparent 50%),
                radial-gradient(at 70% 80%, rgba(6, 182, 212, 0.12) 0px, transparent 50%);
        }

        .card {
            background: var(--bg-card);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border-card);
        }

        .logo {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .logo i {
            color: var(--primary);
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .alert-error {
            background: rgba(225, 29, 72, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(225, 29, 72, 0.3);
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Form elements styling override for dark theme */
        input[type="text"], input[type="email"], input[type="password"] {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-card);
            color: #fff;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            width: 100%;
            margin-top: 0.35rem;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(225, 29, 72, 0.3);
        }
        button[type="submit"], .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 0.75rem;
            padding: 0.85rem 1.5rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 0 20px rgba(225, 29, 72, 0.3);
            margin-top: 1rem;
        }
        button[type="submit"]:hover, .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(225, 29, 72, 0.4);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <i class="fas fa-shield-halved"></i>
            <span>{{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</span>
        </div>
        
        @yield('content')
    </div>
    <script src="{{ asset('js/mask_script.js') }}"></script>
</body>
</html>
