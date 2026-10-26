<?php

namespace App\Services\Glpi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GlpiApiClient
{
    public function createTicket(array $event): int
    {
        $config = config('services.glpi');
        $baseUrl = (string) ($config['base_url'] ?? '');

        if (!$config['enabled'] || $baseUrl === '' || empty($config['app_token']) || empty($config['user_token'])) {
            throw new RuntimeException('GLPI no está habilitado o su configuración de API está incompleta.');
        }

        if (!str_starts_with($baseUrl, 'https://') && app()->environment('production')) {
            throw new RuntimeException('GLPI debe usar HTTPS en el entorno de producción.');
        }
        if (!$config['verify_tls'] && app()->environment('production')) {
            throw new RuntimeException('La validación TLS no se puede desactivar en producción.');
        }

        $http = $this->http($config);
        $sessionToken = null;

        try {
            $sessionResponse = $http
                ->withHeaders([
                    'App-Token' => $config['app_token'],
                    'Authorization' => 'user_token '.$config['user_token'],
                ])
                ->get($baseUrl.'/initSession');

            if (!$sessionResponse->successful() || !is_string($sessionResponse->json('session_token'))) {
                throw new RuntimeException('GLPI rechazó el inicio de sesión de API (HTTP '.$sessionResponse->status().').');
            }

            $sessionToken = $sessionResponse->json('session_token');
            $ticket = $this->ticketInput($event, (array) $config['user_map'], $config['default_group_id'], (int) $config['entity_id']);

            if ($ticket['assignment_missing']) {
                throw new RuntimeException('No hay usuario técnico asignable en GLPI ni grupo predeterminado configurado.');
            }
            unset($ticket['assignment_missing']);

            $response = $http
                ->withHeaders([
                    'App-Token' => $config['app_token'],
                    'Session-Token' => $sessionToken,
                ])
                ->post($baseUrl.'/Ticket', ['input' => $ticket]);

            if (!$response->successful()) {
                throw new RuntimeException('GLPI no creó el caso (HTTP '.$response->status().').');
            }

            $ticketId = $response->json('id');
            if (!is_numeric($ticketId) || (int) $ticketId < 1) {
                throw new RuntimeException('GLPI respondió sin un identificador de caso válido.');
            }

            return (int) $ticketId;
        } finally {
            if ($sessionToken) {
                try {
                    $http->withHeaders([
                        'App-Token' => $config['app_token'],
                        'Session-Token' => $sessionToken,
                    ])->get($baseUrl.'/killSession');
                } catch (\Throwable) {
                    // Session cleanup failure must not hide ticket creation outcome.
                }
            }
        }
    }

    public function testConnection(): array
    {
        $config = config('services.glpi');
        $baseUrl = (string) ($config['base_url'] ?? '');
        if ($baseUrl === '' || empty($config['app_token']) || empty($config['user_token'])) {
            throw new RuntimeException('Completa GLPI_BASE_URL, GLPI_APP_TOKEN y GLPI_USER_TOKEN antes de probar.');
        }

        if (!str_starts_with($baseUrl, 'https://') && app()->environment('production')) {
            throw new RuntimeException('La conexión de producción debe usar HTTPS.');
        }
        if (!$config['verify_tls'] && app()->environment('production')) {
            throw new RuntimeException('La validación TLS no se puede desactivar en producción.');
        }

        $response = $this->http($config)
            ->withHeaders([
                'App-Token' => $config['app_token'],
                'Authorization' => 'user_token '.$config['user_token'],
            ])
            ->get($baseUrl.'/initSession');

        if (!$response->successful() || !is_string($response->json('session_token'))) {
            throw new RuntimeException('No se pudo autenticar con GLPI (HTTP '.$response->status().').');
        }

        $token = $response->json('session_token');
        $this->http($config)->withHeaders([
            'App-Token' => $config['app_token'],
            'Session-Token' => $token,
        ])->get($baseUrl.'/killSession');

        return ['status' => $response->status(), 'api' => 'GLPI REST v1'];
    }

    private function http(array $config): PendingRequest
    {
        return Http::acceptJson()
            ->asJson()
            ->connectTimeout(max(1, (int) $config['connect_timeout']))
            ->timeout(max(2, (int) $config['timeout']))
            ->withOptions(['verify' => (bool) $config['verify_tls']]);
    }

    private function ticketInput(array $event, array $userMap, mixed $defaultGroupId, int $entityId): array
    {
        $input = [
            'name' => mb_substr((string) $event['title'], 0, 250),
            'content' => (string) $event['description'],
            'entities_id' => $entityId,
            'type' => (int) config('services.glpi.ticket_type', 2),
            'urgency' => 3,
            'impact' => 3,
        ];

        $categoryId = config('services.glpi.categories.'.$event['event_type']);
        if (is_numeric($categoryId) && (int) $categoryId > 0) {
            $input['itilcategories_id'] = (int) $categoryId;
        }

        $normalizedMap = [];
        foreach ($userMap as $email => $id) {
            if (is_string($email) && is_numeric($id) && (int) $id > 0) {
                $normalizedMap[mb_strtolower(trim($email))] = (int) $id;
            }
        }

        $requesterId = $normalizedMap[mb_strtolower(trim((string) ($event['requester_email'] ?? '')))] ?? null;
        if ($requesterId) {
            $input['_users_id_requester'] = $requesterId;
        }

        $assigneeId = $normalizedMap[mb_strtolower(trim((string) ($event['assignee_email'] ?? '')))] ?? null;
        if ($assigneeId) {
            $input['_users_id_assign'] = $assigneeId;
        } elseif (is_numeric($defaultGroupId) && (int) $defaultGroupId > 0) {
            $input['_groups_id_assign'] = (int) $defaultGroupId;
        }

        $input['assignment_missing'] = !isset($input['_users_id_assign']) && !isset($input['_groups_id_assign']);

        return $input;
    }
}
