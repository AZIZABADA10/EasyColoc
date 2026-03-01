@extends('layouts.app')

@section('page_title', 'Modifier - ' . $colocation->nom_colocation)
@section('title', 'Modifier la colocation - EasyColoc')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900">Modifier la colocation</h1>
        <p class="text-slate-600 mt-2">Mettez à jour les informations de votre colocation</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('colocations.update', $colocation) }}">
                @csrf
                @method('PUT')

                <!-- Nom Colocation -->
                <div class="mb-6">
                    <label for="nom_colocation" class="block text-sm font-semibold text-slate-900 mb-2">
                        Nom de la colocation
                    </label>
                    <input 
                        type="text"
                        id="nom_colocation"
                        name="nom_colocation"
                        value="{{ old('nom_colocation', $colocation->nom_colocation) }}"
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom_colocation') !border-red-500 @enderror"
                        placeholder="Ex: Colocation Rue de la Paix"
                        required
                    />
                    @error('nom_colocation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <label for="discription" class="block text-sm font-semibold text-slate-900 mb-2">
                        Description (optionnel)
                    </label>
                    <textarea 
                        id="discription"
                        name="discription"
                        rows="4"
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('discription') !border-red-500 @enderror"
                        placeholder="Décrivez votre colocation (localisation, ambiance, etc.)"
                    >{{ old('discription', $colocation->discription) }}</textarea>
                    @error('discription')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <button 
                        type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors"
                    >
                        Enregistrer les modifications
                    </button>
                    <a 
                        href="{{ route('colocations.show', $colocation) }}"
                        class="px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold rounded-lg transition-colors"
                    >
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-blue-900 text-sm">
            <strong>💡 Info :</strong> Seul le propriétaire peut modifier les informations de la colocation.
        </p>
    </div>
</div>

@endsection
