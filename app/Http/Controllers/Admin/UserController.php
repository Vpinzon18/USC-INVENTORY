<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|integer|in:1,2,3',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $validated['name'] = strip_tags($validated['name']);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
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
    abort_unless((int) Auth::user()?->role === 1, 403);

    if (Auth::id() === $user->id) {
        return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
    }

    $references = [
        ['table' => 'assignments', 'column' => 'user_id', 'label' => 'movimientos o asignaciones'],
        ['table' => 'technical_services', 'column' => 'user_id', 'label' => 'servicios técnicos'],
        ['table' => 'sigma_agent_enrollment_codes', 'column' => 'created_by_user_id', 'label' => 'códigos de enrolamiento'],
        ['table' => 'remote_support_sessions', 'column' => 'requested_by_user_id', 'label' => 'sesiones de asistencia remota'],
    ];

    $linkedRecords = [];
    foreach ($references as $reference) {
        // Some optional modules may not have been migrated in every deployment.
        if (!Schema::hasTable($reference['table']) || !Schema::hasColumn($reference['table'], $reference['column'])) {
            continue;
        }

        $count = DB::table($reference['table'])
            ->where($reference['column'], $user->id)
            ->count();

        if ($count > 0) {
            $linkedRecords[] = number_format($count) . ' ' . $reference['label'];
        }
    }

    if ($linkedRecords !== []) {
        return redirect()->route('users.index')->with(
            'error',
            'No se puede eliminar este usuario porque conserva registros asociados: ' .
            implode(', ', $linkedRecords) . '. Reasigna esos registros desde su módulo para conservar el historial.'
        );
    }

    try {
        $user->delete();
    } catch (QueryException $exception) {
        // A concurrent request may create a reference after the checks above.
        if ($exception->getCode() === '23503') {
            return redirect()->route('users.index')->with(
                'error',
                'No se puede eliminar este usuario porque otro registro del sistema todavía depende de su cuenta.'
            );
        }

        throw $exception;
    }

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
