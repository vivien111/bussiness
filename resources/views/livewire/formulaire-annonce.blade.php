<div class="p-6 bg-white rounded-lg shadow">
    @if (session()->has('message'))
        <div class="p-3 mb-3 text-white bg-green-500 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="title" class="block text-gray-700">Titre</label>
            <input type="text" id="title" wire:model="title" class="w-full p-2 border rounded">
            @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block text-gray-700">Contenu</label>
            <textarea id="content" wire:model="content" class="w-full p-2 border rounded"></textarea>
            @error('content') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700">Prix</label>
            <input type="number" id="price" wire:model="price" class="w-full p-2 border rounded">
            @error('price') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded">Publier</button>
    </form>
</div>
