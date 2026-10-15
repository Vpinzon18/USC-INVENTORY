<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Support\PaginationOptions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'integer', Rule::in([1, 2, 3])],
            'per_page' => ['nullable', 'integer', Rule::in(PaginationOptions::SIZES)],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $filters = [
            'search' => trim((string) ($validated['search'] ?? '')),
            'role' => (string) ($validated['role'] ?? ''),
            'per_page' => (string) ($validated['per_page'] ?? PaginationOptions::fromRequest($request)),
        ];

        $query = User::query();

        if ($filters['search'] !== '') {
            $term = mb_strtolower($filters['search']);
            $query->where(function ($usersQuery) use ($term) {
                $usersQuery
                    ->whereRaw('LOWER(name) LIKE ?', ['%' . $term . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $term . '%']);
            });
        }

        if ($filters['role'] !== '') {
            $query->where('role', (int) $filters['role']);
        }

        $users = $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate((int) $filters['per_page'])
            ->withQueryString();

        return view('admin.users.index', compact('users', 'filters'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function create()
{
    return view('admin.users.create');
}
   public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer|in:1,2,3',
        ]);

        // ESCUDO ANTI-XSS: Limpiamos solo el nombre
        $validated['name'] = strip_tags($validated['name']);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado de forma segura');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer|in:1,2,3',
        ]);

        User::create([
            // Limpiamos el nombre en el momento de guardarlo
            'name' => strip_tags($validated['name']), 
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Contraseña intacta y hasheada
            'role' => $validated['role'],
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
public function resetPassword(Request $request, User $user)
{
    // 1. Validamos que llegue la nueva clave correctamente
    $request->validate([
        'new_password' => 'required|min:8'
    ]);
    
    // 2. ASIGNACIÓN DIRECTA: Evita problemas si 'password' no está en el $fillable
    // Usamos Hash::make de forma estándar
    $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
    
    // 3. Guardamos directamente el modelo en la base de datos
    $user->save();

    return back()->with('success', 'La contraseña de ' . $user->name . ' ha sido actualizada exitosamente.');
}

}
