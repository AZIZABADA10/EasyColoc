@extends('layouts.app')

@section('page_title', $colocation->nom_colocation)
@section('title', $colocation->nom_colocation . ' - EasyColoc')

@section('content')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Message Flash Success
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
            <div class="flex-shrink-0">
                <i class='bx bx-check-circle text-emerald-600 text-xl'></i>
            </div>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

  Message Flash Error -->
    <!-- @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <div class="flex-shrink-0">
                <i class='bx bx-error-circle text-red-600 text-xl'></i>
            </div>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif -->  

    <!-- Gestion des Catégories (Owner Only) -->
    @if(auth()->id() === $colocation->owner_id)
        <div class="mb-6 bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-slate-900 text-sm">Catégories</h3>
                <a href="{{ route('categories.create', $colocation) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-xs transition-colors">
                    <i class='bx bx-plus text-sm'></i>
                    Ajouter
                </a>
            </div>

            @if($colocation->categories->count() > 0)
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($colocation->categories as $category)
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-lg border border-slate-200 group hover:bg-slate-200 transition">
                            <span class="text-xs font-medium text-slate-700">{{ $category->titre_categorie }}</span>
                            <div class="flex gap-1">
                                <!-- Modifier -->
                                <a href="{{ route('categories.edit', $category) }}" 
                                   class="text-slate-400 hover:text-blue-600 transition"
                                   title="Modifier">
                                    <i class='bx bx-edit-alt text-xs'></i>
                                </a>
                                <!-- Supprimer -->
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display:inline;"
                                      onsubmit="return confirm('Supprimer cette catégorie ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition">
                                        <i class='bx bx-x text-sm'></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500 mt-3">Aucune catégorie. <a href="{{ route('categories.create', $colocation) }}" class="text-blue-600 hover:underline">En créer une</a></p>
            @endif
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $colocation->nom_colocation }}</h1>
                <p class="text-slate-500 mt-1 text-sm">{{ $colocation->discription }}</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full 
                    @if($colocation->isActive()) bg-emerald-100 text-emerald-800 @else bg-slate-100 text-slate-800 @endif">
                    <div class="w-2 h-2 rounded-full @if($colocation->isActive()) bg-emerald-500 @else bg-slate-400 @endif"></div>
                    <span class="font-semibold text-xs">@if($colocation->isActive()) Actif @else Inactif @endif</span>
                </div>

                <!-- Bouton Annuler (Owner Only) -->
                @if(auth()->id() === $colocation->owner_id)
                    <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" style="display:inline;"
                          onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-300 hover:bg-red-50 text-red-600 font-semibold text-sm transition-colors">
                            <i class='bx bx-trash text-base'></i>
                            Annuler la colocation
                        </button>
                    </form>
                @endif

                <!-- Bouton Retour -->
                <a href="{{ route('colocations.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm transition-colors">
                    <i class='bx bx-arrow-back text-base'></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout 70/30 -->
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">

        <!-- Left Column (70%) - Dépenses -->
        <div class="lg:col-span-5">

            <!-- Header Dépenses Récentes -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-slate-900">Dépenses récentes</h2>
                <a href="{{ route('expenses.create', $colocation) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm transition-colors">
                    <i class='bx bx-plus text-base'></i>
                    Nouvelle dépense
                </a>
            </div>

            <!-- Filter par mois -->
            <div class="mb-4">
                <form method="GET" action="{{ route('colocations.show', $colocation) }}" class="flex items-center gap-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-600">
                        <i class='bx bx-filter-alt text-slate-500'></i>
                        Filtrer par mois:
                    </label>
                    <select name="mois" onchange="this.form.submit()"
                            class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="">Tous les mois</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @if(request('mois') == $m) selected @endif>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F Y') }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>

            <!-- Dépenses Table -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <!-- Table Header -->
                <div class="grid grid-cols-12 px-4 py-3 bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    <div class="col-span-5">Titre / Catégorie</div>
                    <div class="col-span-3 text-center">Payeur</div>
                    <div class="col-span-2 text-right">Montant</div>
                    <div class="col-span-2 text-center">Action</div>
                </div>

                <!-- Dépenses Rows -->
                @php
                    $filteredExpenses = $colocation->depenses()->latest();
                    if(request('mois')) {
                        $filteredExpenses = $filteredExpenses->whereMonth('created_at', request('mois'));
                    }
                    $filteredExpenses = $filteredExpenses->get();
                @endphp

                @forelse($filteredExpenses as $expense)
                    <div class="grid grid-cols-12 px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition-colors items-center last:border-0">
                        <!-- Titre / Catégorie -->
                        <div class="col-span-5">
                            <p class="font-semibold text-slate-900 text-sm">{{ $expense->titre_depense }}</p>
                            @if($expense->categorie)
                                <p class="text-xs text-slate-400 mt-0.5">{{ $expense->categorie->titre_categorie }}</p>
                            @endif
                        </div>

                        <!-- Payeur - Avatar uniquement -->
                        <div class="col-span-3 flex justify-center">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center"
                                 title="{{ $expense->user->name }}">
                                <span class="text-white font-bold text-xs">
                                    {{ strtoupper(substr($expense->user->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Montant -->
                        <div class="col-span-2 text-right">
                            <p class="font-bold text-slate-900 text-sm">
                                {{ number_format($expense->montant, 2, ',', ' ') }} €
                            </p>
                        </div>

                        <!-- Action -->
                        <div class="col-span-2 flex justify-center">
                            @if($expense->user_id === auth()->id() || $colocation->owner_id === auth()->id())
                                <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;"
                                      onsubmit="return confirm('Supprimer cette dépense ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class='bx bx-x text-lg'></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center">
                        <i class='bx bx-receipt text-slate-300 text-4xl mb-2'></i>
                        <p class="text-slate-400 font-medium text-sm">Aucune dépense enregistrée</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column (30%) -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Bloc "Qui doit à qui ?" -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm">Qui doit à qui ?</h3>
                </div>

                <div class="p-4 space-y-3">
                    @if($debts && count($debts) > 0)
                        @foreach($debts as $debt)
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <p class="text-xs text-slate-600 mb-2 flex items-center gap-1">
                                    <span class="font-semibold text-slate-800">{{ $debt['from']->name }}</span>
                                    <i class='bx bx-right-arrow-alt text-slate-400'></i>
                                    <span class="font-semibold text-slate-800">{{ $debt['to']->name }}</span>
                                </p>
                                <div class="flex justify-between items-center">
                                    <p class="text-base font-bold text-emerald-600">
                                        +{{ number_format($debt['montant'], 2, ',', ' ') }} €
                                    </p>
                                    @if(auth()->id() === $debt['from_id'] || auth()->id() === $colocation->owner_id)
                                        <form method="POST"
                                              action="{{ route('debts.mark-as-paid', [$colocation, $debt['from_id'], $debt['to_id']]) }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="text-xs px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                                Marquer payé
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i class='bx bx-check-double text-emerald-400 text-3xl mb-1'></i>
                            <p class="text-slate-400 font-medium text-sm">Aucune dette</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bloc "Membres de la coloc" - Dark Theme -->
            <div class="bg-slate-800 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-white text-sm">Membres de la coloc</h3>
                    <span class="text-xs font-bold bg-blue-600 text-white px-2 py-0.5 rounded">
                        ACTIFS
                    </span>
                </div>

                <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                    @foreach($colocation->users as $user)
                        <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-700 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Avatar -->
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-white text-sm truncate">{{ $user->name }}</p>
                                    @if($user->id === $colocation->owner_id)
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-400 mt-0.5">
                                            <i class='bx bxs-crown text-xs'></i>
                                            OWNER
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">
                                            Rep: {{ $user->reputation_score ?? 0 }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <!-- Owner : bouton kick sur les autres membres -->
                                    @if(auth()->id() === $colocation->owner_id && $user->id !== $colocation->owner_id)
                                        <form method="POST" action="{{ route('colocations.removeMember', [$colocation, $user]) }}"
                                            onsubmit="return confirm('Retirer {{ $user->name }} de la colocation ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-red-400 hover:bg-slate-600 rounded-lg transition-colors"
                                                    title="Retirer le membre">
                                                <i class='bx bx-user-minus text-sm'></i>
                                            </button>
                                        </form>

                                    <!-- Membre connecté (non-owner) : bouton quitter sur sa propre ligne -->
                                    @elseif(auth()->id() === $user->id && $user->id !== $colocation->owner_id)
                                        <form method="POST" action="{{ route('colocations.leave', $colocation) }}"
                                            onsubmit="return confirm('Quitter cette colocation ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-amber-400 hover:bg-slate-600 rounded-lg transition-colors"
                                                    title="Quitter la colocation">
                                                <i class='bx bx-log-out text-sm'></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bouton Inviter Un Membre -->
                @if($colocation->owner_id === auth()->id())
                    <div class="p-4 border-t border-slate-700">
                        <a href="{{ route('invitations.create', $colocation) }}"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-semibold text-sm transition-colors border border-slate-600">
                            <i class='bx bx-user-plus text-base'></i>
                            Inviter un membre
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection