<x-guest-layout>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyColoc — Connexion</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
     
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:    #2a9db5;
            --teal:    #1d8a9e;
            --green:   #4caf50;
            --green-d: #388e3c;
            --dark:    #0d1f26;
            --card-bg: #ffffff;
            --text:    #1a2e35;
            --muted:   #6b8892;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark);
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(42,157,181,0.35) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(76,175,80,0.25) 0%, transparent 55%),
                radial-gradient(ellipse 40% 40% at 60% 30%, rgba(29,138,158,0.2) 0%, transparent 50%);
            animation: meshFloat 8s ease-in-out infinite alternate;
        }

        @keyframes meshFloat {
            0%   { opacity: .8; transform: scale(1) rotate(0deg); }
            100% { opacity: 1;  transform: scale(1.05) rotate(1deg); }
        }

        .bg-shape {
            position: fixed;
            opacity: .07;
            animation: floatShape 12s ease-in-out infinite;
        }
        .bg-shape:nth-child(1) { top: 5%;  left: 5%;  width: 180px; animation-delay: 0s; }
        .bg-shape:nth-child(2) { top: 60%; right: 8%; width: 130px; animation-delay: 3s; }
        .bg-shape:nth-child(3) { bottom: 5%; left: 30%; width: 100px; animation-delay: 6s; }

        @keyframes floatShape {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50%       { transform: translateY(-20px) rotate(3deg); }
        }

        /* Card — plus étroit */
        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 360px;
            margin: 1.5rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(0,0,0,.45), 0 0 0 1px rgba(42,157,181,.15);
            overflow: hidden;
            animation: cardIn .6s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Header — plus compact */
        .card-header {
            background: linear-gradient(135deg, var(--blue) 0%, var(--teal) 40%, var(--green-d) 100%);
            padding: 1.6rem 2rem 2.4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -26px;
            left: 0; right: 0;
            height: 52px;
            background: var(--card-bg);
            border-radius: 50% 50% 0 0 / 52px 52px 0 0;
        }

        /* Logo réduit */
        .card-header .logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,.3));
            animation: logoPop .7s .2s cubic-bezier(.34,1.56,.64,1) both;
        }

        @keyframes logoPop {
            from { opacity: 0; transform: scale(.5); }
            to   { opacity: 1; transform: scale(1); }
        }

        .brand-name {
            margin-top: .4rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.4px;
        }

        .brand-name span { color: rgba(255,255,255,.7); font-weight: 400; }

        /* Body — espacement réduit */
        .card-body {
            padding: 1.5rem 1.8rem 1.8rem;
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1.1rem;
        }

        /* Champs — plus serrés */
        .field { margin-bottom: .8rem; }

        label {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: .28rem;
        }

        .input-wrap { position: relative; }

        .input-wrap svg {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--blue);
            width: 15px; height: 15px;
            pointer-events: none;
        }

        /* Inputs plus petits */
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: .55rem .8rem .55rem 2.2rem;
            border: 1.5px solid #e2edf0;
            border-radius: 10px;
            font-family: inherit;
            font-size: .875rem;
            color: var(--text);
            background: #f7fbfc;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }

        input:focus {
            border-color: var(--blue);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(42,157,181,.12);
        }

        /* Remember + forgot */
        .row-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: .7rem 0 1.1rem;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .8rem;
            color: var(--muted);
            cursor: pointer;
        }

        input[type="checkbox"] {
            accent-color: var(--blue);
            width: 14px; height: 14px;
        }

        .forgot-link {
            font-size: .78rem;
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }
        .forgot-link:hover { color: var(--green); }

        /* Bouton — padding réduit */
        .btn-submit {
            width: 100%;
            padding: .72rem;
            background: linear-gradient(135deg, var(--blue) 0%, var(--green-d) 100%);
            color: #fff;
            font-family: inherit;
            font-size: .9rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: .02em;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 5px 16px rgba(42,157,181,.4);
        }

        .btn-submit:hover {
            opacity: .92;
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(42,157,181,.5);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Footer */
        .card-footer {
            text-align: center;
            padding: 0 1.8rem 1.4rem;
            font-size: .8rem;
            color: var(--muted);
        }

        .card-footer a {
            color: var(--green-d);
            font-weight: 700;
            text-decoration: none;
        }
        .card-footer a:hover { color: var(--blue); }

        .alert-success {
            background: #e8f5e9;
            border-left: 4px solid var(--green);
            color: var(--green-d);
            padding: .55rem .85rem;
            border-radius: 7px;
            font-size: .83rem;
            margin-bottom: .85rem;
        }

        .alert-error {
            background: #fdecea;
            border-left: 4px solid #e53935;
            color: #c62828;
            padding: .55rem .85rem;
            border-radius: 7px;
            font-size: .83rem;
            margin-bottom: .85rem;
        }
    </style>
</head>
<body>

    <svg class="bg-shape" viewBox="0 0 100 90" fill="white" xmlns="http://www.w3.org/2000/svg">
        <polygon points="50,5 95,40 5,40"/><rect x="20" y="40" width="60" height="50"/><rect x="38" y="55" width="24" height="35"/>
    </svg>
    <svg class="bg-shape" viewBox="0 0 100 90" fill="white" xmlns="http://www.w3.org/2000/svg">
        <polygon points="50,5 95,40 5,40"/><rect x="20" y="40" width="60" height="50"/><rect x="38" y="55" width="24" height="35"/>
    </svg>
    <svg class="bg-shape" viewBox="0 0 100 90" fill="white" xmlns="http://www.w3.org/2000/svg">
        <polygon points="50,5 95,40 5,40"/><rect x="20" y="40" width="60" height="50"/><rect x="38" y="55" width="24" height="35"/>
    </svg>

    <div class="card">
        <div class="card-header">
           
            <div class="brand-name">Easy<span>Coloc</span></div>
        </div>

        <div class="card-body">
            <div class="section-title">Bienvenue</div>

            <x-validation-errors class="alert-error" />

            @session('status')
                <div class="alert-success">{{ $value }}</div>
            @endsession

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Adresse email</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="votre@email.com">
                    </div>
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>
                </div>

                <div class="row-meta">
                    <label class="check-label">
                        <input type="checkbox" id="remember_me" name="remember">
                        Se souvenir de moi
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">Se connecter</button>
            </form>
        </div>

        <div class="card-footer">
            Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a>
        </div>
    </div>

</body>
</html>
</x-guest-layout>