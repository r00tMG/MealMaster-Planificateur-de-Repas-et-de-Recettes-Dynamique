<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function manageUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function manageRecipes()
    {
        $recipes = Recipe::all();
        return response()->json($recipes);
    }

    public function managePartnerships()
    {
        // Logic to manage partnerships with suppliers
    }
}
