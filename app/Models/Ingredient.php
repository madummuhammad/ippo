<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    function category()
    {
        return $this->belongsTo('App\Models\IngredientCategory', 'ingredient_category_id', 'id');
    }

    function purchase_unit()
    {
        return $this->belongsTo('App\Models\IngredientUnit', 'purchase_unit', 'id');
    }
    function consumtion_unit()
    {
        return $this->belongsTo('App\Models\IngredientUnit', 'consumtion_unit', 'id');
    }
}
