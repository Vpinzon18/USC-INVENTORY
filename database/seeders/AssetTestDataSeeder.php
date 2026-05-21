<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear datos maestros (Ubicaciones y Custodios)
        $sedeId = DB::table('locations')->insertGetId(['name' => 'Sede Palmira', 'address' => 'Av. Principal 123']);
        $roomId = DB::table('rooms')->insertGetId(['name' => 'Sala de Sistemas 1', 'location_id' => $sedeId]);
        $custodianId = DB::table('custodians')->insertGetId(['name' => 'Juan Pérez', 'email' => 'jperez@usc.edu.co']);

        // 2. Crear Equipos de prueba
        DB::table('assets')->insert([
            [
                'serial_number' => 'SN-USC-001',
                'hostname' => 'PC-PALMIRA-01',
                'internal_code' => 'USC-001',
                'sede_id' => $sedeId,
                'room_id' => $roomId,
                'created_at' => now(),
            ],
            [
                'serial_number' => 'SN-USC-002',
                'hostname' => 'PC-PALMIRA-02',
                'internal_code' => 'USC-002',
                'sede_id' => $sedeId,
                'room_id' => $roomId,
                'created_at' => now(),
            ]
        ]);
    }
}