@extends('layouts.app')

@section('page_title', 'Gestion du Site')
@section('title', 'Admin Panel - EasyColoc')

@section('content')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<div class="space-y-8">

    <!-- Statistiques Globales -->
    <div>
        <h2 class="text-2xl font-bold text-slate-900 mb-6">Statistiques Globales</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card: Total Utilisateurs -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Utilisateurs</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-slate-500 mt-2">
                            {{ $stats['active_users'] }} actifs · {{ $stats['banned_users'] }} bannis
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class='bx bx-user text-blue-600 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Card: Administrateurs -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Administrateurs</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['admin_count'] }}</p>
                        <p class="text-xs text-slate-500 mt-2">
                            Utilisateurs avec droits admin
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i class='bx bxs-crown text-amber-600 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Card: Colocations -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Colocations</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_colocations'] }}</p>
                        <p class="text-xs text-slate-500 mt-2">
                            {{ $stats['active_colocations'] }} actives · {{ $stats['inactive_colocations'] }} inactives
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class='bx bx-building text-emerald-600 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Card: Dépenses -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Dépenses Totales</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_spent'], 2, ',', ' ') }} DH</p>
                        <p class="text-xs text-slate-500 mt-2">
                            {{ $stats['total_expenses'] }} transactions · Moy: {{ number_format($stats['avg_expense'], 2, ',', ' ') }} DH
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class='bx bx-money text-purple-600 text-xl'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des Utilisateurs -->
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Gestion des Utilisateurs</h2>
        </div>

        <!-- Filtre et Recherche -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Recherche -->
                <input type="text" 
                       placeholder="Rechercher par nom ou email..."
                       class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm"
                       id="search-input">

                <!-- Filtres -->
                <div class="flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition
                       @if(!request('filter')) bg-slate-900 text-white @else bg-slate-100 text-slate-900 hover:bg-slate-200 @endif">
                        Tous
                    </a>
                    <a href="{{ route('admin.dashboard', ['filter' => 'active']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition
                       @if(request('filter') === 'active') bg-emerald-600 text-white @else bg-slate-100 text-slate-900 hover:bg-slate-200 @endif">
                        Actifs
                    </a>
                    <a href="{{ route('admin.dashboard', ['filter' => 'banned']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition
                       @if(request('filter') === 'banned') bg-red-600 text-white @else bg-slate-100 text-slate-900 hover:bg-slate-200 @endif">
                        Bannis
                    </a>
                    <a href="{{ route('admin.dashboard', ['filter' => 'admin']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition
                       @if(request('filter') === 'admin') bg-amber-600 text-white @else bg-slate-100 text-slate-900 hover:bg-slate-200 @endif">
                        Admin
                    </a>
                </div>
            </div>
        </div>

        <!-- Table des Utilisateurs -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <!-- Table Header -->
            <div class="grid grid-cols-12 px-6 py-4 bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                <div class="col-span-1">ID</div>
                <div class="col-span-3">Utilisateur</div>
                <div class="col-span-3">Email</div>
                <div class="col-span-2 text-center">Réputation</div>
                <div class="col-span-3">Statut</div>
            </div>

            <!-- Table Rows -->
            @forelse($users as $user)
                <div class="grid grid-cols-12 px-6 py-4 border-b border-slate-100 hover:bg-slate-50 transition items-center">
                    <!-- ID -->
                    <div class="col-span-1">
                        <span class="text-xs font-mono text-slate-500">{{ $user->id }}</span>
                    </div>

                    <!-- Utilisateur / Nom -->
                    <div class="col-span-3">
                        <p class="font-semibold text-slate-900 text-sm">{{ $user->name }}</p>
                        @if($user->is_admin)
                            <span class="inline-flex items-center gap-1 text-xs text-amber-600 font-bold mt-1">
                                <i class='bx bxs-crown text-xs'></i> Admin
                            </span>
                        @endif
                    </div>

                    <!-- Email -->
                    <div class="col-span-3">
                        <p class="text-sm text-slate-600 truncate">{{ $user->email }}</p>
                    </div>

                    <!-- Réputation -->
                    <div class="col-span-2 text-center">
                        <span class="text-sm font-bold text-yellow-600">{{ $user->reputation ?? 0 }}</span>
                    </div>

                    <!-- Statut & Actions -->
                    <div class="col-span-3 flex items-center justify-between">
                        <!-- Badge Statut -->
                        @if($user->is_banned)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                <i class='bx bx-block text-sm'></i> Banni
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                <i class='bx bx-check-circle text-sm'></i> Actif
                            </span>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-2">
                            @if($user->id !== auth()->id())
                                @if($user->is_banned)
                                    <!-- Débannir -->
                                    <form method="POST" action="{{ route('admin.unban', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" 
                                                class="w-8 h-8 flex items-center justify-center text-emerald-600 hover:bg-emerald-100 rounded-lg transition"
                                                title="Débannir">
                                            <i class='bx bx-check text-sm'></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Bannir -->
                                    <form method="POST" action="{{ route('admin.ban', $user) }}" 
                                          onsubmit="return confirm('Êtes-vous sûr ? Cet utilisateur sera ban et retiré de toutes ses colocations.');" 
                                          style="display:inline;">
                                        @csrf
                                        <button type="submit" 
                                                class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-100 rounded-lg transition"
                                                title="Bannir">
                                            <i class='bx bx-block text-sm'></i>
                                        </button>
                                    </form>

                                    <!-- Toggle Admin -->
                                    @if($user->is_admin)
                                        <form method="POST" 
                                              action="{{ route('admin.removeAdmin', $user) }}"
                                              onsubmit="return confirm('Retirer les droits admin ?');"
                                              style="display:inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-8 h-8 flex items-center justify-center text-amber-600 hover:bg-amber-100 rounded-lg transition"
                                                    title="Retirer Admin">
                                                <i class='bx bxs-crown text-sm'></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" 
                                              action="{{ route('admin.makeAdmin', $user) }}"
                                              style="display:inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-100 rounded-lg transition"
                                                    title="Promouvoir Admin">
                                                <i class='bx bx-crown text-sm'></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <i class='bx bx-search text-slate-300 text-4xl mb-3'></i>
                    <p class="text-slate-400 font-medium">Aucun utilisateur trouvé</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>

</div>

<script>
// Recherche en direct (optionnel - ajoute un délai)
document.getElementById('search-input').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const search = this.value;
        window.location.href = '{{ route("admin.dashboard") }}?search=' + encodeURIComponent(search);
    }
});
</script>

@endsection
