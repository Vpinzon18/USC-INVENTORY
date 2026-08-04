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

        // Elimina valores nulos
        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

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