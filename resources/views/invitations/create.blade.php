@extends('layouts.app')

@section('page_title', 'Inviter un Membre')
@section('title', 'Inviter un Membre - EasyColoc')

@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow">

    <h1 class="text-2xl font-bold text-gray-800 mb-1">Inviter un Membre</h1>
    <p class="text-gray-500 mb-6">Pour: {{ $colocation->nom_colocation }}</p>

    <form method="POST" action="{{ route('invitations.store', $colocation->id) }}">
        @csrf

        {{-- Champ Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Adresse Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="exemple@email.com"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

         

        {{-- Boutons --}}
        <div class="flex items-center gap-3">
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-md transition"
            >
                Envoyer l'Invitation
            </button>
            <a
                href="{{ route('colocations.show', $colocation->id) }}"
                class="text-gray-500 hover:text-gray-700 text-sm"
            >
                Retour
            </a>
        </div>

    </form>
</div>
@endsection