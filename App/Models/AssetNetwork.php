<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetNetwork extends Model
{
    protected $fillable = [
        'asset_id',
        'gateway',
        'dns_server',
        'dhcp_enabled',
        'connected',
    ];

    protected $casts = [
        'dhcp_enabled' => 'boolean',
        'connected' => 'boolean',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(
            Asset::class,
            'asset_id'
        );
    }

    public function adapters(): HasMany
    {
        return $this->hasMany(
            AssetNetworkAdapter::class,
            'asset_network_id'
        );
    }
}