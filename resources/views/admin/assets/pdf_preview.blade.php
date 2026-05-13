<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Vida SOMA - {{ $asset->internal_code ?? $asset->serial_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* RESET TOTAL PARA FORZAR MÁRGENES */
        * { box-sizing: border-box !important; -webkit-print-color-adjust: exact; }
        
        body { 
            background-color: #525659; /* Fondo gris oscuro para resaltar las hojas blancas */
            margin: 0; 
            padding: 40px 0; 
            font-family: Arial, sans-serif; 
        }

        /* CONFIGURACIÓN ESTRICTA TAMAÑO CARTA */
        .hoja-carta { 
            background-color: white !important; 
            width: 21.59cm !important; 
            height: 27.94cm !important; 
            margin: 0 auto 2cm auto !important; /* SEPARACIÓN REAL DE 2CM ENTRE HOJAS */
            padding: 1cm !important; 
            box-shadow: 0 0 15px rgba(0,0,0,0.5); 
            display: flex;
            flex-direction: column;
            color: black;
            overflow: hidden;
            page-break-after: always;
        }

        /* ESTILO DE TABLAS (RÉPLICA USC) */
        table { 
            width: 100% !important; 
            border-collapse: collapse !important; 
            margin-bottom: 0px !important; 
            font-size: 8px !important; 
            text-transform: uppercase; 
            table-layout: fixed !important; /* OBLIGATORIO PARA QUE LOS ANCHOS % FUNCIONEN */
        }
        
        th, td { 
            border: 1px solid #000 !important; 
            padding: 2px !important; 
            text-align: center; 
            height: 18px; 
            word-wrap: break-word; 
        }
        
        .bg-header { background-color: #d9e1f2 !important; font-weight: bold; font-size: 8.5px; }
        .text-left { text-align: left !important; padding-left: 6px !important; }
        .font-black { font-weight: 900 !important; }
        .seccion { margin-top: 6px !important; }
        
        /* FILAS DE HISTORIAL (DISTRIBUCIÓN IMAGEN 2) */
        .historial-row td { height: 22px !important; }

        @media print {
            body { background-color: white; padding: 0; }
            .hoja-carta { margin: 0 !important; box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body>

    <div class="hoja-carta">
        <table>
            <tr>
                <td rowspan="3" style="width: 15%;"><img src="{{ asset('img/logoUSC.png') }}" style="max-height: 55px; margin: 0 auto;"></td>
                <td colspan="4" class="font-bold text-sm">UNIVERSIDAD SANTIAGO DE CALI</td>
                <td style="width: 15%; font-weight: bold;">R-GT004</td>
            </tr>
            <tr>
                <td colspan="4" class="font-bold">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td>
                <td>VERSIÓN. 3</td>
            </tr>
            <tr>
                <td colspan="4" class="font-bold">SOPORTE TECNICO<br>FORMATO DE HOJA DE VIDA DE EQUIPOS DE CÓMPUTO</td>
                <td>Fecha: 11 SEP 2019</td>
            </tr>
        </table>

        <div class="seccion">
            <table>
                <tr class="bg-header"><td colspan="6">DATOS DEL RESPONSABLE DEL EQUIPO</td></tr>
                <tr class="bg-gray-100"><td colspan="2">NOMBRE COMPLETO</td><td colspan="2">N° DOCUMENTO</td><td colspan="2">DEPENDENCIA</td></tr>
                <tr class="font-bold">
                    <td colspan="2">{{ $asset->currentCustodian->full_name ?? 'N/A' }}</td>
                    <td colspan="2">{{ $asset->currentCustodian->document_number ?? 'N/A' }}</td>
                    <td colspan="2">{{ $asset->currentCustodian->dependency ?? 'N/A' }}</td>
                </tr>
                <tr class="bg-gray-100"><td>CARGO</td><td>CORREO USC</td><td>EXT</td><td>BLOQUE</td><td>PISO</td><td>UBICACIÓN</td></tr>
                <tr>
                    <td>{{ $asset->currentCustodian->job_title ?? 'N/A' }}</td>
                    <td>{{ $asset->currentCustodian->email ?? 'N/A' }}</td>
                    <td>{{ $asset->currentCustodian->extension ?? 'N/A' }}</td>
                    <td>{{ $asset->room->building->name ?? 'N/A' }}</td>
                    <td>{{ $asset->room->floor ?? 'N/A' }}</td>
                    <td class="font-black">{{ $asset->room->nomenclatura ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="seccion">
            <table>
                <tr class="bg-header"><td colspan="7">DATOS DEL EQUIPO Y PERIFERICOS</td></tr>
                <tr class="bg-gray-100 text-[7px]">
                    <td>REFERENCIA / MARCA</td><td>ACTIVO TORRE</td><td>SERIAL TORRE</td><td>ACTIVO MONITOR</td><td>SERIAL MONITOR</td><td>SERIAL TECLADO</td><td>SERIAL MOUSE</td>
                </tr>
                <tr>
                    <td>{{ $asset->hostname ?? 'N/A' }}</td>
                    <td class="font-bold">{{ $asset->internal_code ?? 'N/A' }}</td>
                    <td class="font-bold">{{ $asset->serial_number ?? 'N/A' }}</td>
                    <td>{{ $asset->monitor_asset ?? 'N/A' }}</td>
                    <td>{{ $asset->monitor_serial ?? 'N/A' }}</td>
                    <td>{{ $asset->keyboard_serial ?? 'N/A' }}</td>
                    <td>{{ $asset->mouse_serial ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="seccion">
            <table>
                <tr class="bg-header"><td colspan="3">CARACTERISTICAS DEL EQUIPO DE COMPUTO</td></tr>
                <tr class="bg-gray-100"><td>HARDWARE</td><td>DESCRIPCIÓN</td><td>MARCA</td></tr>
                <tr><td class="text-left font-bold" style="width: 30%;">BOARD</td><td>{{ $asset->board ?? 'N/A' }}</td><td>N/A</td></tr>
                <tr><td class="text-left font-bold">PROCESADOR</td><td>{{ $asset->cpu ?? 'N/A' }}</td><td>INTEL/AMD</td></tr>
                <tr><td class="text-left font-bold">RAM</td><td>{{ $asset->ram ?? 'N/A' }}</td><td>N/A</td></tr>
                <tr><td class="text-left font-bold">DISCO DURO</td><td>{{ $asset->storage ?? 'N/A' }}</td><td>N/A</td></tr>
            </table>
        </div>

        <div class="seccion">
            <table>
                <tr class="bg-header"><td colspan="6">SOFTWARE DEL EQUIPO</td></tr>
                <tr class="bg-gray-100">
                    <td style="width: 30%;">SOFTWARE</td><td style="width: 20%;">INSTALACIÓN</td><td colspan="2">AUTORIZADO</td><td style="width: 30%;">SOFTWARE</td><td style="width: 20%;">INSTALACIÓN</td>
                </tr>
                @for ($i = 0; $i < 6; $i++)
                <tr><td>&nbsp;</td><td>&nbsp;</td><td style="width: 5%;">SI</td><td style="width: 5%;">NO</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                @endfor
            </table>
        </div>

        <div style="margin-top: auto; text-align: right; font-size: 8px;">PÁGINA 1 DE 2</div>
    </div>

    <div class="hoja-carta">
        <table>
            <tr>
                <td rowspan="3" style="width: 15%;"><img src="{{ asset('img/logoUSC.png') }}" style="max-height: 55px; margin: 0 auto;"></td>
                <td colspan="4" class="font-bold text-sm">UNIVERSIDAD SANTIAGO DE CALI</td>
                <td style="width: 15%; font-weight: bold;">R-GT004</td>
            </tr>
            <tr>
                <td colspan="4" class="font-bold">DEPARTAMENTO DE GESTIÓN TECNOLÓGICA</td>
                <td>VERSIÓN. 3</td>
            </tr>
            <tr>
                <td colspan="4" class="font-bold">SOPORTE TECNICO<br>FORMATO DE HOJA DE VIDA DE EQUIPOS DE CÓMPUTO</td>
                <td>FECHA</td>
            </tr>
        </table>

        <div class="seccion">
            <table>
                <tr class="bg-header"><td colspan="5">HISTORIAL DE EQUIPO TECNOLOGICO</td></tr>
                <tr class="bg-gray-100 font-bold">
                    <td colspan="2" style="width: 40%;">ACTIVO DEL EQUIPO</td>
                    <td colspan="3" style="width: 60%;">SERIAL DEL EQUIPO</td>
                </tr>
                <tr>
                    <td colspan="2" class="font-bold text-blue-800" style="font-size: 10px;">{{ $asset->internal_code ?? 'N/A' }}</td>
                    <td colspan="3" class="font-bold text-blue-800" style="font-size: 10px;">{{ $asset->serial_number ?? 'N/A' }}</td>
                </tr>
                
                <tr class="bg-header">
                    <td colspan="5">REGISTRO DE DIAGNOSTICOS, CAMBIOS, MODIFICACIONES O INGRESOS A TALLER</td>
                </tr>
                <tr>
                    <td colspan="3" class="bg-gray-100 font-bold" style="width: 15%; height: 12px;">FECHA</td>
                    <td rowspan="2" class="bg-gray-100 font-bold" style="width: 55%;">DESCRIPCIÓN</td>
                    <td rowspan="2" class="bg-gray-100 font-bold" style="width: 30%;">NOMBRE DEL TÉCNICO</td>
                </tr>
                <tr class="bg-gray-100 font-bold">
                    <td style="width: 5%;">DD</td>
                    <td style="width: 5%;">MM</td>
                    <td style="width: 5%;">AA</td>
                </tr>

                @php 
                    $maxRows = 24; 
                    $services = $asset->technicalServices; 
                    $count = count($services);
                @endphp

                @foreach($services as $m)
                <tr class="historial-row">
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->performed_at)->format('y') }}</td>
                    <td class="text-left" style="font-size: 7.5px;">{{ $m->description }}</td>
                    <td>{{ $m->user->name ?? 'N/A' }}</td>
                </tr>
                @endforeach

                @for ($i = $count; $i < $maxRows; $i++)
                <tr class="historial-row">
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                </tr>
                @endfor
            </table>
        </div>
        
        <div style="margin-top: auto; text-align: right; font-size: 8px; border-top: 1px solid black; padding-top: 4px;">
            SOMA - SISTEMA DE GESTIÓN TECNOLÓGICA USC | PÁGINA 2 DE 2
        </div>
    </div>

</body>
</html>