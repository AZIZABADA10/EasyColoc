<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function create(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        return view('invitations.create', compact('colocation'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email'
        ]);

        if ($colocation->users()->where('email', $request->email)->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre de la colocation.');
        }

        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => Str::random(40),
            'colocation_id' => $colocation->id,
        ]);

        Mail::to($request->email)->send(new InvitationMail($invitation));

        return back()->with('success', 'Invitation envoyée !');
    }

    public function handle($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('statut', 'en_attente')
            ->firstOrFail();

        session(['invitation_token' => $token]);

        if (!Auth::check()) {
            if (\App\Models\User::where('email', $invitation->email)->exists()) {
                return redirect()->route('login');
            } else {  
                return redirect()->route('register');
            }
        }

         if ($invitation->colocation->users()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('colocations.show', $invitation->colocation_id)
                ->with('info', 'Vous êtes déjà membre de cette colocation.');
        }

         return view('emails.response', compact('invitation'));
    }

 
    public function accept($token)
    {
         $invitation = Invitation::where('token', $token)
            ->where('statut', 'en_attente')
            ->firstOrFail();

         if ($invitation->email !== Auth::user()->email) {
            abort(403, 'Cette invitation ne vous appartient pas.');
        }

         if ($invitation->colocation->users()->where('user_id', Auth::id())->exists()) {
            return redirect()
                ->route('colocations.show', $invitation->colocation_id)
                ->with('info', 'Vous êtes déjà membre de cette colocation.');
        }

         $invitation->colocation->users()->attach(Auth::id(), [
            'role' => 'member'
        ]);

         $invitation->update([
            'statut' => 'acceptee'
        ]);

         session()->forget('invitation_token');

         return redirect()
            ->route('colocations.show', $invitation->colocation_id)
            ->with('success', 'Invitation acceptée avec succès !');
    }
 
    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('statut', 'en_attente')
            ->firstOrFail();

        $invitation->update([
            'statut' => 'refusee'
        ]);

        session()->forget('invitation_token');

        return redirect()->route('dashboard')
            ->with('success', 'Invitation refusée.');
    }
 
}
