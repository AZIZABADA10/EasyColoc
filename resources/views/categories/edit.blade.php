@extends('layouts.app')

@section('page_title', 'Modifier Catégorie')
@section('title', 'Modifier une Catégorie - EasyColoc')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-white">
            <h2 class="text-3xl font-bold">Modifier Catégorie</h2>
            <p class="text-blue-100 mt-2">Pour: <strong>{{ $colocation->nom_colocation }}</strong></p>
        </div>

        <form method="POST" action="{{ route('categories.update', $categorie) }}" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Titre Catégorie -->
            <div>
                <label for="titre_categorie" class="block text-slate-900 font-semibold mb-3 text-sm uppercase tracking-wider">
                    Nom de la Catégorie
                </label>
                <input type="text" 
                       name="titre_categorie" 
                       id="titre_categorie"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              transition duration-200
                              @error('titre_categorie') border-red-500 @enderror"
                       value="{{ old('titre_categorie', $categorie->titre_categorie) }}"
                       placeholder="ex: Nourriture, Électricité, Nettoyage...">
                @error('titre_categorie')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border border-blue-200 p-6 rounded-xl">
                <h3 class="font-semibold text-blue-900 mb-2 text-sm uppercase tracking-wider">Information</h3>
                <p class="text-blue-800 text-sm">Cette catégorie est utilisée par <strong>{{ $categorie->depenses()->count() }}</strong> dépense(s).</p>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="
                    flex-1 inline-flex items-center justify-center px-6 py-3 rounded-xl
                    bg-gradient-to-r from-blue-600 to-indigo-600
                    text-white font-semibold
                    hover:shadow-lg transform hover:-translate-y-0.5
                    transition-all duration-200
                ">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mettre à jour
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
