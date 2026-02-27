<h2>Invitation EasyColoc</h2>

<p>Vous êtes invité à rejoindre la colocation : 
   {{ $invitation->colocation->nom_colocation }}</p>

<a href="{{ route('invitations.accept', $invitation->token) }}">
    Cliquer ici pour répondre à l'invitation
</a>
<br>
 {{ route('invitations.accept', $invitation->token) }}