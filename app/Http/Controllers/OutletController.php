<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\UserOutlet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Outlet/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Outlet/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'image' => 'required',
            'pic' => 'required',
            'address' => 'required'
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        Outlet::create($validatedData);

        return redirect()->route('outlet');
    }

    public function upload(Request $request)
    {
        $image = $request->file('image')->store('public/image');

        $url = url('/') . '/' . str_replace("public/", "storage/", $image);

        return response()->json(['status' => 'success', 'data' => $url]);
    }

    public function list()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Masalah authentikasi']);
        }

        $outlet = Outlet::with('user')->get();

        return response()->json(['status' => 'success', 'data' => $outlet]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Masalah authentikasi']);
        }

        $outlet = Outlet::where('id', $id)->with('user')->first();

        return response()->json(['status' => 'success', 'data' => $outlet]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Outlet/Edit', [
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
            'image' => 'required',
            'pic' => 'required',
            'address' => 'required'
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        Outlet::where('id', $id)->update($validatedData);

        return redirect()->route('outlet');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Outlet::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Outlet berhasil dihapus']);
    }
}
