<?php

namespace App\Http\Requests\Assets;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Asset $asset */
        $asset = $this->route('asset');
        $custodianId = $this->input('custodian_id')
            ?: $asset?->currentAssignment?->custodian_id
            ?: $asset?->custodian_id;
        $dependencyId = $custodianId
            ? \App\Models\Custodian::whereKey($custodianId)->where('status', 'active')->value('dependency_id')
            : null;

        return [

            /*
            |--------------------------------------------------------------------------
            | ADMINISTRATIVO
            |--------------------------------------------------------------------------
            */

            'room_id' => [
                'required',
                'exists:rooms,id',
                function ($attribute, $value, $fail) use ($dependencyId) {
                    $room = \App\Models\Room::find($value);
                    if ($room && $room->dependency_id !== null && $dependencyId
                        && (int) $room->dependency_id !== (int) $dependencyId) {
                        $fail('La ubicación debe pertenecer a la dependencia del responsable.');
                    }
                },
            ],

            'internal_code' =>
            'nullable|string|max:255|unique:assets,internal_code,' .
                $asset->id,

            'custodian_id' =>
            'nullable|exists:custodians,id',

            'keyboard_serial' =>
            'nullable|string|max:255',

            'mouse_serial' =>
            'nullable|string|max:255',

            'security_guaya' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN GENERAL
            |--------------------------------------------------------------------------
            */

            'serial_number' =>
            'required|string|max:255|unique:assets,serial_number,' .
                $asset->id,

            'hostname' =>
            'nullable|string|max:255',

            'manufacturer' =>
            'nullable|string|max:255',

            'model_version' =>
            'nullable|string|max:255',

            'chassis_type' =>
            'nullable|string|max:255',

            'os_version' =>
            'nullable|string|max:255',

            'domain_name' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | MOTHERBOARD
            |--------------------------------------------------------------------------
            */

            'board_brand' =>
            'nullable|string|max:255',

            'board_model' =>
            'nullable|string|max:255',

            'board_serial' =>
            'nullable|string|max:255',

            'board_version' =>
            'nullable|string|max:255',

            'board_status' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | BIOS
            |--------------------------------------------------------------------------
            */

            'bios_manufacturer' =>
            'nullable|string|max:255',

            'bios_version' =>
            'nullable|string|max:255',

            'bios_serial' =>
            'nullable|string|max:255',

            'bios_release_date' =>
            'nullable|date',


            /*
            |--------------------------------------------------------------------------
            | WINDOWS
            |--------------------------------------------------------------------------
            */

            'windows_version' =>
            'nullable|string|max:255',

            'windows_build' =>
            'nullable|string|max:255',

            'windows_edition' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | SEGURIDAD
            |--------------------------------------------------------------------------
            */

            'license_status' =>
            'nullable|string|max:255',

            'secure_boot' =>
            'nullable|boolean',

            'bitlocker_status' =>
            'nullable|string|max:255',

            'tpm_version' =>
            'nullable|string|max:255',

            'antivirus' =>
            'nullable|string|max:255',

            'firewall_enabled' =>
            'nullable|boolean',

            'uptime_minutes' =>
            'nullable|integer|min:0',


            /*
            |--------------------------------------------------------------------------
            | COMPONENTES
            |--------------------------------------------------------------------------
            */

            'processor' =>
            'nullable|array',

            'processor.brand' =>
            'nullable|string|max:255',

            'processor.model' =>
            'nullable|string|max:255',

            'processor.processor_id' =>
            'nullable|string|max:255',

            'processor.socket' =>
            'nullable|string|max:255',

            'processor.status' =>
            'nullable|string|max:255',

            'processor.cores' =>
            'nullable|integer|min:0',

            'processor.threads' =>
            'nullable|integer|min:0',

            'processor.speed_mhz' =>
            'nullable|integer|min:0',

            'processor.architecture' =>
            'nullable|string|max:255',

            'processor.family' =>
            'nullable|string|max:255',

            'processor.generation' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | RAM
            |--------------------------------------------------------------------------
            */

            'ram_modules' =>
            'nullable|array',

            'ram_modules.*.manufacturer' =>
            'nullable|string|max:255',

            'ram_modules.*.model' =>
            'nullable|string|max:255',

            'ram_modules.*.serial_number' =>
            'nullable|string|max:255',

            'ram_modules.*.bank' =>
            'nullable|string|max:255',

            'ram_modules.*.slot' =>
            'nullable|string|max:255',

            'ram_modules.*.memory_type' =>
            'nullable|string|max:255',

            'ram_modules.*.capacity_gb' =>
            'nullable|numeric|min:0',

            'ram_modules.*.speed_mhz' =>
            'nullable|integer|min:0',


            /*
            |--------------------------------------------------------------------------
            | STORAGE
            |--------------------------------------------------------------------------
            */

            'storage_devices' =>
            'nullable|array',

            'storage_devices.*.brand' =>
            'nullable|string|max:255',

            'storage_devices.*.model' =>
            'nullable|string|max:255',

            'storage_devices.*.serial_number' =>
            'nullable|string|max:255',

            'storage_devices.*.firmware' =>
            'nullable|string|max:255',

            'storage_devices.*.capacity_gb' =>
            'nullable|numeric|min:0',

            'storage_devices.*.free_gb' =>
            'nullable|numeric|min:0',

            'storage_devices.*.type' =>
            'nullable|string|max:255',

            'storage_devices.*.health' =>
            'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | GPU
            |--------------------------------------------------------------------------
            */

            'gpus' =>
            'nullable|array',

            'gpus.*.brand' =>
            'nullable|string|max:255',

            'gpus.*.model' =>
            'nullable|string|max:255',

            'gpus.*.memory_mb' =>
            'nullable|integer|min:0',

            'gpus.*.driver_version' =>
            'nullable|string|max:255',

            'gpus.*.processor' =>
            'nullable|string|max:255',

            'gpus.*.resolution' =>
            'nullable|string|max:255',

            'gpus.*.refresh_rate' =>
            'nullable|integer|min:0',


            /*
            |--------------------------------------------------------------------------
            | RED
            |--------------------------------------------------------------------------
            */

            'network' =>
            'nullable|array',

            'network.gateway' =>
            'nullable|ip',

            'network.dns_server' =>
            'nullable|ip',

            'network.dhcp_enabled' =>
            'nullable|boolean',

            'network.adapters' =>
            'nullable|array',

            'network.adapters.*.name' =>
            'nullable|string|max:255',

            'network.adapters.*.description' =>
            'nullable|string|max:255',

            'network.adapters.*.type' =>
            'nullable|string|max:255',

            'network.adapters.*.mac_address' => [
                'nullable',
                'string',
                'regex:/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/',
            ],

            'network.adapters.*.ipv4' =>
            'nullable|ipv4',

            'network.adapters.*.ipv6' =>
            'nullable|ipv6',

            'network.adapters.*.connected' =>
            'nullable|boolean',

            'network.adapters.*.is_primary' =>
            'nullable|boolean',

            'network.adapters.*.is_active' =>
            'nullable|boolean',


            /*
            |--------------------------------------------------------------------------
            | BATERÍA
            |--------------------------------------------------------------------------
            */

            'battery' =>
            'nullable|array',

            'battery.manufacturer' =>
            'nullable|string|max:255',

            'battery.model' =>
            'nullable|string|max:255',

            'battery.serial_number' =>
            'nullable|string|max:255',

            'battery.health' =>
            'nullable|string|max:255',

            'battery.cycle_count' =>
            'nullable|integer|min:0',

            'battery.design_capacity' =>
            'nullable|integer|min:0',

            'battery.full_charge_capacity' =>
            'nullable|integer|min:0',

            'battery.remaining_capacity' =>
            'nullable|integer|min:0',

            'battery.charge_percent' =>
            'nullable|integer|min:0|max:100',

            'battery.is_charging' =>
            'nullable|boolean',

            'battery.estimated_runtime_minutes' =>
            'nullable|integer|min:0|max:1440',


            /*
            |--------------------------------------------------------------------------
            | SOFTWARE
            |--------------------------------------------------------------------------
            */

            'software' =>
            'nullable|array',

            'software.*.is_active' =>
            'required|boolean',


            /*
            |--------------------------------------------------------------------------
            | MONITORES
            |--------------------------------------------------------------------------
            */

            'monitors' =>
            'nullable|array',

            'monitors.*.id' =>
            'nullable|integer',

            'monitors.*.brand' =>
            'nullable|string|max:255',

            'monitors.*.model' =>
            'nullable|string|max:255',

            'monitors.*.serial_number' =>
            'nullable|string|max:255',

            'monitors.*.manufacturer_code' =>
            'nullable|string|max:255',

            'monitors.*.year' =>
            'nullable|integer|min:0',

            'monitors.*.size' =>
            'nullable|numeric|min:0',

            'monitors.*.resolution' =>
            'nullable|string|max:255',

            'monitors.*.refresh_rate' =>
            'nullable|integer|min:0',

            'monitors.*.fixed_asset_code' =>
            'nullable|string|max:255',

        ];
    }
}
