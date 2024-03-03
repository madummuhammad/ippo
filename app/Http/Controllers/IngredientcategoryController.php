<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IngredientcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Ingredient/Category/Index');
    }

    public function list()
    {
        $unit = IngredientCategory::get();

        return response()->json(['status' => 'success', 'data' => $unit]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Ingredient/Category/Create');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        IngredientCategory::create($validatedData);

        return redirect()->route('ingredient_category');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = IngredientCategory::where('id', $id)->first();
        return response()->json(['status' => 'success', 'data' => $unit]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Ingredient/Category/Edit', [
            'id' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        IngredientCategory::where('id', $id)->update($validatedData);

        return redirect()->route('ingredient_category');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        IngredientCategory::where('id', $id)->delete();
        Ingredient::where('ingredient_category_id', $id)->delete();

        return response()->json(['status' => 'success', 'message' => 'Ingredient category berhasil dihapus']);
    }
}
