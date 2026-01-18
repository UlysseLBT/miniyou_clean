<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MiniYou') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    {{-- On garde Vite si tu l’as, mais on FORCE notre CSS après --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* FORCE le rendu même si app.css/Tailwind écrase */
        :root { color-scheme: dark; }
        html, body { height: 100%; }

        body {
            margin: 0 !important;
            font-family: "Instrument Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif !important;
            background: #070707 !important;
            color: #fff !important;
            overflow-x: hidden;
        }

        /* Background glow */
        body::before{
            content:"";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(900px 500px at 20% 15%, rgba(255,70,70,.22), transparent 60%),
                radial-gradient(900px 600px at 85% 25%, rgba(255,140,0,.14), transparent 60%),
                radial-gradient(900px 700px at 40% 90%, rgba(120,120,255,.12), transparent 65%);
            pointer-events: none;
            z-index: 0;
        }

        a { color: inherit; text-decoration: none; }

        .wrap{
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar{
            padding: 22px 26px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .navlink{
            padding: 9px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.05);
            color: rgba(255,255,255,.92);
            transition: .15s ease;
            font-weight: 600;
            font-size: 13px;
        }
        .navlink:hover{ background: rgba(255,255,255,.09); transform: translateY(-1px); }

        .navsolid{
            padding: 9px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.16);
            background: rgba(255,255,255,.11);
            color: #fff;
            transition: .15s ease;
            font-weight: 700;
            font-size: 13px;
        }
        .navsolid:hover{ background: rgba(255,255,255,.15); transform: translateY(-1px); }

        .main{
            flex: 1;
            display: grid;
            place-items: center;
            padding: 26px;
        }

        .shell{
            width: min(1100px, 100%);
        }

        .panel{
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.04);
            box-shadow:
                0 20px 60px rgba(0,0,0,.55),
                inset 0 1px 0 rgba(255,255,255,.06);
        }

        .left{
            padding: 34px;
            position: relative;
            background: rgba(0,0,0,.25);
        }

        .brand{
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(0,0,0,.25);
            color: rgba(255,255,255,.92);
            font-weight: 700;
            letter-spacing: .02em;
            font-size: 12px;
        }

        .dot{
            width: 9px; height: 9px; border-radius: 999px;
            background: #ff3b30;
            box-shadow: 0 0 0 7px rgba(255,59,48,.12);
        }

        h1{
            margin: 14px 0 10px;
            font-size: 44px;
            line-height: 1.05;
            letter-spacing: -0.02em;
        }

        .sub{
            margin: 0;
            color: rgba(255,255,255,.70);
            font-size: 15px;
            line-height: 1.6;
            max-width: 52ch;
        }

        .cta{
            margin-top: 18px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btnPrimary{
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.16);
            background: rgba(255,59,48,.20);
            font-weight: 800;
            font-size: 14px;
            transition: .15s ease;
        }
        .btnPrimary:hover{ background: rgba(255,59,48,.30); transform: translateY(-1px); }

        .btnGhost{
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.06);
            font-weight: 700;
            font-size: 14px;
            transition: .15s ease;
        }
        .btnGhost:hover{ background: rgba(255,255,255,.10); transform: translateY(-1px); }

        .chips{
            margin-top: 18px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .chip{
            padding: 8px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(0,0,0,.22);
            color: rgba(255,255,255,.78);
            font-size: 12px;
            font-weight: 700;
        }

        .right{
            padding: 28px;
            background:
                radial-gradient(700px 380px at 20% 10%, rgba(255,59,48,.22), transparent 60%),
                rgba(30,0,0,.35);
            border-left: 1px solid rgba(255,255,255,.08);
        }

        .rightTitle{
            margin: 0 0 10px;
            font-size: 16px;
            font-weight: 800;
        }

        .card{
            margin-top: 12px;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(0,0,0,.25);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.05);
        }

        .card h3{
            margin: 0 0 6px;
            font-size: 13px;
            font-weight: 900;
            color: rgba(255,255,255,.92);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .muted{
            margin: 0;
            font-size: 13px;
            line-height: 1.55;
            color: rgba(255,255,255,.68);
        }

        .footer{
            padding: 18px 26px;
            display: flex;
            justify-content: center;
            color: rgba(255,255,255,.45);
            font-size: 12px;
        }

        @media (max-width: 900px){
            .panel{ grid-template-columns: 1fr; }
            .right{ border-left: 0; border-top: 1px solid rgba(255,255,255,.08); }
            h1{ font-size: 34px; }
        }
    </style>
</head>

<body>
<div class="wrap">

    {{-- Login / Register en haut à droite --}}
    <header class="topbar">
        @if (Route::has('login'))
            @auth
                <a class="navsolid" href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a class="navlink" href="{{ route('login') }}">Log in</a>

                @if (Route::has('register'))
                    <a class="navsolid" href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        @endif
    </header>

    <main class="main">
        <div class="shell">
            <section class="panel">

                <div class="left">
                    <div class="brand">
                        <span class="dot"></span>
                        <span>{{ config('app.name', 'MiniYou') }}</span>
                        <span style="opacity:.55;">•</span>
                        <span style="opacity:.85;">Accueil</span>
                    </div>

                    <h1>Bienvenue sur {{ config('app.name', 'MiniYou') }}.</h1>

                    <p class="sub">
                        Page d'acceuil de MinyYou
                        Plateforme de partage de liens et de discussions.
                        Rejoignez notre communauté dès aujourd'hui et commencez à partager vos idées !
                    </p>
                </div>

            </section>
        </div>
    </main>

    <footer class="footer">
        © {{ date('Y') }} {{ config('app.name', 'MiniYou') }} — Tous droits réservés
    </footer>
</div>
</body>
</html>
