<aside class="w-64 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white p-6 flex flex-col border-r border-slate-700">
    <!-- Logo & Brand -->
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-2">

            <div>
                <h1 class="text-xl font-bold">EasyColoc</h1>
                <p class="text-xs text-slate-400">Gestion simplifiée</p>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent mb-6"></div>

    <!-- Menu Navigation -->
    <nav class="flex-1 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="
           group
           flex items-center px-4 py-3 rounded-xl
           transition-all duration-200
           @if(request()->routeIs('dashboard')) 
               bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg
           @else 
               text-slate-300 hover:bg-slate-700/50 hover:text-white
           @endif
           ">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            <span class="font-semibold text-sm">Dashboard</span>
        </a>

        <!-- Colocations -->
        <a href="{{ route('colocations.index') }}" 
           class="
           group
           flex items-center px-4 py-3 rounded-xl
           transition-all duration-200
           @if(request()->routeIs('colocations.*')) 
               bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg
           @else 
               text-slate-300 hover:bg-slate-700/50 hover:text-white
           @endif
           ">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4-4"/>
            </svg>
            <span class="font-semibold text-sm">Colocations</span>
        </a>

        <!-- Admin Panel -->
        @if(auth()->user()->is_admin)
            <div class="pt-2 mt-4 border-t border-slate-700">
                <p class="text-xs font-semibold text-slate-400 px-4 py-2">ADMINISTRATION</p>
                <a href="{{ route('admin.dashboard') }}" 
                   class="
                   flex items-center px-4 py-3 rounded-xl
                   transition-all duration-200
                   @if(request()->routeIs('admin.*')) 
                       bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg
                   @else 
                       text-slate-300 hover:bg-red-600/20 hover:text-red-400
                   @endif
                   ">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-semibold text-sm">Admin Panel</span>
                </a>
            </div>
        @endif
    </nav>

    <!-- Divider -->
    <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent my-6"></div>

    <!-- User Info Footer -->
    <div class="bg-slate-700/40 rounded-xl p-4 border border-slate-600/50">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Profil</span>
            @if(auth()->user()->is_banned)
                <span class="text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded">Banni</span>
            @endif
        </div>
        <div class="space-y-2">
            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400">Réputation</span>
                <span class="text-sm font-bold text-yellow-400">{{ auth()->user()->reputation ?? 0 }}</span>
            </div>
        </div>
    </div>
</aside>