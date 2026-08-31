<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Campus;
use App\Models\Custodian;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 15);

        // 1. NOMBRES PARA COMPONENTES AJAX (Evita cargar miles de registros, solo busca el seleccionado)
        $selectedCustodianName = $request->filled('custodian_id') ? \App\Models\Custodian::find($request->custodian_id)?->full_name : '';
        $selectedCampusName    = $request->filled('campus_id')    ? \App\Models\Campus::find($request->campus_id)?->name : '';
        $selectedBuildingName  = $request->filled('building_id')  ? \App\Models\Building::find($request->building_id)?->name : '';
        $selectedRoomName      = $request->filled('room_id')      ? \App\Models\Room::find($request->room_id)?->nomenclatura : '';

        // Catálogo estático (Solo SO porque son muy poquitos)
        $osVersions = \App\Models\Asset::whereNotNull('os_version')->distinct()->pluck('os_version');

        // 2. CONSTRUIR LA CONSULTA BASE
        $query = \App\Models\Asset::with(['room.building.campus', 'currentCustodian']);

        // Búsqueda de texto libre (Serial, Hostname, Placa)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'LIKE', "%{$search}%")
                    ->orWhere('hostname', 'LIKE', "%{$search}%")
                    ->orWhere('internal_code', 'LIKE', "%{$search}%");
            });
        }

        // ==========================================
        // 3. APLICAR FILTROS AVANZADOS SI EXISTEN
        // ==========================================

        // Filtros de Ubicación (Sede > Bloque > Oficina)
        if ($request->filled('campus_id')) {
            $query->whereHas('room.building', function ($q) use ($request) {
                $q->where('campus_id', $request->campus_id);
            });
        }
        if ($request->filled('building_id')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('building_id', $request->building_id);
            });
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Filtro: Responsable
        if ($request->filled('custodian_id')) {
            $query->whereHas('currentAssignment', function ($q) use ($request) {
                $q->where('custodian_id', $request->custodian_id);
            });
        }

        // Filtro: Sistema Operativo
        if ($request->filled('os_version')) {
            $query->where('os_version', $request->os_version);
        }

        // Filtro: Agente SIGMA
        if ($request->filled('agent')) {
            $query->where('is_agent_managed', $request->agent);
        }

        // Filtro: Ficha Técnica
        if ($request->filled('inventory_status')) {
            if ($request->inventory_status == 'completo') {
                $query->whereNotNull('monitor_serial')->whereNotNull('keyboard_serial')->whereNotNull('security_guaya');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('monitor_serial')->orWhereNull('keyboard_serial')->orWhereNull('security_guaya');
                });
            }
        }

        // Filtro: Conectividad
        if ($request->filled('connectivity')) {
            if ($request->connectivity == 'online') {
                $query->where('last_seen_at', '>=', now()->subMinutes(10));
            } else {
                $query->where(function ($q) {
                    $q->where('last_seen_at', '<', now()->subMinutes(10))->orWhereNull('last_seen_at');
                });
            }
        }

        // Ejecutar consulta paginada
        $assets = $query->latest()->paginate($perPage)->withQueryString();

        // 4. CÁLCULO DE KPIs
        $total = \App\Models\Asset::count();
        $online = \App\Models\Asset::where('last_seen_at', '>=', now()->subMinutes(10))->count();
        $offline = $total - $online;
        $agentManaged = \App\Models\Asset::where('is_agent_managed', true)->count();
        $unassigned = \App\Models\Asset::whereDoesntHave('assignments', function ($q) {
            $q->where('status', 'active');
        })->count();
        $incomplete = \App\Models\Asset::where(function ($query) {
            $query->whereNull('keyboard_serial')
                ->orWhereNull('security_guaya')
                ->orWhereDoesntHave('monitors');
        })->count();

        // ENVIAR TODO A LA VISTA
        return view('admin.assets.index', compact(
            'assets',
            'search',
            'perPage',
            'total',
            'online',
            'offline',
            'agentManaged',
            'unassigned',
            'incomplete',
            'osVersions',
            'selectedCustodianName',
            'selectedCampusName',
            'selectedBuildingName',
            'selectedRoomName' // Variables limpias para AJAX
        ));
    }

    public function create()
    {
        $rooms = Room::all();
        $custodians = Custodian::all();
        $campuses = Campus::all();
        return view('admin.assets.create', compact('rooms', 'custodians', 'campuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'       => 'required|exists:rooms,id',
            'serial_number' => 'required|unique:assets,serial_number',
            'hostname'      => 'nullable|string',
            'model_version' => 'nullable|string',
            'ip_address'    => 'nullable|ip',
            'cpu'           => 'nullable|string',
            'ram'           => 'nullable|string',
            'storage'       => 'nullable|string',
            'custodian_id'  => 'required|exists:custodians,id',
        ]);

        $asset = Asset::create($validated);
        Assignment::create([
            'asset_id' => $asset->id,
            'custodian_id' => $request->custodian_id,
            'room_id' => $request->room_id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        return redirect()->route('assets.index')->with('success', 'Equipo registrado con éxito.');
    }
    public function edit(Asset $asset)
    {

        $rooms = Room::all();
        $custodians = Custodian::all();
        $campuses = \App\Models\Campus::all();


        return view('admin.assets.edit', compact('asset', 'rooms', 'custodians', 'campuses'));
    }

    public function update(Request $request, Asset $asset)
    {

        $validated = $request->validate([
            'room_id'         => 'required|exists:rooms,id',
            'serial_number'   => 'required|unique:assets,serial_number,' . $asset->id,
            'internal_code'   => 'nullable|unique:assets,internal_code,' . $asset->id,
            'custodian_id'    => 'nullable|exists:custodians,id',
            'hostname'        => 'nullable|string',
            'model_version'   => 'nullable|string',
            'ip_address'      => 'nullable|ip',
            'cpu'             => 'nullable|string',
            'ram'             => 'nullable|string',
            'storage'         => 'nullable|string',
            'monitor_asset'   => 'nullable|string',
            'monitor_serial'  => 'nullable|string',
            'keyboard_serial' => 'nullable|string',
            'mouse_serial'    => 'nullable|string',
            'security_guaya'  => 'nullable|string',
            'mac_address'     => 'nullable|string',
            'wifi_card'       => 'nullable|string',
            'graphics_card'   => 'nullable|string',
            'os_version'      => 'nullable|string',
            'domain_name'     => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Hoja de Vida actualizada correctamente.');
    }
    /**
     * Muestra la Hoja de Vida detallada de un equipo específico.
     */
    public function previewPdf(Asset $asset)
    {

        $asset->load(['currentCustodian', 'room.building', 'assignments', 'technicalServices']);


        return view('admin.assets.pdf_preview', compact('asset'));
    }

    public function downloadPdf(int $id)
    {
        $asset = Asset::with([
            'currentCustodian',
            'room.building',
            'currentCustodian.jobTitle',   // <-- AGREGADO
            'currentCustodian.dependency',
            'technicalServices.user'
        ])->findOrFail($id);


        $html = view('admin.assets.pdf_export', compact('asset'))->render();

        $pdf = Browsershot::html($html)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setChromePath('C:\Program Files\Google\Chrome\Application\chrome.exe')
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'disable-gpu',
                'no-zygote'
            ])
            ->showBackground()
            ->format('Letter')
            ->setMargins(10, 10, 10, 10)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Hoja_Vida_USC_' . $asset->internal_code . '.pdf"');
    }
    public function destroy(Asset $asset)
    {
        try {

            $asset->delete();

            return redirect()->route('assets.index')
                ->with('success', 'El activo ha sido dado de baja y eliminado del CMDB exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Si la base de datos bloquea el borrado por llaves foráneas (Integridad referencial)
            return redirect()->route('assets.index')
                ->with('error', 'No se puede dar de baja el equipo porque tiene historiales de movimiento o mantenimientos asociados en el sistema.');
        } catch (\Exception $e) {
            // Cualquier otro error inesperado
            return redirect()->route('assets.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el activo.');
        }
    }
}
