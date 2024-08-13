<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use App\Models\Recipe;
use App\Services\SpoonacularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends Controller
{
   /* protected $spoonacularService;

    public function __construct(SpoonacularService $spoonacularService)
    {
        $this->spoonacularService = $spoonacularService;
    }

    public function generateRecipes(Request $request): \Illuminate\Http\JsonResponse
    {
        //dd($request);
        $query = $request->input('query');
        //dd($query);
        $recipes = $this->spoonacularService->generateRecipes($query);

        return response()->json($recipes);
    }*/

    public function search(Request $request)
    {
        //dd(Auth::id());
        $query = $request->input('query');
        //dd($query);
        $response = Http::get('https://api.spoonacular.com/recipes/complexSearch', [
            'query' => $query,
            'apiKey' => env('SPOONACULAR_API_KEY'),
            'number' => 10,
        ]);
        //dd($response);
        $recipes = $response->json()['results'];
        #dd($recipes);
        /*foreach ($recipes as $recipe) {
            //dd($recipe);
            Recipe::updateOrCreate(
                [
                    'title' => $recipe['title'],
                    'image' => $recipe['image'],
                    //'summary' => $recipe['summary'] ?? null,
                    'ingredients' => json_encode($recipe['ingredients']),
                    //'image_url' => $recipe['image'] ?? null
                ]
            );
        }*/
        foreach ($recipes as $recipe) {
            $detailsResponse = Http::get('https://api.spoonacular.com/recipes/' . $recipe['id'] . '/information', [
                'apiKey' => env('SPOONACULAR_API_KEY'),
            ]);
            $details = $detailsResponse->json();
            //dd($details);

            Recipe::updateOrCreate(
                ['title' => $recipe['title']],
                [
                    'title' => $recipe['title'],
                    'image' => $details['image'],
                    'summary' => $details['summary'] ?? null,
                    'ingredients' => json_encode(array_column($details['extendedIngredients'], 'original')),
                ]
            );
        }
        //dd($recipes);

        return response()->json([
            'error' => false,
            'message' => "Vos recettes ont été généré avec succés",
            'recipes' => $recipes
        ], Response::HTTP_OK);
    }
    public function generateByPreferences(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = auth()->user();

        // Récupérer les préférences et tolérances de l'utilisateur
        $preferences = Preference::where('user_id', $user->id)->first();
        //dd($preferences);

        if (!$preferences) {
            return response()->json(['message' => 'No preferences found for this user'], 404);
        }

        // Assurer que les intolérances soient un tableau
        $intolerances = $preferences->intolerances;
        if (is_string($intolerances)) {
            $intolerances = json_decode($intolerances, true);
        }

        // Construire les paramètres de la requête en fonction des préférences
        $queryParameters = [
            'apiKey' => env('SPOONACULAR_API_KEY'),
            'diet' => $preferences->diet,
            'intolerances' => is_array($intolerances) ? implode(',', $intolerances) : $intolerances,
            'number' => 10,
        ];

        // Effectuer l'appel à l'API Spoonacular pour rechercher des recettes
        $response = Http::get('https://api.spoonacular.com/recipes/complexSearch', $queryParameters);
        //dd($response);
        $recipes = $response->json()['results'];
        //dd($recipes);
        foreach ($recipes as $recipe) {
            // Effectuer un second appel pour obtenir les détails de la recette
            $detailsResponse = Http::get('https://api.spoonacular.com/recipes/' . $recipe['id'] . '/information', [
                'apiKey' => env('SPOONACULAR_API_KEY'),
            ]);

            $details = $detailsResponse->json();

            Recipe::updateOrCreate(
                ['title' => $recipe['title']],
                [
                    'summary' => $details['summary'] ?? null,
                    'ingredients' => json_encode(array_column($details['extendedIngredients'], 'original')),
                    'image' => $details['image'] ?? null
                ]
            );
        }

        return response()->json($recipes);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        //dd($user);
        if ($user)
        {
            return \response()->json([
                'error' => false,
                'message' => "Vos recettes sont bien générées",
                'recipes' => Recipe::all()
            ]);
        }
        //return
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}
