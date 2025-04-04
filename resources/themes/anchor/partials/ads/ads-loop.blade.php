<!-- Loop Through Announcements -->
@foreach($posts as $announcement)
    <article id="announcement-{{ $announcement->id }}" class="overflow-hidden relative col-span-2 p-4 bg-white rounded-2xl border cursor-pointer border-zinc-200 border-zinc-100 dark:bg-black sm:col-span-1" onClick="window.location='{{ route('announcements.show', $announcement->id) }}'">
        <meta property="name" content="{{ $announcement->title }}">
        <meta property="dateModified" content="{{ \Carbon\Carbon::parse($announcement->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ \Carbon\Carbon::parse($announcement->created_at)->toIso8601String() }}">

        <img src="{{ asset('storage/' . $announcement->image) }}" class="w-full h-48 object-cover rounded-lg">
        
        <div class="px-1 py-1">
            <div class="flex gap-x-4 items-center my-3 text-xs text-zinc-500">
                <time datetime="{{ $announcement->updated_at }}">{{ $announcement->updated_at->format('M d, Y') }}</time>

                @if($announcement->country)
                    <span class="bg-zinc-50 px-2 py-1 rounded-full">{{ $announcement->country->name }}</span>
                @endif

                @if($announcement->state)
                    <span class="bg-zinc-100 px-2 py-1 rounded-full">{{ $announcement->state->name }}</span>
                @endif
            </div>

            <h2 class="text-lg font-semibold leading-6 text-zinc-900 group-hover:text-zinc-600">
                <a href="">
                    <span class="absolute inset-0"></span>
                    {{ $announcement->title }}
                </a>
            </h2>

            <p class="mt-2 text-sm text-zinc-600 line-clamp-3">
                {{ Str::limit(strip_tags($announcement->content), 110, '...') }}
            </p>

            <div class="mt-3 flex justify-between items-center">
                <span class="text-sm font-bold text-green-600">{{ number_format($announcement->price, 0, ',', ' ') }} FCFA</span>
                <span class="text-xs uppercase tracking-wide text-white bg-blue-500 px-2 py-1 rounded">{{ ucfirst($announcement->status) }}</span>
            </div>
        </div>
    </article>
@endforeach
<!-- End Announcement Loop -->
