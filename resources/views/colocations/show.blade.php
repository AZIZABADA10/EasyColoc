@extends('layouts.app')

@section('page_title', $colocation->nom_colocation)
@section('title', $colocation->nom_colocation . ' - EasyColoc')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Message Flash Success -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Message Flash Error -->
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2" />
                </svg>
            </div>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">{{ $colocation->nom_colocation }}</h1>
                <p class="text-slate-600 mt-2">{{ $colocation->discription }}</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                    @if($colocation->isActive()) 
                        bg-emerald-100 text-emerald-800 
                    @else 
                        bg-slate-100 text-slate-800 
                    @endif
                ">
                    <div class="w-2 h-2 rounded-full @if($colocation->isActive()) bg-emerald-600 @else bg-slate-400 @endif"></div>
                    <span class="font-semibold text-sm">@if($colocation->isActive()) Actif @else Inactif @endif</span>
                </div>

                <!-- Bouton Retour -->
                <a href="{{ route('colocations.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold transition-colors">
                    ← Retour
                </a>

                <!-- Bouton Annuler (Owner Only) -->
                @if(auth()->id() === $colocation->owner_id)
                    <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" style="display:inline;" 
                          onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition-colors">
                            🗑️ Annuler la colocation
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Grid Layout 70/30 -->
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
        
        <!-- Left Column (70%) - Dépenses -->
        <div class="lg:col-span-5">
            
            <!-- Header Dépenses Récentes -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">Dépenses récentes</h2>
                    <a href="{{ route('expenses.create', $colocation) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        + Nouvelle dépense
                    </a>
                </div>

                <!-- Filter (optional) -->
                <div class="mb-4">
                    <button class="text-slate-600 hover:text-slate-900 transition-colors text-sm font-medium">
                        ▼ Filtrer par mois: Tous les mois
                    </button>
                </div>
            </div>

            <!-- Dépenses List -->
            <div class="space-y-3">
                @forelse($colocation->depenses()->latest()->get() as $expense)
                    <div class="bg-white rounded-xl p-4 border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start gap-4">
                            <!-- Expense Info -->
                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-2">
                                    <!-- Avatar Circle -->
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-sm">
                                            {{ strtoupper(substr($expense->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $expense->titre_depense }}</p>
                                        <p class="text-sm text-slate-600">
                                            <span class="font-semibold">{{ $expense->user->name }}</span>
                                            @if($expense->categorie)
                                                • <span class="text-slate-500">{{ $expense->categorie->titre_categorie }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount and Action -->
                            <div class="flex items-center gap-4 flex-shrink-0">
                                <p class="text-lg font-bold text-slate-900 whitespace-nowrap">
                                    {{ number_format($expense->montant, 2, ',', ' ') }} €
                                </p>
                                @if($expense->user_id === auth()->id() || $colocation->owner_id === auth()->id())
                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;" 
                                          onsubmit="return confirm('Supprimer cette dépense ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl p-12 border border-slate-200 text-center">
                        <p class="text-slate-500 font-medium">Aucune dépense enregistrée</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column (30%) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Bloc "Qui doit à qui ?" -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-100 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-900">Qui doit à qui ?</h3>
                </div>

                <div class="p-6 space-y-3">
                    @if($debts && count($debts) > 0)
                        @foreach($debts as $debt)
                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
                                <p class="text-sm text-slate-700 mb-2">
                                    <span class="font-semibold">{{ $debt['from']->name }}</span> 
                                    <span class="text-slate-500">→</span> 
                                    <span class="font-semibold">{{ $debt['to']->name }}</span>
                                </p>
                                <div class="flex justify-between items-center">
                                    <p class="text-lg font-bold text-emerald-600">
                                        +{{ number_format($debt['montant'], 2, ',', ' ') }} €
                                    </p>
                                    @if(auth()->id() === $debt['from_id'])
                                        <form method="POST" 
                                              action="{{ route('debts.mark-as-paid', [$colocation, $debt['from_id'], $debt['to_id']]) }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                    class="text-xs px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium transition-colors">
                                                Marquer payé
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <p class="text-slate-500 font-medium">✓ Aucune dette</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bloc "Membres de la coloc" -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-slate-100 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-900">Membres de la coloc</h3>
                    <span class="text-xs font-bold bg-slate-200 text-slate-900 px-2 py-1 rounded">{{ $colocation->getMembersCount() }}</span>
                </div>

                <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                    @foreach($colocation->users as $user)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Avatar -->
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-semibold text-slate-900 text-sm truncate">{{ $user->name }}</p>
                                        @if($user->id === $colocation->owner_id)
                                            <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded whitespace-nowrap">
                                                👑 OWNER
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-600">⭐ Rep: {{ $user->reputation_score ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bouton Inviter Un Membre -->
                @if($colocation->owner_id === auth()->id())
                    <div class="border-t border-slate-200 p-6">
                        <a href="{{ route('invitations.create', $colocation) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors">
                            👥 Inviter un membre
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
