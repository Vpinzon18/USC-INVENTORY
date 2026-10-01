<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetNetworkAdapter extends Model
{
    protected $fillable = [
        'asset_network_id',
        'name',
        'description',
        'mac_address',
        'type',
        'ipv4',
        'ipv6',
        'connected',
        'is_primary',
        'is_active',
        'last_seen_at',
    ];

    protected $casts = [
        'connected' => 'boolean',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function network(): BelongsTo
    {
        return $this->belongsTo(
            AssetNetwork::class,
            'asset_network_id'
        ); 
    }
}