<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Mantenimiento Preventivo R-GT-051</title>
    <style>
        @page {
            size: letter landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-family: Arial, Helvetica, sans-serif !important;
            font-weight: 700 !important;
        }

        .logo-cell img {
            display: block;
            width: 45px;
            height: 45px;
            object-fit: contain;
            margin: 0 auto;
        }

        html,
        body {
            width: 100%;
            color: #000;
            background: #fff;
            font-size: 8pt;
        }

        .page {
            width: 100%;
            page-break-after: always;
            break-after: page;
        }

        .page:last-child {
            page-break-after: auto;
            break-after: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            font-family: Arial, Helvetica, sans-serif !important;
            font-weight: 700 !important;
        }

        .header-table {
            margin-bottom: 6px;
        }

        .header-table td {
            height: 18px;
            padding: 2px 4px;
        }

        .logo-cell {
            width: 7%;
            padding: 3px !important;
            color: #000;
            background: #fff;
            line-height: 1;
        }

        .logo-cell strong {
            display: block;
            font-size: 14pt;
            letter-spacing: -1px;
        }

        .logo-cell span {
            display: block;
            margin-top: 3px;
            font-size: 4.7pt;
            line-height: 1.05;
        }

        .title-cell {
            width: 78%;
            font-size: 9pt;
            line-height: 1.05;
        }

        .title-cell.institution {
            font-size: 8pt;
        }

        .title-cell.area {
            font-size: 8pt;
        }

        .meta-cell {
            width: 15%;
            font-size: 7pt;
            text-align: center;
            white-space: nowrap;
        }

        .main-grid col.col-dia { width: 3%; }
        .main-grid col.col-mes { width: 3%; }
        .main-grid col.col-ano { width: 3%; }
        .main-grid col.col-bloque { width: 7%; }
        .main-grid col.col-piso { width: 3%; }
        .main-grid col.col-dependencia { width: 15%; }
        .main-grid col.col-activo { width: 7%; }
        .main-grid col.col-descripcion { width: 33%; }
        .main-grid col.col-tecnico { width: 13%; }
        .main-grid col.col-usuario { width: 13%; }

        .main-grid thead th {
            height: 25px;
            padding: 3px 2px;
            font-size: 7pt;
            line-height: 1.05;
            text-transform: uppercase;
        }

        .main-grid thead tr:first-child th[rowspan="2"] {
            height: 42px;
        }

        .main-grid thead .sub-header {
            height: 17px;
            padding: 2px 1px;
            font-size: 6.5pt;
        }

        .data-row {
            height: 38px;
        }

        .data-row td {
            height: 38px;
            max-height: 38px;
            padding: 2px 3px;
            overflow: hidden;
            font-size: 7pt;
            line-height: 1.05;
            overflow-wrap: break-word;
        }

        .data-row .location-block {
            font-size: 6.5pt;
            overflow-wrap: anywhere;
        }

        .data-row .description {
            text-align: left;
            padding-left: 5px;
            font-size: 6.7pt;
        }

        .data-row .asset-number {
            font-size: 6.5pt;
        }
    </style>
</head>

@php
    $logoPath = public_path('img/logoUSC.png');
    $logo = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp
<body>
    @foreach($pages as $page)
        <section class="page">
            <table class="header-table" aria-label="Encabezado institucional">
                <tbody>
                    <tr>
                        <td class="logo-cell" rowspan="3">
                              @if($logo)
                    <img src="{{ $logo }}" alt="USC">
                    @endif
                        </td>
                        <td class="title-cell">REGISTRO DE MANTENIMIENTO PREVENTIVO</td>
                        <td class="meta-cell">CÓDIGO: R-GT-051</td>
                    </tr>
                    <tr>
                        <td class="title-cell institution">UNIVERSIDAD SANTIAGO DE CALI</td>
                        <td class="meta-cell">VERSIÓN: 1</td>
                    </tr>
                    <tr>
                        <td class="title-cell area">GESTIÓN TECNOLÓGICA</td>
                        <td class="meta-cell">FECHA: 23 OCTUBRE DE 2017</td>
                    </tr>
                </tbody>
            </table>

            <table class="main-grid" aria-label="Registro de mantenimientos preventivos">
                <colgroup>
                    <col class="col-dia"><col class="col-mes"><col class="col-ano">
                    <col class="col-bloque"><col class="col-piso">
                    <col class="col-dependencia"><col class="col-activo">
                    <col class="col-descripcion"><col class="col-tecnico"><col class="col-usuario">
                </colgroup>
                <thead>
                    <tr>
                        <th colspan="3">FECHA</th>
                        <th colspan="2">UBICACIÓN</th>
                        <th rowspan="2">DEPENDENCIA<br>/ PROGRAMA</th>
                        <th rowspan="2">N° ACTIVO</th>
                        <th rowspan="2">DESCRIPCIÓN ACTIVIDAD</th>
                        <th rowspan="2">TÉCNICO<br>RESPONSABLE</th>
                        <th rowspan="2">USUARIO RESPONSABLE</th>
                    </tr>
                    <tr>
                        <th class="sub-header">DÍA</th>
                        <th class="sub-header">MES</th>
                        <th class="sub-header">AÑO</th>
                        <th class="sub-header">BLOQUE</th>
                        <th class="sub-header">PISO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($page as $row)
                        @if(!$row->is_empty)
                            @php
                                $schedule = $row->data;
                                $asset = $schedule->asset;
                                $room = $asset?->room;
                                $buildingName = trim((string) ($room?->building?->name ?? ''));
                                $block = preg_replace('/^BLOQUE\s*/i', '', $buildingName);
                                $custodian = $asset?->currentCustodian ?? $asset?->custodian;
                                $dependency = $custodian?->dependency?->name ?? $room?->name ?? 'SIN DEPENDENCIA';
                                $description = $schedule->technicalService?->description ?: 'MANTENIMIENTO PREVENTIVO PROGRAMADO';
                            @endphp
                            <tr class="data-row">
                                <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('m') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('y') }}</td>
                                <td class="location-block">{{ $block !== '' ? strtoupper($block) : 'N/A' }}</td>
                                <td>{{ $room?->floor ?? 'N/A' }}</td>
                                <td>{{ strtoupper($dependency) }}</td>
                                <td class="asset-number">{{ $asset?->internal_code ?: ($asset?->serial_number ?: 'N/A') }}</td>
                                <td class="description">{{ \Illuminate\Support\Str::limit(strip_tags($description), 220) }}</td>
                                <td>{{ $schedule->technician?->name ?? 'N/A' }}</td>
                                <td>{{ $custodian?->full_name ?? 'N/A' }}</td>
                            </tr>
                        @else
                            <tr class="data-row">
                                <td></td><td></td><td></td><td></td><td></td>
                                <td></td><td></td><td></td><td></td><td></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </section>
    @endforeach
</body>
</html>
