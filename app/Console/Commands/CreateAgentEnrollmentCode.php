<?php

namespace App\Console\Commands;

use App\Models\SigmaAgentEnrollmentCode;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateAgentEnrollmentCode extends Command
{
    protected $signature = 'sigma:agent-enrollment-code {--expires=30 : Minutos antes de que caduque (5-1440)}';

    protected $description = 'Genera un código de un solo uso para autorizar el enrolamiento de SIGMA Agent';

    public function handle(): int
    {
        $expiresIn = filter_var($this->option('expires'), FILTER_VALIDATE_INT);
        if ($expiresIn === false || $expiresIn < 5 || $expiresIn > 1440) {
            $this->error('La expiración debe estar entre 5 y 1440 minutos.');
            return self::INVALID;
        }

        $administrator = User::query()->where('role', 1)->orderBy('id')->first();
        if (!$administrator) {
            $this->error('No se encontró un usuario administrador que pueda emitir el token del agente.');
            return self::FAILURE;
        }

        $code = bin2hex(random_bytes(32));

        DB::transaction(function () use ($administrator, $code, $expiresIn): void {
            SigmaAgentEnrollmentCode::create([
                'code_hash' => hash('sha256', $code),
                'created_by_user_id' => $administrator->getKey(),
                'expires_at' => now()->addMinutes($expiresIn),
            ]);
        });

        $this->newLine();
        $this->warn('Código de un solo uso. Ingréselo en el instalador; no lo agregue al repositorio ni lo comparta.');
        $this->line($code);
        $this->line('Vence en ' . $expiresIn . ' minutos.');
        $this->newLine();

        return self::SUCCESS;
    }
}
