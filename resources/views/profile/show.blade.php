@extends('layouts.app-layout')

@section('page_title', 'Mon Profil')
@section('title', 'Profil - EasyColoc')

@section('content')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<div class="space-y-8">
        <!-- Informations du Profil -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Informations Personnelles</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Nom</label>
                        <p class="text-slate-900 text-base mt-1">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Email</label>
                        <p class="text-slate-900 text-base mt-1">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Réputation</label>
                        <p class="text-yellow-600 font-bold text-base mt-1">{{ auth()->user()->reputation ?? 0 }} pts</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Statut</label>
                        @if(auth()->user()->is_banned)
                            <span class="inline-flex items-center gap-1 text-red-600 font-bold text-base mt-1">
                                <i class='bx bx-block'></i> Banni
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-base mt-1">
                                <i class='bx bx-check-circle'></i> Actif
                            </span>
                        @endif
                    </div>
                </div>

                @if(auth()->user()->is_admin)
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-sm text-amber-800 font-semibold">
                            <i class='bx bxs-crown text-amber-600'></i> Vous êtes administrateur du site
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modifier le Profil -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Modifier le Profil</h3>
            
            <form method="POST" action="{{ route('user-profile-information.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nom</label>
                    <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                    Mettre à jour
                </button>
            </form>
        </div>

        <!-- Changer le Mot de Passe -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Changer le Mot de Passe</h3>
            
            <form method="POST" action="{{ route('user-password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('current_password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                    Mettre à jour le mot de passe
                </button>
            </form>
        </div>

        <!-- Supprimer le Compte -->
        <div class="bg-white rounded-xl border border-red-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-red-600 mb-4">Zone de Danger</h3>
            
            <p class="text-slate-600 text-sm mb-4">Une fois votre compte supprimé, il n'y a pas de retour en arrière. S'il vous plaît, soyez certain.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');">
                @csrf
                @method('DELETE')

                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                    Supprimer mon compte
                </button>
            </form>
        </div>
    </div>

@endsection
