<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilterApiController extends Controller
{
    // 1. Endpoint para Responsables
    public function custodians(Request $request)
    {
        $q = $request->q;
        
        $results = \App\Models\Custodian::with('dependency')
            ->when($q, function($query, $q) {
                $query->where('full_name', 'ilike', "%{$q}%")
                      ->orWhere('document_number', 'ilike', "%{$q}%")
                      ->orWhereHas('dependency', function($dep) use ($q) {
                          $dep->where('name', 'ilike', "%{$q}%");
                      });
            })
            ->take(20) // Solo enviamos los 20 más relevantes
            ->get();

        // Estandarizamos la respuesta JSON para el componente
        return response()->json($results->map(function ($item) {
            return [
                'id' => $item->id,
                'primary' => $item->full_name, // Lo principal que ve el usuario
                'secondary' => $item->document_number . ' • ' . ($item->dependency->name ?? 'Sin dependencia') // Contexto adicional
            ];
        }));
    }

    // 2. Endpoint para Ubicaciones (Agrupadas visualmente)
    public function locations(Request $request)
    {
        $q = $request->q;
        
        $results = \App\Models\Room::with('building.campus')
            ->when($q, function($query, $q) {
                $query->where('nomenclatura', 'ilike', "%{$q}%")
                      ->orWhere('name', 'ilike', "%{$q}%")
                      ->orWhereHas('building.campus', function($c) use ($q) {
                          $c->where('name', 'ilike', "%{$q}%");
                      });
            })
            ->take(20)
            ->get();

        return response()->json($results->map(function ($item) {
            return [
                'id' => $item->id,
                'primary' => $item->nomenclatura . ' - ' . $item->name,
                'secondary' => 'Sede: ' . ($item->building->campus->name ?? '') . ' | Bloque: ' . ($item->building->name ?? '')
            ];
        }));
    }
    
    // Búsqueda de Sedes
    public function campuses(Request $request)
    {
        $q = $request->q;
        $results = \App\Models\Campus::when($q, function($query, $q) {
                $query->where('name', 'LIKE', "%{$q}%");
            })->take(20)->get();

        return response()->json($results->map(function ($item) {
            return ['id' => $item->id, 'primary' => $item->name, 'secondary' => 'Sede Universitaria'];
        }));
    }

    // Búsqueda de Bloques / Edificios
    public function buildings(Request $request)
    {
        $q = $request->q;
        $results = \App\Models\Building::with('campus')->when($q, function($query, $q) {
                $query->where('name', 'LIKE', "%{$q}%");
            })->take(20)->get();

        return response()->json($results->map(function ($item) {
            return [
                'id' => $item->id, 
                'primary' => $item->name, 
                'secondary' => 'Sede: ' . ($item->campus->name ?? 'Sin sede asignada')
            ];
        }));
    }

    // Búsqueda de Oficinas / Salones
    public function rooms(Request $request)
    {
        $q = $request->q;
        $results = \App\Models\Room::with('building.campus')->when($q, function($query, $q) {
                $query->where('nomenclatura', 'LIKE', "%{$q}%")
                      ->orWhere('name', 'LIKE', "%{$q}%");
            })->take(20)->get();

        return response()->json($results->map(function ($item) {
            return [
                'id' => $item->id, 
                'primary' => $item->nomenclatura . ' - ' . $item->name, 
                'secondary' => ($item->building->name ?? '') . ' | ' . ($item->building->campus->name ?? '')
            ];
        }));
    }
}