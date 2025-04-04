<?php
use function Laravel\Folio\{name};
name('Annonce');

$posts = \App\Models\Announcement::where('status', 'published')->orderBy('created_at', 'DESC')->paginate(6);
$categories = \Wave\Category::all();
?>

<section
    class="relative top-0 flex flex-col items-center justify-center w-full min-h-screen -mt-24 bg-white lg:min-h-screen">

    <div
        class="flex flex-col items-center justify-between flex-1 w-full max-w-2xl gap-6 px-8 pt-32 mx-auto text-left md:px-12 xl:px-20 lg:pt-32 lg:pb-16 lg:max-w-7xl lg:flex-row">
        <div class="w-full lg:w-1/2">
            <h1
                class="text-6xl font-bold tracking-tighter text-left sm:text-7xl md:text-8xl sm:text-center lg:text-left text-zinc-900 text-balance">
                <span class="block origin-left lg:scale-90 text-nowrap">Bienvenue sur</span> <span
                    class="pr-4 text-transparent text-neutral-600 bg-clip-text bg-gradient-to-b from-neutral-900 to-neutral-500">business
                    letter</span>
            </h1>
            <p
                class="mx-auto mt-5 text-2xl font-normal text-left sm:max-w-md lg:ml-0 lg:max-w-md sm:text-center lg:text-left text-zinc-500">
                Ciblez les clients de votre secteur<span class="hidden sm:inline"> and features</span>.
            </p>
            @guest
                <div
                    class="flex flex-col items-center justify-center gap-3 mx-auto mt-8 md:gap-2 lg:justify-start md:ml-0 md:flex-row">
                    <x-button href="{{ route('login') }}" size="lg" color="primary" class="w-full lg:w-auto">
                        Se connecter
                    </x-button>
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center gap-3 mx-auto mt-8 md:gap-2 lg:justify-start md:ml-0 md:flex-row">
                    <x-button href="{{ route('wave.dashboard.announcements.create') }}" size="lg" color="primary" class="w-full lg:w-auto">
                        Passer une annonce
                    </x-button>
                </div>
            @endguest


        </div>
        <div class="flex items-center justify-center w-full mt-12 lg:w-1/2 lg:mt-0">
            <img alt="Wave Character" class="relative w-full lg:scale-125 xl:translate-x-6"
                src="wave/img/arriere-plan-annonce-gradient.avif" style="max-width:450px;">
        </div>
    </div>
    <div class="flex-shrink-0 lg:h-[150px] flex border-t border-zinc-200 items-center w-full bg-white">
        <div
            class="grid h-auto grid-cols-1 px-8 py-10 mx-auto space-y-5 divide-y max-w-7xl lg:space-y-0 lg:divide-y-0 divide-zinc-200 lg:py-0 lg:divide-x md:px-12 lg:px-20 lg:divide-zinc-200 lg:grid-cols-3">
            <div class="">
                <h3 class="flex items-center font-medium text-zinc-900">
                    Key Feature
                </h3>
                <p class="mt-2 text-sm font-medium text-zinc-500">
                    Why might users be interested in using your product. <span class="hidden lg:inline">Tell them more
                        here.</span>
                </p>
            </div>
            <div class="pt-5 lg:pt-0 lg:px-10">
                <h3 class="font-medium text-zinc-900">Pain Points</h3>
                <p class="mt-2 text-sm text-zinc-500">
                    What are some pain points that your SaaS aims to solve? <span class="hidden lg:inline">Explain
                        here.</span>
                </p>
            </div>
            <div class="pt-5 lg:pt-0 lg:px-10">
                <h3 class="font-medium text-zinc-900">Unique Advantage</h3>
                <p class="mt-2 text-sm text-zinc-500">
                    Explain your unique advantage over others in the market.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="w-full px-4 py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Annonces récentes</h2>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $announcement)
                <article id="announcement-{{ $announcement->id }}"
                    class="overflow-hidden relative bg-white rounded-2xl border cursor-pointer border-zinc-200 shadow-sm transition hover:shadow-md"
                    onClick="window.location=''">
                    <meta property="name" content="{{ $announcement->title }}">
                    <meta property="dateModified"
                        content="{{ \Carbon\Carbon::parse($announcement->updated_at)->toIso8601String() }}">
                    <meta property="datePublished"
                        content="{{ \Carbon\Carbon::parse($announcement->created_at)->toIso8601String() }}">

                    <img src="{{ asset('storage/' . $announcement->image) }}"
                        class="w-full h-48 object-cover rounded-t-2xl">

                    <div class="px-4 py-4">
                        <div class="flex gap-2 items-center text-xs text-zinc-500 mb-2">
                            <time
                                datetime="{{ $announcement->updated_at }}">{{ $announcement->updated_at->format('M d, Y') }}</time>

                            @if($announcement->country)
                                <span class="bg-zinc-100 px-2 py-1 rounded-full">{{ $announcement->country->name }}</span>
                            @endif

                            @if($announcement->state)
                                <span class="bg-zinc-100 px-2 py-1 rounded-full">{{ $announcement->state->name }}</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold text-zinc-900 mb-2 line-clamp-1">
                            <a href="">
                                <span class="absolute inset-0"></span>
                                {{ $announcement->title }}
                            </a>
                        </h3>

                        <p class="text-sm text-zinc-600 line-clamp-3">
                            {{ Str::limit(strip_tags($announcement->content), 110, '...') }}
                        </p>

                        <div class="mt-4 flex justify-between items-center">
                            <span
                                class="text-sm font-bold text-green-600">{{ number_format($announcement->price, 0, ',', ' ') }}
                                EUR</span>
                            <span
                                class="text-xs uppercase tracking-wide text-white bg-blue-500 px-2 py-1 rounded">{{ ucfirst($announcement->status) }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </div>
</section>