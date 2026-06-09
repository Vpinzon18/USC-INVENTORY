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

    $assets = Asset::with(['room', 'currentCustodian'])
        ->when($search, function ($query, $search) {
            return $query->where('serial_number', 'LIKE', "%{$search}%")
                         ->orWhere('hostname', 'LIKE', "%{$search}%")
                         ->orWhere('internal_code', 'LIKE', "%{$search}%");
        })
        ->paginate($perPage) 
        ->withQueryString(); 

    return view('admin.assets.index', compact('assets', 'search', 'perPage'));
}

    public function create()
    {
        $rooms = Room::all();
        $custodians = Custodian::all();
        $campuses = Campus::all();
        return view('admin.assets.create', compact('rooms', 'custodians','campuses'));
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

    
    return view('admin.assets.edit', compact('asset', 'rooms', 'custodians','campuses'));
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
    $asset = Asset::with(['currentCustodian', 'room.building', 'technicalServices.user'])->findOrFail($id);

    
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
        ->header('Content-Disposition', 'attachment; filename="Hoja_Vida_USC_'.$asset->internal_code.'.pdf"');
}

}