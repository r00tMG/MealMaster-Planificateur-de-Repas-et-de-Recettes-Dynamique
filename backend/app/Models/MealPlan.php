<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'user_id');
    }
}
