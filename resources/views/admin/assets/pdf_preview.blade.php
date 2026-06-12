<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hoja de Vida SIGMA - {{ $asset->internal_code ?? $asset->serial_number }}</title>
    <style>
        /* 1. CONFIGURACIÓN GLOBAL DE IMPRESIÓN (Corrige el pegado al borde superior) */
        @page {
            margin-top: 1.5cm;    /* Separa el encabezado del borde físico de la hoja impresa o PDF */
            margin-bottom: 1.2cm;
            margin-left: 1.2cm;
            margin-right: 1.2cm;
        }

        /* 2. Fondo gris para la simulación en pantalla */
        body {
            background-color: #525659 !important;
            margin: 0;
            padding: 40px 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* 3. Definición del contenedor de la hoja */
        .hoja-carta {
            background-color: white !important;
            width: 21.59cm !important;
            min-height: 27.94cm !important;
            margin: 0 auto 2cm auto !important;
            padding: 1.2cm 1.2cm !important; /* Incrementado para dar más aire interno al formato */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            color: black;
            overflow: hidden;
            position: relative;
        }

        /* 4. Ajuste para que la página impresa sea limpia */
        @media print {
            @page {
                margin-top: 1.5cm; /* Reafirma la separación en el controlador de impresión */
            }
            
            body {
                background-color: white !important;
                padding: 0 !important;
            }

            .hoja-carta {
                margin: 0 !important;
                padding: 0 !important; /* El margen lo controla @page, evitamos duplicación de espacio */
                box-shadow: none !important;
                border: none !important;
            }
        }

        /* 5. Estilos de tabla del formato R-GT004 */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin-bottom: 6px;
            font-size: 8px;
            text-transform: uppercase;
        }

        td, th {
            border: 1px solid #000 !important;
            padding: 4px 3px !important; /* Un poco más de respiro vertical interno en las celdas */
            text-align: center;
            vertical-align: middle;
        }

        .bg-header {
            background-color: #d9e1f2 !important;
            font-weight: bold;
            font-size: 8.5px;
        }

        .bg-sub {
            background-color: #f2f2f2 !important;
            font-weight: bold;
        }

        .text-left {
            text-align: left !important;
            padding-left: 6px !important;
        }

        .blue-val {
            color: #0000FF;
            font-weight: bold;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="hoja-carta">
        <table>
            <colgroup>
                <col style="width: 15%;">
                <col style="width: 70%;">
                <col style="width: 15%;">
            </colgroup>
            <tr>
                <td rowspan="3">
                    <img src="{{ asset('img/logoUSC.png') }}" style="max-height: 55px; margin: 0 auto; display: block;">
                </td>
                <td style="font-size: 12px; font-weight: bold;">UNIVERSIDAD SANTIAGO DE CALI</td>
                <td class="bg-header">R-GT004</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td>
                <td class="bg-header">VERSIÓN. 3</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">SOPORTE TECNICO<br>FORMATO DE HOJA DE VIDA DE EQUIPOS DE CÓMPUTO</td>
                <td class="bg-header">11 SEP 2019</td>
            </tr>
        </table>

        <table>
            <tr class="bg-header">
                <td colspan="6">DATOS DEL RESPONSABLE DEL EQUIPO</td>
            </tr>
            <tr class="bg-sub">
                <td colspan="2">NOMBRE COMPLETO</td>
                <td colspan="2">N° DOCUMENTO</td>
                <td colspan="2">DEPENDENCIA</td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="2">{{ $asset->currentCustodian->full_name ?? 'N/A' }}</td>
                <td colspan="2">{{ $asset->currentCustodian->document_number ?? 'N/A' }}</td>
                <td class="px-2 py-1">
                    {{ $asset->currentCustodian->dependency->name ?? 'N/A' }}
                </td>
            </tr>
            <tr class="bg-sub">
                <td>CARGO</td>
                <td>CORREO USC</td>
                <td>EXT</td>
                <td>BLOQUE</td>
                <td>PISO</td>
                <td>UBICACIÓN</td>
            </tr>
            <tr>
                <td class="px-2 py-1">
                    {{ $asset->currentCustodian->jobTitle->name ?? 'N/A' }}
                </td>
                <td>{{ $asset->currentCustodian->email ?? 'N/A' }}</td>
                <td>{{ $asset->currentCustodian->extension ?? 'N/A' }}</td>
                <td>{{ $asset->room->building->name ?? 'N/A' }}</td>
                <td>{{ $asset->room->floor ?? 'N/A' }}</td>
                <td style="font-weight: 900;">{{ $asset->room->nomenclatura ?? 'N/A' }}</td>
            </tr>
        </table>

        <table>
            <tr class="bg-header">
                <td colspan="7">DATOS DEL EQUIPO Y PERIFERICOS</td>
            </tr>
            <tr class="bg-sub" style="font-size: 7.5px;">
                <td>MARCA/REF</td>
                <td>ACTIVO TORRE</td>
                <td>SERIAL TORRE</td>
                <td>ACTIVO MONIT</td>
                <td>SERIAL MONIT</td>
                <td>SERIAL TECLADO</td>
                <td>SERIAL MOUSE</td>
            </tr>
            <tr>
                <td>{{ $asset->hostname ?? 'N/A' }}</td>
                <td style="font-weight: bold;">{{ $asset->internal_code ?? 'N/A' }}</td>
                <td style="font-weight: bold;">{{ $asset->serial_number ?? 'N/A' }}</td>
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

        <div style="margin-top: auto; text-align: right; font-size: 8px; font-weight: bold;">SIGMA - PÁGINA 1 DE 2</div>
    </div>

    <div class="hoja-carta">
        <table>
            <colgroup>
                <col style="width: 15%;">
                <col style="width: 70%;">
                <col style="width: 15%;">
            </colgroup>
            <tr>
                <td rowspan="3"><img src="{{ asset('img/logoUSC.png') }}" style="max-height: 55px; margin: 0 auto; display: block;"></td>
                <td style="font-size: 12px; font-weight: bold;">UNIVERSIDAD SANTIAGO DE CALI</td>
                <td class="bg-header">R-GT004</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td>
                <td class="bg-header">VERSIÓN. 3</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">SOPORTE TECNICO - HOJA DE VIDA</td>
                <td class="bg-header">FECHA</td>
            </tr>
        </table>

        <table>
            <colgroup>
                <col style="width: 4%;">
                <col style="width: 4%;">
                <col style="width: 4%;">
                <col style="width: 63%;">
                <col style="width: 25%;">
            </colgroup>
            <tr class="bg-header">
                <td colspan="5">HISTORIAL DE EQUIPO TECNOLOGICO</td>
            </tr>
            <tr class="bg-sub">
                <td colspan="3">ACTIVO DEL EQUIPO</td>
                <td colspan="2">SERIAL DEL EQUIPO</td>
            </tr>
            <tr>
                <td colspan="3" class="blue-val">{{ $asset->internal_code ?? 'N/A' }}</td>
                <td colspan="2" class="blue-val">{{ $asset->serial_number ?? 'N/A' }}</td>
            </tr>
            <tr class="bg-header">
                <td colspan="5">REGISTRO DE DIAGNOSTICOS, CAMBIOS, MODIFICACIONES O INGRESOS A TALLER</td>
            </tr>
            <tr>
                <td colspan="3" class="bg-sub">FECHA</td>
                <td rowspan="2" class="bg-sub">DESCRIPCIÓN</td>
                <td rowspan="2" class="bg-sub">NOMBRE DEL TÉCNICO</td>
            </tr>
            <tr class="bg-sub">
                <td>DD</td>
                <td>MM</td>
                <td>AA</td>
            </tr>
            @php
            $maxUnits = 24;
            $usedUnits = 0;
            $services = $asset->technicalServices;
            @endphp
            @foreach($services as $m)
            @php $usedUnits++; @endphp
            <tr class="fila-historial">
                <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('d') }}</td>
                <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('m') }}</td>
                <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('y') }}</td>
                <td class="text-left" style="font-size: 8px;">{{ $m->description }}</td>
                <td>{{ $m->user->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
            @for ($i = $usedUnits; $i < $maxUnits; $i++)
                <tr class="fila-vacia">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                @endfor
        </table>
        <div style="margin-top: auto; text-align: right; font-size: 9px; font-weight: bold; border-top: 1px solid black; padding-top: 5px;">
            SIGMA  - SISTEMA DE GESTIÓN TECNOLÓGICA USC | PÁGINA 2 DE 2
        </div>
    </div>
</body>

</html>