<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Combate — A Plataforma Inteligente para Tatames e Academias de Artes Marciais</title>
    <meta name="description" content="Gestão completa para academias de Jiu-Jitsu e artes marciais. Reconhecimento facial por IA, controle de presença, automação financeira Asaas e portal do aluno.">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🥋</text></svg>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* ─── TOKENS & RESET ─────────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --gc-bg: #090d16;
            --gc-surf: #111726;
            --gc-card: #182234;
            --gc-card-hover: #1e2c42;

            --gc-red: #e11d48;
            --gc-red-bright: #f43f5e;
            --gc-red-glow: rgba(225, 29, 72, 0.35);

            --gc-cyan: #06b6d4;
            --gc-cyan-bright: #38bdf8;
            --gc-cyan-glow: rgba(6, 182, 212, 0.3);

            --gc-txt: #f8fafc;
            --gc-muted: #94a3b8;
            --gc-dim: #475569;
            --gc-border: rgba(255, 255, 255, 0.08);
            --gc-border-bright: rgba(255, 255, 255, 0.15);

            --gc-font-head: 'Outfit', sans-serif;
            --gc-font-body: 'Inter', sans-serif;
            --gc-radius: 16px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--gc-bg);
            color: var(--gc-txt);
            font-family: var(--gc-font-body);
            font-size: 15px;
            line-height: 1.7;
            overflow-x: hidden;
        }

        /* ─── UTILITIES ──────────────────────────────────────────────── */
        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .gc-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .gc-sec {
            padding: 100px 0;
            position: relative;
        }

        .gc-sec--surf {
            background: var(--gc-surf);
        }

        .gc-sec--card {
            background: var(--gc-card);
        }

        .gc-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--gc-red-bright);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .gc-tag::before {
            content: '';
            width: 20px;
            height: 2px;
            background: var(--gc-red);
            border-radius: 2px;
            flex-shrink: 0;
        }

        .gc-tag--cyan {
            color: var(--gc-cyan-bright);
        }

        .gc-tag--cyan::before {
            background: var(--gc-cyan);
        }

        .gc-h2 {
            font-family: var(--gc-font-head);
            font-size: clamp(34px, 5vw, 56px);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.05;
            color: var(--gc-txt);
            margin: 0 0 18px;
        }

        .gc-h2 em {
            color: var(--gc-red-bright);
            font-style: normal;
        }

        .gc-h2--cyan em {
            color: var(--gc-cyan-bright);
            font-style: normal;
        }

        .gc-desc {
            color: var(--gc-muted);
            font-size: 15.5px;
            max-width: 560px;
            line-height: 1.8;
            font-weight: 400;
        }

        /* ─── NAVBAR ─────────────────────────────────────────────────── */
        .gc-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1500;
            padding: 20px 0;
            transition: all .35s ease;
            border-bottom: 1px solid transparent;
        }

        .gc-nav.is-scrolled {
            background: rgba(9, 13, 22, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 12px 0;
            border-bottom-color: var(--gc-border);
        }

        .gc-nav__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .gc-nav__logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--gc-font-head);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--gc-txt);
        }

        .gc-nav__logo i {
            color: var(--gc-red-bright);
            font-size: 24px;
        }

        .gc-nav__logo span {
            color: var(--gc-red-bright);
        }

        .gc-nav__links {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .gc-nav__links a {
            color: var(--gc-muted);
            font-size: 13.5px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all .2s;
            white-space: nowrap;
        }

        .gc-nav__links a:hover {
            color: var(--gc-txt);
            background: rgba(255, 255, 255, .05);
        }

        .gc-nav__cta {
            background: linear-gradient(135deg, var(--gc-red), #b91c1c);
            color: #fff !important;
            padding: 10px 22px !important;
            border-radius: 10px;
            font-weight: 700 !important;
            margin-left: 8px;
            box-shadow: 0 0 24px var(--gc-red-glow);
            transition: all .25s !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .gc-nav__cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px var(--gc-red-glow);
        }

        .gc-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: 1px solid var(--gc-border-bright);
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        .gc-hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--gc-txt);
            border-radius: 2px;
        }

        /* ─── MOBILE MENU ────────────────────────────────────────────── */
        .gc-mmenu {
            position: fixed;
            inset: 0;
            background: rgba(9, 13, 22, 0.98);
            backdrop-filter: blur(30px);
            z-index: 1600;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 48px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .gc-mmenu.is-open {
            opacity: 1;
            pointer-events: all;
        }

        .gc-mmenu a {
            font-family: var(--gc-font-head);
            font-size: clamp(26px, 7vw, 40px);
            font-weight: 700;
            text-transform: uppercase;
            color: var(--gc-txt);
            transition: color .2s;
        }

        .gc-mmenu a:hover {
            color: var(--gc-red-bright);
        }

        .gc-mmenu__close {
            position: absolute;
            top: 24px;
            right: 24px;
            background: none;
            border: 1px solid var(--gc-border-bright);
            color: var(--gc-muted);
            font-size: 20px;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* ─── HERO ───────────────────────────────────────────────────── */
        .gc-hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            padding: 150px 24px 80px;
            position: relative;
        }

        .gc-hero__bg {
            position: absolute;
            inset: 0;
            background: var(--gc-bg);
        }

        .gc-hero__mesh {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(at 20% 30%, rgba(225, 29, 72, 0.18) 0px, transparent 50%),
                radial-gradient(at 80% 20%, rgba(6, 182, 212, 0.16) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(225, 29, 72, 0.1) 0px, transparent 50%);
            filter: blur(40px);
            opacity: 0.9;
        }

        .gc-hero__grid-lines {
            position: absolute;
            inset: 0;
            opacity: 0.04;
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.5) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.5) 1px, transparent 1px);
        }

        .gc-hero__body {
            position: relative;
            z-index: 2;
            max-width: 960px;
        }

        .gc-hero__badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(225, 29, 72, 0.1);
            border: 1px solid rgba(225, 29, 72, 0.3);
            color: var(--gc-red-bright);
            padding: 6px 18px;
            border-radius: 100px;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .gc-hero__badge .dot {
            width: 7px;
            height: 7px;
            background: var(--gc-red-bright);
            border-radius: 50%;
            animation: gcPulse 2s infinite;
        }

        @keyframes gcPulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .3;
                transform: scale(0.8);
            }
        }

        .gc-hero__h1 {
            font-family: var(--gc-font-head);
            font-size: clamp(48px, 8vw, 92px);
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 0.98;
            color: var(--gc-txt);
            margin: 0 0 22px;
        }

        .gc-hero__h1 .c-red {
            background: linear-gradient(135deg, #f43f5e, #e11d48);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gc-hero__h1 .c-cyan {
            background: linear-gradient(135deg, #38bdf8, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gc-hero__sub {
            font-size: clamp(16px, 2vw, 20px);
            color: var(--gc-muted);
            max-width: 640px;
            margin: 0 auto 40px;
            font-weight: 300;
            line-height: 1.7;
        }

        .gc-hero__ctas {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 60px;
        }

        .gc-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--gc-red), #be123c);
            color: #fff;
            padding: 16px 36px;
            border-radius: var(--gc-radius);
            font-weight: 700;
            font-size: 14.5px;
            letter-spacing: .02em;
            box-shadow: 0 0 35px var(--gc-red-glow);
            transition: all .3s;
        }

        .gc-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 45px var(--gc-red-glow);
        }

        .gc-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, .03);
            color: var(--gc-txt);
            padding: 15px 32px;
            border-radius: var(--gc-radius);
            font-weight: 600;
            font-size: 14.5px;
            border: 1px solid var(--gc-border-bright);
            transition: all .3s;
        }

        .gc-btn-secondary:hover {
            background: rgba(255, 255, 255, .08);
            border-color: rgba(255, 255, 255, .3);
            transform: translateY(-3px);
        }

        .gc-hero__stats {
            display: inline-flex;
            background: rgba(24, 34, 52, 0.6);
            border: 1px solid var(--gc-border-bright);
            border-radius: 20px;
            overflow: hidden;
            backdrop-filter: blur(12px);
        }

        .gc-hero__stat {
            padding: 22px 42px;
            text-align: center;
            border-right: 1px solid var(--gc-border);
        }

        .gc-hero__stat:last-child {
            border-right: none;
        }

        .gc-hero__snum {
            display: block;
            font-family: var(--gc-font-head);
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1;
            margin-bottom: 4px;
        }

        .gc-hero__snum--red {
            color: var(--gc-red-bright);
        }

        .gc-hero__snum--cyan {
            color: var(--gc-cyan-bright);
        }

        .gc-hero__slabel {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--gc-muted);
            font-weight: 600;
        }

        /* ─── FEATURES GRID ──────────────────────────────────────────── */
        .gc-features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 56px;
        }

        .gc-feat-card {
            background: var(--gc-card);
            border: 1px solid var(--gc-border);
            border-radius: 20px;
            padding: 36px 30px;
            position: relative;
            overflow: hidden;
            transition: all .35s;
        }

        .gc-feat-card:hover {
            background: var(--gc-card-hover);
            border-color: rgba(225, 29, 72, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .gc-feat-icon {
            width: 52px;
            height: 52px;
            background: rgba(225, 29, 72, 0.12);
            border: 1px solid rgba(225, 29, 72, 0.25);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--gc-red-bright);
            margin-bottom: 20px;
            transition: all .3s;
        }

        .gc-feat-card:hover .gc-feat-icon {
            background: var(--gc-red);
            color: #fff;
            border-color: var(--gc-red);
            box-shadow: 0 0 25px var(--gc-red-glow);
        }

        .gc-feat-card h3 {
            font-family: var(--gc-font-head);
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.01em;
            margin: 0 0 10px;
            color: var(--gc-txt);
        }

        .gc-feat-card p {
            color: var(--gc-muted);
            font-size: 14px;
            line-height: 1.75;
            margin: 0;
        }

        /* ─── SPLIT SECTIONS ─────────────────────────────────────────── */
        .gc-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 72px;
            align-items: center;
        }

        .gc-scanner-box {
            background: linear-gradient(145deg, var(--gc-card), #141c2c);
            border: 1px solid var(--gc-cyan);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 0 50px var(--gc-cyan-glow);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .gc-scanner-target {
            width: 110px;
            height: 110px;
            margin: 0 auto 24px;
            border: 2px stroke var(--gc-cyan);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: rgba(6, 182, 212, 0.08);
        }

        .gc-scanner-target i {
            font-size: 50px;
            color: var(--gc-cyan-bright);
        }

        .gc-scanner-reticle {
            position: absolute;
            inset: -8px;
            border: 2px dashed var(--gc-cyan-bright);
            border-radius: 50%;
            animation: gcSpin 12s linear infinite;
        }

        @keyframes gcSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .gc-flist {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin: 0;
            padding: 0;
        }

        .gc-flist li {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            color: var(--gc-muted);
            font-size: 14.5px;
            line-height: 1.65;
        }

        .gc-flist__icon {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            background: rgba(6, 182, 212, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gc-cyan-bright);
            font-size: 14px;
            margin-top: 1px;
        }

        .gc-cklist {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin: 0;
            padding: 0;
        }

        .gc-cklist li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: var(--gc-muted);
            font-size: 14.5px;
        }

        .gc-cklist__icon {
            flex-shrink: 0;
            color: var(--gc-red-bright);
            font-size: 14px;
            margin-top: 3px;
        }

        /* ─── COUNTERS BAR ───────────────────────────────────────────── */
        .gc-counters {
            background: var(--gc-bg);
            padding: 75px 0;
            border-top: 1px solid var(--gc-border);
            border-bottom: 1px solid var(--gc-border);
        }

        .gc-counters__grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .gc-counter-card {
            background: var(--gc-card);
            border: 1px solid var(--gc-border);
            border-radius: 18px;
            padding: 36px 20px;
            text-align: center;
            transition: background .3s;
        }

        .gc-counter-card:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .gc-cnum {
            display: block;
            font-family: var(--gc-font-head);
            font-size: clamp(40px, 4vw, 56px);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1;
            margin-bottom: 8px;
            color: var(--gc-txt);
        }

        .gc-cnum--red {
            color: var(--gc-red-bright);
        }

        .gc-cnum--cyan {
            color: var(--gc-cyan-bright);
        }

        .gc-clabel {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--gc-muted);
            font-weight: 600;
        }

        /* ─── PRICING ────────────────────────────────────────────────── */
        .gc-plans-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 48px;
        }

        .gc-plan-card {
            background: var(--gc-card);
            border: 1px solid var(--gc-border);
            border-radius: 24px;
            padding: 38px 30px;
            position: relative;
            transition: all .35s;
            display: flex;
            flex-direction: column;
        }

        .gc-plan-card:hover {
            transform: translateY(-6px);
            border-color: rgba(225, 29, 72, 0.4);
        }

        .gc-plan-card.is-featured {
            border-color: var(--gc-red);
            background: var(--gc-card-hover);
            box-shadow: 0 0 40px var(--gc-red-glow);
        }

        .gc-plan-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gc-red);
            color: #fff;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 4px 12px;
            border-radius: 100px;
        }

        .gc-plan-name {
            font-family: var(--gc-font-head);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--gc-txt);
            margin: 0 0 4px;
        }

        .gc-plan-price {
            font-family: var(--gc-font-head);
            font-size: 50px;
            font-weight: 900;
            color: var(--gc-red-bright);
            line-height: 1;
            margin: 16px 0 4px;
        }

        .gc-plan-price span {
            font-size: 16px;
            color: var(--gc-muted);
            font-family: var(--gc-font-body);
            font-weight: 400;
        }

        .gc-plan-feats {
            list-style: none;
            border-top: 1px solid var(--gc-border);
            padding: 20px 0 0;
            margin: 20px 0 28px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }

        .gc-plan-feats li {
            font-size: 13.5px;
            color: var(--gc-muted);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .gc-plan-feats li i {
            color: var(--gc-red-bright);
            font-size: 12px;
        }

        /* ─── FAQ ACCORDION ──────────────────────────────────────────── */
        .gc-faq {
            margin-top: 56px;
        }

        .gc-faq__grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .gc-faq-item {
            background: var(--gc-card);
            border: 1px solid var(--gc-border);
            border-radius: 16px;
            overflow: hidden;
            transition: border-color .3s;
        }

        .gc-faq-item.is-open {
            border-color: rgba(6, 182, 212, 0.4);
        }

        .gc-faq-q {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 26px;
            cursor: pointer;
            font-size: 14.5px;
            font-weight: 600;
            color: var(--gc-txt);
            gap: 16px;
            user-select: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .gc-faq-q i {
            color: var(--gc-cyan-bright);
            font-size: 13px;
            transition: transform .3s;
        }

        .gc-faq-item.is-open .gc-faq-q i {
            transform: rotate(45deg);
        }

        .gc-faq-a {
            padding: 0 26px;
            font-size: 14px;
            color: var(--gc-muted);
            line-height: 1.75;
            max-height: 0;
            overflow: hidden;
            transition: max-height .4s ease, padding .3s;
        }

        .gc-faq-item.is-open .gc-faq-a {
            max-height: 250px;
            padding: 0 26px 22px;
        }

        /* ─── FOOTER ─────────────────────────────────────────────────── */
        .gc-footer {
            background: var(--gc-bg);
            border-top: 1px solid var(--gc-border);
            padding: 36px 0;
        }

        .gc-footer__inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .gc-footer__left,
        .gc-footer__right {
            font-size: 13px;
            color: var(--gc-muted);
        }

        /* ─── FLOATING WHATSAPP ──────────────────────────────────────── */
        .gc-wa {
            position: fixed;
            bottom: 32px;
            right: 32px;
            z-index: 800;
            width: 56px;
            height: 56px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            box-shadow: 0 6px 28px rgba(37, 211, 102, .4);
            transition: all .3s;
        }

        .gc-wa:hover {
            transform: scale(1.1) translateY(-3px);
            color: #fff;
        }

        /* ─── RESPONSIVE ─────────────────────────────────────────────── */
        @media (max-width: 992px) {
            .gc-nav__links {
                display: none;
            }

            .gc-hamburger {
                display: flex;
            }

            .gc-features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .gc-split {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .gc-counters__grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .gc-plans-grid {
                grid-template-columns: 1fr;
            }

            .gc-faq__grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .gc-features-grid {
                grid-template-columns: 1fr;
            }

            .gc-hero__stats {
                flex-direction: column;
            }

            .gc-hero__stat {
                border-right: none;
                border-bottom: 1px solid var(--gc-border);
            }

            .gc-hero__stat:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Menu -->
    <div class="gc-mmenu" id="gcMMenu">
        <button class="gc-mmenu__close" id="gcMMenuClose" aria-label="Fechar menu">&#x2715;</button>
        <a href="#funcionalidades">Funcionalidades</a>
        <a href="#reconhecimento-facial">Reconhecimento Facial</a>
        <a href="#portal-aluno">Portal do Aluno</a>
        <a href="#planos">Planos</a>
        <a href="#faq">FAQ</a>
        <a href="{{ route('portal.login') }}">Portal do Aluno</a>
        <a href="{{ route('login') }}">Painel da Academia</a>
    </div>

    <!-- Navbar -->
    <nav class="gc-nav" id="gcNav">
        <div class="gc-wrap">
            <div class="gc-nav__inner">
                <a href="#" class="gc-nav__logo">
                    <i class="fa-solid fa-shield-halved"></i>
                    GESTÃO <span>COMBATE</span>
                </a>
                <ul class="gc-nav__links">
                    <li><a href="#funcionalidades">Funcionalidades</a></li>
                    <li><a href="#reconhecimento-facial">Reconhecimento Facial</a></li>
                    <li><a href="#portal-aluno">Portal do Aluno</a></li>
                    <li><a href="#planos">Planos</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="{{ route('portal.login') }}"><i class="fa-solid fa-user-graduate"></i> Aluno</a></li>
                    <a class="gc-nav__cta" href="{{ route('login') }}">
                        <i class="fa-solid fa-lock"></i> Acessar Sistema
                    </a>
                </ul>
                <button class="gc-hamburger" id="gcHamburgerBtn" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="gc-hero" id="home">
        <div class="gc-hero__bg">
            <div class="gc-hero__mesh"></div>
            <div class="gc-hero__grid-lines"></div>
        </div>
        <div class="gc-hero__body">
            <div class="gc-hero__badge">
                <span class="dot"></span>
                GESTAO INTELIGENTE • ARTES MARCIAIS • BIOMETRIA FACIAL POR IA
            </div>
            <h1 class="gc-hero__h1">
                A PLATAFORMA DEFINITIVA PARA SEU <span class="c-red">TATAME</span>
            </h1>
            <p class="gc-hero__sub">
                Reconhecimento Facial automático por Inteligência Artificial, presença em 1 segundo, financeiro Asaas integrado e portal do aluno responsivo.
            </p>
            <div class="gc-hero__ctas">
                <a class="gc-btn-primary" href="{{ route('login') }}">
                    <i class="fa-solid fa-lock"></i> Entrar no Sistema
                </a>
                <a class="gc-btn-secondary" href="{{ route('portal.login') }}">
                    <i class="fa-solid fa-user-graduate"></i> Portal do Aluno
                </a>
                <a class="gc-btn-secondary" style="border-color: var(--gc-cyan); color: var(--gc-cyan-bright);" href="{{ route('demo.landing') }}">
                    <i class="fa-solid fa-play"></i> Ver Demonstração
                </a>
            </div>
            <div class="gc-hero__stats">
                <div class="gc-hero__stat">
                    <span class="gc-hero__snum gc-hero__snum--red" data-count="2500">+0</span>
                    <span class="gc-hero__slabel">Alunos Ativos</span>
                </div>
                <div class="gc-hero__stat">
                    <span class="gc-hero__snum gc-hero__snum--cyan" data-count="120000">+0</span>
                    <span class="gc-hero__slabel">Presenças Registradas</span>
                </div>
                <div class="gc-hero__stat">
                    <span class="gc-hero__snum" data-count="99">0%</span>
                    <span class="gc-hero__slabel">Precisão Facial</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FUNCIONALIDADES GRID -->
    <section class="gc-sec gc-sec--surf" id="funcionalidades">
        <div class="gc-wrap">
            <div style="text-align:center; max-width:640px; margin:0 auto 0;">
                <div class="gc-tag">Recursos Exclusivos</div>
                <h2 class="gc-h2">Tecnologia Criada para <em>Mestres e Professores</em></h2>
                <p class="gc-desc" style="margin:0 auto;">
                    Diga adeus à burocracia das planilhas. Gerencie turmas, mensalidades e chamadas em um painel simples e ultrarrápido.
                </p>
            </div>
            <div class="gc-features-grid">
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-camera-rotate"></i></div>
                    <h3>Reconhecimento Facial</h3>
                    <p>Identificação biométrica por foto para registrar o treino automaticamente ao entrar na academia.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-building-columns"></i></div>
                    <h3>Financeiro & Asaas</h3>
                    <p>Emissão de cobranças, links de pagamento, PIX e Boletos com baixa automática via Webhook.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-clock"></i></div>
                    <h3>Frequência em Lote</h3>
                    <p>Grade de horários dinâmica e lançamento de presenças em grupo com apenas 1 clique.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-user-graduate"></i></div>
                    <h3>Portal do Aluno</h3>
                    <p>Área exclusiva para o aluno fazer check-in nas aulas, consultar mensalidades e atualizar sua foto.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <h3>Controle de Inadimplência</h3>
                    <p>Relatórios gerenciais de pagamentos em atraso para manter a saúde financeira sob controle.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-file-csv"></i></div>
                    <h3>Importação em Massa</h3>
                    <p>Cadastre dezenas de alunos simultaneamente enviando planilhas CSV/Excel.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-award"></i></div>
                    <h3>Graduações & Faixas</h3>
                    <p>Histórico de presenças por aluno para acompanhamento de exames de faixa e graus.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Multi-Tenant Seguro</h3>
                    <p>Isolamento total dos dados da sua academia com segurança corporativa.</p>
                </div>
                <div class="gc-feat-card">
                    <div class="gc-feat-icon"><i class="fa-solid fa-headset"></i></div>
                    <h3>Suporte Dedicado</h3>
                    <p>Equipe pronta para orientar o setup e tirar todas as suas dúvidas operacionais.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SPLIT 1: RECONHECIMENTO FACIAL -->
    <section class="gc-sec gc-sec--card" id="reconhecimento-facial">
        <div class="gc-wrap">
            <div class="gc-split">
                <div>
                    <div class="gc-tag gc-tag--cyan">Inovação em IA</div>
                    <h2 class="gc-h2 gc-h2--cyan">Check-in por <em>Biometria Facial</em></h2>
                    <p class="gc-desc" style="margin-bottom:24px;">
                        Com o nosso microserviço seus alunos registram presença olhando para a câmera na entrada do tatame. Rápido, seguro e sem filas.
                    </p>
                    <ul class="gc-flist">
                        <li>
                            <span class="gc-flist__icon"><i class="fa-solid fa-check"></i></span>
                            Identificação biométrica ultrarrápida.
                        </li>
                        <li>
                            <span class="gc-flist__icon"><i class="fa-solid fa-check"></i></span>
                            O próprio aluno envia/atualiza a foto de perfil pelo Portal do Aluno.
                        </li>
                        <li>
                            <span class="gc-flist__icon"><i class="fa-solid fa-check"></i></span>
                            Histórico de presenças sincronizado instantaneamente.
                        </li>
                    </ul>
                </div>
                <div>
                    <div class="gc-scanner-box">
                        <div class="gc-scanner-target">
                            <div class="gc-scanner-reticle"></div>
                            <i class="fa-solid fa-face-smile"></i>
                        </div>
                        <h3 style="font-family: var(--gc-font-head); font-size: 26px; font-weight: 700; color: var(--gc-txt);">Scanner Facial em Tempo Real</h3>
                        <p style="color: var(--gc-muted); font-size: 13.5px; margin-top: 10px;">
                            Utilize um tablet ou smartphone fixado no tatame para validação presencial imediata.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SPLIT 2: PORTAL DO ALUNO -->
    <section class="gc-sec gc-sec--surf" id="portal-aluno">
        <div class="gc-wrap">
            <div class="gc-split">
                <div>
                    <div class="gc-scanner-box" style="border-color: var(--gc-red); box-shadow: 0 0 45px var(--gc-red-glow);">
                        <i class="fa-solid fa-mobile-screen-button" style="font-size: 70px; color: var(--gc-red-bright); margin-bottom: 16px;"></i>
                        <h3 style="font-family: var(--gc-font-head); font-size: 26px; font-weight: 700; color: var(--gc-txt);">Portal Web Responsivo</h3>
                        <p style="color: var(--gc-muted); font-size: 13.5px; margin-top: 10px;">
                            Total comodidade para o aluno acompanhar seus treinos e mensalidades em qualquer dispositivo.
                        </p>
                    </div>
                </div>
                <div>
                    <div class="gc-tag">Transparência & Autonomia</div>
                    <h2 class="gc-h2">Portal Exclusivo do <em>Aluno</em></h2>
                    <p class="gc-desc" style="margin-bottom:24px;">
                        Ofereça uma experiência moderna para seus alunos com um portal prático para ver faturas, fazer auto check-in e manter seus dados de cadastro em dia.
                    </p>
                    <ul class="gc-cklist">
                        <li><span class="gc-cklist__icon"><i class="fa-solid fa-check"></i></span> Auto check-in na grade de aulas do dia</li>
                        <li><span class="gc-cklist__icon"><i class="fa-solid fa-check"></i></span> Consulta de mensalidades e emissão de PIX/Boleto</li>
                        <li><span class="gc-cklist__icon"><i class="fa-solid fa-check"></i></span> Upload da foto de perfil para biometria</li>
                        <li><span class="gc-cklist__icon"><i class="fa-solid fa-check"></i></span> Troca de senha e gestão de perfil</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- COUNTERS -->
    <div class="gc-counters">
        <div class="gc-wrap">
            <div class="gc-counters__grid">
                <div class="gc-counter-card">
                    <span class="gc-cnum gc-cnum--red" data-count="100">100%</span>
                    <span class="gc-clabel">Dados Protegidos</span>
                </div>
                <div class="gc-counter-card">
                    <span class="gc-cnum gc-cnum--cyan" data-count="24">24/7</span>
                    <span class="gc-clabel">Acesso Online</span>
                </div>
                <div class="gc-counter-card">
                    <span class="gc-cnum" data-count="1">1s</span>
                    <span class="gc-clabel">Leitura Biométrica</span>
                </div>
                <div class="gc-counter-card">
                    <span class="gc-cnum gc-cnum--red" data-count="0">R$ 0</span>
                    <span class="gc-clabel">Taxa de Adesão</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PLANOS -->
    <section class="gc-sec gc-sec--card" id="planos">
        <div class="gc-wrap">
            <div style="text-align:center; max-width:640px; margin:0 auto;">
                <div class="gc-tag">Assinatura Transparente</div>
                <h2 class="gc-h2">Planos Adequados ao Tamanho do Seu <em>Tatame</em></h2>
                <p class="gc-desc" style="margin:0 auto;">Escolha o plano ideal para a sua academia e comece agora mesmo.</p>
            </div>
            <div class="gc-plans-grid">
                <div class="gc-plan-card">
                    <p class="gc-plan-name">Iniciante</p>
                    <p class="gc-plan-price">R$ 59<span>/mês</span></p>
                    <ul class="gc-plan-feats">
                        <li><i class="fa-solid fa-check"></i> Até 30 Alunos Ativos</li>
                        <li><i class="fa-solid fa-check"></i> Controle de Presenças</li>
                        <li><i class="fa-solid fa-check"></i> Portal do Aluno</li>
                    </ul>
                    <a class="gc-btn-primary" style="text-align:center; justify-content:center;" href="{{ route('register') }}">Começar Agora</a>
                </div>
                <div class="gc-plan-card is-featured">
                    <span class="gc-plan-badge">Mais Recomendado</span>
                    <p class="gc-plan-name">Intermediário</p>
                    <p class="gc-plan-price">R$ 109<span>/mês</span></p>
                    <ul class="gc-plan-feats">
                        <li><i class="fa-solid fa-check"></i> Até 100 Alunos Ativos</li>
                        <li><i class="fa-solid fa-check"></i> Reconhecimento Facial por IA</li>
                        <li><i class="fa-solid fa-check"></i> Automação Asaas (PIX / Boleto)</li>
                        <li><i class="fa-solid fa-check"></i> Relatórios de Inadimplência</li>
                        <li><i class="fa-solid fa-check"></i> Importação em Lote CSV</li>
                    </ul>
                    <a class="gc-btn-primary" style="text-align:center; justify-content:center;" href="{{ route('register') }}">Começar Agora</a>
                </div>
                <div class="gc-plan-card">
                    <p class="gc-plan-name">Pro / Ilimitado</p>
                    <p class="gc-plan-price">R$ 189<span>/mês</span></p>
                    <ul class="gc-plan-feats">
                        <li><i class="fa-solid fa-check"></i> Alunos Ilimitados</li>
                        <li><i class="fa-solid fa-check"></i> Reconhecimento Facial por IA</li>
                        <li><i class="fa-solid fa-check"></i> Gestão Financeira Completa</li>
                        <li><i class="fa-solid fa-check"></i> Múltiplos Professores / Turmas</li>
                        <li><i class="fa-solid fa-check"></i> Suporte Prioritário</li>
                    </ul>
                    <a class="gc-btn-primary" style="text-align:center; justify-content:center;" href="{{ route('register') }}">Começar Agora</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ ACCORDION -->
    <section class="gc-sec gc-sec--surf" id="faq">
        <div class="gc-wrap">
            <div style="text-align:center; max-width:640px; margin:0 auto 36px;">
                <div class="gc-tag gc-tag--cyan">FAQ</div>
                <h2 class="gc-h2 gc-h2--cyan">Dúvidas <em>Frequentes</em></h2>
            </div>
            <div class="gc-faq__grid">
                <div class="gc-faq-item">
                    <button class="gc-faq-q" type="button">
                        Como funciona o Reconhecimento Facial no tatame?
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <div class="gc-faq-a">
                        O aluno registra a foto no Portal do Aluno. Na entrada da academia, você pode posicionar um tablet ou smartphone. A câmera lê a face do aluno e o sistema confirma a presença na aula correspondente em menos de 1 segundo.
                    </div>
                </div>
                <div class="gc-faq-item">
                    <button class="gc-faq-q" type="button">
                        O aluno precisa pagar para acessar o portal?
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <div class="gc-faq-a">
                        Não! O acesso do aluno ao Portal do Aluno é totalmente grátis e já faz parte da assinatura do sistema.
                    </div>
                </div>
                <div class="gc-faq-item">
                    <button class="gc-faq-q" type="button">
                        Como a baixa automática de pagamentos Asaas funciona?
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <div class="gc-faq-a">
                        Você gera as cobranças de mensalidade no painel. O Asaas disponibiliza links de PIX e boleto. Assim que o aluno quita a mensalidade, o Asaas notifica o sistema via Webhook e dá baixa automática!
                    </div>
                </div>
                <div class="gc-faq-item">
                    <button class="gc-faq-q" type="button">
                        Consigo importar alunos cadastrados em planilhas?
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <div class="gc-faq-a">
                        Sim! Você pode cadastrar uma turma inteira enviando um arquivo CSV/Excel de forma extremamente fácil.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="gc-footer">
        <div class="gc-wrap">
            <div class="gc-footer__inner">
                <div class="gc-footer__left">
                    Gestão Combate &copy; {{ date('Y') }} — Todos os direitos reservados.
                </div>
                <div class="gc-footer__right">
                    Gestão Inteligente para Academias e Artes Marciais.
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating -->
    <a href="https://wa.me/5562993236704" target="_blank" class="gc-wa" title="Fale conosco no WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- SCRIPTS -->
    <script>
        (function() {
            'use strict';

            // Navbar scroll
            var nav = document.getElementById('gcNav');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 40) {
                    nav.classList.add('is-scrolled');
                } else {
                    nav.classList.remove('is-scrolled');
                }
            }, {
                passive: true
            });

            // Mobile menu
            var mmenu = document.getElementById('gcMMenu');
            var mopen = document.getElementById('gcHamburgerBtn');
            var mclose = document.getElementById('gcMMenuClose');
            if (mopen && mmenu) {
                mopen.addEventListener('click', function() {
                    mmenu.classList.add('is-open');
                });
            }
            if (mclose && mmenu) {
                mclose.addEventListener('click', function() {
                    mmenu.classList.remove('is-open');
                });
            }
            if (mmenu) {
                mmenu.querySelectorAll('a').forEach(function(a) {
                    a.addEventListener('click', function() {
                        mmenu.classList.remove('is-open');
                    });
                });
            }

            // FAQ Accordion
            document.querySelectorAll('.gc-faq-q').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var item = btn.closest('.gc-faq-item');
                    var wasOpen = item.classList.contains('is-open');
                    document.querySelectorAll('.gc-faq-item').forEach(function(el) {
                        el.classList.remove('is-open');
                    });
                    if (!wasOpen) item.classList.add('is-open');
                });
            });

            // Stat Counter animation
            function animateCount(el) {
                var target = parseInt(el.getAttribute('data-count'), 10);
                if (!target) return;
                var duration = 1500;
                var startTime = null;

                function step(ts) {
                    if (!startTime) startTime = ts;
                    var progress = Math.min((ts - startTime) / duration, 1);
                    var current = Math.round(progress * target);
                    if (el.textContent.includes('%')) {
                        el.textContent = current + '%';
                    } else if (el.textContent.includes('+')) {
                        el.textContent = '+' + current.toLocaleString('pt-BR');
                    } else {
                        el.textContent = current.toLocaleString('pt-BR');
                    }
                    if (progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            animateCount(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.4
                });
                document.querySelectorAll('[data-count]').forEach(function(el) {
                    observer.observe(el);
                });
            }
        })();
    </script>
</body>

</html>