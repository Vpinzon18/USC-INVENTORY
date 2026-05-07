<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custodian;


class CustodianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');

    $custodians = \App\Models\Custodian::query()
        ->when($search, function ($query, $search) {
            return $query->where('full_name', 'LIKE', "%{$search}%")
                         ->orWhere('document_number', 'LIKE', "%{$search}%")
                         ->orWhere('dependency', 'LIKE', "%{$search}%");
        })
        ->orderBy('full_name', 'asc')
        ->paginate(10) // Aquí ponemos el límite de 10 por página
        ->withQueryString(); // Mantiene la búsqueda al cambiar de página

    return view('admin.custodians.index', compact('custodians', 'search'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('admin.custodians.create');
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
    return view('admin.custodians.edit', compact('custodian'));
}

public function update(Request $request, Custodian $custodian)
{
    $validated = $request->validate([
        'full_name'  => 'required|string|max:255',
        'dependency' => 'required|string|max:255',
        'job_title'  => 'required|string|max:255',
        'email'      => 'nullable|email|max:255',
        'extension'  => 'nullable|string|max:10',
        // Validamos que el documento sea único, excepto para este mismo registro
        'document_number' => 'required|string|unique:custodians,document_number,' . $custodian->id,
    ]);

    $custodian->update($validated);

    return redirect()->route('custodians.index')
        ->with('success', 'Información del responsable actualizada correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
