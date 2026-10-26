<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetRemoteSupportController extends Controller
{
    public function updateRustDeskId(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'rustdesk_id' => [
                'nullable',
                'string',
                'max:64',
                'regex:/^[A-Za-z0-9-]+$/',
                Rule::unique('assets', 'rustdesk_id')->ignore($asset->getKey()),
            ],
        ]);

        $asset->forceFill(['rustdesk_id' => $validated['rustdesk_id'] ?? null])->save();

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Se actualizó el ID de RustDesk.');
    }


}
