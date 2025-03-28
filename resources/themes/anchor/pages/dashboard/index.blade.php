<?php
    use function Laravel\Folio\{middleware, name};
    middleware('auth');
    name('dashboard');
?>

@php
    use App\Models\Announcement;
    use App\Models\Plans;

    $totalAnnoncesActives = Announcement::where('status', 'approved')->count();
    $userAnnonces = Announcement::where('user_id', auth()->id())->latest()->get();
@endphp

<x-layouts.app>
    <x-app.container x-data class="lg:space-y-6" x-cloak>
        
        <x-app.alert id="dashboard_alert" class="hidden lg:flex"><a href="https://devdojo.com/wave/docs" target="_blank" class="mx-1 underline">View the docs</a> to learn more.</x-app.alert>

        <x-app.heading
            title="Tableau de bord"
            description="Bienvenue sur le tableau de bord. Retrouvez plus de ressources ci-dessous."
            :border="false"
        />

        <div class="flex flex-col w-full mt-6 space-y-5 md:flex-row lg:mt-0 md:space-y-0 md:space-x-5">
            <x-app.dashboard-card
                title="Annonces Actives"
                description="Nombre total d'annonces validées."
                link_text="{{ $totalAnnoncesActives }} Annonces"
                image="/wave/img/ads.webp"
            />
            <x-app.dashboard-card
                href="https://devdojo.com/questions"
                target="_blank"
                title="forfait actif"
                description="Nombre total de forfait actif"
                link_text="Forfaits"
                image="/wave/img/community.png"
            />
        </div>

        <!-- Nouvelle section pour le tableau des annonces -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Mes Annonces</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($userAnnonces as $annonce)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $annonce->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $annonce->price }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $annonce->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($annonce->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $annonce->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $annonce->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                <form action="" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucune annonce trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 space-y-5">
            @subscriber
                <p>You are a subscribed user with the <strong>{{ auth()->user()->roles()->first()->name }}</strong> role. Learn <a href="https://devdojo.com/wave/docs/features/roles-permissions" target="_blank" class="underline">more about roles</a> here.</p>
                <x-app.message-for-subscriber />
            @else
                <p>This current logged in user has a <strong>{{ auth()->user()->roles()->first()->name }}</strong> role. To upgrade, <a href="{{ route('settings.subscription') }}" class="underline">subscribe to a plan</a>. Learn <a href="https://devdojo.com/wave/docs/features/roles-permissions" target="_blank" class="underline">more about roles</a> here.</p>
            @endsubscriber
            
            @admin
                <x-app.message-for-admin />
            @endadmin
        </div>
    </x-app.container>
</x-layouts.app>