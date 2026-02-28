@extends('layouts.app')

@section('content')

<div class="max-w-lg mx-auto bg-white p-6 rounded-2xl shadow">

    <h2 class="text-2xl font-bold mb-6">
        Nouvelle dépense - {{ $colocation->nom_colocation }}
    </h2>

    <form method="POST" action="{{ route('expenses.store', $colocation) }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">
                Titre
            </label>
            <input type="text" name="titre_depense"
                   class="w-full border rounded-lg px-3 py-2"
                   required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">
                Montant
            </label>
            <input type="number" step="0.01" name="montant"
                   class="w-full border rounded-lg px-3 py-2"
                   required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-1">
                Catégorie
            </label>
            <select name="categorie_id"
                    class="w-full border rounded-lg px-3 py-2"
                    required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->titre_categorie }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
            Ajouter la dépense
        </button>

    </form>

</div>

@endsection