<?php

namespace App\Services\Inventory\Processors;

use App\Models\Asset;

class UserProcessor extends BaseProcessor
{
    public function process(Asset $asset, array $user): Asset
    {
        $values = [];

        $username = trim((string) ($user['username'] ?? ''));
        if ($username !== '') {
            $values['logged_user'] = $username;
        }

        if (!empty($user['lastLogin'])) {
            $values['last_login'] = date('Y-m-d H:i:s', strtotime($user['lastLogin']));
        }

        if ($values === []) {
            return $asset;
        }

        $values = array_filter(
            $values,
            static fn ($value) => $value !== null
        );

        return $this->updateWithHistory($asset, $values);
    }
}
