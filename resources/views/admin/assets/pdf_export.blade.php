<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hoja de Vida SIGMA - {{ $asset->internal_code ?? $asset->serial_number }}</title>
    <style>
        /* 1. CONFIGURACIÓN GLOBAL DE MÁRGENES IMPRESOS */
        @page {
            margin-top: 15mm;
            margin-bottom: 15mm;
            margin-left: 12mm;
            margin-right: 12mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
            color: #000;
            background-color: #fff;
        }

        /* Contenedor seguro para layouts PDF sin Flexbox */
        .hoja-carta {
            position: relative;
            display: block;
            width: 100%;
            clear: both;
            background-color: #fff;
        }

        /* Salto de página estricto para motores de PDF */
        .page-break {
            page-break-before: always;
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* Previene que una fila de software o mantenimiento se parta horizontalmente a la mitad */
        tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .bg-usc {
            background-color: #d9e1f2 !important;
            font-weight: bold;
        }

        .bg-gray {
            background-color: #f2f2f2 !important;
            font-weight: bold;
        }

        .text-blue {
            color: #002060;
            font-weight: bold;
        }

        .text-left {
            text-align: left !important;
            padding-left: 8px;
        }

        .blue-val {
            color: #0000FF;
            font-weight: bold;
            font-size: 10px;
        }
    </style>
</head>

<body>

    @php $logo = public_path('img/logoUSC.png'); @endphp

    {{-- ════════════════════════════ PAGINA 1: ESPECIFICACIONES Y ASIGNACIONES ════════════════════════════ --}}
    <div class="hoja-carta">
        
        <table style="margin-bottom: 12px;">
            <tr>
                <td rowspan="3" style="width: 100px;">@if(file_exists($logo)) <img src="{{ $logo }}" style="width: 60px;"> @endif</td>
                <td style="font-size: 13px; font-weight: bold;">UNIVERSIDAD SANTIAGO DE CALI</td>
                <td class="bg-usc" style="width: 100px;">R-GT004</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td>
                <td class="bg-usc">VERSIÓN. 3</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">SOPORTE TECNICO - HOJA DE VIDA DE EQUIPOS</td>
                <td class="bg-usc">11 SEP 2019</td>
            </tr>
        </table>

        <table>
            <tr class="bg-usc">
                <td colspan="6">DATOS DEL RESPONSABLE DEL EQUIPO</td>
            </tr>
            <tr class="bg-gray">
                <td colspan="2">NOMBRE COMPLETO</td>
                <td colspan="2">N° DOCUMENTO</td>
                <td colspan="2">DEPENDENCIA</td>
            </tr>
            <tr>
                <td colspan="2" class="text-blue">{{ $asset->currentCustodian->full_name ?? 'N/A' }}</td>
                <td colspan="2">{{ $asset->currentCustodian->document_number ?? 'N/A' }}</td>
                <td class="px-2 py-1">{{ $asset->currentCustodian->dependency->name ?? 'N/A' }}</td>
            </tr>
            <tr class="bg-gray">
                <td>CARGO</td>
                <td>CORREO USC</td>
                <td>EXT</td>
                <td>BLOQUE</td>
                <td>PISO</td>
                <td>UBICACIÓN</td>
            </tr>
            <tr>
                <td class="px-2 py-1">{{ $asset->currentCustodian->jobTitle->name ?? 'N/A' }}</td>
                <td style="text-transform: lowercase;">{{ $asset->currentCustodian->email ?? 'N/A' }}</td>
                <td>{{ $asset->currentCustodian->extension ?? 'N/A' }}</td>
                <td>{{ $asset->room->building->name ?? 'N/A' }}</td>
                <td>{{ $asset->room->floor ?? 'N/A' }}</td>
                <td class="text-blue">{{ $asset->room->nomenclatura ?? 'N/A' }}</td>
            </tr>
        </table>

        <table>
            <tr class="bg-usc">
                <td colspan="7">DATOS DEL EQUIPO Y PERIFÉRICOS</td>
            </tr>
            <tr class="bg-gray" style="font-size: 8px;">
                <td>REFERENCIA</td>
                <td>ACTIVO TORRE</td>
                <td>SERIAL TORRE</td>
                <td>ACTIVO MONIT</td>
                <td>SERIAL MONIT</td>
                <td>SER. TECLADO</td>
                <td>SER. MOUSE</td>
            </tr>
            <tr>
                <td>{{ $asset->hostname ?? 'N/A' }}</td>
                <td class="text-blue">{{ $asset->internal_code ?? 'N/A' }}</td>
                <td class="text-blue">{{ $asset->serial_number ?? 'N/A' }}</td>
                <td>{{ $asset->monitor_asset ?? 'N/A' }}</td>
                <td>{{ $asset->monitor_serial ?? 'N/A' }}</td>
                <td>{{ $asset->keyboard_serial ?? 'N/A' }}</td>
                <td>{{ $asset->mouse_serial ?? 'N/A' }}</td>
            </tr>
        </table>

        <table>
            <tr class="bg-usc">
                <td colspan="3" style="font-weight: bold;">CARACTERISTICAS DEL EQUIPO DE COMPUTO</td>
            </tr>
            <tr class="bg-gray">
                <td>HARDWARE</td>
                <td>DESCRIPCIÓN</td>
                <td>MARCA</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold;">BOARD</td>
                <td>{{ $asset->board_model ?? 'N/A' }}</td>
                <td>{{ $asset->board_brand ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold; width: 30%;">PROCESADOR</td>
                <td>{{ $asset->cpu_model ?? 'N/A' }}</td>
                <td>{{ $asset->cpu_brand ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold;">RAM</td>
                <td>{{ $asset->ram ?? 'N/A' }}</td>
                <td>{{ $asset->ram_brand ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold;">DISCO DURO</td>
                <td>{{ $asset->storage_model ?? 'N/A' }}</td>
                <td>{{ $asset->storage_brand ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold;">TARJETA INALAMBRICA</td>
                <td>{{ $asset->wifi_model ?? 'N/A' }}</td>
                <td>{{ $asset->wifi_brand ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold; width: 30%;">TARJETA GRAFICA</td>
                <td>{{ $asset->gpu_model ?? 'N/A' }}</td>
                <td>{{ $asset->gpu_brand ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold; width: 30%;">GUAYA DE SEGURIDAD</td>
                <td>{{ $asset->security_guaya ?? 'N/A' }}</td>
                <td>N/A</td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold; width: 30%;">OTROS (SISTEMA OPERATIVO)</td>
                <td>{{ $asset->os_version ?? 'N/A' }}</td>
                <td>MICROSOFT</td>
            </tr>
        </table>

        <table>
            <tr class="bg-usc">
                <td colspan="6" style="font-weight: bold; font-size: 8.5px; letter-spacing: 0.5px;">
                    SOFTWARE DEL EQUIPO
                </td>
            </tr>
            <tr class="bg-gray" style="font-size: 7.5px;">
                <td style="width: 40%; font-weight: bold;">SOFTWARE</td>
                <td style="width: 5%; font-weight: bold;">SI</td>
                <td style="width: 5%; font-weight: bold;">NO</td>
                <td style="width: 40%; font-weight: bold;">SOFTWARE</td>
                <td style="width: 5%; font-weight: bold;">SI</td>
                <td style="width: 5%; font-weight: bold;">NO</td>
            </tr>

            @php
                $softwarePairs = ($asset->software) ? $asset->software->take(30)->chunk(2) : collect([]);
                $totalRows = 15; 
                $filledRows = count($softwarePairs);
            @endphp

            @foreach($softwarePairs as $pair)
                @php
                    $left = $pair->first();
                    $right = $pair->count() > 1 ? $pair->last() : null;
                @endphp
                <tr style="height: 18px;">
                    <td class="text-left" style="font-size: 7.5px; padding-left: 6px !important;">
                        {{ $left->name }}
                        @if($left->version && $left->version !== 'N/A')
                            <span style="color: #555; font-size: 7px;">({{ $left->version }})</span>
                        @endif
                    </td>
                    <td style="font-weight: bold; color: green; font-size: 9px;">X</td>
                    <td>&nbsp;</td>

                    <td class="text-left" style="font-size: 7.5px; padding-left: 6px !important;">
                        @if($right)
                            {{ $right->name }}
                            @if($right->version && $right->version !== 'N/A')
                                <span style="color: #555; font-size: 7px;">({{ $right->version }})</span>
                            @endif
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td style="font-weight: bold; color: green; font-size: 9px;">{!! $right ? 'X' : '&nbsp;' !!}</td>
                    <td>&nbsp;</td>
                </tr>
            @endforeach

            @for ($i = $filledRows; $i < $totalRows; $i++)
                <tr style="height: 18px;">
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                </tr>
            @endfor
        </table>

        {{-- Pie de página fijo exclusivo para la Hoja 1 --}}
        <div style="position: absolute; bottom: 0; left: 0; right: 0; text-align: right; font-size: 9px; font-weight: bold; border-top: 1px solid #000; padding-top: 4px; background-color: white;">
            SIGMA - SISTEMA DE GESTIÓN TECNOLÓGICA USC | PÁGINA 1 DE 2
        </div>
    </div>

    {{-- ════════════════════════════ PAGINA 2: HISTORIAL DE ENTRADAS A TALLER ════════════════════════════ --}}
    <div class="hoja-carta page-break">
        
        {{-- Espaciador superior nativo para empujar el segundo header --}}
        <div style="height: 15px; width: 100%; clear: both;"></div>

        <table style="margin-bottom: 12px;">
            <tr>
                <td rowspan="3" style="width: 100px; text-align: center; vertical-align: middle;">
                    @if(file_exists($logo)) <img src="{{ $logo }}" style="width: 60px; margin: 0 auto; display: block;"> @endif
                </td>
                <td style="font-size: 13px; font-weight: bold; text-align: center;">UNIVERSIDAD SANTIAGO DE CALI</td>
                <td class="bg-usc" style="width: 100px; font-weight: bold; text-align: center;">R-GT004</td>
            </tr>
            <tr>
                <td style="font-weight: bold; text-align: center;">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td>
                <td class="bg-usc" style="font-weight: bold; text-align: center;">VERSIÓN. 3</td>
            </tr>
            <tr>
                <td style="font-weight: bold; text-align: center;">SOPORTE TECNICO - HOJA DE VIDA DE EQUIPOS</td>
                <td class="bg-usc" style="font-weight: bold; text-align: center;">11 SEP 2019</td>
            </tr>
        </table>

        {{-- Quitamos el segundo header de arriba y dejamos que fluya de forma natural como una tabla independiente --}}
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <colgroup>
                <col style="width: 5%;">
                <col style="width: 5%;">
                <col style="width: 5%;">
                <col style="width: 60%;">
                <col style="width: 25%;">
            </colgroup>
            <thead>
                <tr class="bg-usc">
                    <td colspan="5" style="padding: 5px; font-weight: bold; text-align: center; font-size: 9px;">
                        REGISTRO DE DIAGNOSTICOS Y MODIFICACIONES
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="bg-gray" style="padding: 4px; font-weight: bold; text-align: center;">FECHA</td>
                    <td rowspan="2" class="bg-gray" style="padding: 4px; font-weight: bold; text-align: center; vertical-align: middle;">DESCRIPCIÓN</td>
                    <td rowspan="2" class="bg-gray" style="padding: 4px; font-weight: bold; text-align: center; vertical-align: middle;">TÉCNICO</td>
                </tr>
                <tr class="bg-gray">
                    <td style="padding: 2px; text-align: center; font-weight: bold; font-size: 8px;">DD</td>
                    <td style="padding: 2px; text-align: center; font-weight: bold; font-size: 8px;">MM</td>
                    <td style="padding: 2px; text-align: center; font-weight: bold; font-size: 8px;">AA</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $services = $asset->technicalServices ?? collect([]);
                @endphp

                @forelse($services as $m)
                    <tr>
                        <td style="padding: 4px; text-align: center;">{{ \Carbon\Carbon::parse($m->performed_at)->format('d') }}</td>
                        <td style="padding: 4px; text-align: center;">{{ \Carbon\Carbon::parse($m->performed_at)->format('m') }}</td>
                        <td style="padding: 4px; text-align: center;">{{ \Carbon\Carbon::parse($m->performed_at)->format('y') }}</td>
                        <td class="text-left" style="font-size: 8px; text-align: left; padding: 4px 6px;">{{ $m->description }}</td>
                        <td style="padding: 4px; text-align: center; font-size: 8px;">{{ $m->user->name ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 15px; text-align: center; color: #777; font-style: italic;">
                            No se registran intervenciones técnicas ni ingresos a taller para este activo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pie de página fijo exclusivo para la Hoja 2 --}}
        <div style="position: absolute; bottom: 0; left: 0; right: 0; text-align: right; font-size: 9px; font-weight: bold; border-top: 1px solid #000; padding-top: 4px; background-color: white;">
            SIGMA - SISTEMA DE GESTIÓN TECNOLÓGICA USC | PÁGINA 2 DE 2
        </div>
    </div>

</body>
</html>