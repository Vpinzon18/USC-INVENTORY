<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campus;
use App\Models\Building;
use App\Models\Room;
use App\Models\Dependency;

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
    // Búsqueda de Dependencias
    public function dependencies(Request $request)
    {
        $q = $request->q;
        $results = \App\Models\Dependency::when($q, function($query, $q) {
                $query->where('name', 'LIKE', "%{$q}%");
            })->take(20)->get();

        return response()->json($results->map(function ($item) {
            return [
                'id' => $item->id, 
                'primary' => $item->name, 
                'secondary' => 'Dependencia / Área'
            ];
        }));
    }

    // Búsqueda de Cargos
    public function jobTitles(Request $request)
    {
        $q = $request->q;
        $results = \App\Models\JobTitle::when($q, function($query, $q) {
                $query->where('name', 'LIKE', "%{$q}%");
            })->take(20)->get();

        return response()->json($results->map(function ($item) {
            return [
                'id' => $item->id, 
                'primary' => $item->name, 
                'secondary' => 'Cargo Institucional'
            ];
        }));
    }
    public function getSedes(Request $request)
    {
        $search = $request->query('search');
        $sedes = Campus::select('id', 'name')
            ->when($search, function($query) use ($search) {
                // Cambiado a ilike para ignorar mayúsculas/minúsculas
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($sedes);
    }

    // Buscar Bloques / Edificios
    public function getBuildings(Request $request)
    {
        $search = $request->query('search');
        $buildings = Building::select('id', 'name')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($buildings);
    }

    // Buscar Salones / Espacios (Ahora filtrable por Bloque)
    public function getRooms(Request $request)
    {
        $search = $request->query('search');
        $buildingId = $request->query('building_id'); // Parámetro en cascada

        $rooms = Room::select('id', 'nomenclatura')
            ->when($search, function($query) use ($search) {
                $query->where('nomenclatura', 'ilike', "%{$search}%");
            })
            ->when($buildingId, function($query) use ($buildingId) {
                $query->where('building_id', $buildingId);
            })
            ->limit(20)
            ->get();

        return response()->json($rooms);
    }

    // 🚀 NUEVO: Buscar Equipos (Filtrable por Bloque, Salón y Dependencia)
    public function getAssets(Request $request)
    {
        $search = $request->query('search');
        $roomId = $request->query('room_id');
        $buildingId = $request->query('building_id');
        $dependencyId = $request->query('dependency_id');

        $assets = \App\Models\Asset::select('id', 'serial_number', 'internal_code', 'room_id')
            ->with(['room:id,nomenclatura,building_id', 'room.building:id,name'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('serial_number', 'ilike', "%{$search}%")
                      ->orWhere('internal_code', 'ilike', "%{$search}%");
                });
            })
            ->when($roomId, function($query) use ($roomId) {
                $query->where('room_id', $roomId);
            })
            ->when($buildingId, function($query) use ($buildingId) {
                $query->whereHas('room', function($q) use ($buildingId) {
                    $q->where('building_id', $buildingId);
                });
            })
            ->when($dependencyId, function($query) use ($dependencyId) {
                $query->whereHas('custodian', function($q) use ($dependencyId) {
                    $q->where('dependency_id', $dependencyId);
                });
            })
            ->limit(20)
            ->get()
            ->map(function($asset) {
                // Formateamos la respuesta para que la vista reciba un nombre ultra-detallado
                $roomName = $asset->room ? $asset->room->nomenclatura : 'Sin Salón';
                $bName = ($asset->room && $asset->room->building) ? $asset->room->building->name : '';
                $location = $bName ? "$roomName, $bName" : $roomName;
                
                $code = $asset->internal_code ? " | Placa: {$asset->internal_code}" : "";
                return [
                    'id' => $asset->id,
                    'name' => "SN: {$asset->serial_number}{$code} - Ubicación: {$location}"
                ];
            });

        return response()->json($assets);
    }

    // Buscar Dependencias Organizacionales
    public function getDependencies(Request $request)
    {
        $search = $request->query('search');
        $dependencies = Dependency::select('id', 'name')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($dependencies);
    }

    // Buscar Técnicos Asignados
    public function getTechnicians(Request $request)
    {
        $search = $request->query('search');
        
        $technicians = User::select('id', 'name')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($technicians);
    }
}
    
