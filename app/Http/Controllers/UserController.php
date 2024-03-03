<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auth = auth()->user();
        return Inertia::render('User/Index', [
            'auth' => ['user' => $auth]
        ]);
    }

    public function list()
    {
        $user = User::get();

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        $outlet_id = $validatedData['outlet_id'];

        unset($validatedData['outlet_id']);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['phone'] = '62' . $validatedData['phone'];
        $user = User::create($validatedData);

        if ($outlet_id) {
            UserOutlet::create([
                'outlet_id' => $validatedData['outlet_id'],
                'user_id' => $user->id
            ]);
        }
        return redirect()->route('user');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where('id', $id)->with('user_outlet')->first();
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('User/Edit', [
            'id' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Cari pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Validasi data masukan
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'phone' => 'required|numeric',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $validatedData['phone'] = '62' . $validatedData['phone'];
        $user->update($validatedData);

        return redirect()->route('user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'User berhasil dihapus']);
    }
}
