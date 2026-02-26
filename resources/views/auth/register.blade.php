<x-guest-layout>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyColoc — Inscription</title>
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
            padding: 2rem 0;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 55% at 80% 10%, rgba(76,175,80,0.3) 0%, transparent 55%),
                radial-gradient(ellipse 70% 55% at 10% 80%, rgba(42,157,181,0.3) 0%, transparent 55%);
            animation: meshFloat 9s ease-in-out infinite alternate;
        }
        @keyframes meshFloat { 0% { transform: scale(1); } 100% { transform: scale(1.04) rotate(-1deg); } }
        .bg-shape { position: fixed; opacity: .06; animation: floatShape 14s ease-in-out infinite; }
        .bg-shape:nth-child(1) { top: 3%; right: 5%; width: 160px; }
        .bg-shape:nth-child(2) { bottom: 3%; left: 5%; width: 120px; animation-delay: 4s; }
        @keyframes floatShape { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-18px) rotate(-3deg); } }
        .card {
            position: relative; z-index: 10;
            width: 100%; max-width: 460px; margin: 0 1.5rem;
            background: var(--card-bg); border-radius: 24px;
            box-shadow: 0 30px 80px rgba(0,0,0,.45);
            overflow: hidden;
            animation: cardIn .6s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes cardIn { from { opacity:0; transform: translateY(30px) scale(.97); } to { opacity:1; transform: translateY(0) scale(1); } }
        .card-header {
            background: linear-gradient(135deg, var(--green-d) 0%, var(--teal) 50%, var(--blue) 100%);
            padding: 2rem 2.4rem 2.8rem;
            display: flex; align-items: center; gap: 1.2rem;
            position: relative; overflow: hidden;
        }
        .card-header::after {
            content: ''; position: absolute; bottom: -28px; left: 0; right: 0;
            height: 56px; background: var(--card-bg);
            border-radius: 50% 50% 0 0 / 56px 56px 0 0;
        }
        .card-header .logo {
            width: 70px; height: 70px; object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(0,0,0,.3)); flex-shrink: 0;
            animation: logoPop .7s .2s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes logoPop { from { opacity:0; transform: scale(.4) rotate(-10deg); } to { opacity:1; transform: scale(1); } }
        .header-text h1 { font-size: 1.5rem; font-weight: 800; color: #fff; }
        .header-text p { font-size: .82rem; color: rgba(255,255,255,.75); margin-top: .2rem; }
        .progress-dots { display: flex; gap: .4rem; margin-top: .6rem; }
        .progress-dots span { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,.4); }
        .progress-dots span.active { width: 20px; border-radius: 3px; background: #fff; }
        .card-body { padding: 2rem 2.4rem 1.5rem; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 1.2rem; }
        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 1rem; }
        .field { margin-bottom: 1rem; }
        .field.full { grid-column: 1 / -1; }
        label {
            display: block; font-size: .75rem; font-weight: 600; color: var(--muted);
            text-transform: uppercase; letter-spacing: .08em; margin-bottom: .35rem;
        }
        .input-wrap { position: relative; }
        .input-wrap svg { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--blue); width: 16px; height: 16px; pointer-events: none; }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: .7rem .85rem .7rem 2.4rem;
            border: 1.5px solid #e2edf0; border-radius: 11px;
            font-family: inherit; font-size: .9rem; color: var(--text);
            background: #f7fbfc; transition: border-color .2s, box-shadow .2s; outline: none;
        }
        input:focus { border-color: var(--green); background: #fff; box-shadow: 0 0 0 4px rgba(76,175,80,.12); }
        .terms-block { background: #f0faf1; border: 1.5px solid #c8e6c9; border-radius: 10px; padding: .8rem 1rem; margin-bottom: 1.1rem; }
        .terms-block label { display: flex; align-items: flex-start; gap: .6rem; text-transform: none; letter-spacing: 0; font-size: .84rem; color: var(--text); font-weight: 400; cursor: pointer; }
        input[type="checkbox"] { accent-color: var(--green); width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
        .terms-block a { color: var(--blue); font-weight: 600; text-decoration: none; }
        .btn-submit {
            width: 100%; padding: .88rem;
            background: linear-gradient(135deg, var(--green-d) 0%, var(--blue) 100%);
            color: #fff; font-family: inherit; font-size: 1rem; font-weight: 700;
            border: none; border-radius: 12px; cursor: pointer;
            box-shadow: 0 6px 20px rgba(76,175,80,.4);
            transition: opacity .2s, transform .15s, box-shadow .2s;
        }
        .btn-submit:hover { opacity: .92; transform: translateY(-1px); box-shadow: 0 10px 28px rgba(76,175,80,.5); }
        .card-footer { text-align: center; padding: 0 2.4rem 1.8rem; font-size: .85rem; color: var(--muted); }
        .card-footer a { color: var(--blue); font-weight: 700; text-decoration: none; }
        .alert-error { background: #fdecea; border-left: 4px solid #e53935; color: #c62828; padding: .65rem .9rem; border-radius: 8px; font-size: .86rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <svg class="bg-shape" viewBox="0 0 100 90" fill="white" xmlns="http://www.w3.org/2000/svg"><polygon points="50,5 95,40 5,40"/><rect x="20" y="40" width="60" height="50"/><rect x="38" y="55" width="24" height="35"/></svg>
    <svg class="bg-shape" viewBox="0 0 100 90" fill="white" xmlns="http://www.w3.org/2000/svg"><polygon points="50,5 95,40 5,40"/><rect x="20" y="40" width="60" height="50"/><rect x="38" y="55" width="24" height="35"/></svg>

    <div class="card">
        <div class="card-header">
            <img src="{{ asset('logo.png') }}" alt="EasyColoc" class="logo">
            <div class="header-text">
                <h1>Rejoignez EasyColoc</h1>
                <p>Trouvez votre coloc idéale en quelques clics</p>
                <div class="progress-dots">
                    <span class="active"></span><span></span><span></span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="section-title">Créer votre compte</div>
            <x-validation-errors class="alert-error" />

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="field-grid">
                    <div class="field">
                        <label for="name">Nom complet</label>
                        <div class="input-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M3 21c0-4.418 4.03-8 9-8s9 3.582 9 8"/></svg>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Jean Dupont">
                        </div>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <div class="input-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="jean@email.com">
                        </div>
                    </div>
                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <div class="input-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Confirmer</label>
                        <div class="input-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="terms-block">
                        <label for="terms">
                            <input type="checkbox" name="terms" id="terms" required>
                            <span>J'accepte les <a href="{{ route('terms.show') }}" target="_blank">Conditions d'utilisation</a> et la <a href="{{ route('policy.show') }}" target="_blank">Politique de confidentialité</a></span>
                        </label>
                    </div>
                @endif

                <button type="submit" class="btn-submit">Créer mon compte</button>
            </form>
        </div>

        <div class="card-footer">
            Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
        </div>
    </div>
</body>
</html>
</x-guest-layout>