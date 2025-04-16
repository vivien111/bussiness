<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\User;
use App\Models\State;
use App\Models\Announcement; // Modèle des annonces
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $stateId;

    public function __construct(int $stateId)
    {
        $this->stateId = $stateId;
    }

    public function handle()
    {
        // Récupérer l'état (département)
        $state = State::find($this->stateId);

        if (!$state) {
            // Log si l'état n'existe pas
            return;
        }

        // Récupérer les annonces validées et récentes pour cet état
        $announcements = Announcement::where('state_id', $this->stateId)
                                      ->where('status', 'published') // Annonces publiées
                                      ->with(['country', 'state', 'invoice']) // Récupérer les relations
                                      ->whereDate('created_at', '>=', now()->subDays(7)) // Exemples : dernières 7 jours
                                      ->get();

        // Si aucune annonce n'est trouvée, on arrête
        if ($announcements->isEmpty()) {
            return;
        }

        // Récupérer les utilisateurs de cet état
        $users = User::where('state_id', $this->stateId)->get();

        foreach ($users as $user) {
            // Envoyer l'email avec les annonces à l'utilisateur
            Mail::to($user->email)->send(new NewsletterMail($user, $announcements, $state));
        }
    }
}
