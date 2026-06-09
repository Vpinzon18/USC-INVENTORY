<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Campus;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon; 

class InventoryController extends Controller
{
   public function index()
{
    $assets = \App\Models\Asset::with(['room.building.campus', 'software'])->latest()->get();
    
    $total = $assets->count();
    $online = $assets->filter(function ($asset) {
        return $asset->last_seen_at && \Carbon\Carbon::parse($asset->last_seen_at)->gt(now()->subMinutes(10));
    })->count();
    
    $totalUsers = \App\Models\User::count();
    $totalCampuses = \App\Models\Campus::count();
    $totalRooms = \App\Models\Room::count();

    $modelStats = \Illuminate\Support\Facades\DB::table('assets')
        ->select('model_version', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->whereNotNull('model_version')
        ->groupBy('model_version')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();

    $mostUsedModel = $modelStats->first();

    return view('dashboard', compact(
        'assets', 
        'total', 
        'online', 
        'totalUsers', 
        'totalCampuses', 
        'totalRooms', 
        'modelStats', 
        'mostUsedModel'
    ));
}
    public function report(Request $request)
    { 
        $asset = Asset::updateOrCreate(
            ['serial_number' => $request->serial_number],
            [
                'hostname'   => $request->hostname,
                'ip_address' => $request->ip_address,
                'cpu'        => $request->cpu,        
                'ram'        => $request->ram,      
                'storage'    => $request->storage,
                'model_version'=> $request->model_version,    
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'ip_registrada' => $asset->ip_address
        ]);
    }
    public function heartbeat(Request $request)
    {
     
        $request->validate([
            'mac_address' => 'required|string'
        ]);


        $asset = \App\Models\Asset::where('mac_address', $request->mac_address)->first();

        if ($asset) {
            $asset->update([
                'last_seen_at' => now()
            ]);
            
            return response()->json([
                'status' => 'success', 
                'message' => 'Heartbeat recibido. Equipo en línea.'
            ], 200);
        }

        return response()->json([
            'status' => 'error', 
            'message' => 'Equipo no encontrado en SOMA.'
        ], 404);
    }
}