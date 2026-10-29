<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>R-GT004 - {{ $asset->internal_code ?? $asset->serial_number }}</title>
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        html, body { margin: 0; padding: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; font-weight: 700; color: #000; }
        .page { width: 210mm; height: 297mm; padding: 18.5mm 17.5mm 7mm; overflow: hidden; position: relative; page-break-after: always; break-after: page; background: #fff; }
        .page + .page { padding-top: 37mm; }
        .page:last-child { page-break-after: auto; break-after: auto; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin: 0; }
        td, th { border: 1px solid #000; padding: 1px 3px; text-align: center; vertical-align: middle; line-height: 1.12; overflow: hidden; overflow-wrap: anywhere; }
        .institution-table { height: 72px; margin-bottom: 3px; border: 2px solid #000; }
        .institution-table td { border-width: 1px; }
        .institution-table tr:nth-child(1) { height: 20px; }
        .institution-table tr:nth-child(2) { height: 18px; }
        .institution-table tr:nth-child(3), .institution-table tr:nth-child(4) { height: 17px; }
        .logo-cell { padding: 0; overflow: hidden; }
        .logo-cell img { display: block; width: 72px; height: 72px; object-fit: contain; margin: 0 auto; }
        .institution-main { font-weight: bold; }
        .institution-code { font-size: 8px; font-weight: bold; }
        .institution-name { font-size: 10px; }
        .institution-subtitle, .institution-support, .institution-format { font-size: 8.5px; }
        .institution-format { font-weight: bold; }
        .band { background: #b8b6b6; font-size: 8.5px; font-weight: bold; text-transform: uppercase; border-top-width: 2px; border-bottom-width: 2px; }
        .subhead { background: #e8e8e8; font-size: 7.5px; font-weight: bold; text-transform: uppercase; }
        .field { font-size: 8.5px; height: 22px; }
        .field-small { font-size: 7.5px; height: 18px; }
        .value { font-size: 8.5px; height: 23px; }
        .blue { color: #0000d8; font-weight: bold; }
        .section { margin-top: 3px; }
        .office-table { margin-top: 0; }
        .responsible-fields td { border: 0; }
        .responsible-fields td:not(:last-child) { border-right: 1px solid #000; }
        .responsible-contact { border-left: 1px solid #000; border-right: 1px solid #000; }
        .responsible-contact td { border-left: 0; border-right: 1px solid #000; }
        .responsible-contact td:last-child { border-right: 0; }
        .responsible-contact tr:first-child td { border-bottom: 1px solid #000; }
        .date-box { width: 15%; }
        .seat-box { width: 85%; }
        .date-cell { width: 33.333%; font-size: 8px; }
        .check { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin: 0 2px -2px; font-size: 9px; line-height: 10px; font-weight: bold; }
        .hardware td { height: auto; min-height: 18px; padding: 2px 3px; font-size: 8px; line-height: 1.1; }
        .hardware .label { width: 25%; text-align: left; font-weight: bold; }
        .hardware .description { width: 58%; white-space: pre-line; }
        .hardware .brand { width: 17%; }
        .software .software-name-col { width: 8.333%; }
        .software .software-date-col { width: 5%; }
        .software .software-mark-col { width: 5%; }
        .software .software-head td { height: 18px; font-size: 7px; }
        .software .software-subhead td { height: 13px; font-size: 7px; }
        .software td { height: 16px; font-size: 7px; padding: 1px 2px; }
        .software-name { text-align: left; padding-left: 4px !important; }
        .software-mark { font-weight: bold; }
        .placeholder { color: #a8a8a8; font-weight: bold; }
        .additional td { height: 20px; font-size: 8px; }
        .additional .subhead { height: 17px; }
        .network-options td { height: 18px; font-size: 7px; }
        .technician td { height: 24px; font-size: 8px; }
        .signature { height: 34px !important; }
        .footer { position: absolute; bottom: 4.5mm; right: 17.5mm; min-width: 88px; border: 1px solid #000; padding: 3px 7px; background: #fff; font-size: 8px; text-align: right; }
        .history-title td { height: 25px; font-size: 10px; }
        .history-id td { height: 25px; font-size: 9px; }
        .history-head td { font-size: 8px; font-weight: bold; }
        .history-rows td { height: 28px; font-size: 8px; }
        .history-description { text-align: left; padding: 2px 5px; }
        .preview-toolbar { display: none; }

        @media screen {
            body.preview-mode { background: #525659; padding-top: 64px; }
            body.preview-mode .page { margin: 20px auto; box-shadow: 0 4px 20px rgba(0, 0, 0, .36); }
            body.preview-mode .preview-toolbar { position: fixed; z-index: 20; inset: 0 0 auto; min-height: 56px; padding: 10px 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; background: #1e293b; color: #fff; font-size: 13px; }
            .preview-toolbar strong { display: block; font-size: 13px; }
            .preview-toolbar small { color: #cbd5e1; }
            .preview-actions { display: flex; gap: 8px; }
            .preview-actions a { padding: 9px 13px; border-radius: 7px; color: #fff; text-decoration: none; font-weight: bold; }
            .preview-back { background: #475569; }
            .preview-download { background: #2563eb; }
        }

        @media print {
            body.preview-mode { background: #fff; padding: 0; }
            body.preview-mode .preview-toolbar { display: none !important; }
            .page { margin: 0; box-shadow: none; }
        }
    </style>
</head>
<body class="{{ ($previewMode ?? false) ? 'preview-mode' : '' }}">
@php
    $logoPath = public_path('img/logoUSC.png');
    $logo = is_file($logoPath)
        ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath))
        : null;
    $createdAt = $asset->created_at;
@endphp

@if($previewMode ?? false)
    <div class="preview-toolbar">
        <div>
            <strong>Previsualización R-GT004</strong>
            <small>{{ $asset->internal_code ?? $asset->serial_number ?? 'Activo sin código' }}</small>
        </div>
        <div class="preview-actions">
            <a class="preview-back" href="{{ route('assets.index') }}">← Volver</a>
            <a class="preview-download" href="{{ route('assets.download.pdf', ['id' => $asset->id]) }}">⇩ Descargar PDF</a>
        </div>
    </div>
@endif

<div class="page">
    <table class="institution-table">
        <colgroup><col style="width:15%"><col style="width:65%"><col style="width:20%"></colgroup>
        <tr>
            <td rowspan="4" class="logo-cell">@if($logo)<img src="{{ $logo }}" alt="Logo de la Universidad Santiago de Cali">@endif</td>
            <td class="institution-main institution-name">UNIVERSIDAD SANTIAGO DE CALI</td>
            <td class="institution-code">R-GT004</td>
        </tr>
        <tr><td class="institution-main institution-subtitle">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td><td class="institution-code">VERSIÓN. 3</td></tr>
        <tr><td class="institution-main institution-support">SOPORTE TECNICO</td><td rowspan="2" class="institution-code">FECHA<br>11 SEP 2019</td></tr>
        <tr><td class="institution-main institution-format">FORMATO DE HOJA DE VIDA DE EQUIPOS DE CÓMPUTO</td></tr>
    </table>

    <table class="office-table">
        <colgroup><col style="width:15%"><col style="width:85%"></colgroup>
        <tr>
            <td rowspan="4" class="date-box" style="padding:0">
                <table style="height:100%;border:0">
                    <tr><td class="subhead" style="height:51px">FECHA<br>CREACIÓN DE<br>HOJA DE VIDA</td></tr>
                    <tr><td class="subhead" style="height:16px">D&nbsp;&nbsp;&nbsp;&nbsp; M&nbsp;&nbsp;&nbsp;&nbsp; AA</td></tr>
                    <tr><td class="value" style="height:23px">{{ $createdAt?->format('d') }}&nbsp;&nbsp;&nbsp;{{ $createdAt?->format('m') }}&nbsp;&nbsp;&nbsp;{{ $createdAt?->format('y') }}</td></tr>
                </table>
            </td>
            <td class="seat-box subhead" style="padding:0">
                <table class="site-table">
                    <colgroup><col style="width:10.5%"><col style="width:21%"><col style="width:5.5%"><col style="width:19.5%"><col style="width:6.5%"><col style="width:14%"><col style="width:6.5%"><col style="width:9%"><col style="width:7.5%"></colgroup>
                    <tr>
                        <td>SEDE</td><td>PAMPALINDA</td><td><span class="check">{{ $sheet['isPampalinda'] ? 'X' : '' }}</span></td>
                        <td>PALMIRA</td><td><span class="check">{{ $sheet['isPalmira'] ? 'X' : '' }}</span></td>
                        <td>CENTRO</td><td><span class="check">{{ $sheet['isCentro'] ? 'X' : '' }}</span></td>
                        <td>OTRO{{ $sheet['siteOtherLabel'] !== '' ? ': '.strtoupper($sheet['siteOtherLabel']) : '' }}</td>
                        <td><span class="check">{{ $sheet['isOtherSite'] ? 'X' : '' }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td class="band">DATOS DEL RESPONSABLE DEL EQUIPO</td></tr>
        <tr><td style="padding:0"><table class="responsible-fields" style="border:0"><tr class="subhead"><td style="width:46%">NOMBRE COMPLETO</td><td style="width:33%">N° DOCUMENTO</td><td style="width:21%">DEPENDENCIA</td></tr></table></td></tr>
        <tr><td style="padding:0"><table class="responsible-fields" style="border:0"><tr class="value"><td style="width:46%">{{ $sheet['custodian']?->full_name ?? 'N/A' }}</td><td style="width:33%">{{ $sheet['custodianDocument'] }}</td><td style="width:21%">{{ $sheet['custodian']?->dependency?->name ?? 'N/A' }}</td></tr></table></td></tr>
    </table>

    <table class="contact-table responsible-contact">
        <colgroup><col style="width:24%"><col style="width:25%"><col style="width:9.3%"><col style="width:9%"><col style="width:7%"><col style="width:16.2%"></colgroup>
        <tr class="subhead"><td>CARGO</td><td>CORREO USC</td><td>EXT</td><td>BLOQUE</td><td>PISO</td><td>UBICACIÓN</td></tr>
        <tr class="value">
            <td>{{ $sheet['custodian']?->jobTitle?->name ?? 'N/A' }}</td>
            <td style="text-transform:lowercase">{{ $sheet['custodian']?->email ?? 'N/A' }}</td>
            <td>{{ $sheet['custodian']?->extension ?? 'N/A' }}</td>
            <td>{{ $asset->room?->building?->name ?? 'N/A' }}</td>
            <td>{{ $asset->room?->floor ?? 'N/A' }}</td>
            <td class="blue">{{ $asset->room?->nomenclatura ?? $asset->room?->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="section">
        <tr class="band"><td colspan="7">DATOS DEL EQUIPO Y PERIFÉRICOS</td></tr>
        <tr class="subhead"><td>REFERENCIA,<br>MARCA O MODELO<br>DE TORRE</td><td>ACTIVO TORRE</td><td>SERIAL<br>TORRE</td><td>ACTIVO<br>MONITOR</td><td>SERIAL<br>MONITOR</td><td>SERIAL<br>TECLADO</td><td>SERIAL<br>MOUSE</td></tr>
        <tr class="value">
            <td>{{ $sheet['referenceModel'] }}</td>
            <td class="blue">{{ $asset->internal_code ?? 'N/A' }}</td>
            <td class="blue">{{ $asset->serial_number ?? 'N/A' }}</td>
            <td>{{ $sheet['monitorAssetCodes'] }}</td>
            <td>{{ $sheet['monitorSerials'] }}</td>
            <td>{{ $asset->keyboard_serial ?? 'N/A' }}</td>
            <td>{{ $asset->mouse_serial ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="section hardware">
        <colgroup><col class="label"><col class="description"><col class="brand"></colgroup>
        <tr class="band"><td colspan="3">CARACTERÍSTICAS DEL EQUIPO DE CÓMPUTO</td></tr>
        <tr class="subhead"><td>HARDWARE</td><td>DESCRIPCIÓN</td><td>MARCA</td></tr>
        @foreach($sheet['hardwareRows'] as $row)
            <tr><td class="label">{{ $row['label'] }}</td><td class="description">{{ $row['description'] }}</td><td class="brand">{{ $row['brand'] }}</td></tr>
        @endforeach
    </table>

    <table class="section software">
        <colgroup>
            @for($i = 0; $i < 3; $i++)<col class="software-name-col">@endfor
            @for($i = 0; $i < 3; $i++)<col class="software-date-col">@endfor
            @for($i = 0; $i < 2; $i++)<col class="software-mark-col">@endfor
            @for($i = 0; $i < 3; $i++)<col class="software-name-col">@endfor
            @for($i = 0; $i < 3; $i++)<col class="software-date-col">@endfor
            @for($i = 0; $i < 2; $i++)<col class="software-mark-col">@endfor
        </colgroup>
        <tr class="band"><td colspan="16">SOFTWARE DEL EQUIPO</td></tr>
        <tr class="subhead software-head">
            <td rowspan="2" colspan="3" class="software-name-col">SOFTWARE</td><td colspan="3">FECHA DE INSTALACIÓN</td><td colspan="2">AUTORIZADO</td>
            <td rowspan="2" colspan="3" class="software-name-col">SOFTWARE</td><td colspan="3">FECHA DE INSTALACIÓN</td><td colspan="2">AUTORIZADO</td>
        </tr>
        <tr class="subhead software-subhead"><td>D</td><td>M</td><td>AA</td><td>SI</td><td>NO</td><td>D</td><td>M</td><td>AA</td><td>SI</td><td>NO</td></tr>
        @foreach($sheet['softwareRows'] as [$left, $right])
            @php
                $leftDate = null;
                $rightDate = null;
                try { $leftDate = $left?->install_date ? \Illuminate\Support\Carbon::parse($left->install_date) : null; } catch (\Throwable) {}
                try { $rightDate = $right?->install_date ? \Illuminate\Support\Carbon::parse($right->install_date) : null; } catch (\Throwable) {}
            @endphp
            <tr>
                <td colspan="3" class="software-name">{{ $left?->name ?? '' }}{{ $left?->version ? ' ('.$left->version.')' : '' }}</td>
                <td>{{ $leftDate?->format('d') ?? 'D' }}</td><td>{{ $leftDate?->format('m') ?? 'M' }}</td><td>{{ $leftDate?->format('y') ?? 'AA' }}</td>
                <td class="software-mark">{{ $left && $left->is_active ? 'X' : '' }}</td><td class="software-mark">{{ $left && !$left->is_active ? 'X' : '' }}</td>
                <td colspan="3" class="software-name">{{ $right?->name ?? '' }}{{ $right?->version ? ' ('.$right->version.')' : '' }}</td>
                <td>{{ $rightDate?->format('d') ?? 'D' }}</td><td>{{ $rightDate?->format('m') ?? 'M' }}</td><td>{{ $rightDate?->format('y') ?? 'AA' }}</td>
                <td class="software-mark">{{ $right && $right->is_active ? 'X' : '' }}</td><td class="software-mark">{{ $right && !$right->is_active ? 'X' : '' }}</td>
            </tr>
        @endforeach
    </table>

    <table class="section additional">
        <tr class="band"><td colspan="12">INFORMACIÓN ADICIONAL DEL EQUIPO</td></tr>
        <tr class="subhead"><td colspan="3">NOMBRE DEL EQUIPO</td><td colspan="3">IP</td><td colspan="3">DIRECCIÓN MAC</td><td colspan="3">EQUIPO DENTRO DE DOMINIO</td></tr>
        <tr class="value"><td colspan="3">{{ $asset->hostname ?? 'N/A' }}</td><td colspan="3">{{ $sheet['ipAddress'] }}</td><td colspan="3">{{ $sheet['macAddress'] }}</td><td colspan="3">{{ $asset->domain_name ?: 'NO REGISTRADO' }}</td></tr>
        <tr class="subhead network-options">
            <td colspan="3"></td>
            <td colspan="1">DHCP <span class="check">{{ $sheet['networkConfigured'] && $sheet['dhcpEnabled'] ? 'X' : '' }}</span></td>
            <td colspan="2">FIJA <span class="check">{{ $sheet['networkConfigured'] && !$sheet['dhcpEnabled'] ? 'X' : '' }}</span></td>
            <td colspan="3"></td>
            <td>ADMIN <span class="check">{{ $sheet['domainIsAdmin'] ? 'X' : '' }}</span></td>
            <td>ACAD <span class="check">{{ $sheet['domainIsAcademic'] ? 'X' : '' }}</span></td>
            <td>NO <span class="check">{{ $sheet['domainIsNo'] ? 'X' : '' }}</span></td>
        </tr>
    </table>

    <table class="section technician">
        <tr class="band"><td colspan="3">INFORMACIÓN DEL TÉCNICO RESPONSABLE</td></tr>
        <tr class="subhead"><td>NOMBRE COMPLETO</td><td>FIRMA</td><td>OBSERVACIÓN</td></tr>
        <tr><td>{{ $sheet['technician'] }}</td><td class="signature"></td><td>{{ $sheet['observations'] }}</td></tr>
    </table>
    <div class="footer">PAG. 1 DE 2</div>
</div>

<div class="page">
    <table class="institution-table">
        <colgroup><col style="width:15%"><col style="width:65%"><col style="width:20%"></colgroup>
        <tr>
            <td rowspan="4" class="logo-cell">@if($logo)<img src="{{ $logo }}" alt="Logo de la Universidad Santiago de Cali">@endif</td>
            <td class="institution-main institution-name">UNIVERSIDAD SANTIAGO DE CALI</td>
            <td class="institution-code">R-GT004</td>
        </tr>
        <tr><td class="institution-main institution-subtitle">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td><td class="institution-code">VERSIÓN. 3</td></tr>
        <tr><td class="institution-main institution-support">SOPORTE TECNICO</td><td rowspan="2" class="institution-code">FECHA<br>11 SEP 2019</td></tr>
        <tr><td class="institution-main institution-format">FORMATO DE HOJA DE VIDA DE EQUIPOS DE CÓMPUTO</td></tr>
    </table>
    <table class="history-title"><tr class="band"><td>HISTORIAL DE EQUIPO TECNOLÓGICO</td></tr></table>
    <table class="history-id"><tr class="subhead"><td>ACTIVO DEL EQUIPO</td><td>SERIAL DEL EQUIPO</td></tr><tr><td class="blue">{{ $asset->internal_code ?? 'N/A' }}</td><td>{{ $asset->serial_number ?? 'N/A' }}</td></tr></table>
    <table class="section history-table">
        <colgroup><col style="width:4.6%"><col style="width:4.6%"><col style="width:4.8%"><col style="width:64%"><col style="width:22%"></colgroup>
        <thead class="history-head">
            <tr class="band"><td colspan="5">REGISTRO DE DIAGNÓSTICOS, CAMBIOS, MODIFICACIONES O INGRESOS A TALLER</td></tr>
            <tr class="subhead"><td colspan="3">FECHA</td><td rowspan="2">DESCRIPCIÓN</td><td rowspan="2">NOMBRE DEL TÉCNICO</td></tr>
            <tr class="subhead"><td>DD</td><td>MM</td><td>AA</td></tr>
        </thead>
        <tbody class="history-rows">
            @for($i = 0; $i < 24; $i++)
                @php
                    $entry = $sheet['history']->get($i);
                    $historyDate = null;
                    try { $historyDate = $entry?->performed_at ? \Illuminate\Support\Carbon::parse($entry->performed_at) : null; } catch (\Throwable) {}
                @endphp
                <tr>
                    <td>{{ $historyDate?->format('d') ?? 'D' }}</td><td>{{ $historyDate?->format('m') ?? 'M' }}</td><td>{{ $historyDate?->format('y') ?? 'AA' }}</td>
                    <td class="history-description">{{ $entry?->description ?? '' }}</td>
                    <td>{{ $entry?->technician?->name ?? '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>
    <div class="footer">PAG. 2 DE 2</div>
</div>
</body>
</html>
