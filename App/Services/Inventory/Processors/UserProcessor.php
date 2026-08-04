<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class UserProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $user): asset
    {
        $values = [

            'logged_user' => $user['username'] ?? null,

            'last_login' => isset($user['lastLogin'])
                ? date('Y-m-d H:i:s', strtotime($user['lastLogin']))
                : null,

        ];
        $values = array_filter(
    $values, static fn ($value) => $value !== null);

$this->updateWithHistory($asset, $values);

        // Refresca el modelo desde la base de datos
        $asset->refresh();

        return $asset;
    }
}
