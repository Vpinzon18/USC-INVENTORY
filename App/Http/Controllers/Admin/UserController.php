<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra la lista de todos los usuarios registrados.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Muestra el formulario para editar el rol de un usuario específico.
     */
    public function edit(User $user) // <-- Aquí agregamos el $ que faltaba
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualiza el nombre y el rol en la base de datos.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer|in:1,2,3',
        ]);

        $user->update($request->only('name', 'role'));

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Opcional: Eliminar un usuario (Cuidado: no te elimines a ti mismo).
     */
   
   // Muestra el formulario de creación
public function create()
{
    return view('admin.users.create');
}

// Guarda el nuevo usuario en la base de datos
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
    // Cambia auth()->id() por Auth::id()
    if (Auth::id() === $user->id) {
        return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
    }
    
    $user->delete();
    return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
}
}