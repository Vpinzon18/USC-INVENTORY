<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Custodian;
use App\Models\Room;
use App\Models\JobTitle;    
use App\Models\Dependency; 
use Illuminate\Validation\Rule;

class CustodianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // 1. CAPTURAR VARIABLES DE BÚSQUEDA Y FILTROS
    $search = $request->input('search');
    $perPage = $request->input('per_page', 15); // Paginación dinámica
    $quickFilter = $request->input('quick'); // Filtros rápidos

    // 2. CONSTRUIR LA CONSULTA PRINCIPAL
    $query = \App\Models\Custodian::with([
        'dependency', 
        'jobTitle', 
        'activeAssets' => function($q) {
            $q->select('assets.id', 'assets.model_version', 'assets.is_agent_managed'); 
        }
    ])
    ->withCount(['activeAssets as assets_count']);

    // Filtro de Texto Global
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('full_name', 'LIKE', "%{$search}%")
              ->orWhere('document_number', 'LIKE', "%{$search}%")
              ->orWhereHas('dependency', function($dep) use ($search) {
                  $dep->where('name', 'LIKE', "%{$search}%");
              });
        });
    }

    // Filtros Rápidos
    if ($quickFilter) {
        if ($quickFilter === 'with_assets') {
            $query->has('activeAssets');
        } elseif ($quickFilter === 'no_assets') {
            $query->doesntHave('activeAssets');
        } elseif ($quickFilter === 'managed') {
            $query->whereHas('activeAssets', function($q) {
                $q->where('is_agent_managed', true);
            });
        }
    }

    // 3. EJECUTAR CONSULTA Y ENVIAR A LA VISTA
    $custodians = $query->latest()->paginate($perPage)->withQueryString();

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
        'document_number' => 'required|string|unique:custodians,document_number',
        'status'          => 'required|in:active,inactive',
        'email'           => 'nullable|email|max:255',
        'extension'       => 'nullable|string|max:10',
        'rooms'           => 'nullable|array',
        
        'rooms.*'         => [
            'exists:rooms,id',
            function ($attribute, $value, $fail) {
                if (request('status') === 'active') {
                    $isOccupied = \App\Models\Custodian::where('status', 'active')
                        ->whereHas('rooms', function($query) use ($value) {
                            $query->where('rooms.id', $value);
                        })->exists();

                    if ($isOccupied) {
                        $fail('⚠️ Una de las ubicaciones seleccionadas ya está asignada a otro responsable activo.');
                    }
                }
            }
        ],
        
        'cost_center'     => 'required_if:status,active|nullable|string|max:50',
        
        'dependency_id'   => [
            'required_if:status,active',
            'nullable',
            'exists:dependencies,id',
            \Illuminate\Validation\Rule::unique('custodians', 'dependency_id')
                ->where('status', 'active')
        ],
        
        'job_title_id'    => [
            'required_if:status,active',
            'nullable',
            'exists:job_titles,id',
            \Illuminate\Validation\Rule::unique('custodians', 'job_title_id')
                ->where('status', 'active')
        ],
    ], [
        'document_number.unique'    => 'Este número de documento ya está registrado en SOMA.',
        'dependency_id.required_if' => 'Debe seleccionar una dependencia si el responsable estará activo.',
        'dependency_id.exists'      => 'La dependencia seleccionada no es válida.',
        'dependency_id.unique'      => ' Esta dependencia ya está asignada a un responsable activo.',
        'job_title_id.required_if'  => 'Debe seleccionar un cargo si el responsable estará activo.',
        'job_title_id.exists'       => 'El cargo seleccionado no es válido.',
        'job_title_id.unique'       => ' Este cargo ya está ocupado por un responsable activo.'
    ]);

    foreach ($validated as $key => $value) {
        if (is_string($value)) {
            $validated[$key] = strip_tags($value);
        }
    }

    if ($validated['status'] === 'inactive') {
        $validated['dependency_id'] = null;
        $validated['job_title_id']  = null;
        $validated['cost_center']   = null;
    }

    $custodian = \App\Models\Custodian::create($validated);

    if ($validated['status'] === 'active' && $request->has('rooms')) {
        $custodian->rooms()->attach($request->input('rooms'));
    }

    return redirect()->route('custodians.index')
        ->with('success', 'El responsable ha sido creado exitosamente.');
}


public function update(Request $request, Custodian $custodian)
{
    $validated = $request->validate([
        'full_name'       => 'required|string|max:255',
        'status'          => 'required|in:active,inactive',
        'document_number' => 'required|string|unique:custodians,document_number,' . $custodian->id,
        'email'           => 'nullable|email|max:255',
        'extension'       => 'nullable|string|max:10',
        'rooms'           => 'nullable|array',
        
        'rooms.*'         => [
            'exists:rooms,id',
            function ($attribute, $value, $fail) use ($custodian) {
                if (request('status') === 'active') {
                    $isOccupied = \App\Models\Custodian::where('status', 'active')
                        ->where('id', '!=', $custodian->id)
                        ->whereHas('rooms', function($query) use ($value) {
                            $query->where('rooms.id', $value);
                        })->exists();

                    if ($isOccupied) {
                        $fail('⚠️ Una de las ubicaciones seleccionadas ya está asignada a otro responsable activo.');
                    }
                }
            }
        ], 
        
        'cost_center'     => 'required_if:status,active|nullable|string|max:50',
        
        'dependency_id'   => [
            'required_if:status,active',
            'nullable',
            'exists:dependencies,id',
            \Illuminate\Validation\Rule::unique('custodians', 'dependency_id')
                ->ignore($custodian->id)
                ->where('status', 'active')
        ],
        
        'job_title_id'    => [
            'required_if:status,active',
            'nullable',
            'exists:job_titles,id',
            \Illuminate\Validation\Rule::unique('custodians', 'job_title_id')
                ->ignore($custodian->id)
                ->where('status', 'active')
        ],
    ], [
        'document_number.unique'    => 'Este número de documento ya pertenece a otro registro en SIGMA.',
        'dependency_id.required_if' => 'Debe seleccionar una dependencia si el responsable estará activo.',
        'dependency_id.exists'      => 'La dependencia seleccionada no es válida.',
        'dependency_id.unique'      => '⚠️ Esta dependencia ya está asignada a otro responsable activo.',
        'job_title_id.required_if'  => 'Debe seleccionar un cargo si el responsable estará activo.',
        'job_title_id.exists'       => 'El cargo seleccionado no es válido.',
        'job_title_id.unique'       => '⚠️ Este cargo ya está ocupado por otro responsable activo.'
    ]);

    foreach ($validated as $key => $value) {
        if (is_string($value)) {
            $validated[$key] = strip_tags($value);
        }
    }

    if ($validated['status'] === 'inactive') {
        $validated['dependency_id'] = null;
        $validated['job_title_id']  = null;
        $validated['cost_center']   = null;
        $custodian->rooms()->detach();
    } else {
        if (isset($validated['rooms'])) {
            $custodian->rooms()->sync($validated['rooms']);
        } else {
            $custodian->rooms()->detach();
        }
    }

    $custodian->update($validated);

    return redirect()->route('custodians.index')
        ->with('success', 'Responsable actualizado de forma segura.');
}


    public function destroy(Custodian $custodian) 
    {
        $custodian->delete();

        return redirect()->route('custodians.index')
            ->with('success', 'El responsable ha sido eliminado del sistema.');
    }
    // En CustodianController.php

public function getRoomsData(Request $request)
{
    $custodianId = $request->query('custodian_id');
    
    $rooms = \App\Models\Room::with('building')->get()->map(function($room) use ($custodianId) {
        $occupant = \App\Models\Custodian::where('status', 'active')
            ->where('id', '!=', $custodianId ?? 0)
            ->whereHas('rooms', function($q) use ($room) {
                $q->where('rooms.id', $room->id);
            })->first();

        return [
            'id' => $room->id,
            'nomenclatura' => $room->nomenclatura,
            'building' => $room->building->name ?? 'Sede',
            'is_occupied' => !!$occupant,
            'occupant_name' => $occupant ? $occupant->full_name : null
        ];
    });

    return response()->json($rooms);
}
}