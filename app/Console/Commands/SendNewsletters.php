<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail;
use Carbon\Carbon;

class SendNewsletters extends Command
{
    protected $signature = 'newsletters:send';
    protected $description = 'Envoie les newsletters programmées aux abonnés';

    public function handle()
    {
        $newsletters = Newsletter::where('status', 'scheduled')
            ->where('scheduled_at', '<=', Carbon::now())
            ->get();

        foreach ($newsletters as $newsletter) {
            foreach ($newsletter->announcements as $announcement) {
                $subscribers = \App\Models\Subscriber::pluck('email'); // Récupérer les abonnés

                foreach ($subscribers as $email) {
                    Mail::to($email)->send(new NewsletterMail($announcement));
                }
            }

            $newsletter->update(['status' => 'sent']);
        }

        $this->info('Newsletters envoyées avec succès !');
    }
}
