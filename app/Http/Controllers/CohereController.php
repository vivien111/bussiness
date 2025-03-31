<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CohereController extends Controller
{
    public function generateAdText(Request $request)
    {
        $category = $request->input('category');

        if (!$category) {
            return response()->json(['error' => 'Veuillez fournir une catégorie.'], 400);
        }

        $prompt = "Génère une annonce attrayante pour la catégorie $category. Rends-la courte et engageante.";

        try {
            $client = new Client();
            $response = $client->post('https://api.cohere.ai/v1/generate', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('COHERE_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'command', // ⚠️ Vérifie bien que 'command' est supporté
                    'prompt' => $prompt,
                    'max_tokens' => 100,
                    'temperature' => 0.7,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json([
                'text' => $data['generations'][0]['text'] ?? 'Aucune suggestion trouvée.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue : ' . $e->getMessage(),
            ], 500);
        }
    }
}
