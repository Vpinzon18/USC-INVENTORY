<?php

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAgentToken extends Command
{
    protected $signature = 'sigma:agent-token {asset_id : ID del activo al que quedará ligado el agente} {--user= : ID de usuario administrador que será propietario del token}';

    protected $description = 'Emite una credencial Sanctum de un solo activo para SIGMA Agent';

    public function handle(): int
    {
        $asset = Asset::find($this->argument('asset_id'));
        if (!$asset) {
            $this->error('No existe el activo indicado.');
            return self::FAILURE;
        }

        $userQuery = User::query()->where('role', 1);
        if ($this->option('user')) {
            $userQuery->whereKey($this->option('user'));
        }

        $user = $userQuery->orderBy('id')->first();
        if (!$user) {
            $this->error('No se encontró un usuario administrador propietario del token.');
            return self::FAILURE;
        }

        $name = 'sigma-agent-asset-' . $asset->getKey();
        $existing = $user->tokens()->where('name', $name)->exists();
        if ($existing && !$this->confirm(
            'Ya existe un token para este equipo. ¿Revocarlo y emitir uno nuevo?'
        )) {
            $this->warn('No se hicieron cambios.');
            return self::SUCCESS;
        }

        if ($existing) {
            $user->tokens()->where('name', $name)->delete();
        }

        $issued = $user->createToken(
            $name,
            ['sigma-agent.asset.' . $asset->getKey()]
        );

        $this->newLine();
        $this->warn('Copie el token ahora: solo se muestra una vez. No lo agregue al repositorio ni al instalador.');
        $this->line($issued->plainTextToken);
        $this->newLine();

        return self::SUCCESS;
    }
}
