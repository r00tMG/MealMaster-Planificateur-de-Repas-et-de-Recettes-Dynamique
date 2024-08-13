<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpoonacularService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.api_key');
    }

    public function generateRecipes($query)
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->get('https://api.spoonacular.com/recipes/complexSearch', [
            'query' => $query,
            'number' => 10
        ]);

        return $response->json();
    }
}
