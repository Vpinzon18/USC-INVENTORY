<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: letter;
            margin: 0;
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
            padding: 40px;
            text-transform: uppercase;
            color: #000;
        }

        .page-break {
            page-break-after: always;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            overflow: hidden;
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

        /* Clase para datos técnicos largos */
        .text-break {
            word-wrap: break-word;
            word-break: break-all;
            font-size: 8px !important;
        }

        .table-fixed {
            table-layout: fixed !important;
            width: 100% !important;
            border-collapse: collapse;
        }

        .col-fecha-item {
            width: 35px !important;
        }

        .col-tecnico {
            width: 150px !important;
        }
    </style>
</head>

<body>

    @php $logo = public_path('img/logoUSC.png'); @endphp

    <div class="page-break">
        <table>
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
                <td class="px-2 py-1"> {{ $asset->currentCustodian->dependency->name ?? 'N/A' }}</td>
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
            <tr class="bg-header">
                <td colspan="3">CARACTERISTICAS DEL EQUIPO DE COMPUTO</td>
            </tr>
            <tr class="bg-sub">
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
                <td>{{ $asset->ram_brand}}</td>
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
                <td></td>
            </tr>
            <tr>
                <td class="text-left" style="font-weight: bold; width: 30%;">OTROS</td>
                <td>{{ $asset->os_version ?? 'N/A' }}</td>
                <td>MICROSOFT</td>
            </tr>
        </table>

        <table>
            <tr class="bg-header">
                <td colspan="6" style="font-weight: bold; font-size: 8.5px; letter-spacing: 0.5px;">
                    SOFTWARE DEL EQUIPO
                </td>
            </tr>

            <tr class="bg-sub" style="font-size: 7.5px;">
                <td style="width: 40%; font-weight: bold;">SOFTWARE</td>
                <td style="width: 5%; font-weight: bold;">SI</td>
                <td style="width: 5%; font-weight: bold;">NO</td>
                <td style="width: 40%; font-weight: bold;">SOFTWARE</td>
                <td style="width: 5%; font-weight: bold;">SI</td>
                <td style="width: 5%; font-weight: bold;">NO</td>
            </tr>

            @php
            // Tomamos máximo 30 aplicaciones y las dividimos en parejas (15 a la izquierda, 15 a la derecha)
            $softwarePairs = ($asset->software) ? $asset->software->take(30)->chunk(2) : collect([]);
            $totalRows = 15; // Límite exacto de 15 filas hacia abajo
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
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                @endfor
        </table>

        <div>
            <table>
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
                <colgroup>
                    <col style="width: 5%;">
                    <col style="width: 5%;">
                    <col style="width: 5%;">
                    <col style="width: 60%;">
                    <col style="width: 25%;">
                </colgroup>

                <tr class="bg-header">
                    <td colspan="5">REGISTRO DE DIAGNOSTICOS Y MODIFICACIONES</td>
                </tr>
                <tr>
                    <td colspan="3" class="bg-sub">FECHA</td>
                    <td rowspan="2" class="bg-sub">DESCRIPCIÓN</td>
                    <td rowspan="2" class="bg-sub">TÉCNICO</td>
                </tr>
                <tr class="bg-sub">
                    <td>DD</td>
                    <td>MM</td>
                    <td>AA</td>
                </tr>

                @php
                $services = $asset->technicalServices ?? collect([]);
                $maxRows = 25;
                @endphp

                @foreach($services as $m)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('y') }}</td>
                    <td class="text-left" style="font-size: 8px;">{{ $m->description }}</td>
                    <td style="font-size: 8px;">{{ $m->user->name ?? 'N/A' }}</td>
                </tr>
                @endforeach

                @for ($i = count($services); $i < $maxRows; $i++)
                    <tr style="height: 20px;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>
                    @endfor
            </table>

            <div style="margin-top: 10px; text-align: right; font-size: 9px; font-weight: bold;">
                SIGMA - SISTEMA DE GESTIÓN TECNOLÓGICA USC | PÁGINA 2 DE 2
            </div>
        </div>

</body>

</html>