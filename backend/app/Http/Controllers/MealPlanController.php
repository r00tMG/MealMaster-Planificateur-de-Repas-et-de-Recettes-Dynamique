<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MealPlanController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'recipe_id' => 'required|exists:recipes,id',
            'date' => 'required|date',
            'meal_time' => 'required|in:breakfast,lunch,dinner,snack',
        ]);
        //dd($validate->fails());
        if ($validate->fails())
        {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => "Mauvaise requête",
                '$meal_plans' => $validate->errors()
            ]);
        }
        //dd($request->all());
        $mealPlan = MealPlan::create([
            'user_id' => 1,
            'recipe_id' => $request->input('recipe_id'),
            'date' => $request->input('date'),
            'meal_time' => $request->input('meal_time'),
        ]);

        return response()->json($mealPlan, 201);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        #dd($user);
        $mealPlans = MealPlan::where('user_id', $user->id)
            ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->with('recipes')
            ->get();

        return response()->json($mealPlans);
    }

    public function update(Request $request, $id)
    {
        $mealPlan = MealPlan::findOrFail($id);

        if ($mealPlan->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'date' => 'required|date',
            'meal_time' => 'required|in:breakfast,lunch,dinner,snack',
        ]);

        $mealPlan->update([
            'recipe_id' => $request->input('recipe_id'),
            'date' => $request->input('date'),
            'meal_time' => $request->input('meal_time'),
        ]);

        return response()->json($mealPlan);
    }

    public function destroy($id)
    {
        $mealPlan = MealPlan::findOrFail($id);

        if ($mealPlan->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $mealPlan->delete();

        return response()->json(['message' => 'Meal plan deleted']);
    }
}
