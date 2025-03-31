<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OpenAIController extends Controller
{
    public function generateAdText(Request $request)
    {
        $category = $request->input('category');

        $prompt = "Génère une annonce attrayante pour une catégorie $category. Rends-la courte et engageante.";

        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/responses', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo', // ou 'gpt-4' si disponible
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => 100,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json([
            'text' => $data['choices'][0]['message']['content'] ?? 'Aucune suggestion trouvée.',
        ]);
    }
}
