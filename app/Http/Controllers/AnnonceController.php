<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    private $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function generateAnnonce(Request $request)
    {
        $category = $request->input('category');
        $description = $request->input('content');

        $generatedText = $this->openAIService->generateAnnonce($category, $description);

        return response()->json(['annonce' => $generatedText]);
    }
}
