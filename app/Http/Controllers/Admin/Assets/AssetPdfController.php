<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\Assets\AssetPdfDataBuilder;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Browsershot\Browsershot;

class AssetPdfController extends Controller
{
    public function previewPdf(Asset $asset)
    {
        $asset = $this->assetQuery()->findOrFail($asset->getKey());

        return view('admin.assets.pdf_preview', $this->viewData($asset, true));
    }

    public function downloadPdf(int $id)
    {
        $asset = $this->assetQuery()->findOrFail($id);
        $html = view('admin.assets.pdf_export', $this->viewData($asset, false))->render();

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

        $identifier = $asset->internal_code ?: $asset->serial_number ?: (string) $asset->id;
        $identifier = preg_replace('/[^A-Za-z0-9_-]+/', '_', $identifier);
        $fileName = 'Hoja_Vida_USC_'.$identifier.'.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    }

    /**
     * Load each normalized collection in a bounded number of queries. Separate
     * eager loads avoid multiplying rows across several one-to-many relations.
     */
    private function assetQuery(): Builder
    {
        return Asset::query()->with([
            'currentCustodian.dependency',
            'currentCustodian.jobTitle',
            'custodian.dependency',
            'custodian.jobTitle',
            'room.building.campus',
            'technicalServices' => static fn ($query) => $query
                ->with('technician')
                ->reorder()
                ->orderByDesc('performed_at')
                ->orderByDesc('id')
                ->limit(24),
            'processors',
            'ramModules',
            'storageDevices',
            'gpus',
            'network.adapters',
            'monitors',
            'software',
        ]);
    }

    private function viewData(Asset $asset, bool $previewMode): array
    {
        return [
            'asset' => $asset,
            'sheet' => app(AssetPdfDataBuilder::class)->build($asset),
            'previewMode' => $previewMode,
        ];
    }
}
