@extends('layouts.app')

@section('page_title', 'Mes Colocations')
@section('title', 'Mes Colocations - EasyColoc')

@section('content')

<div class="flex justify-between items-center mb-12">
    <div>
        <h2 class="text-3xl font-bold text-slate-900">Mes Colocations</h2>
        <p class="text-slate-500 text-sm mt-1">Gérez et explorez vos colocations</p>
    </div>
    <a href="{{ route('colocations.create') }}" class="
        inline-flex items-center px-6 py-3 rounded-xl
        bg-gradient-to-r from-blue-600 to-indigo-600
        text-white font-semibold
        hover:shadow-lg transform hover:-translate-y-0.5
        transition-all duration-200
    ">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Créer
    </a>
</div>

@if($colocations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($colocations as $colocation)
            <div class="group relative overflow-hidden rounded-2xl bg-white/90 backdrop-blur-md border border-slate-200 shadow-lg hover:shadow-xl transition-all duration-300">
                <!-- Gradient Badge -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r
                    @if($colocation->isActive()) from-emerald-500 to-teal-500 @else from-slate-400 to-slate-500 @endif
                "></div>

                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-900">{{ $colocation->nom_colocation }}</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="w-2 h-2 rounded-full @if($colocation->isActive()) bg-emerald-500 @else bg-slate-400 @endif"></div>
                                <p class="text-xs text-slate-600 font-medium uppercase tracking-wider">
                                    @if($colocation->isActive()) Actif @else Inactif @endif
                                </p>
                            </div>
                        </div>
                        @if($colocation->owner_id === auth()->id())
                            <span class="px-3 py-1 rounded-full bg-gradient-to-r from-amber-100 to-orange-100 text-amber-700 text-xs font-semibold">Propriétaire</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 text-xs font-semibold">Membre</span>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($colocation->discription)
                        <p class="text-slate-600 text-sm mb-6 line-clamp-2 min-h-8">{{ $colocation->discription }}</p>
                    @else
                        <p class="text-slate-400 text-sm italic mb-6\">Aucune description</p>
                    @endif

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-3 mb-6 p-4 bg-slate-50 rounded-xl">
                        <div class="text-center">
                            <svg class="w-5 h-5 mx-auto mb-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3V5a6 6 0 0118 0v14zm-6-13a2 2 0 11-4 0 2 2 0 014 0zM9 21v-2m6 0v-2"/>
                            </svg>
                            <p class="text-xs text-slate-600 mb-1">Membres</p>
                            <p class="text-lg font-bold text-slate-900">{{ $colocation->getMembersCount() }}</p>
                        </div>
                        <div class="text-center">
                            <svg class="w-5 h-5 mx-auto mb-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <p class="text-xs text-slate-600 mb-1">Catégories</p>
                            <p class="text-lg font-bold text-slate-900">{{ $colocation->categories()->count() }}</p>
                        </div>
                        <div class="text-center">
                            <svg class="w-5 h-5 mx-auto mb-1 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-slate-600 mb-1">Dépenses</p>
                            <p class="text-lg font-bold text-slate-900">{{ $colocation->depenses()->count() }}</p>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-2">
                        <a href="{{ route('colocations.show', $colocation) }}" class="
                            flex-1 inline-flex items-center justify-center px-4 py-2 rounded-xl
                            bg-slate-100 text-slate-900
                            font-semibold text-sm
                            hover:bg-slate-200
                            transition-colors
                        ">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Voir
                        </a>
                        @if($colocation->owner_id === auth()->id())
                            <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" class="flex-1" onsubmit="return confirm('Êtes-vous sûr ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="
                                    w-full px-4 py-2 rounded-xl
                                    bg-red-50 text-red-600
                                    font-semibold text-sm
                                    hover:bg-red-100
                                    transition-colors
                                ">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-slate-600 text-lg font-medium mb-4">Vous n'avez pas encore de colocation</p>
        <p class="text-slate-500 mb-6">Créez votre première colocation pour commencer</p>
        <a href="{{ route('colocations.create') }}" class="
            inline-flex items-center px-6 py-3 rounded-xl
            bg-gradient-to-r from-blue-600 to-indigo-600
            text-white font-semibold
            hover:shadow-lg transform hover:-translate-y-0.5
            transition-all duration-200
        ">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer une Colocation
        </a>
    </div>
@endif

@endsection
