<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Dependency;
use App\Models\JobTitle;

class MigrateCustodiansDataSeeder extends Seeder
{
    public function run()
    {
        // Traemos todos los registros actuales
        $custodians = DB::table('custodians')->get();

        foreach ($custodians as $custodian) {
            // 1. Buscamos o creamos la dependencia usando el texto viejo
            // Se usa trim() para quitar espacios accidentales
            $dependencyName = trim($custodian->dependency);
            if (!empty($dependencyName)) {
                $dep = Dependency::firstOrCreate(['name' => $dependencyName]);
                $depId = $dep->id;
            } else {
                $depId = null;
            }

            // 2. Buscamos o creamos el cargo usando el texto viejo
            $jobTitleName = trim($custodian->job_title);
            if (!empty($jobTitleName)) {
                $job = JobTitle::firstOrCreate(['name' => $jobTitleName]);
                $jobId = $job->id;
            } else {
                $jobId = null;
            }

            // 3. Actualizamos el registro de la persona con los nuevos IDs numéricos
            DB::table('custodians')
                ->where('id', $custodian->id)
                ->update([
                    'dependency_id' => $depId,
                    'job_title_id' => $jobId
                ]);
        }
    }
}