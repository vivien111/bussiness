<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Announcement;
use App\Models\User;
use App\Models\State; // Ajout de l'importation de la classe State

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $announcements;
    public $state;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $announcements, $state)
    {
        // Assurez-vous que $announcements est une collection ou un tableau d'annonces
        $this->user = $user;
        $this->announcements = $announcements;
        $this->state = $state;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        if (!$this->state) {
            // Si l'état est null, renvoyer un message d'erreur ou une autre valeur par défaut
            return $this->subject("Aucune annonce disponible")
                        ->view('emails.error') // Une vue d'erreur personnalisée
                        ->with([
                            'user' => $this->user,
                            'announcements' => $this->announcements,
                            'state' => $this->state, // L'état sera null ici
                        ]);
        }

        return $this->subject("Nouvelles annonces dans votre département : {$this->state->name}")
                    ->view('emails.newsletter') // Vue Blade pour le corps de l'email
                    ->with([
                        'user' => $this->user,
                        'announcements' => $this->announcements,
                        'state' => $this->state,
                    ]);
    }
    
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // Si tu as des pièces jointes, tu peux les ajouter ici
    }
}
