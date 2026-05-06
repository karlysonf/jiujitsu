<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CT Denyson Anderson' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/design_system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --bg-main: #F9FAFB;
            --bg-card: #FFFFFF;
            --text-main: #1F2937;
            --text-muted: #6B7280;
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
        }

        .card {
            background: var(--bg-card);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid #E5E7EB;
        }

        .logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
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
            background: #FEE2E2;
            color: #991B1B;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <i class="fas fa-user-ninja"></i>
            <span>CT Denyson Anderson</span>
        </div>
        
        @yield('content')
    </div>
    <script src="{{ asset('js/mask_script.js') }}"></script>
</body>
</html>
