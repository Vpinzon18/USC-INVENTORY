<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: letter; margin: 0; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; box-sizing: border-box; }
        
        body { font-family: Arial, sans-serif; font-size: 9px; margin: 0; padding: 40px; text-transform: uppercase; color: #000; }

        .page-break { page-break-after: always; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; word-wrap: break-word; overflow: hidden; }

        .bg-usc { background-color: #d9e1f2 !important; font-weight: bold; }
        .bg-gray { background-color: #f2f2f2 !important; font-weight: bold; }
        .text-blue { color: #002060; font-weight: bold; }
        .text-left { text-align: left !important; padding-left: 8px; }
        
        /* Clase para datos técnicos largos */
        .text-break { word-wrap: break-word; word-break: break-all; font-size: 8px !important; }
        
        .table-fixed { table-layout: fixed !important; width: 100% !important; border-collapse: collapse; }
        .col-fecha-item { width: 35px !important; }
        .col-tecnico { width: 150px !important; }
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
            <tr class="bg-usc"><td colspan="6">DATOS DEL RESPONSABLE DEL EQUIPO</td></tr>
            <tr class="bg-gray"><td colspan="2">NOMBRE COMPLETO</td><td colspan="2">N° DOCUMENTO</td><td colspan="2">DEPENDENCIA</td></tr>
            <tr>
                <td colspan="2" class="text-blue">{{ $asset->currentCustodian->full_name ?? 'N/A' }}</td>
                <td colspan="2">{{ $asset->currentCustodian->document_number ?? 'N/A' }}</td>
                <td colspan="2">{{ $asset->currentCustodian->dependency ?? 'N/A' }}</td>
            </tr>
            <tr class="bg-gray"><td>CARGO</td><td>CORREO USC</td><td>EXT</td><td>BLOQUE</td><td>PISO</td><td>UBICACIÓN</td></tr>
            <tr>
                <td>{{ $asset->currentCustodian->job_title ?? 'N/A' }}</td>
                <td style="text-transform: lowercase;">{{ $asset->currentCustodian->email ?? 'N/A' }}</td>
                <td>{{ $asset->currentCustodian->extension ?? 'N/A' }}</td>
                <td>{{ $asset->room->building->name ?? 'N/A' }}</td>
                <td>{{ $asset->room->floor ?? 'N/A' }}</td>
                <td class="text-blue">{{ $asset->room->nomenclatura ?? 'N/A' }}</td>
            </tr>
        </table>

        <table>
            <tr class="bg-usc"><td colspan="7">DATOS DEL EQUIPO Y PERIFÉRICOS</td></tr>
            <tr class="bg-gray" style="font-size: 8px;">
                <td>REFERENCIA</td><td>ACTIVO TORRE</td><td>SERIAL TORRE</td><td>ACTIVO MONIT</td><td>SERIAL MONIT</td><td>SER. TECLADO</td><td>SER. MOUSE</td>
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
            <tr class="bg-usc"><td colspan="3">CARACTERÍSTICAS DEL EQUIPO DE CÓMPUTO</td></tr>
            <tr class="bg-gray"><td>HARDWARE</td><td>DESCRIPCIÓN</td><td>MARCA</td></tr>
            <tr><td class="text-left bg-gray">PROCESADOR</td><td>{{ $asset->cpu ?? 'N/A' }}</td><td>INTEL/AMD</td></tr>
            <tr><td class="text-left bg-gray">RAM</td><td>{{ $asset->ram ?? 'N/A' }}</td><td>N/A</td></tr>
            <tr><td class="text-left bg-gray">DISCO DURO</td><td>{{ $asset->storage ?? 'N/A' }}</td><td>N/A</td></tr>
            <tr><td class="text-left bg-gray">BOARD</td><td>{{ $asset->board ?? 'N/A' }}</td><td>N/A</td></tr>
        </table>

        <table>
            <tr class="bg-usc"><td colspan="4">DETALLES TÉCNICOS ADICIONALES</td></tr>
            <tr class="bg-gray">
                <td>MAC ADDRESS</td><td>TARJETA INALÁMBRICA</td><td>TARJETA GRÁFICA</td><td>SISTEMA OPERATIVO</td>
            </tr>
            <tr>
                <td class="text-break">{{ $asset->mac_address ?? 'N/A' }}</td>
                <td class="text-break">{{ $asset->wifi_card ?? 'N/A' }}</td>
                <td class="text-break">{{ $asset->graphics_card ?? 'N/A' }}</td>
                <td class="text-break">{{ $asset->os_version ?? 'N/A' }}</td>
            </tr>
            <tr class="bg-gray"><td colspan="4">DOMINIO REGISTRADO</td></tr>
            <tr><td colspan="4" class="text-blue">{{ $asset->domain_name ?? 'N/A' }}</td></tr>
        </table>

        <div style="text-align: right; font-weight: bold; border-top: 1px solid #000; padding-top: 5px;">SOMA - PÁGINA 1 DE 2</div>
    </div>

    <div class="container">
        <div style="text-align: right; font-weight: bold; border-top: 1px solid #000; padding-top: 5px;">SOMA - PÁGINA 2 DE 2</div>
    </div>

</body>
</html>