<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Preference;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        $user->preferences()->delete();

        foreach ($request->preferences as $preference) {
            $user->preferences()->create($preference);
        }

        return response()->json(['message' => 'Preferences updated successfully']);
    }

    public function generateShoppingList()
    {
        $user = auth()->user();
        $recipes = $user->recipes;
        $ingredients = [];

        foreach ($recipes as $recipe) {
            foreach ($recipe->ingredients as $ingredient) {
                if (!isset($ingredients[$ingredient->id])) {
                    $ingredients[$ingredient->id] = [
                        'name' => $ingredient->name,
                        'quantity' => 0
                    ];
                }
                $ingredients[$ingredient->id]['quantity'] += $ingredient->pivot->quantity;
            }
        }

        return response()->json(['shopping_list' => array_values($ingredients)]);
    }

    public function orderIngredients(Request $request)
    {
        // Logic to connect with local suppliers or delivery services
    }

    /**
     * @return JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            'error' => false,
            'message' => "Votre requête a bien réussie",
            'users' => UserResource::collection(User::all())
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required','confirmed'],
            ]);
            logger('Request data:'. json_encode($validator));
            //dd($validator->fails());

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation des données échouée',
                    'errors' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            logger('Request data:'. json_encode($request->has('roles')));

            // Attribution des rôles
            /*if ($request->has('roles')) {
                $roles = Role::whereIn('name', $request->roles)->get();
                logger('Role:'. json_encode($roles));
                $user->roles()->sync($roles->pluck('id'));

            }

            // Attribution des permissions
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('name', $request->permissions)->get();
                logger('Permission:'. json_encode($permissions));
                $user->permissions()->sync($permissions->pluck('id'));
            }*/
            return response()->json([
                'error' => false,
                'message' => "L'utilisateur est enregistré avec succès",
                'user' => new UserResource($user)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Exception:', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de l\'enregistrement de l\'utilisateur.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
