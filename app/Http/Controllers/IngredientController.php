<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Ingredient/Index');
    }

    public function list()
    {
        $ingredient = Ingredient::with('category', 'user', 'purchase_unit', 'consumtion_unit')->get();

        return response()->json(['status' => 'success', 'data' => $ingredient]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Ingredient/Create');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'purchase_price' => 'required|numeric',
            'ingredient_category_id' => 'required',
            'purchase_unit' => 'required',
            'code' => 'required|unique:ingredients,code',
            'consumtion_unit' => 'required',
            'conversion_rate' => 'required|numeric',
            'stock' => 'required|numeric',
            'min_stock' => 'required|numeric',
        ]);
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['cost_per_unit'] = $validatedData['purchase_price'] / $validatedData['conversion_rate'];

        Ingredient::create($validatedData);

        return redirect()->route('ingredient');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ingredient = Ingredient::where('id', $id)->first();
        return response()->json(['status' => 'success', 'data' => $ingredient]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Ingredient/Edit', [
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
            'purchase_price' => 'required|numeric',
            'ingredient_category_id' => 'required',
            'purchase_unit' => 'required',
            'code' => 'required|unique:ingredients,code,' . $id,
            'consumtion_unit' => 'required',
            'conversion_rate' => 'required|numeric',
            'stock' => 'required|numeric',
            'min_stock' => 'required|numeric',
        ]);
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['cost_per_unit'] = $validatedData['purchase_price'] / $validatedData['conversion_rate'];

        Ingredient::where('id', $id)->update($validatedData);

        return redirect()->route('ingredient');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Ingredient::where('id', $id)->delete();

        return response()->json(['status' => 'success', 'message' => 'Ingredient berhasil dihapus']);
    }
}
