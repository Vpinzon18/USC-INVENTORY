<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Formato Único para el Movimiento de Activos - USC</title>



    @php

        $logoPath = public_path('img/logoUSC.png');



        $logo = is_file($logoPath)

            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))

            : null;



        $assignment = $assets->first()

            ? $assets->first()->assignments->where('acta_number', $actaNumber)->first()

            : null;



        $observations = $assignment?->observations
            ?? 'Movimiento masivo gestionado en SIGMA';

        // Normaliza valores para que coincidan aunque el formulario guarde
        // mayúsculas/minúsculas, tildes o etiquetas ligeramente diferentes.
        $normalizeValue = static function ($value): string {
            $value = mb_strtoupper(trim((string) ($value ?? '')), 'UTF-8');
            $value = strtr($value, [
                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                'Ü' => 'U', 'Ñ' => 'N',
            ]);
            $value = preg_replace('/[^A-Z0-9]+/', ' ', $value) ?? '';
            return trim(preg_replace('/\s+/', ' ', $value) ?? '');
        };

        $movementType = $normalizeValue($assignment?->movement_type ?? '');
        $isMovement = static function (array $aliases) use ($movementType, $normalizeValue): bool {
            foreach ($aliases as $alias) {
                if ($movementType === $normalizeValue($alias)) {
                    return true;
                }
            }
            return false;
        };

        $headquartersRaw = $assignment?->headquarters
            ?? $assignment?->site
            ?? $assignment?->campus
            ?? $assignment?->sede
            ?? '';
        $headquarters = $normalizeValue($headquartersRaw);
        $isPampalinda = str_contains($headquarters, 'PAMPALINDA');
        $isCentro = str_contains($headquarters, 'CENTRO');
        $isPalmira = str_contains($headquarters, 'PALMIRA');
        $isOtherHeadquarters = $headquarters !== '' && !($isPampalinda || $isCentro || $isPalmira);

    @endphp



    <style>

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }



        @page {

            size: letter portrait;

            margin: 6mm;

        }



        html, body {

            width: 100%;

            margin: 0;

            padding: 0;

            color: #000;

            background: #fff;

            font-family: Arial, Helvetica, sans-serif;

            font-size: 9px;

        }



        .page {

            width: 100%;

            margin: 0;

            padding: 0;

        }



        .page-break {

            page-break-before: always;

            break-before: page;

        }



        /* CABECERA INSTITUCIONAL */



        .header-table {

            width: 100%;

            table-layout: fixed;

            border-collapse: collapse;

            border: none !important;

            margin: 0;

            padding: 0;

        }



        .header-table > tbody > tr > td {

            border: none !important;

            margin: 0;

            padding: 0;

            vertical-align: middle;

        }



        .header-logo {

            width: 24%;

            margin: 0 !important;

            padding: 2mm 0 !important;

            text-align: left !important;

            vertical-align: middle;

            border: none !important;

        }



        .header-logo img {

            display: block;

            width: 110px;

            height: auto;

            max-width: 100%;

            margin: 0 !important;

            padding: 0 !important;

            border: none !important;

        }



        .header-title {

            width: 46%;

            margin: 0;

            padding: 3mm 1mm !important;

            text-align: center;

            vertical-align: middle;

            font-family: Arial, Helvetica, sans-serif;

            font-size: 14px;

            font-weight: bold;

            line-height: 1.5;

            text-transform: uppercase;

            border: none !important;

        }



        .header-right {

            width: 30%;

            margin: 0;

            padding: 0 1mm !important;

            vertical-align: top !important;

            border: none !important;

        }



        /* CONSECUTIVO */



        .consecutivo-block {

            width: 100%;

            margin: 0 0 2mm;

            padding: 0;

            border: 1px solid #000;

        }



        .consecutivo-label {

            display: block;

            width: 100%;

            padding: 1.5mm;

            background: #000;

            color: #fff;

            text-align: center;

            text-transform: uppercase;

            font-size: 8px;

            font-weight: bold;

        }



        .consecutivo-box {

            width: 100%;

            height: 10mm;

            margin: 0;

            padding: 0;

            background: #fff;

            border: none;

        }



        /* FECHA: UNA SOLA FILA */



        .fecha-block {

            width: 100%;

            margin: 0;

            padding: 0;

        }



        .fecha-table {

            width: 100%;

            table-layout: fixed;

            border-collapse: collapse;

            border: 1px solid #000;

            margin: 0;

            padding: 0;

        }



        .fecha-table td {

            height: 7mm;

            border: 1px solid #000;

            margin: 0;

            padding: 1mm 0.5mm;

            text-align: center;

            vertical-align: middle;

            white-space: nowrap;

        }



        .fecha-table .fecha-label {

            width: 35%;

            background: #000;

            color: #fff;

            font-size: 8px;

            font-weight: bold;

            text-align: center;

            text-transform: uppercase;

        }



        .fecha-table .fecha-guide {

            width: 21.66%;

            background: #e8e8e8;

            color: #777;

            font-size: 7px;

            font-weight: bold;

            text-align: center;

        }



        .version-text {

            width: 100%;

            margin: 0;

            padding: 1mm 0 0;

            text-align: right;

            font-size: 9px;

            line-height: 1.2;

            white-space: nowrap;

        }



        /* ENCABEZADOS */



        .section-header {

            width: 100%;

            margin: 0;

            padding: 1.5mm 1mm;

            border: 1px solid #000;

            background: #000;

            color: #fff;

            text-align: center;

            text-transform: uppercase;

            font-size: 9px;

            font-weight: bold;

            page-break-after: avoid;

        }



        /* TIPO DE MOVIMIENTO */



        .tipo-table {

            width: 100%;

            table-layout: fixed;

            border-collapse: collapse;

            margin: 0;

            padding: 0;

        }



        .tipo-table td {

            border: 1px solid #000;

            padding: 2mm 1.5mm;

            vertical-align: middle;

            font-size: 8px;

        }



        .checkbox-cell {

            width: 6mm;

            height: 5mm;

            padding: 0 !important;

            text-align: center;

            vertical-align: middle;

        }



        .checkbox {
            display: inline-block;
            width: 3mm;
            height: 3mm;
            margin: 0;
            padding: 0;
            border: 1px solid #000;
            background: #fff;
            vertical-align: middle;
            text-align: center;
            line-height: 2.7mm;
            font-size: 7px;
            font-weight: bold;
        }



        .otro-line {

            display: inline-block;

            width: 25mm;

            margin-left: 2mm;

            border-bottom: 1px solid #000;

            vertical-align: bottom;

        }



        /* SEDE */



        .sede-table {

            width: 100%;

            table-layout: fixed;

            border-collapse: collapse;

            margin: 0;

            padding: 0;

        }



        .sede-table td {

            border: 1px solid #000;

            padding: 2mm 1.5mm;

            font-size: 8px;

            vertical-align: middle;

        }



        .sede-label {

            text-align: center;

            font-weight: bold;

        }



        .sede-checkbox {

            width: 6mm;

            padding: 0 !important;

            text-align: center;

        }



        /* ÁREAS DE ORIGEN Y DESTINO */



        /* ÁREAS DE ORIGEN Y DESTINO: bordes finos y uniformes para PDF */
        .datos-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0 0 2mm;
            padding: 0;
            border: 0.5px solid #333;
        }

        .datos-table > tbody > tr > td {
            width: 50%;
            padding: 0;
            border: 0.5px solid #333;
            vertical-align: top;
        }

        .area-fields {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0;
            padding: 0;
            border: 0.5px solid #333;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            color: #000;
        }

        .area-fields td {
            border: 0.5px solid #333;
            padding: 2px;
            height: 7mm;
            vertical-align: middle;
            text-align: center;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .area-fields .area-header {
            height: 8mm;
            padding: 2px !important;
            background: #000;
            color: #fff;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border: 0.5px solid #333;
        }

        .area-fields .field-label {
            background: #fff;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
            white-space: normal;
        }

        .area-fields .field-value {
            background: #fff;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .activos-table {

            width: 100%;

            table-layout: fixed;

            border-collapse: collapse;

            margin: 0;

            padding: 0;

        }



        .activos-table th,

        .activos-table td {

            border: 1px solid #000;

            padding: 2mm 1.5mm;

            vertical-align: middle;

            font-size: 8px;

            overflow-wrap: anywhere;

            word-wrap: break-word;

        }



        .activos-table th {

            background: #fff;

            text-align: center;

            text-transform: uppercase;

            font-weight: bold;

        }



        .asset-empty-row td {

            height: 6mm;

        }



        /* CLÁUSULA DE COMPROMISO */



        .clausula-text {

            margin: 0;

            padding: 2mm;

            border: 1px solid #000;

            border-top: none;

            font-size: 7px;

            line-height: 1.4;

            text-align: justify;

        }



        .clausula-text p {

            margin: 0 0 2mm;

            padding: 0;

        }



        .clausula-text p:last-child {

            margin-bottom: 0;

        }



        /* OBSERVACIONES */



        .obs-container {

            min-height: 18mm;

            margin: 0;

            padding: 2mm;

            border: 1px solid #000;

            border-top: none;

            font-size: 9px;

            line-height: 6mm;

            overflow-wrap: anywhere;

            word-wrap: break-word;

            background-image: linear-gradient(#ccc 1px, transparent 1px);

            background-size: 100% 6mm;

        }



        /* FIRMAS */



        .firmas-table {

            width: 100%;

            table-layout: fixed;

            border-collapse: collapse;

            margin: 2mm 0 0;

            padding: 0;

        }



        .firmas-table td {

            width: 33.33%;

            margin: 0;

            padding: 0;

            border: 1px solid #000;

            vertical-align: top;

        }



        .firma-header {

            padding: 2mm 1mm;

            background: #000;

            color: #fff;

            text-align: center;

            text-transform: uppercase;

            font-size: 7px;

            font-weight: bold;

            line-height: 1.4;

        }



        .firma-space {

            height: 23mm;

        }



        /* ANEXO TÉCNICO */



        .anexo-title-table {

            width: 100%;

            border-collapse: collapse;

            margin: 0 0 4mm;

            padding: 0;

        }



        .anexo-title-table td {

            vertical-align: middle;

        }



        .anexo-table {

            width: 100%;

            table-layout: fixed;

            border-collapse: collapse;

            font-size: 9px;

        }



        .anexo-table th {

            padding: 2mm;

            border: 1px solid #000;

            background: #000;

            color: #fff;

            text-align: center;

            text-transform: uppercase;

            font-size: 8px;

            font-weight: bold;

        }



        .anexo-table td {

            padding: 2mm;

            border: 1px solid #000;

            text-align: center;

            overflow-wrap: anywhere;

            word-wrap: break-word;

        }



        @media print {

            html, body {

                width: 100%;

                margin: 0;

                padding: 0;

            }



            .page {

                width: 100%;

                margin: 0;

                padding: 0;

            }



            .page-break {

                page-break-before: always;

                break-before: page;

            }

        }

    

        /* AJUSTES FINALES: tablas de áreas, activos y firmas */
        .datos-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0 0 2mm;
            border: 0;
        }

        /* La tabla exterior solo distribuye las dos mitades; evita bordes duplicados. */
        .datos-table > tbody > tr > td {
            width: 50%;
            padding: 0;
            border: 0;
            vertical-align: top;
        }

        .area-fields {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0;
            padding: 0;
            border: 0.5px solid #444;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            color: #000;
        }

        .area-fields td {
            border: 0.5px solid #444;
            padding: 2px;
            height: 7mm;
            vertical-align: middle;
            text-align: center;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: normal;
        }

        .area-fields .area-header {
            height: 8mm;
            padding: 2px;
            background: #000;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border: 0.5px solid #444;
        }

        .area-fields .field-label,
        .area-fields .field-value {
            text-align: center;
            vertical-align: middle;
        }

        .area-fields .field-label {
            background: #fff;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: normal;
        }

        .area-fields .field-value {
            background: #fff;
            font-size: 8px;
            font-weight: bold;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /* Restaurar los bordes propios de la tabla de activos; no heredar los finos de las áreas. */
        .activos-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0;
            padding: 0;
            border: 1px solid #000;
        }

        .activos-table th,
        .activos-table td {
            border: 1px solid #000;
            padding: 2mm 1.5mm;
            vertical-align: middle;
            font-size: 8px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .activos-table th {
            background: #fff;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Mantener las tres firmas completas en una sola página. */
        .firmas-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin: 2mm 0 0;
            padding: 0;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .firmas-table tr,
        .firmas-table td {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .firmas-table td {
            width: 33.33%;
            margin: 0;
            padding: 0;
            border: 1px solid #000;
            vertical-align: top;
        }

        .firma-header {
            padding: 2mm 1mm;
            background: #000;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            font-size: 7px;
            font-weight: bold;
            line-height: 1.4;
        }

        .firma-space {
            height: 23mm;
        }

    </style>

</head>



<body>

    <div class="page">



        {{-- CABECERA INSTITUCIONAL --}}

        <table class="header-table">

            <colgroup>

                <col style="width: 24%;">

                <col style="width: 46%;">

                <col style="width: 30%;">

            </colgroup>



            <tr>

                <td class="header-logo">

                    @if ($logo)

                        <img src="{{ $logo }}" alt="Logo USC" style="width: 110px; height: auto;">

                    @else

                        <strong style="font-size: 22px;">USC</strong><br>

                        <span style="font-size: 7px; font-weight: bold;">

                            UNIVERSIDAD<br>SANTIAGO<br>DE CALI

                        </span>

                    @endif

                </td>



                <td class="header-title">

                    FORMATO ÚNICO PARA<br>

                    EL MOVIMIENTO<br>

                    DE ACTIVOS

                </td>



                <td class="header-right">

                    <div class="consecutivo-block">

                        <div class="consecutivo-label">CONSECUTIVO No.</div>

                        <div class="consecutivo-box">&nbsp;</div>

                    </div>



                    <div class="fecha-block">

                        <table class="fecha-table">

                            <colgroup>

                                <col style="width: 35%;">

                                <col style="width: 21.66%;">

                                <col style="width: 21.66%;">

                                <col style="width: 21.68%;">

                            </colgroup>

                            <tr>

                                <td class="fecha-label">FECHA</td>

                                <td class="fecha-guide">DD</td>

                                <td class="fecha-guide">MM</td>

                                <td class="fecha-guide">AA</td>

                            </tr>

                        </table>

                    </div>



                    <div class="version-text">

                        <span style="font-size: 9px;">R-AF001/Versión 2/12 de junio de 2019</span>

                    </div>

                </td>

            </tr>

        </table>



        {{-- TIPO DE MOVIMIENTO --}}

        <div class="section-header">TIPO DE MOVIMIENTO</div>



        <table class="tipo-table">

            <colgroup>

                <col style="width: 6mm;">

                <col style="width: 44%;">

                <col style="width: 6mm;">

                <col>

            </colgroup>

            <tr>

                <td class="checkbox-cell"><span class="checkbox">{{ $isMovement(['TRASLADO ASIGNACION', 'TRASLADO EN CALIDAD DE ASIGNACION', 'TRASLADO EN CALIDAD DE ASIGNACIÓN']) ? 'X' : '' }}</span></td>

                <td>TRASLADO EN CALIDAD DE ASIGNACIÓN</td>

                <td class="checkbox-cell"><span class="checkbox">{{ $isMovement(['PRESTAMO FUERA USC', 'PRESTAMO FUERA DE LA USC', 'PRESTAMO FUERA DE LAS INSTALACIONES DE LA USC']) ? 'X' : '' }}</span></td>

                <td>PRÉSTAMO FUERA DE LAS INSTALACIONES DE LA USC</td>

            </tr>

            <tr>

                <td class="checkbox-cell"><span class="checkbox">{{ $isMovement(['REPARACION DENTRO USC', 'REPARACION DENTRO DE USC', 'REPARACION DENTRO DE LA USC', 'TRASLADO EN CALIDAD DE REPARACION DENTRO DE LA USC']) ? 'X' : '' }}</span></td>

                <td>TRASLADO EN CALIDAD DE REPARACIÓN DENTRO DE LA USC</td>

                <td class="checkbox-cell"><span class="checkbox">{{ $isMovement(['ASIGNACION INICIAL']) ? 'X' : '' }}</span></td>

                <td>ASIGNACIÓN INICIAL</td>

            </tr>

            <tr>

                <td class="checkbox-cell"><span class="checkbox">{{ $isMovement(['REPARACION FUERA USC', 'REPARACION FUERA DE USC', 'REPARACION FUERA DE LA USC', 'TRASLADO EN CALIDAD DE REPARACION FUERA DE LA USC']) ? 'X' : '' }}</span></td>

                <td>TRASLADO EN CALIDAD DE REPARACIÓN FUERA DE LA USC</td>

                <td class="checkbox-cell"><span class="checkbox">{{ $isMovement(['OTRO', 'OTROS']) ? 'X' : '' }}</span></td>

                <td>OTRO: <span class="otro-line">{{ $isMovement(['OTRO', 'OTROS']) ? ($assignment?->movement_other ?? $assignment?->other_movement ?? '') : '' }}</span></td>

            </tr>

            <tr>

                <td class="checkbox-cell"><span class="checkbox">{{ $isMovement(['PRESTAMO DENTRO USC', 'PRESTAMO DENTRO DE LA USC', 'PRESTAMO DENTRO DE LAS INSTALACIONES DE LA USC']) ? 'X' : '' }}</span></td>

                <td colspan="3">PRÉSTAMO DENTRO DE LAS INSTALACIONES DE LA USC</td>

            </tr>

        </table>



        {{-- SEDE --}}

        <div class="section-header">SEDE</div>



        <table class="sede-table">

            <colgroup>

                <col style="width: 22%;">

                <col style="width: 6mm;">

                <col style="width: 19%;">

                <col style="width: 6mm;">

                <col style="width: 15%;">

                <col style="width: 6mm;">

                <col style="width: 10%;">

                <col>

            </colgroup>

            <tr>

                <td class="sede-label">PAMPALINDA (CALI)</td>

                <td class="sede-checkbox"><span class="checkbox">{{ $isPampalinda ? 'X' : '' }}</span></td>

                <td class="sede-label">CENTRO (CALI)</td>

                <td class="sede-checkbox"><span class="checkbox">{{ $isCentro ? 'X' : '' }}</span></td>

                <td class="sede-label">PALMIRA</td>

                <td class="sede-checkbox"><span class="checkbox">{{ $isPalmira ? 'X' : '' }}</span></td>

                <td class="sede-label">OTRO</td>

                <td>{{ $isOtherHeadquarters ? $headquartersRaw : '' }}</td>

            </tr>

        </table>



        {{-- ÁREAS DE ORIGEN Y DESTINO: una tabla por bloque, sin tablas anidadas --}}
        <table class="datos-table" border="0" cellspacing="0" cellpadding="0">
            <colgroup>
                <col style="width:50%;">
                <col style="width:50%;">
            </colgroup>
            <tr>
                <td>
                    <table class="area-fields" border="0" cellspacing="0" cellpadding="0">
                        <colgroup>
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                        </colgroup>
                        <tr><td class="area-header" colspan="20">ÁREA DE ORIGEN (ENTREGA)</td></tr>
                        <tr>
                            <td class="field-label" colspan="4">RESPONSABLE ACTUAL</td>
                            <td class="field-value" colspan="16">{{ $responsableAnterior->full_name ?? 'UNIDAD DE ACTIVOS FIJOS' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="4">DCTO. DE IDENTIDAD</td>
                            <td class="field-value" colspan="16">{{ $responsableAnterior->document_number ?? $responsableAnterior->identification_number ?? $responsableAnterior->document ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="4">DEPENDENCIA</td>
                            <td class="field-value" colspan="9">{{ $responsableAnterior->dependency->name ?? 'N/A' }}</td>
                            <td class="field-label" colspan="2" style="text-align:center;">CC</td>
                            <td class="field-value" colspan="5">{{ $responsableAnterior->cost_center ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="3">BLOQUE</td>
                            <td class="field-value" colspan="5">{{ $salaOrigen->building->name ?? '' }}</td>
                            <td class="field-label" colspan="2">PISO</td>
                            <td class="field-value" colspan="2">{{ $salaOrigen->floor ?? '' }}</td>
                            <td class="field-label" colspan="3">UBICACIÓN</td>
                            <td class="field-value" colspan="5">{{ $salaOrigen->nomenclatura ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="7">TELÉFONO - EXTENSIÓN</td>
                            <td class="field-value" colspan="13">{{ $responsableAnterior->extension ?? '' }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="area-fields" border="0" cellspacing="0" cellpadding="0">
                        <colgroup>
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                            <col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;"><col style="width:5%;">
                        </colgroup>
                        <tr><td class="area-header" colspan="20">ÁREA DE DESTINO (RECIBE)</td></tr>
                        <tr>
                            <td class="field-label" colspan="4">RESPONSABLE ACTUAL</td>
                            <td class="field-value" colspan="16">{{ $nuevoResponsable->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="4">DCTO. DE IDENTIDAD</td>
                            <td class="field-value" colspan="16">{{ $nuevoResponsable->document_number ?? $nuevoResponsable->identification_number ?? $nuevoResponsable->document ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="4">DEPENDENCIA</td>
                            <td class="field-value" colspan="9">{{ $nuevoResponsable->dependency->name ?? 'N/A' }}</td>
                            <td class="field-label" colspan="2" style="text-align:center;">CC</td>
                            <td class="field-value" colspan="5">{{ $nuevoResponsable->cost_center ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="3">BLOQUE</td>
                            <td class="field-value" colspan="5">{{ $salaDestino->building->name ?? '' }}</td>
                            <td class="field-label" colspan="2">PISO</td>
                            <td class="field-value" colspan="2">{{ $salaDestino->floor ?? '' }}</td>
                            <td class="field-label" colspan="3">UBICACIÓN</td>
                            <td class="field-value" colspan="5">{{ $salaDestino->nomenclatura ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="field-label" colspan="7">TELÉFONO - EXTENSIÓN</td>
                            <td class="field-value" colspan="13">{{ $nuevoResponsable->extension ?? '' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- INFORMACIÓN DE ACTIVOS FIJOS --}}

        <div class="section-header">INFORMACIÓN DE LOS ACTIVOS FIJOS</div>



        <table class="activos-table">

            <thead>

                <tr>

                    <th style="width: 20%;">No. Sticker</th>

                    <th style="width: 60%;">Descripción del activo fijo</th>

                    <th style="width: 20%;">No. Serial</th>

                </tr>

            </thead>

            <tbody>

                @if (count($assets) > 5)

                    @for ($i = 0; $i < 5; $i++)

                        <tr class="asset-empty-row">

                            <td>&nbsp;</td>

                            <td>&nbsp;</td>

                            <td>&nbsp;</td>

                        </tr>

                    @endfor

                @else

                    @foreach ($assets as $asset)

                        <tr>

                            <td style="text-align: center; font-weight: bold;">

                                {{ $asset->internal_code ?? '' }}

                            </td>

                            <td>

                                @php
                                    $assetBrand = $asset->brand
                                        ?? $asset->manufacturer
                                        ?? $asset->brand_name
                                        ?? $asset->marca
                                        ?? '';
                                    $assetModel = $asset->model_version
                                        ?? $asset->model
                                        ?? $asset->model_name
                                        ?? $asset->modelo
                                        ?? '';
                                @endphp
                                {{ $asset->hostname ?? 'EQUIPO DE CÓMPUTO' }}
                                @if (filled($assetBrand)) - MARCA: {{ $assetBrand }}@endif
                                @if (filled($assetModel)) - MODELO: {{ $assetModel }}@endif
                                @if (filled($asset->cpu ?? null)) - CPU: {{ $asset->cpu }}@endif

                            </td>

                            <td style="text-align: center; font-weight: bold;">

                                {{ $asset->serial_number ?? '' }}

                            </td>

                        </tr>

                    @endforeach



                    @for ($i = count($assets); $i < 5; $i++)

                        <tr class="asset-empty-row">

                            <td>&nbsp;</td>

                            <td>&nbsp;</td>

                            <td>&nbsp;</td>

                        </tr>

                    @endfor

                @endif

            </tbody>

        </table>



        {{-- CLÁUSULA DE COMPROMISO --}}

        <div class="section-header">CLÁUSULA DE COMPROMISO</div>



        <div class="clausula-text">

            <p>

                Como funcionario de la Universidad Santiago de Cali declaro que los activos relacionados en el presente documento están bajo mi responsabilidad, por lo cual les daré un uso adecuado al desempeño de mis funciones y a la destinación Institucional prevista para cada uno de ellos. En consecuencia, serán asumidos por mí, el daño o la pérdida de los mismos debidos a mi negligencia o incumplimiento de los instructivos relacionados con su uso y conservación.

            </p>



            <p>

                Me comprometo a informar oportunamente a la oficina de Vicerrectoría Administrativa cualquier desplazamiento, traslado temporal o definitivo de dichos activos mediante la tramitación de los formatos respectivos y sobre cualquier situación que ponga en inminente riesgo los bienes relacionados.

            </p>



            <p>

                Dado que la omisión de estas disposiciones se considera falta por el reglamento interno de trabajo, asumo las consecuencias económicas que conlleven el daño o la pérdida de los bienes mencionados si ocurren por mi negligencia o incumplimiento de los instructivos correspondientes, y en tal evento autorizo a la Universidad Santiago de Cali a efectuar el descuento correspondiente al valor de reposición del bien afectado, deduciéndolo de mis salarios y prestaciones sociales o eventuales indemnizaciones a mi favor.

            </p>

        </div>



        {{-- OBSERVACIONES --}}

        <div class="section-header">OBSERVACIONES ADICIONALES</div>

        <div class="obs-container">{{ strip_tags($observations) }}</div>



        {{-- FIRMAS --}}

        <table class="firmas-table">

            <tr>

                <td>

                    <div class="firma-header">FIRMA Y SELLO RESPONSABLE<br>ACTUAL DEL ACTIVO</div>

                    <div class="firma-space"></div>

                </td>

                <td>

                    <div class="firma-header">FIRMA Y SELLO DEL NUEVO<br>RESPONSABLE DEL ACTIVO</div>

                    <div class="firma-space"></div>

                </td>

                <td>

                    <div class="firma-header">FIRMA Y SELLO UNIDAD DE<br>ACTIVOS FIJOS</div>

                    <div class="firma-space"></div>

                </td>

            </tr>

        </table>

    </div>



    {{-- ANEXO PARA MOVIMIENTOS DE MÁS DE CINCO ACTIVOS --}}

    @if (count($assets) > 5)

        <div class="page page-break">

            <table class="anexo-title-table">

                <tr>

                    <td>

                        <h2 style="font-size: 18px; font-weight: bold;">

                            SIGMA - ANEXO TÉCNICO

                        </h2>

                        <p style="margin-top: 3px; font-size: 10px; font-weight: bold; text-transform: uppercase;">

                            Relación detallada de hardware por volumen de movimiento masivo

                        </p>

                    </td>

                </tr>

            </table>



            <div style="border-top: 2px solid #000; margin-bottom: 4mm;"></div>



            <table class="anexo-table">

                <thead>

                    <tr>

                        <th style="width: 5%;">Ítem</th>

                        <th style="width: 25%;">No. Sticker / Placa</th>

                        <th style="width: 45%;">Descripción / Modelo de hardware</th>

                        <th style="width: 25%;">Número de serial</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($assets as $index => $asset)

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td style="font-weight: bold;">

                                {{ $asset->internal_code ?? 'S/N' }}

                            </td>

                            <td style="text-align: left;">

                                @php
                                    $assetBrand = $asset->brand
                                        ?? $asset->manufacturer
                                        ?? $asset->brand_name
                                        ?? $asset->marca
                                        ?? '';
                                    $assetModel = $asset->model_version
                                        ?? $asset->model
                                        ?? $asset->model_name
                                        ?? $asset->modelo
                                        ?? '';
                                @endphp
                                <strong>{{ $asset->hostname ?? 'COMPUTADOR' }}</strong>
                                @if (filled($assetBrand)) - MARCA: {{ $assetBrand }}@endif
                                @if (filled($assetModel)) - MODELO: {{ $assetModel }}@endif
                                @if (filled($asset->cpu ?? null)) - CPU: {{ $asset->cpu }}@endif

                            </td>

                            <td style="font-weight: bold;">

                                {{ $asset->serial_number ?? '' }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>



            <div style="margin-top: 6mm; border: 1px solid #000; padding: 3mm; font-size: 8px; line-height: 1.5; text-align: justify;">

                <strong>ANEXO TÉCNICO DE INVENTARIO USC:</strong>

                Este documento anexo forma parte integral del movimiento de activos y contiene la relación detallada de los equipos incluidos en la operación registrada en SIGMA.

            </div>

        </div>

    @endif

</body>

</html>