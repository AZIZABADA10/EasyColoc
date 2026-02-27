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

        if (!Auth::check()) {
            session(['invitation_token' => $token]);
            return redirect()->route('register');
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

        if (!$invitation->colocation->users()->where('user_id', Auth::id())->exists()) {
            $invitation->colocation->users()->attach(Auth::id(), [
                'role' => 'member'
            ]);
        }

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
