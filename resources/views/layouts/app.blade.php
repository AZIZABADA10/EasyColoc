<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EasyColoc') - Gestion de Colocation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="flex h-screen">
        <!-- SIDEBAR -->
        @include('components.sidebar')

        <!-- CONTENU PRINCIPAL -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <div class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-8 py-4 flex justify-between items-center shadow-sm">
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    @yield('page_title', 'Dashboard')
                </h1>
                
                <div class="flex items-center space-x-6">
                    <div class="flex flex-col items-end">
                        <a href="{{ route('profile.show') }}" class="flex flex-col items-end group">
                            <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="text-xs text-slate-500 group-hover:text-blue-500 transition">
                                Utilisateur
                            </span>
                        </a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="
                            text-slate-600 hover:text-red-600 
                            font-medium text-sm 
                            transition duration-200 
                            hover:bg-red-50 px-3 py-2 rounded-lg
                        ">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contenu avec scroll -->
            <div class="flex-1 overflow-auto">
                <!-- Messages de succès/erreur -->
                @if ($message = Session::get('success'))
                    <div class="m-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ $message }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="m-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm backdrop-blur-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="font-medium">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contenu de la page -->
                <div class="p-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>