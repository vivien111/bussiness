<x-layouts.app>
	<x-app.container x-data="{ step: 1 }" x-cloak class="lg:space-y-8 flex h-full">
		<!-- Menu latéral -->
		<div class="flex h-full w-1/5 border-r p-4">
			<ul class="space-y-4">
				<template x-for="(label, index) in ['Etape', 'Etape', 'Etape']" :key="index">
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
		<div class="flex-1 flex">

			<div class="max-w-8xl mx-auto  rounded-xl shadow-sm p-8" style="width: 500px;">

				<x-app.heading title="Soumettre l'Annonce" description="Bienvenue sur votre tableau de bord. "
					class="mb-80" />

				<form action="{{route('announcements.store')}}" method="POST" enctype="multipart/form-data"
					class="space-y-8">
					@csrf

					<!-- Étape 1 -->
					<div x-show="step === 1" class="space-y-6">
						<div class="space-y-3">
							<label for="title" class="block text-sm font-medium text-gray-700">Titre de
								l'annonce</label>
							<input type="text" name="title" id="title"
								class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
								required>
						</div>
						<div class="space-y-3">
							<label for="price" class="block text-sm font-medium text-gray-700">Prix de l'annonce</label>
							<input type="text" name="price" id="price" class="w-full p-3 border rounded-lg" required>
						</div>
						<div class="space-y-3">
							<label for="invoice_id" class="block text-sm font-medium text-gray-700">Sélectionnez une
								facture</label>
							<select class="w-full p-3 border rounded-lg" name="invoice_id" id="invoice_id" required>
								<option value="">Choisissez une facture</option>
								@foreach($invoices as $invoice)
									<option value="{{ $invoice->id }}">Facture #{{ $invoice->id }} -
										{{ $invoice->total_amount }}€
									</option>
								@endforeach
							</select>
						</div>

						<div class="flex justify-between pt-4">
							<button type="button" class="bg-gray-400 text-white px-6 py-3 rounded-lg" disabled>←
								Précédent</button>
							<button type="button" class="bg-blue-600 text-white px-6 py-3 rounded-lg"
								x-on:click="step = 2">Suivant →</button>
						</div>
					</div>

					<!-- Étape 2 -->
					<div x-show="step === 2" class="space-y-6">

						<div class="space-y-3">
							<label class="block text-sm font-medium text-gray-700">Votre Adresse Ciblé</label>
							<select name="country_id" id="country_id" class="w-full p-3 border rounded-lg" required>
								<option value="">Sélectionnez un pays</option>
								@foreach($countries as $country)
									<option value="{{ $country->id }}">{{ $country->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="space-y-3">
							<label for="state_id" class="block text-sm font-medium text-gray-700">Ville/État</label>
							<select name="state_id" id="state_id" class="w-full p-3 border rounded-lg" required>
								<option value="">Sélectionnez d'abord une ville</option>
							</select>
						</div>
						<div x-data="{
    category: '',
    generatedText: '',
    isLoading: false,
    async generateText() {
        if (!this.category) {
            alert('Veuillez sélectionner une catégorie.');
            return;
        }

        this.isLoading = true;
        this.generatedText = 'Génération en cours...';

        const prompt = `Génère une annonce attrayante en français pour la catégorie ${this.category}. Rends-la courte et engageante.`;

        try {
            const response = await fetch('https://api.cohere.ai/v1/generate', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + '5o2k7urS4KEf2I1WkaZBTWudBBDv665pvs6tNyGb', // Remplace 'YOUR_COHERE_API_KEY' par ta clé API
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    model: 'command',
                    prompt: prompt,
                    max_tokens: 100,
                    temperature: 0.7,
                })
            });

            if (!response.ok) {
                throw new Error('Erreur de génération');
            }

            const data = await response.json();
            this.generatedText = data.generations[0]?.text || 'Aucune suggestion générée.';
        } catch (error) {
            console.error('Erreur lors de la génération du texte :', error);
            this.generatedText = 'Une erreur est survenue. Veuillez réessayer.';
        } finally {
            this.isLoading = false;
        }
    }
}" class="space-y-6">
							<div class="space-y-3">
								<label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
								<select id="category" x-model="category" class="w-full p-3 border rounded-lg"
									@change="generateText">
									<option value="">Sélectionner une catégorie</option>
									<option value="Auto">Auto</option>
									<option value="Immobilier">Immobilier</option>
									<option value="Électronique">Électronique</option>
								</select>
							</div>

							<div class="space-y-3">
								<label for="content" class="block text-sm font-medium text-gray-700">Description</label>
								<textarea id="hiddenContent" name="content" cols="60" rows="8"
									class="w-full p-3 border rounded-lg" x-model="generatedText" required></textarea>
							</div>

							<div class="flex justify-between pt-4">
								<button type="button" class="bg-gray-600 text-white px-7 py-3 rounded-lg"
									x-on:click="step = 1" :disabled="isLoading">
									← Précédent
								</button>
								<button type="button" class="bg-blue-600 text-white px-7 py-3 rounded-lg"
									x-on:click="step = 3" :disabled="isLoading">
									Suivant →
								</button>
							</div>
						</div>
					</div>

					<!-- Étape 3 -->


					<!-- Étape 4 -->


					<!-- Étape 5 -->
					<div x-show="step === 3" class="space-y-6">
						<!-- Champ pour les images -->
						<div class="space-y-3">
							<label for="image" class="block text-sm font-medium text-gray-700">Images de
								l'annonce</label>
							<input type="file" name="image" id="image" class="w-full p-3 border rounded-lg"
								x-ref="imageInput" onchange="previewImage(event)" accept="image/*" x-data="preview">
						</div>

						<!-- Champ pour les liens -->
						<div class="space-y-3">
							<label for="external_links" class="block text-sm font-medium text-gray-700">Liens externes
								(optionnel)</label>
							<div x-data="{ links: [''] }">
								<template x-for="(link, index) in links" :key="index">
									<div class="flex space-x-2 mb-2">
										<input type="url" x-model="links[index]" name="link"
											class="flex-1 p-3 border rounded-lg" placeholder="https://exemple.com">
										<button type="button" @click="links.splice(index, 1)"
											class="px-3 py-2 bg-red-500 text-white rounded-lg"
											x-show="links.length > 1">
											✕
										</button>
									</div>
								</template>
								<button type="button" @click="links.push('')"
									class="mt-2 px-4 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm">
									+ Ajouter un autre lien
								</button>
							</div>
						</div>

						<!-- Aperçu de l'image -->


						<!-- Boutons de navigation -->
						<div class="flex justify-between pt-4">
							<button type="button" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700"
								x-on:click="step = 2">
								← Précédent
							</button>
							<button type="submit"
								class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
								Publier
							</button>
						</div>
					</div>

					<!-- Étape 6 -->

				</form>
			</div>
			<!-- Aperçu de l'annonce (1/3 de la largeur) -->
			<div class="w-[500px] p-8 bg-gray-50 border-l h-full overflow-y-auto sticky top-0 ml-12">
				<h3 class="text-lg font-semibold mb-8">Aperçu de votre annonce</h3>

				<div class="bg-white rounded-lg shadow-md overflow-hidden">
					<!-- Image (mise à jour en temps réel) -->
					<div class="h-64 bg-gray-100 flex items-center justify-center">
						<img id="imagePreview" class="h-full w-full object-cover" style="display:none;">
						<div id="defaultImage" class="text-center p-4">
							<svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
								viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
								</path>
							</svg>
							<p class="mt-2 text-sm text-gray-500">Aperçu de l'image</p>
						</div>
					</div>


					<!-- Contenu de l'annonce -->
					<div class="p-6">
						<h4 class="font-bold text-lg mb-2"
							x-text="step >= 1 ? document.getElementById('title')?.value || 'Titre de l\'annonce' : 'Titre de l\'annonce'">
						</h4>

						<p class="text-gray-600 mb-3"
							x-text="step >= 2 ? document.getElementById('hiddenContent')?.value || 'Description de l\'annonce...' : 'Description de l\'annonce...'">
						</p>

						<div class="flex justify-between items-center">
							<span class="font-bold text-blue-600"
								x-text="step >= 1 ? (document.getElementById('price')?.value ? document.getElementById('price')?.value + '€' : 'Prix') : 'Prix'"></span>

							<div class="text-sm text-gray-500">
								<span
									x-text="step >= 2 ? document.getElementById('country_id')?.options[document.getElementById('country_id')?.selectedIndex]?.text || 'Localisation' : 'Localisation'"></span>

								<span x-show="step >= 2 && document.getElementById('state_id')?.value"
									x-text="', ' + document.getElementById('state_id')?.options[document.getElementById('state_id')?.selectedIndex]?.text"></span>
							</div>
						</div>
					</div>

					<!-- Pied de l'annonce -->
					<div class="px-4 py-3 bg-gray-50 border-t flex justify-between items-center">
						<span class="text-sm text-gray-500">Aujourd'hui</span>
						<button class="text-blue-600 text-sm font-medium">Enregistrer</button>
					</div>
				</div>

				<div class="mt-4 text-sm text-gray-500 mt-8">
					<p>Cet aperçu montre comment votre annonce apparaîtra aux utilisateurs.</p>
				</div>
			</div>
			<script>
				function previewImage(event) {
					var file = event.target.files[0];
					var reader = new FileReader();

					reader.onload = function (e) {
						var imagePreview = document.getElementById('imagePreview');
						var defaultImage = document.getElementById('defaultImage');

						imagePreview.style.display = 'block';  // Affiche l'image
						imagePreview.src = e.target.result;   // Charge l'image

						defaultImage.style.display = 'none';  // Cache l'icône par défaut
					}

					reader.readAsDataURL(file);  // Lire le fichier image
				}
			</script>

			<!-- Script pour gérer l'affichage de l'image -->
			<script>
				document.addEventListener('alpine:init', () => {
					Alpine.data('preview', () => ({
						imagePreview: null,

						init() {
							// Écouteur pour le changement de fichier
							const fileInput = document.getElementById('imageUpload'); // Remplacez par l'ID de votre input file
							if (fileInput) {
								fileInput.addEventListener('change', (e) => {
									this.previewImage(e);
								});
							}
						},

						previewImage(event) {
							const input = event.target;
							if (input.files && input.files[0]) {
								const reader = new FileReader();
								reader.onload = (e) => {
									this.imagePreview = e.target.result;
								};
								reader.readAsDataURL(input.files[0]);
							}
						}
					}));
				});
			</script>
		</div>
		</div>
	</x-app.container>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			// 1. Vérification que les éléments existent
			const countrySelect = document.getElementById('country_id');
			const stateSelect = document.getElementById('state_id');

			if (!countrySelect || !stateSelect) {
				console.error('Erreur: Les éléments country_id ou state_id introuvables');
				return;
			}

			// 2. Fonction pour charger les villes
			async function loadStates(countryId) {
				try {
					stateSelect.innerHTML = '<option value="">Chargement...</option>';

					const response = await fetch(`/get-states/${countryId}`);

					if (!response.ok) throw new Error('Erreur serveur: ' + response.status);

					const states = await response.json();

					// 3. Remplir le select
					let options = '<option value="">Sélectionnez une ville</option>';
					states.forEach(state => {
						options += `<option value="${state.id}">${state.name}</option>`;
					});

					stateSelect.innerHTML = options;

					// 4. Debug
					console.log('Villes chargées:', states);
				} catch (error) {
					console.error('Erreur:', error);
					stateSelect.innerHTML = '<option value="">Erreur de chargement</option>';
				}
			}

			// 5. Écouteur d'événement
			countrySelect.addEventListener('change', function () {
				loadStates(this.value);
			});

			// 6. Chargement initial si pays déjà sélectionné
			if (countrySelect.value) {
				loadStates(countrySelect.value);
			}
		});
	</script>

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