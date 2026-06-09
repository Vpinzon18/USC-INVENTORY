<?php

namespace App\Http\Controllers;

use App\Models\TechnicalService;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicalServiceController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 15, 25, 50])) {
            $perPage = 10;
        }

        $query = TechnicalService::with(['asset.room.building', 'technician']);


        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {

                $q->whereHas('asset', function ($assetQuery) use ($search) {
                    $assetQuery->where('serial_number', 'ilike', "%{$search}%")
                        ->orWhere('internal_code', 'ilike', "%{$search}%");
                })

                    ->orWhereHas('technician', function ($techQuery) use ($search) {
                        $techQuery->where('name', 'ilike', "%{$search}%");
                    })

                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }


        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('performed_at', [$fromDate, $toDate]);
        }


        $services = $query->latest('performed_at')
            ->paginate($perPage)
            ->withQueryString();
        return view('admin.maintenances.index', compact('services', 'search', 'fromDate', 'toDate', 'perPage'));
    }

    public function create(Request $request)
    {
        $query = Asset::with('room.building');

        if ($request->filled('asset_id')) {
            $query->where('id', $request->asset_id);
        }

        $assets = $query->orderBy('serial_number')->get();

        return view('admin.maintenances.create', compact('assets'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'asset_id'      => 'required|exists:assets,id',
            'performed_at'  => 'required|date',
            'description'   => 'required|string|min:3',
            'type'          => 'required',
        ]);

        $finalType = ($request->type === 'Otro')
            ? $request->custom_type
            : $request->type;


        \App\Models\TechnicalService::create([
            'asset_id'                => $request->asset_id,
            'user_id'                 => Auth::user()->id,
            'performed_at'            => $request->performed_at,
            'type'                    => $finalType,
            'description'             => $request->description,
            'security_guaya'          => $request->security_guaya,
            'maintenance_schedule_id' => $request->input('maintenance_schedule_id'),
        ]);

        if (strtoupper($finalType) === 'PREVENTIVO' && $request->filled('maintenance_schedule_id')) {
            \App\Models\MaintenanceSchedule::where('id', $request->maintenance_schedule_id)
                ->update(['status' => 'REALIZADO']);
        }

        return redirect()->route('maintenances.index')
            ->with('success', 'Registro guardado correctamente.');
    }

    public function edit(int $id)
    {

        $maintenance = TechnicalService::findOrFail($id);
        $assets = \App\Models\Asset::all();
        return view('admin.maintenances.edit', compact('maintenance', 'assets'));
    }
    public function update(Request $request, TechnicalService $maintenance)
    {
        $validated = $request->validate([
            'performed_at' => 'required|date',
            'type'         => 'required|string',
            'description'  => 'required|string|min:5',
            'asset_id'     => 'required|exists:assets,id',
        ]);


        $maintenance->update($validated);

        return redirect()->route('maintenances.index')
            ->with('success', 'Bitácora actualizada correctamente.');
    }
}
