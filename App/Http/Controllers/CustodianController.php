<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custodian;
use App\Models\Room;


class CustodianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Capturamos la búsqueda
    $search = $request->input('search');

    // Capturamos el límite dinámico desde el selector. Por defecto será 10.
    $perPage = $request->input('per_page', 10);

    $custodians = \App\Models\Custodian::query()
        ->when($search, function ($query, $search) {
            return $query->where('full_name', 'LIKE', "%{$search}%")
                         ->orWhere('document_number', 'LIKE', "%{$search}%")
                         ->orWhere('dependency', 'LIKE', "%{$search}%");
        })
        ->orderBy('full_name', 'asc')
        ->paginate($perPage) // Reemplazamos el 10 fijo por la variable dinámica
        ->withQueryString(); // Crucial: Mantiene tanto 'search' como 'per_page' en la URL

    // Pasamos 'perPage' a la vista para que el <select> mantenga la opción elegida
    return view('admin.custodians.index', compact('custodians', 'search', 'perPage'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
    $rooms = Room::with('building')->orderBy('nomenclatura')->get();
    return view('admin.custodians.create', compact('rooms'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'full_name'       => 'required|string|max:255',
        'document_number' => 'required|string|unique:custodians,document_number',
        'dependency'      => 'required|string|max:255',
        'job_title'       => 'required|string|max:255',
        'email'           => 'nullable|email|max:255',
        'extension'       => 'nullable|string|max:10',
    ], [
        'document_number.unique' => 'Este número de documento ya está registrado en SOMA.'
    ]);

    \App\Models\Custodian::create($validated);

    return redirect()->route('custodians.index')
        ->with('success', 'El responsable ha sido creado exitosamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Custodian $custodian)
{
    // 1. Cargamos todas las ubicaciones para el buscador y los checkboxes
    // Incluimos 'building' para que el script de búsqueda por bloque funcione
    $rooms = \App\Models\Room::with('building')->orderBy('nomenclatura')->get();

    // 2. Retornamos la vista pasando AMBAS variables
    return view('admin.custodians.edit', compact('custodian', 'rooms'));
}

public function update(Request $request, Custodian $custodian)
{
    $validated = $request->validate([
        'full_name'       => 'required|string|max:255',
        'dependency'      => 'required|string|max:255',
        'job_title'       => 'required|string|max:255',
        'email'           => 'nullable|email|max:255',
        'extension'       => 'nullable|string|max:10',
        'document_number' => 'required|string|unique:custodians,document_number,' . $custodian->id,
        
        // Nueva validación para las ubicaciones
        'rooms'           => 'nullable|array',
        'rooms.*'         => 'exists:rooms,id', 
    ]);

    // Actualizamos los datos básicos del responsable
    $custodian->update($validated);

    // Sincronizamos las nomenclaturas/ubicaciones
    // Si el usuario desmarca todo, el array vendrá vacío o no vendrá, 
    // sync([]) se encarga de limpiar la tabla intermedia correctamente.
    $custodian->rooms()->sync($request->input('rooms', []));

    return redirect()->route('custodians.index')
        ->with('success', 'Información del responsable y sus ubicaciones actualizadas correctamente.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
