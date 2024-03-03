<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientUnit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IngredientunitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Ingredient/Unit/Index');
    }

    public function list()
    {
        $unit = IngredientUnit::get();

        return response()->json(['status' => 'success', 'data' => $unit]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Ingredient/Unit/Create');
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

        IngredientUnit::create($validatedData);

        return redirect()->route('ingredient_unit');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = IngredientUnit::where('id', $id)->first();
        return response()->json(['status' => 'success', 'data' => $unit]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Ingredient/Unit/Edit', [
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

        IngredientUnit::where('id', $id)->update($validatedData);

        return redirect()->route('ingredient_unit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        IngredientUnit::where('id', $id)->delete();
        Ingredient::where('consumtion_unit', $id)->orWhere('purchase_unit', $id)->delete();

        return response()->json(['status' => 'success', 'message' => 'Ingredient unit berhasil dihapus']);
    }
}
