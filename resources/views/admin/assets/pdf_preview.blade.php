<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Vida SOMA - {{ $asset->internal_code ?? $asset->serial_number }}</title>
    <style>
        /* --- CONFIGURACIÓN TÉCNICA INSTITUCIONAL SOMA --- */
        
        /* 1. Compatibilidad de Impresión y Color */
        * { 
            box-sizing: border-box !important; 
            -webkit-print-color-adjust: exact !important; /* Chrome, Safari, Edge */
            print-color-adjust: exact !important;         /* Estándar Firefox */
        }

        body { 
            background-color: #525659; /* Fondo de contraste para previsualización web */
            margin: 0; 
            padding: 40px 0; 
            font-family: Arial, Helvetica, sans-serif; 
        }

        /* 2. Definición Estricta de Hoja Carta (Letter) */
        .hoja-carta { 
            background-color: white !important; 
            width: 21.59cm !important; 
            min-height: 27.94cm !important; 
            margin: 0 auto 2cm auto !important; /* Separación de 2cm entre hojas en el navegador */
            padding: 0.8cm 1cm !important; 
            box-shadow: 0 0 20px rgba(0,0,0,0.5); 
            display: flex;
            flex-direction: column;
            color: black;
            overflow: hidden;
            page-break-after: always;
        }

        /* 3. Estilos de Tabla (Réplica Formato R-GT004 USC) */
        table { 
            width: 100% !important; 
            border-collapse: collapse !important; 
            table-layout: fixed !important; /* Fuerza el respeto a los anchos % fijos */
            margin-bottom: 6px;
            font-size: 8px;
            text-transform: uppercase;
        }
        
        td, th { 
            border: 1px solid #000 !important; 
            padding: 3px !important; 
            text-align: center; 
            word-wrap: break-word; /* Permite que textos largos bajen al siguiente renglón */
            vertical-align: middle;
        }

        .bg-header { background-color: #d9e1f2 !important; font-weight: bold; font-size: 8.5px; }
        .bg-sub { background-color: #f2f2f2 !important; font-weight: bold; }
        .text-left { text-align: left !important; padding-left: 6px !important; }
        .blue-val { color: #0000FF; font-weight: bold; font-size: 10px; }
        
        /* Altura base de las filas para el cálculo de espacio */
        .fila-historial { height: auto; min-height: 22px; }
        .fila-vacia { height: 22px; color: transparent; }

        @media print {
            body { background-color: white; padding: 0; }
            .hoja-carta { margin: 0 !important; box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body>

    <div class="hoja-carta">
        <table>
            <colgroup><col style="width: 15%;"><col style="width: 70%;"><col style="width: 15%;"></colgroup>
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
            <tr class="bg-header"><td colspan="6">DATOS DEL RESPONSABLE DEL EQUIPO</td></tr>
            <tr class="bg-sub"><td colspan="2">NOMBRE COMPLETO</td><td colspan="2">N° DOCUMENTO</td><td colspan="2">DEPENDENCIA</td></tr>
            <tr style="font-weight: bold;">
                <td colspan="2">{{ $asset->currentCustodian->full_name ?? 'N/A' }}</td>
                <td colspan="2">{{ $asset->currentCustodian->document_number ?? 'N/A' }}</td>
                <td colspan="2">{{ $asset->currentCustodian->dependency ?? 'N/A' }}</td>
            </tr>
            <tr class="bg-sub"><td>CARGO</td><td>CORREO USC</td><td>EXT</td><td>BLOQUE</td><td>PISO</td><td>UBICACIÓN</td></tr>
            <tr>
                <td>{{ $asset->currentCustodian->job_title ?? 'N/A' }}</td>
                <td>{{ $asset->currentCustodian->email ?? 'N/A' }}</td>
                <td>{{ $asset->currentCustodian->extension ?? 'N/A' }}</td>
                <td>{{ $asset->room->building->name ?? 'N/A' }}</td>
                <td>{{ $asset->room->floor ?? 'N/A' }}</td>
                <td style="font-weight: 900;">{{ $asset->room->nomenclatura ?? 'N/A' }}</td>
            </tr>
        </table>

        <table>
            <tr class="bg-header"><td colspan="7">DATOS DEL EQUIPO Y PERIFERICOS</td></tr>
            <tr class="bg-sub" style="font-size: 7.5px;">
                <td>REFERENCIA / MARCA</td><td>ACTIVO TORRE</td><td>SERIAL TORRE</td><td>ACTIVO MONITOR</td><td>SERIAL MONITOR</td><td>SERIAL TECLADO</td><td>SERIAL MOUSE</td>
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
            <tr class="bg-header"><td colspan="3">CARACTERISTICAS DEL EQUIPO DE COMPUTO</td></tr>
            <tr class="bg-sub"><td>HARDWARE</td><td>DESCRIPCIÓN</td><td>MARCA</td></tr>
            <tr><td class="text-left" style="font-weight: bold; width: 30%;">PROCESADOR</td><td>{{ $asset->cpu ?? 'N/A' }}</td><td>INTEL/AMD</td></tr>
            <tr><td class="text-left" style="font-weight: bold;">RAM</td><td>{{ $asset->ram ?? 'N/A' }}</td><td>N/A</td></tr>
            <tr><td class="text-left" style="font-weight: bold;">DISCO DURO</td><td>{{ $asset->storage ?? 'N/A' }}</td><td>N/A</td></tr>
            <tr><td class="text-left" style="font-weight: bold;">BOARD</td><td>{{ $asset->board ?? 'N/A' }}</td><td>N/A</td></tr>
        </table>

        <table>
            <tr class="bg-header"><td colspan="6">SOFTWARE DEL EQUIPO</td></tr>
            <tr class="bg-sub">
                <td style="width: 30%;">SOFTWARE</td><td style="width: 20%;">INSTALACIÓN</td><td colspan="2">AUTORIZADO</td><td style="width: 30%;">SOFTWARE</td><td style="width: 20%;">INSTALACIÓN</td>
            </tr>
            @for ($i = 0; $i < 6; $i++)
            <tr><td>&nbsp;</td><td>&nbsp;</td><td style="width: 5%;">SI</td><td style="width: 5%;">NO</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            @endfor
        </table>

        <div style="margin-top: auto; text-align: right; font-size: 8px; font-weight: bold;">SOMA - PÁGINA 1 DE 2</div>
    </div>

    <div class="hoja-carta">
        <table>
            <colgroup><col style="width: 15%;"><col style="width: 70%;"><col style="width: 15%;"></colgroup>
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
                <td style="font-weight: bold;">SOPORTE TECNICO - HOJA DE VIDA</td>
                <td class="bg-header">FECHA</td>
            </tr>
        </table>

        <table>
            <colgroup>
                <col style="width: 4%;"> <col style="width: 4%;"> <col style="width: 4%;">
                <col style="width: 63%;"> <col style="width: 25%;">
            </colgroup>
            
            <tr class="bg-header"><td colspan="5">HISTORIAL DE EQUIPO TECNOLOGICO</td></tr>
            <tr class="bg-sub"><td colspan="3">ACTIVO DEL EQUIPO</td><td colspan="2">SERIAL DEL EQUIPO</td></tr>
            <tr>
                <td colspan="3" class="blue-val">{{ $asset->internal_code ?? 'N/A' }}</td>
                <td colspan="2" class="blue-val">{{ $asset->serial_number ?? 'N/A' }}</td>
            </tr>
            <tr class="bg-header"><td colspan="5">REGISTRO DE DIAGNOSTICOS, CAMBIOS, MODIFICACIONES O INGRESOS A TALLER</td></tr>
            
            <tr>
                <td colspan="3" class="bg-sub">FECHA</td>
                <td rowspan="2" class="bg-sub">DESCRIPCIÓN</td>
                <td rowspan="2" class="bg-sub">NOMBRE DEL TÉCNICO</td>
            </tr>
            <tr class="bg-sub"><td>DD</td><td>MM</td><td>AA</td></tr>

            @php 
                $maxUnits = 24; // Presupuesto de espacio vertical en la hoja
                $usedUnits = 0; 
                $services = $asset->technicalServices; 
            @endphp

            {{-- 1. Mostramos TODOS los registros del historial sin excepción --}}
            @foreach($services as $m)
                @php 
                    // Calculamos cuántas líneas ocupa cada descripción para compensar el relleno
                    $textLength = strlen($m->description);
                    $estimatedRows = max(1, ceil($textLength / 80)); 
                    $usedUnits += $estimatedRows;
                @endphp
                <tr class="fila-historial">
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('y') }}</td>
                    <td class="text-left" style="font-size: 8px;">{{ $m->description }}</td>
                    <td>{{ $m->user->name ?? 'N/A' }}</td>
                </tr>
            @endforeach

            {{-- 2. Rellenamos solo el espacio que SOBRA hasta completar la hoja carta --}}
            @for ($i = $usedUnits; $i < $maxUnits; $i++)
                <tr class="fila-vacia">
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                </tr>
            @endfor
        </table>
        
        <div style="margin-top: auto; text-align: right; font-size: 9px; font-weight: bold; border-top: 1px solid black; padding-top: 5px;">
            SOMA - SISTEMA DE GESTIÓN TECNOLÓGICA USC | PÁGINA 2 DE 2
        </div>
    </div>

</body>
</html>