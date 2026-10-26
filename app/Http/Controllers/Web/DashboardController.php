<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Campus;
use App\Models\Room;
use App\Models\TechnicalService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = Asset::query()->count();
        $managedAgents = Asset::query()->where('is_agent_managed', true)->count();
        $onlineAgents = Asset::query()
            ->where('is_agent_managed', true)
            ->where('agent_last_seen', '>=', now()->subMinutes(Asset::ONLINE_THRESHOLD_MINUTES))
            ->count();
        $offlineAgents = max(0, $managedAgents - $onlineAgents);
        $campusCount = Campus::query()->count();
        $roomCount = Room::query()->count();

        $assetsByCampus = Campus::query()
            ->leftJoin('buildings', 'buildings.campus_id', '=', 'campuses.id')
            ->leftJoin('rooms', 'rooms.building_id', '=', 'buildings.id')
            ->leftJoin('assets', 'assets.room_id', '=', 'rooms.id')
            ->select(['campuses.id', 'campuses.name'])
            ->selectRaw('COUNT(DISTINCT assets.id) AS total')
            ->groupBy('campuses.id', 'campuses.name')
            ->orderBy('campuses.name')
            ->get();

        $assetsWithoutCampus = Asset::query()
            ->leftJoin('rooms as assigned_rooms', 'assets.room_id', '=', 'assigned_rooms.id')
            ->leftJoin('buildings as assigned_buildings', 'assigned_rooms.building_id', '=', 'assigned_buildings.id')
            ->whereNull('assigned_buildings.campus_id')
            ->count();

        if ($assetsWithoutCampus > 0) {
            $assetsByCampus->push((object) [
                'id' => null,
                'name' => 'Sin sede asignada',
                'total' => $assetsWithoutCampus,
            ]);
        }

        $maxAssetsByCampus = max(1, (int) $assetsByCampus->max('total'));

        $mostUsedModel = Asset::query()
            ->select('model_version', DB::raw('COUNT(*) AS total'))
            ->whereNotNull('model_version')
            ->where('model_version', '<>', '')
            ->groupBy('model_version')
            ->orderByDesc('total')
            ->first();

        $allTechnicianReports = TechnicalService::query()
            ->leftJoin('users', 'users.id', '=', 'technical_services.user_id')
            ->selectRaw("CASE WHEN users.role = 2 THEN COALESCE(NULLIF(BTRIM(users.name), ''), 'Sin técnico asignado') ELSE 'Sin técnico asignado' END AS technician_name")
            ->selectRaw('COUNT(technical_services.id) AS total')
            ->groupByRaw("CASE WHEN users.role = 2 THEN COALESCE(NULLIF(BTRIM(users.name), ''), 'Sin técnico asignado') ELSE 'Sin técnico asignado' END")
            ->orderByDesc('total')
            ->get();

        $technicianReportTotal = (int) $allTechnicianReports->sum('total');
        $technicianReportSegments = $allTechnicianReports->take(5)->values();
        $otherTechnicianReports = (int) $allTechnicianReports->slice(5)->sum('total');
        if ($otherTechnicianReports > 0) {
            $technicianReportSegments->push((object) [
                'technician_name' => 'Otros técnicos',
                'total' => $otherTechnicianReports,
            ]);
        }

        $technicianChartColors = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#f43f5e', '#64748b'];
        $technicianReportSegments = $technicianReportSegments
            ->values()
            ->map(function ($segment, $index) use ($technicianChartColors, $technicianReportTotal) {
                $segment->total = (int) $segment->total;
                $segment->percentage = $technicianReportTotal > 0
                    ? round(($segment->total / $technicianReportTotal) * 100, 4)
                    : 0;
                $segment->color = $technicianChartColors[$index % count($technicianChartColors)];

                return $segment;
            });

        $technicianPieStops = [];
        $pieOffset = 0.0;
        foreach ($technicianReportSegments as $segmentIndex => $segment) {
            $nextOffset = $segmentIndex === $technicianReportSegments->count() - 1
                ? 100
                : min(100, $pieOffset + $segment->percentage);
            $technicianPieStops[] = "{$segment->color} {$pieOffset}% {$nextOffset}%";
            $pieOffset = $nextOffset;
        }
        $technicianPieStyle = $technicianPieStops === []
            ? 'conic-gradient(#e2e8f0 0% 100%)'
            : 'conic-gradient(' . implode(', ', $technicianPieStops) . ')';

        $recentAssets = Asset::query()
            ->select(['id', 'hostname', 'internal_code', 'serial_number', 'model_version', 'agent_last_seen', 'is_agent_managed'])
            ->orderByRaw('COALESCE(agent_last_seen, last_seen_at) DESC NULLS LAST')
            ->limit(6)
            ->get();

        $powerBiEmbedUrl = config('services.power_bi.embed_url');

        return view('dashboard', compact(
            'totalAssets',
            'managedAgents',
            'onlineAgents',
            'offlineAgents',
            'campusCount',
            'roomCount',
            'assetsByCampus',
            'maxAssetsByCampus',
            'mostUsedModel',
            'technicianReportTotal',
            'technicianReportSegments',
            'technicianPieStyle',
            'recentAssets',
            'powerBiEmbedUrl',
        ));
    }
}
