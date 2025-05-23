<?php
use function Laravel\Folio\{name};
name('blog');

$posts = \App\Models\Announcement::where('status', 'published')->orderBy('created_at', 'DESC')->paginate(6);
$categories = \Wave\Category::all();
?>

<x-layouts.marketing :seo="[
        'title' => 'Blog',
        'description' => 'Our Blog',
    ]">
    <x-container>
        <div class="relative pt-6">
            <x-marketing.elements.heading title="Nos annonces" description="Découvrez les dernières annonces publiées."
                align="left" />


            @include('theme::partials.blog.categories')

            <div class="grid gap-5 mx-auto mt-5 md:mt-10 sm:grid-cols-2 lg:grid-cols-3">
                @include('theme::partials.ads.ads-loop', ['posts' => $posts])
            </div>
        </div>

        <div class="flex justify-center my-10">
            {{ $posts->links('theme::partials.pagination') }}
        </div>

    </x-container>
</x-layouts.marketing>