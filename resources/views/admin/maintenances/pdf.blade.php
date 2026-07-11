<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Mantenimiento R-GT-051</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            background-color: #fff;
            width: 100%;
            padding: 20px; /* Margen para que no quede pegado al borde del PDF */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1.5px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 2px;
        }

        /* 1. Cabecera Institucional */
        .header-table { margin-bottom: 5px; }
        .logo-cell { width: 15%; font-weight: 900; font-size: 14pt; line-height: 1.1; }
        .logo-cell span { font-size: 7pt; display: block; margin-top: 2px; }
        .title-cell { width: 63%; font-weight: 900; font-size: 11pt; letter-spacing: 0.5px; }
        .title-cell span { display: block; font-size: 10pt; margin-top: 2px; color: #222; }
        .meta-cell { width: 22%; font-weight: bold; font-size: 8.5pt; text-align: left; padding-left: 8px; line-height: 1.4; }

        /* 2. Cuadrícula Principal */
        .main-grid th { background-color: #ffffff; font-weight: bold; font-size: 8pt; text-transform: uppercase; }
        
        .main-grid th.sub-header {
            font-size: 7.5pt; 
            padding: 2px 1px;
            letter-spacing: normal; 
        }

        /* ANCHOS REDISEÑADOS MATEMÁTICAMENTE PARA SOMA */
        .col-dia { width: 3.5%; }
        .col-mes { width: 3.5%; }
        .col-ano { width: 3.5%; }
        .col-bloque { width: 8.5%; } 
        .col-piso { width: 4.5%; }    

        .col-dependencia { width: 15%; } 
        .col-activo { width: 8.5%; }   
        .col-descripcion { width: 29%; }
        .col-tecnico { width: 12%; }
        .col-usuario { width: 12%; }

        .data-row { height: 38px; }
        .data-row td { font-size: 8pt; overflow: hidden; word-wrap: break-word; }

        .text-left { text-align: left !important; padding-left: 5px !important; font-size: 7.5pt !important; }
        .text-bold { font-weight: bold; }
        .text-mono { font-family: monospace; font-size: 9pt; font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-cell">USC<span>UNIVERSIDAD SANTIAGO DE CALI</span></td>
            <td class="title-cell">REGISTRO DE MANTENIMIENTO PREVENTIVO<span>UNIVERSIDAD SANTIAGO DE CALI</span><span>GESTIÓN TECNOLÓGICA</span></td>
            <td class="meta-cell">CÓDIGO: R-GT-051<br>VERSIÓN: 1<br>FECHA: 23 OCTUBRE DE 2017</td>
        </tr>
    </table>

    <table class="main-grid">
        <thead>
            <tr>
                <th colspan="3">FECHA</th>
                <th colspan="2">UBICACIÓN</th>
                <th rowspan="2" class="col-dependencia">DEPENDENCIA / PROGRAMA</th>
                <th rowspan="2" class="col-activo">N° ACTIVO</th>
                <th rowspan="2" class="col-descripcion">DESCRIPCIÓN ACTIVIDAD</th>
                <th rowspan="2" class="col-tecnico">TÉCNICO RESPONSABLE</th>
                <th rowspan="2" class="col-usuario">USUARIO RESPONSABLE</th>
            </tr>
            <tr>
                <th class="col-dia">Día</th>
                <th class="col-mes">Mes</th>
                <th class="col-ano sub-header">AÑO</th>
                <th class="col-bloque sub-header">BLOQUE</th>
                <th class="col-piso sub-header">PISO</th>
            </tr>
        </thead>
        <tbody>
            
            @php 
                $fecha = \Carbon\Carbon::parse($service->performed_at);
                
                // Extraer número de bloque (ej. "Bloque 3" -> "3")
                $buildingName = $service->asset->room->building->name ?? '';
                $bloqueNumero = preg_replace('/[^0-9]/', '', $buildingName);

                // Dependencia
                $dependencia = $service->asset->room->name ?? 'SIN DEPENDENCIA';
            @endphp
            
            <tr class="data-row">
                <td>{{ $fecha->format('d') }}</td>
                <td>{{ $fecha->format('m') }}</td>
                <td>{{ $fecha->format('y') }}</td>
                
                <td class="text-bold text-mono">{{ $bloqueNumero ?: 'N/A' }}</td>
                <td>{{ $service->asset->room->piso ?? '1' }}</td>
                
                <td class="text-bold text-mono" style="font-size: 7.5pt;">{{ strtoupper($dependencia) }}</td>
                
                <td class="text-mono">{{ $service->asset->internal_code ?? $service->asset->serial_number }}</td>
                
                <td class="text-left">{{ Str::limit(strip_tags($service->description), 250) }}</td>
                
                <td class="text-bold">{{ $service->technician->name ?? '' }}</td>
                <td></td> </tr>

            @for ($i = 0; $i < 15; $i++)
            <tr class="data-row">
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td>
            </tr>
            @endfor

        </tbody>
    </table>

</body>
</html>