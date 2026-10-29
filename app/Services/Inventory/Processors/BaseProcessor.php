<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

abstract class BaseProcessor
{
    protected HistoryProcessor $history;

    /**
     * Campos que no deben generar historial.
     */
    protected array $ignoreHistory = [
        'uptime_minutes',
    ];

    public function __construct(HistoryProcessor $history)
    {
        $this->history = $history;
    }

    /**
     * Actualiza la tabla principal assets
     * registrando el historial de cambios.
     */
    protected function updateWithHistory(
        Asset $asset,
        array $values
    ): Asset {

        // Los agentes usan cadenas vacías para representar datos que WMI no
        // pudo leer. No deben borrar un valor válido existente. false y 0 sí
        // son datos legítimos y se conservan.
        $values = array_filter($values, static function ($value): bool {
            return $value !== null
                && (!is_string($value) || trim($value) !== '');
        });

        if ($values === []) {
            return $asset;
        }

        foreach ($values as $field => $newValue) {

            if (in_array($field, $this->ignoreHistory, true)) {
                continue;
            }

            $oldValue = $asset->{$field};

            if ($oldValue != $newValue) {
                $this->history->logChange(
                    $asset,
                    $field,
                    $oldValue,
                    $newValue
                );
            }
        }

        $asset->update($values);

        return $asset->fresh();
    }

    /**
     * Actualiza una relación HasOne (battery, bios, monitor, etc.).
     */
    protected function updateRelation(
        Asset $asset,
        string $relation,
        array $values
    ): Model {

        $values = array_filter($values, static function ($value): bool {
            return $value !== null
                && (!is_string($value) || trim($value) !== '');
        });

        // Si no hay datos, no hace nada
        if (empty($values)) {
            return $asset->$relation()->firstOrNew();
        }

        return $asset->$relation()->updateOrCreate(
            [
                'asset_id' => $asset->id
            ],
            $values
        );
    }
}
