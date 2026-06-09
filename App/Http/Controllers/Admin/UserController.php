<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer|in:1,2,3',
        ]);

        $user->update($request->only('name', 'role'));

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

  
public function create()
{
    return view('admin.users.create');
}


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|integer|in:1,2,3',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
} public function destroy(User $user)
{

    if (Auth::id() === $user->id) {
        return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
    }
    
    $user->delete();
    return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
}
}