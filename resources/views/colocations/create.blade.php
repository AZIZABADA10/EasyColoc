@extends('layouts.app')

@section('page_title', 'Nouvelle Colocation')
@section('title', 'Créer une Colocation - EasyColoc')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-white">
            <h2 class="text-3xl font-bold">Créer une Colocation</h2>
            <p class="text-blue-100 mt-2">Lancez une nouvelle colocation et invitez vos colocataires</p>
        </div>

        <form method="POST" action="{{ route('colocations.store') }}" class="p-8 space-y-6">
            @csrf

            <!-- Nom Colocation -->
            <div>
                <label for="nom_colocation" class="block text-slate-900 font-semibold mb-3 text-sm uppercase tracking-wider">
                    Nom de la Colocation
                </label>
                <input type="text" 
                       name="nom_colocation" 
                       id="nom_colocation"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              transition duration-200
                              @error('nom_colocation') border-red-500 @enderror"
                       value="{{ old('nom_colocation') }}"
                       placeholder="ex: Colocation Rue de la Paix">
                @error('nom_colocation')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="discription" class="block text-slate-900 font-semibold mb-3 text-sm uppercase tracking-wider">
                    Description
                </label>
                <textarea name="discription" 
                          id="discription"
                          rows="4"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                transition duration-200
                                @error('discription') border-red-500 @enderror resize-none"
                          placeholder="Décrivez votre colocation, localisation, nombre de chambres...">{{ old('discription') }}</textarea>
                @error('discription')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer la Colocation
                </button>
                <a href="{{ route('colocations.index') }}" class="
                    flex-1 inline-flex items-center justify-center px-6 py-3 rounded-xl
                    bg-slate-200 text-slate-900
                    font-semibold
                    hover:bg-slate-300
                    transition-all duration-200
                ">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
