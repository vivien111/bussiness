<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
        ]);
    }

    public function generateAnnonce($category, $description)
    {
        $prompt = "Catégorie : {$category}. Description : {$description}. Rédige une annonce attrayante.";

        $response = $this->client->post('completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 200,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['choices'][0]['text'] ?? 'Erreur lors de la génération.';
    }
}
