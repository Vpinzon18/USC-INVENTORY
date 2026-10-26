<?php

namespace App\Console\Commands;

use App\Services\Glpi\GlpiApiClient;
use Illuminate\Console\Command;
use Throwable;

class TestGlpiConnection extends Command
{
    protected $signature = 'sigma:glpi-test-connection';

    protected $description = 'Verifica conectividad y autenticación de SIGMA con la API REST de GLPI sin crear tickets';

    public function handle(GlpiApiClient $client): int
    {
        try {
            $result = $client->testConnection();
            $this->info('Conexión y autenticación con GLPI correctas.');
            $this->line('API: '.$result['api'].' | HTTP '.$result['status']);
            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        }
    }
}
