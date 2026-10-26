<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Spatie\Browsershot\Browsershot;

class AssetPdfController extends Controller
{
    /**
     * =========================================================
     * PREVISUALIZACIÓN PDF
     * =========================================================
     */
    public function previewPdf(Asset $asset)
    {
        $asset->load([
            'currentCustodian.dependency',
            'currentCustodian.jobTitle',
            'room.building',
            'technicalServices.user',
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'network.adapters',
            'monitors',
            'battery',
            'software',
        ]);

        return view(
            'admin.assets.pdf_preview',
            compact('asset')
        );
    }


    public function downloadPdf(int $id)
    {
        $asset = Asset::with([
            'currentCustodian.dependency',
            'currentCustodian.jobTitle',
            'room.building',
            'technicalServices.user',
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'network.adapters',
            'monitors',
            'battery',
            'software',
        ])->findOrFail($id);

        $html = view(
            'admin.assets.pdf_export',
            compact('asset')
        )->render();

        $pdf = Browsershot::html($html)
            ->setNodeBinary('/usr/local/bin/node')
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'disable-gpu',
                'no-zygote',
            ])
            ->showBackground()
            ->timeout(120)
            ->setOption('waitUntil', 'domcontentloaded')
            ->format('A4')
            ->setMargins(0, 0, 0, 0)
            ->pdf();

        $fileName = 'Hoja_Vida_USC_' .
            ($asset->internal_code ?? $asset->serial_number ?? $asset->id) .
            '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'attachment; filename="' . $fileName . '"'
            );
    }

}
