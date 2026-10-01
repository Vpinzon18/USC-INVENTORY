<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>R-GT004 - {{ $asset->internal_code ?? $asset->serial_number }}</title>
    <style>
        /* El formato institucional de referencia es A4, sin escalado. */
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        html, body { margin: 0; padding: 0; background: #fff; font-family: Arial, Helvetica, sans-serif; color: #000; }
        .page { width: 210mm; height: 297mm; padding: 18.5mm 17.5mm 7mm; overflow: hidden; position: relative; page-break-after: always; background: #fff; }
        .page:last-child { page-break-after: auto; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin: 0; }
        td, th { border: 1px solid #000; padding: 1px 3px; text-align: center; vertical-align: middle; line-height: 1.12; overflow: hidden; word-wrap: break-word; }
        .institution-table { height: 72px; margin-bottom: 3px; border: 2px solid #000; }
        .institution-table td { border-width: 1px; }
        .institution-table tr:nth-child(1) { height: 20px; }.institution-table tr:nth-child(2) { height: 18px; }.institution-table tr:nth-child(3), .institution-table tr:nth-child(4) { height: 17px; }
        .logo-cell { padding: 0; overflow: hidden; }.logo-cell img { display: block; width: 45px; height: 45px; object-fit: contain; margin: 0 auto; }
        .institution-main { font-weight: bold; }.institution-code { font-size: 8px; font-weight: bold; }
        .institution-name { font-size: 10px; }.institution-subtitle, .institution-support, .institution-format { font-size: 8.5px; }.institution-format { font-weight: bold; }
        .band { background: #b8b6b6; font-size: 8.5px; font-weight: bold; text-transform: uppercase; border-top-width: 2px; border-bottom-width: 2px; }.subhead { background: #e8e8e8; font-size: 7.5px; font-weight: bold; text-transform: uppercase; }
        .field { font-size: 8.5px; height: 22px; }.field-small { font-size: 7.5px; height: 18px; }.value { font-size: 8.5px; height: 23px; }.blue { color: #0000d8; font-weight: bold; }
        .section { margin-top: 3px; }.office-table { margin-top: 0; }.date-box { width: 15%; }.seat-box { width: 85%; }
        .date-cell { width: 33.333%; font-size: 8px; }.check { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin: 0 3px -2px 4px; font-size: 11px; line-height: 10px; }
        .hardware td { height: 18px; font-size: 8px; }.hardware .label { width: 25%; text-align: left; font-weight: bold; }.hardware .description { width: 58%; }.hardware .brand { width: 17%; }
        .software th { height: 31px; }.software td { height: 16px; font-size: 7px; }.software-name { text-align: left; padding-left: 4px; }.software-date { width: 5%; }.software-mark { width: 5%; font-weight: bold; }.software-name-col { width: 35%; }
        .additional td { height: 20px; font-size: 8px; }.additional .subhead { height: 17px; }.technician td { height: 24px; font-size: 8px; }.signature { height: 34px !important; }
        .footer { position: absolute; bottom: 4.5mm; right: 17.5mm; min-width: 88px; border: 1px solid #000; padding: 3px 7px; background: #fff; font-size: 8px; text-align: right; }
        .history-title td { height: 25px; font-size: 10px; }.history-id td { height: 25px; font-size: 9px; }.history-head td { font-size: 8px; font-weight: bold; }.history-rows td { height: 25px; font-size: 8px; }.history-description { text-align: left; padding: 2px 5px; }
        @media print { .page { margin: 0; } }
    </style>
</head>
<body>
@php
    $logoPath = public_path('img/logoUSC.png');
    $logo = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $custodian = $asset->currentCustodian;
    $processor = $asset->processors->first();
    $ram = $asset->ramModules->first();
    $storage = $asset->storageDevices->first();
    $gpu = $asset->gpus->first();
    $adapters = $asset->network?->adapters ?? collect();
    $adapter = $adapters->firstWhere('is_primary', true) ?? $adapters->first();
    $monitor = $asset->monitors->first();
    $software = ($asset->software ?? collect())->take(28)->values();
    $softwareRows = collect(range(0, 13))->map(function ($index) use ($software) { return [$software->get($index), $software->get($index + 14)]; });
    $history = ($asset->technicalServices ?? collect())->take(24)->values();
    $createdAt = $asset->created_at;
    $technician = $history->first()?->user?->name ?? 'N/A';
@endphp

<div class="page">
    <table class="institution-table">
        <colgroup><col style="width:15%"><col style="width:65%"><col style="width:20%"></colgroup>
        <tr>
            <td rowspan="4" class="logo-cell">@if($logo)<img src="{{ $logo }}" alt="USC">@endif</td>
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
            <td class="seat-box subhead">SEDE&nbsp;&nbsp; PAMPALINDA <span class="check">X</span>&nbsp; PALMIRA CENTRO <span class="check"></span>&nbsp; OTRO <span class="check"></span></td>
        </tr>
        <tr><td class="band">DATOS DEL RESPONSABLE DEL EQUIPO</td></tr>
        <tr><td style="padding:0"><table style="border:0"><tr class="subhead"><td style="width:46%">NOMBRE COMPLETO</td><td style="width:30%">N° DOCUMENTO</td><td style="width:24%">DEPENDENCIA</td></tr></table></td></tr>
        <tr><td style="padding:0"><table style="border:0"><tr class="value"><td style="width:46%">{{ $custodian?->full_name ?? 'N/A' }}</td><td style="width:30%">{{ $custodian?->document_number ?? 'N/A' }}</td><td style="width:24%">{{ $custodian?->dependency?->name ?? 'N/A' }}</td></tr></table></td></tr>
    </table>
    <table>
        <tr class="subhead"><td>CARGO</td><td>CORREO USC</td><td>EXT</td><td>BLOQUE</td><td>PISO</td><td>UBICACIÓN</td></tr>
        <tr class="value"><td>{{ $custodian?->jobTitle?->name ?? 'N/A' }}</td><td style="text-transform:lowercase">{{ $custodian?->email ?? 'N/A' }}</td><td>{{ $custodian?->extension ?? 'N/A' }}</td><td>{{ $asset->room?->building?->name ?? 'N/A' }}</td><td>{{ $asset->room?->floor ?? 'N/A' }}</td><td class="blue">{{ $asset->room?->nomenclatura ?? 'N/A' }}</td></tr>
    </table>

    <table class="section">
        <tr class="band"><td colspan="7">DATOS DEL EQUIPO Y PERIFÉRICOS</td></tr>
        <tr class="subhead"><td>REFERENCIA<br>TORRE</td><td>ACTIVO<br>TORRE</td><td>SERIAL<br>TORRE</td><td>ACTIVO<br>MONITOR</td><td>SERIAL<br>MONITOR</td><td>SERIAL<br>TECLADO</td><td>SERIAL<br>MOUSE</td></tr>
        <tr class="value"><td>{{ $asset->manufacturer ?? $asset->hostname ?? 'N/A' }}</td><td class="blue">{{ $asset->internal_code ?? 'N/A' }}</td><td class="blue">{{ $asset->serial_number ?? 'N/A' }}</td><td>{{ $monitor?->fixed_asset_code ?? 'N/A' }}</td><td>{{ $monitor?->serial_number ?? 'N/A' }}</td><td>{{ $asset->keyboard_serial ?? 'N/A' }}</td><td>{{ $asset->mouse_serial ?? 'N/A' }}</td></tr>
    </table>

    <table class="section hardware">
        <tr class="band"><td colspan="3">CARACTERÍSTICAS DEL EQUIPO DE COMPUTO</td></tr>
        <tr class="subhead"><td class="label">HARDWARE</td><td class="description">DESCRIPCIÓN</td><td class="brand">MARCA</td></tr>
        <tr><td class="label">BOARD</td><td>{{ $asset->board_model ?? 'N/A' }}</td><td>{{ $asset->board_brand ?? 'N/A' }}</td></tr>
        <tr><td class="label">PROCESADOR</td><td>{{ $processor?->model ?? 'N/A' }}</td><td>{{ $processor?->brand ?? 'N/A' }}</td></tr>
        <tr><td class="label">RAM</td><td>{{ $ram?->capacity_gb ? $ram->capacity_gb . ' GB' : 'N/A' }}</td><td>{{ $ram?->manufacturer ?? 'N/A' }}</td></tr>
        <tr><td class="label">DISCO DURO</td><td>{{ $storage?->model ?? 'N/A' }}{{ $storage?->capacity_gb ? ' ' . $storage->capacity_gb . ' GB' : '' }}</td><td>{{ $storage?->brand ?? 'N/A' }}</td></tr>
        <tr><td class="label">TARJETA INALÁMBRICA</td><td>{{ $adapter?->name ?? 'N/A' }}</td><td>{{ $adapter?->type ?? 'N/A' }}</td></tr>
        <tr><td class="label">TARJETA GRÁFICA</td><td>{{ $gpu?->model ?? 'N/A' }}</td><td>{{ $gpu?->brand ?? 'N/A' }}</td></tr>
        <tr><td class="label">GUAYA DE SEGURIDAD</td><td>{{ $asset->security_guaya ?? 'N/A' }}</td><td>N/A</td></tr>
        <tr><td class="label">OTROS</td><td>{{ $asset->os_version ?? 'N/A' }}</td><td>MICROSOFT</td></tr>
    </table>

    <table class="section software">
        <tr class="band"><td colspan="16">SOFTWARE DEL EQUIPO</td></tr>
        <tr class="subhead"><td rowspan="2" colspan="3" class="software-name-col">SOFTWARE</td><td colspan="3">FECHA DE INSTALACIÓN</td><td colspan="2">AUTORIZADO</td><td rowspan="2" colspan="3" class="software-name-col">SOFTWARE</td><td colspan="3">FECHA DE INSTALACIÓN</td><td colspan="2">AUTORIZADO</td></tr>
        <tr class="subhead"><td>D</td><td>M</td><td>AA</td><td>SI</td><td>NO</td><td>D</td><td>M</td><td>AA</td><td>SI</td><td>NO</td></tr>
        @foreach($softwareRows as [$left, $right])
            @php $leftDate = $left?->install_date ? \Carbon\Carbon::parse($left->install_date) : null; $rightDate = $right?->install_date ? \Carbon\Carbon::parse($right->install_date) : null; @endphp
            <tr><td colspan="3" class="software-name">{{ $left?->name }}{{ $left?->version ? ' (' . $left->version . ')' : '' }}</td><td>{{ $leftDate?->format('d') }}</td><td>{{ $leftDate?->format('m') }}</td><td>{{ $leftDate?->format('y') }}</td><td>{{ $left && $left->is_active ? '[X]' : '' }}</td><td>{{ $left && !$left->is_active ? '[X]' : '' }}</td><td colspan="3" class="software-name">{{ $right?->name }}{{ $right?->version ? ' (' . $right->version . ')' : '' }}</td><td>{{ $rightDate?->format('d') }}</td><td>{{ $rightDate?->format('m') }}</td><td>{{ $rightDate?->format('y') }}</td><td>{{ $right && $right->is_active ? '[X]' : '' }}</td><td>{{ $right && !$right->is_active ? '[X]' : '' }}</td></tr>
        @endforeach
    </table>

    <table class="section additional">
        <tr class="band"><td colspan="6">INFORMACIÓN ADICIONAL DEL EQUIPO</td></tr>
        <tr class="subhead"><td colspan="2">NOMBRE DEL EQUIPO</td><td colspan="1">IP</td><td colspan="2">DIRECCIÓN MAC</td><td>DOMINIO</td></tr>
        <tr><td colspan="2">{{ $asset->hostname ?? 'N/A' }}</td><td>{{ $adapter?->ipv4 ?? 'N/A' }}</td><td colspan="2">{{ $adapter?->mac_address ?? 'N/A' }}</td><td>{{ $asset->domain_name ?? 'N/A' }}</td></tr>
        <tr class="subhead"><td colspan="2">DHCP <span class="check">{{ $asset->network?->dhcp_enabled ? 'X' : '' }}</span>&nbsp; FIJA <span class="check">{{ $asset->network && !$asset->network->dhcp_enabled ? 'X' : '' }}</span></td><td colspan="4">&nbsp;</td></tr>
    </table>
    <table class="section technician"><tr class="band"><td colspan="3">INFORMACIÓN DEL TÉCNICO RESPONSABLE</td></tr><tr class="subhead"><td>NOMBRE COMPLETO</td><td>FIRMA</td><td>OBSERVACIÓN</td></tr><tr><td>{{ $technician }}</td><td class="signature"></td><td>{{ $asset->notes ?? '' }}</td></tr></table>
    <div class="footer">PAG, 1 DE 2</div>
</div>

<div class="page">
    <table class="institution-table">
        <colgroup><col style="width:15%"><col style="width:65%"><col style="width:20%"></colgroup>
        <tr><td rowspan="4" class="logo-cell">@if($logo)<img src="{{ $logo }}" alt="USC">@endif</td><td class="institution-main institution-name">UNIVERSIDAD SANTIAGO DE CALI</td><td class="institution-code">R-GT004</td></tr>
        <tr><td class="institution-main institution-subtitle">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td><td class="institution-code">VERSIÓN. 3</td></tr>
        <tr><td class="institution-main institution-support">SOPORTE TECNICO</td><td rowspan="2" class="institution-code">FECHA<br>11 SEP 2019</td></tr>
        <tr><td class="institution-main institution-format">FORMATO DE HOJA DE VIDA DE EQUIPOS DE CÓMPUTO</td></tr>
    </table>
    <table class="history-title"><tr class="band"><td>HISTORIAL DE EQUIPO TECNOLÓGICO</td></tr></table>
    <table class="history-id"><tr class="subhead"><td>ACTIVO DEL EQUIPO</td><td>SERIAL DEL EQUIPO</td></tr><tr><td class="blue">{{ $asset->internal_code ?? 'N/A' }}</td><td>{{ $asset->serial_number ?? 'N/A' }}</td></tr></table>
    <table class="section">
        <thead class="history-head"><tr class="band"><td colspan="5">REGISTRO DE DIAGNÓSTICOS, CAMBIOS, MODIFICACIONES O INGRESOS A TALLER</td></tr><tr class="subhead"><td colspan="3">FECHA</td><td rowspan="2" style="width:64%">DESCRIPCIÓN</td><td rowspan="2" style="width:22%">NOMBRE DEL TÉCNICO</td></tr><tr class="subhead"><td style="width:4.6%">DD</td><td style="width:4.6%">MM</td><td style="width:4.8%">AA</td></tr></thead>
        <tbody class="history-rows">
            @for($i = 0; $i < 24; $i++)
                @php $entry = $history->get($i); $date = $entry?->performed_at ? \Carbon\Carbon::parse($entry->performed_at) : null; @endphp
                <tr><td>{{ $date?->format('d') }}</td><td>{{ $date?->format('m') }}</td><td>{{ $date?->format('y') }}</td><td class="history-description">{{ $entry?->description }}</td><td>{{ $entry?->user?->name }}</td></tr>
            @endfor
        </tbody>
    </table>
    <div class="footer">PAG, 2 DE 2</div>
</div>
</body>
</html>
