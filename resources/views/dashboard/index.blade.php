@extends('layouts.app')

@section('page_title', 'Dashboard')
@section('title', 'Dashboard - EasyColoc')

@section('content')

<div class="min-h-screen bg-slate-50 p-6">

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

        <!-- Card: Mon score réputation -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Mon score réputation</p>
                    <p class="text-4xl font-bold text-slate-900">{{ $stats['reputation'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Dépenses Globales -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Dépenses Globales ({{ now()->format('M') }})</p>
                    <p class="text-4xl font-bold text-slate-900">{{ number_format($stats['monthly_expenses'] ?? 0, 2) }} <span class="text-2xl">€</span></p>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Dépenses Récentes (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Dépenses récentes</h2>
                    @if($activeColocation)
                        <a href="{{ route('expenses.index', $activeColocation) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-semibold transition-colors">
                            Voir tout
                        </a>
                    @endif
                </div>

                <!-- Table Header -->
                <div class="grid grid-cols-12 px-6 py-3 bg-slate-50 border-b border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider col-span-4">Titre / Catégorie</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider col-span-3 text-center">Payeur</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider col-span-3 text-right">Montant</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider col-span-2 text-right">Coloc</p>
                </div>

                <!-- Expense Rows -->
                @if($recentExpenses->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($recentExpenses as $expense)
                            <div class="grid grid-cols-12 px-6 py-4 hover:bg-slate-50 transition-colors duration-150 items-center">
                                <!-- Title + Category -->
                                <div class="col-span-4">
                                    <p class="font-semibold text-slate-800 text-sm">{{ $expense->titre_depense }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-xs font-medium">
                                        {{ $expense->categorie->titre_categorie }}
                                    </span>
                                </div>
                                <!-- Payeur -->
                                <div class="col-span-3 flex justify-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0" title="{{ $expense->user->name }}">
                                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($expense->user->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <!-- Montant -->
                                <div class="col-span-3 text-right">
                                    <p class="text-base font-bold text-slate-900">{{ $expense->getMontantFormatted() }}</p>
                                </div>
                                <!-- Coloc -->
                                <div class="col-span-2 text-right">
                                    <p class="text-slate-400 text-xs font-medium truncate">{{ $expense->colocation->nom_colocation ?? '—' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-slate-400 text-sm font-medium">Aucune dépense récente.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Sidebar (1/3 width) -->
        <div class="space-y-4">

            <!-- Membres de la coloc -->
            <div class="bg-slate-900 rounded-2xl shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/10">
                    <h3 class="text-sm font-bold text-white">Membres de la coloc</h3>
                    @if($activeColocation)
                        <span class="px-2 py-0.5 rounded-full bg-white/10 text-white/70 text-xs font-semibold">
                            {{ $activeColocation->getMembersCount() }}
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-white/10 text-white/70 text-xs font-semibold">VIDE</span>
                    @endif
                </div>

                <!-- Members List or Empty -->
                <div class="px-5 py-4">
                    @if($activeColocation)
                        <div class="space-y-3">
                            @foreach($activeColocation->users as $user)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-white text-sm font-semibold">{{ $user->name }}</p>
                                            <p class="text-white/50 text-xs">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    @if($user->id === $activeColocation->owner_id)
                                        <span class="px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold">Owner</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-white/10 text-white/60 text-xs font-semibold">Membre</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Actions -->
                        @if($activeColocation->owner_id === auth()->id())
                            <div class="mt-4 pt-4 border-t border-white/10 space-y-2">
                                <a href="{{ route('invitations.create', $activeColocation) }}" class="flex items-center gap-2 w-full px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Inviter un membre
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-white/50 text-sm">Aucune colocation active.</p>
                        <a href="{{ route('colocations.create') }}" class="mt-4 flex items-center gap-2 w-full px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Créer une colocation
                        </a>
                    @endif
                </div>
            </div>



        </div>
    </div>

</div>

@endsection