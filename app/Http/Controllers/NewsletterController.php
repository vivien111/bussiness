<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Announcement;
use App\Models\State;
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function sendTestNewsletter()
    {
        // Récupérer un utilisateur de test (assure-toi d'avoir un utilisateur en base de données)
        $user = User::first(); 

        // Récupérer des annonces récentes publiées (tu peux ajuster cette logique selon tes besoins)
        $announcements = Announcement::where('status', 'published')
            ->where('state_id', $user->state_id) // Récupérer les annonces du même département
            ->get();

        // Récupérer l'état du département
        $state = State::find($user->state_id);

        // Envoyer l'email
        Mail::to($user->email)->send(new NewsletterMail($user, $announcements, $state));

        return 'Email envoyé avec succès!';
    }
}
