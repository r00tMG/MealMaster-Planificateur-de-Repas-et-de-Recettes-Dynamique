<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('users',[UserController::class,'index']);
Route::post('users',[UserController::class,'store']);
Route::post('login',[AuthController::class,'login']);

Route::get('/generate-recipes',[RecipeController::class,'generateRecipes']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('getPreferences',[PreferenceController::class,'getPreferences']);
    Route::post('setPreferences',[PreferenceController::class,'setPreferences']);

    Route::get('/generateRecipes',[RecipeController::class,'search']);
    Route::get('/recipes',[RecipeController::class,'index']);
    Route::get('/recipesByPreferences',[RecipeController::class,'generateByPreferences']);

    Route::get('/meal-plans', [MealPlanController::class, 'index']);
    Route::post('/meal-plans', [MealPlanController::class, 'store']);
    Route::put('/meal-plans/{id}', [MealPlanController::class, 'update']);
    Route::delete('/meal-plans/{id}', [MealPlanController::class, 'destroy']);

   /* Route::put('/preferences', [UserController::class, 'updatePreferences']);
    Route::get('/shopping-list', [UserController::class, 'generateShoppingList']);
    Route::post('/order-ingredients', [UserController::class, 'orderIngredients']);

   # Route::middleware('admin')->group(function () {
        Route::get('/manage-users', [AdminController::class, 'manageUsers']);
        Route::get('/manage-recipes', [AdminController::class, 'manageRecipes']);
        Route::get('/manage-partnerships', [AdminController::class, 'managePartnerships']);
    #});*/
});
