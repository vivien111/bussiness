<x-layouts.app>
	<x-app.container x-data="{ step: 1 }" x-cloak class="lg:space-y-6 flex">
		<!-- Menu latéral -->
		<div class="w-1/4 border-r p-4">
			<ul class="space-y-4">
				<template x-for="(label, index) in ['Général', 'Détails', 'Prix', 'Adresse', 'Médias', 'Promotions']"
					:key="index">
					<li class="flex items-center space-x-2">
						<span class="w-8 h-8 flex items-center justify-center rounded-full text-white" :class="step > index + 1 ? 'bg-green-600' : step === index + 1 ? 'bg-blue-600' : 'bg-gray-400'">
							<span x-text="index + 1"></span>
						</span>
						<span :class="step === index + 1 ? 'font-bold text-blue-600' : 'text-gray-400'"
							x-text="(index + 1) + '. ' + label"></span>
					</li>
				</template>
			</ul>
		</div>

		<!-- Contenu du formulaire -->
		<div class="w-3/4 p-6">
			<x-app.heading title="Soumettre l'Annonce" description="Bienvenue sur votre tableau de bord." />

			<!-- Image dynamique en fonction de l'étape -->
			


			<form action="" method="POST" enctype="multipart/form-data">
				@csrf

				<!-- Contenu dynamique des étapes -->
				<div x-show="step === 1">
					<label for="title" class="block text-sm font-medium text-gray-700">Titre de l'annonce</label>
					<input type="text" name="title" id="title" class="w-full p-2 border rounded" required>

					<label for="content" class="block mt-4 text-sm font-medium text-gray-700">Description</label>
					<textarea name="content" id="hiddenContent" cols="30" rows="10" class="w-full p-2 border rounded"
						required></textarea>
					<div class="flex justify-between mt-4">
						<button type="button" class="bg-gray-400 text-white px-4 py-2 rounded" disabled>←
							Précédent</button>
						<button type="button" class="bg-blue-600 text-white px-4 py-2 rounded"
							x-on:click="step = 2">Suivant →</button>
					</div>
				</div>

				<div x-show="step === 2">
					<label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
					<select name="category" id="category" class="w-full p-2 border rounded">
						<option>Auto</option>
						<option>Immobilier</option>
						<option>Électronique</option>
					</select>

					<div class="flex justify-between mt-4">
						<button type="button" class="bg-gray-600 text-white px-4 py-2 rounded" x-on:click="step = 1">←
							Précédent</button>
						<button type="button" class="bg-blue-600 text-white px-4 py-2 rounded"
							x-on:click="step = 3">Suivant →</button>
					</div>
				</div>

				<div x-show="step === 3">
					<label for="title" class="block text-sm font-medium text-gray-700">Titre de l'annonce</label>
					<input type="text" name="title" id="title" class="w-full p-2 border rounded" required>

					<label for="content" class="block mt-4 text-sm font-medium text-gray-700">Description</label>
					<textarea name="content" id="hiddenContent" cols="30" rows="10" class="w-full p-2 border rounded"
						required></textarea>
					<div class="flex justify-between mt-4">
						<button type="button" class="bg-gray-400 text-white px-4 py-2 rounded" disabled>←
							Précédent</button>
						<button type="button" class="bg-blue-600 text-white px-4 py-2 rounded"
							x-on:click="step = 4">Suivant →</button>
					</div>
				</div>


				<div x-show="step === 4">
					<label for="title" class="block text-sm font-medium text-gray-700">Titre de l'annonce</label>
					<input type="text" name="title" id="title" class="w-full p-2 border rounded" required>

					<label for="content" class="block mt-4 text-sm font-medium text-gray-700">Description</label>
					<textarea name="content" id="hiddenContent" cols="30" rows="10" class="w-full p-2 border rounded"
						required></textarea>
					<div class="flex justify-between mt-4">
						<button type="button" class="bg-gray-400 text-white px-4 py-2 rounded" disabled>←
							Précédent</button>
						<button type="button" class="bg-blue-600 text-white px-4 py-2 rounded"
							x-on:click="step = 5">Suivant →</button>
					</div>
				</div>

				<div x-show="step === 5">
					<label for="title" class="block text-sm font-medium text-gray-700">Titre de l'annonce</label>
					<input type="text" name="title" id="title" class="w-full p-2 border rounded" required>

					<label for="content" class="block mt-4 text-sm font-medium text-gray-700">Description</label>
					<textarea name="content" id="hiddenContent" cols="30" rows="10" class="w-full p-2 border rounded"
						required></textarea>
					<div class="flex justify-between mt-4">
						<button type="button" class="bg-gray-400 text-white px-4 py-2 rounded" disabled>←
							Précédent</button>
						<button type="button" class="bg-blue-600 text-white px-4 py-2 rounded"
							x-on:click="step = 6">Suivant →</button>
					</div>
				</div>

				<!-- Ajout des autres étapes de la même manière... -->

				<!-- Dernier étape: soumission -->
				<div x-show="step === 6">
					<label for="promo" class="block text-sm font-medium text-gray-700">Code Promo (optionnel)</label>
					<input type="text" name="promo" id="promo" class="w-full p-2 border rounded">

					<div class="flex justify-between mt-4">
						<button type="button" class="bg-gray-600 text-white px-4 py-2 rounded" x-on:click="step = 5">←
							Précédent</button>
						<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Publier</button>
					</div>
				</div>
			</form>
		</div>
	</x-app.container>

	<!-- Inclusion de Quill -->
	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			var quill = new Quill('#editor', {
				theme: 'snow'
			});
			quill.on('text-change', function () {
				document.getElementById('hiddenContent').value = quill.root.innerHTML;
			});
		});
	</script>
</x-layouts.app>