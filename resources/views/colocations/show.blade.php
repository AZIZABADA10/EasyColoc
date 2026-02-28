@extends('layouts.app')

@section('page_title', $colocation->nom_colocation)
@section('title', $colocation->nom_colocation . ' - EasyColoc')

@section('content')


@if(session('info'))
    <div class="bg-blue-100 text-blue-800 p-3 mb-4 rounded shadow">
        {{ session('info') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-3 mb-4 rounded shadow">
        {{ session('error') }}
    </div>
@endif

<!-- Header Card -->
<div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-4xl font-bold">{{ $colocation->nom_colocation }}</h1>
                @if($colocation->discription)
                    <p class="text-blue-100 mt-3 text-lg">{{ $colocation->discription }}</p>
                @endif
                <p class="text-blue-200 text-sm mt-4">Créée le {{ $colocation->created_at->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full 
                    @if($colocation->isActive()) bg-emerald-500/20 text-emerald-100 @else bg-slate-500/20 text-slate-100 @endif
                ">
                    <div class="w-2 h-2 rounded-full @if($colocation->isActive()) bg-emerald-400 @else bg-slate-400 @endif"></div>
                    <span class="font-semibold">@if($colocation->isActive()) Actif @else Inactif @endif</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grid de 3 sections -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- ================= MEMBRES ================= -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col">
        
        <div class="bg-gradient-to-r from-blue-100 to-indigo-100 p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Colocataires</h3>
                <span class="px-3 py-1 rounded-full bg-blue-600 text-white text-sm font-bold">
                    {{ $colocation->getMembersCount() }}
                </span>
            </div>
        </div>

        <div class="flex-1 p-6 space-y-3">

            @foreach($colocation->users as $user)

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">

                    <!-- Infos utilisateur -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-900 text-sm">
                                {{ $user->name }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ $user->email }}
                            </p>

                            <p class="text-xs mt-1">
                                @if($user->id === $colocation->owner_id)
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-semibold">
                                        Owner
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-semibold">
                                        Member
                                    </span>
                                @endif

                                <span class="ml-2 text-slate-500">
                                     Réputation : {{ $user->reputation_score ?? 0 }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">

                        <!-- Owner peut retirer -->
                        @if(auth()->id() === $colocation->owner_id && $user->id !== $colocation->owner_id)
                            <form method="POST"
                                  action="{{ route('colocations.removeMember', [$colocation, $user]) }}"
                                  onsubmit="return confirm('Retirer ce membre ?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 transition-colors text-sm font-semibold">
                                    Retirer
                                </button>
                            </form>
                        @endif

                        <!-- Member peut quitter -->
                        @if(auth()->id() === $user->id && $user->id !== $colocation->owner_id)
                            <form method="POST"
                                  action="{{ route('colocations.leave', $colocation) }}"
                                  onsubmit="return confirm('Quitter la colocation ?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-slate-600 hover:text-slate-900 transition-colors text-sm font-semibold">
                                    Quitter
                                </button>
                            </form>
                        @endif

                    </div>

                </div>

            @endforeach

        </div>

        <!-- Bouton Inviter -->
        @if($colocation->owner_id === auth()->id())
            <div class="p-6 border-t border-slate-200">
                <a href="{{ route('invitations.create', $colocation) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl
                          bg-gradient-to-r from-purple-600 to-pink-600
                          text-white font-semibold text-sm
                          hover:shadow-lg transform hover:-translate-y-0.5
                          transition-all duration-200">
                    Inviter
                </a>
            </div>
        @endif

    </div>

    <!-- ================= CATÉGORIES ================= -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col">

        <div class="bg-gradient-to-r from-emerald-100 to-teal-100 p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Catégories</h3>
                <span class="px-3 py-1 rounded-full bg-emerald-600 text-white text-sm font-bold">
                    {{ $colocation->categories()->count() }}
                </span>
            </div>
        </div>

        <div class="flex-1 p-6 space-y-2 overflow-y-auto max-h-64">
            @forelse($colocation->categories as $category)
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <p class="font-semibold text-slate-900 text-sm">
                        {{ $category->titre_categorie }}
                    </p>
                </div>
            @empty
                <p class="text-slate-500 text-sm italic text-center py-8">
                    Aucune catégorie
                </p>
            @endforelse
        </div>
    </div>

    <!-- ================= STATS ================= -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col">

        <div class="bg-gradient-to-r from-orange-100 to-red-100 p-6 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900">Dépenses</h3>
        </div>

        <div class="flex-1 p-6 space-y-4">

            <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
                <p class="text-orange-600 text-xs font-semibold uppercase mb-2">
                    Ce Mois-ci
                </p>
                <p class="text-3xl font-bold text-orange-600">
                    {{ number_format($colocation->getMonthlyExpensesTotal(), 2) }} DH
                </p>
            </div>

            <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                <p class="text-purple-600 text-xs font-semibold uppercase mb-2">
                    Par Personne
                </p>
                <p class="text-3xl font-bold text-purple-600">
                    {{ number_format($colocation->getMonthlyExpensesTotal() / max($colocation->getMembersCount(), 1), 2) }} DH
                </p>
            </div>

        </div>

    </div>

</div>



    @if($colocation->depenses->count() > 0)
        <div class="space-y-3">
            @foreach($colocation->depenses()->latest()->limit(5)->get() as $expense)
                <div class="flex justify-between items-center p-4 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-red-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $expense->titre_depense }}</p>
                                <p class="text-xs text-slate-600">
                                    <span class="font-semibold">{{ $expense->user->name }}</span> • 
                                    <span class="text-slate-500">{{ $expense->categorie->titre_categorie }}</span>
                                </p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500">{{ $expense->created_at->format('d M Y \\à H:i') }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <p class="text-lg font-bold text-orange-600 whitespace-nowrap">{{ $expense->getMontantFormatted() }}</p>
                        @if($expense->user_id === auth()->id() || $colocation->owner_id === auth()->id())
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;" onsubmit="return confirm('Supprimer cette dépense ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-12 text-center">
            <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-slate-600 text-lg font-medium">Aucune dépense enregistrée</p>
        </div>
    @endif
</div>

@endsection
 