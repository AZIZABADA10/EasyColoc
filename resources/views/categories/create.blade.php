@extends('layouts.app')

@section('page_title', 'Nouvelle Catégorie')
@section('title', 'Créer une Catégorie - EasyColoc')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-8 text-white">
            <h2 class="text-3xl font-bold">Nouvelle Catégorie</h2>
            <p class="text-emerald-100 mt-2">Pour: <strong>{{ $colocation->nom_colocation }}</strong></p>
        </div>

        <form method="POST" action="{{ route('categories.store', $colocation) }}" class="p-8 space-y-6">
            @csrf

            <!-- Titre Catégorie -->
            <div>
                <label for="titre_categorie" class="block text-slate-900 font-semibold mb-3 text-sm uppercase tracking-wider">
                    Nom de la Catégorie
                </label>
                <input type="text" 
                       name="titre_categorie" 
                       id="titre_categorie"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                              focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                              transition duration-200
                              @error('titre_categorie') border-red-500 @enderror"
                       value="{{ old('titre_categorie') }}"
                       placeholder="ex: Nourriture, Électricité, Nettoyage...">
                @error('titre_categorie')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Exemples -->
            <div class="bg-emerald-50 border border-emerald-200 p-6 rounded-xl">
                <h3 class="font-semibold text-emerald-900 mb-3 text-sm uppercase tracking-wider">Catégories Courantes</h3>
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex items-center gap-2 text-emerald-800 text-sm">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        Nourriture
                    </div>
                    <div class="flex items-center gap-2 text-emerald-800 text-sm">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        Électricité
                    </div>
                    <div class="flex items-center gap-2 text-emerald-800 text-sm">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        Nettoyage
                    </div>
                    <div class="flex items-center gap-2 text-emerald-800 text-sm">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        Internet
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="
                    flex-1 inline-flex items-center justify-center px-6 py-3 rounded-xl
                    bg-gradient-to-r from-emerald-600 to-teal-600
                    text-white font-semibold
                    hover:shadow-lg transform hover:-translate-y-0.5
                    transition-all duration-200
                ">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer la Catégorie
                </button>
                <a href="{{ route('colocations.show', $colocation) }}" class="
                    flex-1 inline-flex items-center justify-center px-6 py-3 rounded-xl
                    bg-slate-200 text-slate-900
                    font-semibold
                    hover:bg-slate-300
                    transition-all duration-200
                ">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
