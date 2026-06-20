<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custodian;
use App\Models\Room;
use App\Models\JobTitle;    // IMPORTANTE: Agregado
use App\Models\Dependency;  // IMPORTANTE: Agregado

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

        // with() carga las relaciones de antemano para evitar sobrecargar la base de datos (N+1 problem)
        $custodians = Custodian::with(['jobTitle', 'dependency'])
            ->when($search, function ($query, $search) {
                return $query->where('full_name', 'LIKE', "%{$search}%")
                             ->orWhere('document_number', 'LIKE', "%{$search}%")
                             // Busca dentro del nombre de la relación Dependencia
                             ->orWhereHas('dependency', function ($q) use ($search) {
                                 $q->where('name', 'LIKE', "%{$search}%");
                             })
                             // Busca dentro del nombre de la relación Cargo
                             ->orWhereHas('jobTitle', function ($q) use ($search) {
                                 $q->where('name', 'LIKE', "%{$search}%");
                             });
            })
            ->orderBy('full_name', 'asc')
            ->paginate($perPage)
            ->withQueryString(); 

        return view('admin.custodians.index', compact('custodians', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        $rooms = Room::with('building')->orderBy('nomenclatura')->get();
        
        // Cargamos los catálogos ordenados alfabéticamente
        $jobtitles = JobTitle::orderBy('name')->get();
        $dependencies = Dependency::orderBy('name')->get();

        return view('admin.custodians.create', compact('rooms', 'jobtitles', 'dependencies'));
    }
 public function edit(Custodian $custodian)
    {
        $rooms = Room::with('building')->orderBy('nomenclatura')->get();
        
        // Cargamos los catálogos para el formulario de edición
        $jobtitles = JobTitle::orderBy('name')->get();
        $dependencies = Dependency::orderBy('name')->get();

        return view('admin.custodians.edit', compact('custodian', 'rooms', 'jobtitles', 'dependencies'));
    }
   public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'cost_center'     => 'required|string|max:50',
            'document_number' => 'required|string|unique:custodians,document_number',
            'dependency_id'   => 'required|exists:dependencies,id',
            'job_title_id'    => 'required|exists:job_titles,id',
            'email'           => 'nullable|email|max:255',
            'extension'       => 'nullable|string|max:10',
        ], [
            'document_number.unique' => 'Este número de documento ya está registrado en SOMA.',
            'dependency_id.required' => 'Debe seleccionar una dependencia.',
            'job_title_id.required'  => 'Debe seleccionar un cargo.'
        ]);

        // ESCUDO ANTI-XSS DINÁMICO: Limpia nombres, centros de costo, extensiones...
        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        Custodian::create($validated);

        return redirect()->route('custodians.index')
            ->with('success', 'El responsable ha sido creado exitosamente de forma segura.');
    }

    public function update(Request $request, Custodian $custodian)
    {
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'cost_center'     => 'required|string|max:50',
            'dependency_id'   => 'required|exists:dependencies,id',
            'job_title_id'    => 'required|exists:job_titles,id',
            'email'           => 'nullable|email|max:255',
            'extension'       => 'nullable|string|max:10',
            'document_number' => 'required|string|unique:custodians,document_number,' . $custodian->id,
            'rooms'           => 'nullable|array',
            'rooms.*'         => 'exists:rooms,id', 
        ], [
            'dependency_id.required' => 'Debe seleccionar una dependencia.',
            'job_title_id.required'  => 'Debe seleccionar un cargo.'
        ]);

        // ESCUDO ANTI-XSS DINÁMICO
        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        $custodian->update($validated);

        if (isset($validated['rooms'])) {
            $custodian->rooms()->sync($validated['rooms']);
        } else {
            $custodian->rooms()->detach();
        }

        return redirect()->route('custodians.index')->with('success', 'Responsable actualizado de forma segura.');
    }


    public function destroy(Custodian $custodian) 
    {
        $custodian->delete();

        return redirect()->route('custodians.index')
            ->with('success', 'El responsable ha sido eliminado del sistema.');
    }
}