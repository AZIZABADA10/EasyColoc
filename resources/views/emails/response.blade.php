@extends('layouts.app')

@section('page_title', 'Réponse à l\'invitation')
@section('title', 'Invitation - EasyColoc')

@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Invitation à rejoindre {{ $invitation->colocation->nom_colocation }}</h1>

    <p class="text-gray-600 mb-6">
        Vous avez été invité à rejoindre cette colocation.
    </p>

    <div class="flex gap-4">
        <form method="POST" action="{{ route('invitations.confirm', $invitation->token) }}">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Accepter
            </button>
        </form>

        <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Refuser
            </button>
        </form>
    </div>
</div>
@endsection