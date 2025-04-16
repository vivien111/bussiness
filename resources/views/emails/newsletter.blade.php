@component('mail::message')
# Bonjour {{ $user->name }},

Voici les nouvelles annonces dans votre département **{{ $state->name }}** :

@foreach ($announcements as $announcement)
- **{{ $announcement->title }}**  
  {{ Str::limit($announcement->content, 100) }}  
  [Voir l'annonce]({{ url('/annonces/' . $announcement->slug) }})
@endforeach

@component('mail::button', ['url' => url('/annonces')])
Voir toutes les annonces
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
