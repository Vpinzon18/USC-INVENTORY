<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formato Único para el Movimiento de Activos - USC</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    @page {
      size: Letter portrait;
      margin: 0;
    }

    html, body {
      width: 216mm;
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 9px;
      color: #000;
      background: #fff;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    .page {
      width: 216mm;
      min-height: 279mm;
      margin: 0 auto;
      padding: 6mm 6mm 5mm;
      background: #fff;
      overflow: hidden;
    }

    .page-break {
      page-break-before: always;
    }

    /* ── HEADER ── */
    .header-table {
      width: 100%;
      table-layout: fixed;
      border-collapse: collapse;
      border: 1px solid #000;
      margin: 0;
    }

    .header-table td {
      border: 1px solid #000;
      vertical-align: middle;
    }

    .header-logo {
      width: 25mm;
      height: 25mm;
      text-align: center;
      padding: 2mm;
      vertical-align: middle;
    }

    .logo-box {
      width: 21mm;
      height: 21mm;
      border: 2px solid #000;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      font-weight: bold;
      font-size: 12px;
      text-align: center;
      line-height: 1.2;
    }

    .logo-usc {
      font-size: 22px;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .logo-universidad {
      font-size: 6.5px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .header-title {
      text-align: center;
      padding: 2mm 3mm;
      vertical-align: middle;
    }

    .header-title p {
      font-size: 11px;
      font-weight: bold;
      text-transform: uppercase;
      line-height: 1.45;
    }

    .header-right {
      width: 40mm;
      padding: 0;
      vertical-align: top;
    }

    .consecutivo-block {
      border-bottom: 1px solid #000;
      padding: 1.5mm 2mm 2mm;
    }

    .consecutivo-label {
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
      text-align: center;
      display: block;
    }

    .consecutivo-box {
      width: 100%;
      min-height: 10mm;
      border: 1px solid #000;
      margin-top: 1mm;
      display: table;
      text-align: center;
      vertical-align: middle;
      font-size: 9px;
      font-weight: bold;
      overflow-wrap: anywhere;
    }

    .fecha-block {
      padding: 1.5mm 2mm 0;
    }

    .fecha-table {
      width: 100%;
      table-layout: fixed;
      border-collapse: collapse;
    }

    .fecha-table td {
      border: 1px solid #000;
      padding: 1mm 0.5mm;
      text-align: center;
      vertical-align: middle;
      font-size: 8px;
      line-height: 1.15;
    }

    .fecha-table .fecha-title {
      width: 35%;
      font-weight: bold;
      text-transform: uppercase;
    }

    .fecha-table .fecha-part {
      width: 21.66%;
      font-size: 7px;
      font-weight: bold;
    }

    .fecha-table .fecha-value {
      font-size: 9px;
      font-weight: bold;
    }

    .fecha-label {
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
      margin-right: 3px;
    }

    .version-text {
      font-size: 6px;
      line-height: 1.2;
      text-align: center;
      padding: 1mm 1mm 1.5mm;
      color: #000;
      white-space: nowrap;
    }

    /* ── SECTION HEADERS (black bar) ── */
    .section-header {
      background-color: #000;
      color: #fff;
      font-weight: bold;
      font-size: 9px;
      text-transform: uppercase;
      text-align: center;
      padding: 1.2mm 1mm;
      border: 1px solid #000;
      letter-spacing: 0.25px;
      page-break-after: avoid;
    }

    /* ── TIPO DE MOVIMIENTO ── */
    .tipo-table {
      width: 100%;
      border-collapse: collapse;
    }

    .tipo-table td {
      border: 1px solid #000;
      padding: 3px 6px;
      vertical-align: middle;
      font-size: 9.5px;
    }

    .checkbox-cell {
      width: 14px;
      border: 1px solid #000 !important;
      height: 14px;
      padding: 0 !important;
      vertical-align: middle;
      text-align: center;
      font-weight: bold;
    }

    .otro-line {
      display: inline-block;
      width: 80px;
      border-bottom: 1px solid #000;
      vertical-align: bottom;
      margin-left: 3px;
    }

    /* ── SEDE ── */
    .sede-table {
      width: 100%;
      border-collapse: collapse;
    }

    .sede-table td {
      border: 1px solid #000;
      padding: 3px 6px;
      font-size: 9.5px;
      vertical-align: middle;
    }

    .sede-checkbox {
      width: 14px;
      border: 1px solid #000 !important;
      height: 14px;
      padding: 0 !important;
      text-align: center;
      vertical-align: middle;
      font-weight: bold;
    }

    .field-label {
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .data-value {
      font-size: 10px;
      font-weight: bold;
      margin-top: 2px;
    }

    /* ── ACTIVOS FIJOS TABLE ── */
    .activos-table {
      width: 100%;
      border-collapse: collapse;
    }

    .activos-table td,
    .activos-table th {
      border: 1px solid #000;
      padding: 3px 5px;
      vertical-align: middle;
      font-size: 9.5px;
    }

    .activos-table th {
      font-weight: bold;
      text-align: center;
      background-color: #fff;
      font-size: 9px;
      text-transform: uppercase;
    }

    /* ── CLAUSULA ── */
    .clausula-text {
      border: 1px solid #000;
      border-top: 0;
      padding: 5px 7px;
      font-size: 7.5px;
      line-height: 1.4;
      text-align: justify;
    }

    /* ── FIRMAS ── */
    .firmas-table {
      width: 100%;
      border-collapse: collapse;
    }

    .firmas-table td {
      border: 1px solid #000;
      width: 33.33%;
      vertical-align: top;
      padding: 0;
    }

    .firma-header {
      background-color: #000;
      color: #fff;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 8.5px;
      text-align: center;
      padding: 4px 5px;
      line-height: 1.4;
    }

    .firma-space {
      height: 75px;
      display: block;
    }

    /* Anexo Styles */
    .anexo-title-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .anexo-title-table td {
      vertical-align: middle;
    }
    .anexo-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }
    .anexo-table th {
      background-color: #000;
      color: #fff;
      font-weight: bold;
      text-transform: uppercase;
      padding: 6px;
      border: 1px solid #000;
      font-size: 9px;
      text-align: center;
    }
    .anexo-table td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
    }

    table, tr, td, th, .section-header, .clausula-text, .obs-container, .firmas-table {
      page-break-inside: avoid;
    }

    @media print {
      html, body {
        width: 216mm;
        margin: 0 !important;
        padding: 0 !important;
      }
      .page {
        width: 216mm;
        min-height: 279mm;
        padding: 6mm 6mm 5mm;
        margin: 0;
        overflow: hidden;
      }
      .page-break {
        page-break-before: always;
        break-before: page;
      }
    }
  </style>
</head>

<body>
  <div class="page">

    <table class="header-table">
      <tr>
        <td class="header-logo">
          <div class="logo-box">
            <span class="logo-usc">USC</span>
            <span class="logo-universidad">UNIVERSIDAD<br>SANTIAGO<br>DE CALI</span>
          </div>
        </td>

        <td class="header-title">
          <p>FORMATO UNICO PARA</p>
          <p>EL MOVIMIENTO</p>
          <p>DE ACTIVOS</p>
        </td>

        <td class="header-right">
          <div class="consecutivo-block">
            <span class="consecutivo-label">CONSECUTIVO No.</span>
            <div class="consecutivo-box">{{ $actaNumber }}</div>
          </div>
          <div class="fecha-block">
            <table class="fecha-table">
              <tr>
                <td class="fecha-title" rowspan="2">FECHA</td>
                <td class="fecha-part">DD</td>
                <td class="fecha-part">MM</td>
                <td class="fecha-part">AA</td>
              </tr>
              <tr>
                <td class="fecha-value">{{ date('d') }}</td>
                <td class="fecha-value">{{ date('m') }}</td>
                <td class="fecha-value">{{ date('y') }}</td>
              </tr>
            </table>
          </div>
          <div class="version-text">R-AF001/Versión 2/12 de junio de 2019</div>
        </td>
      </tr>
    </table>

    @php
      $movType = $assets->first()->assignments->where('acta_number', $actaNumber)->first()->movement_type ?? '';
    @endphp

    <table class="tipo-table">
      <tr>
        <td class="checkbox-cell">{{ $movType == 'TRASLADO ASIGNACION' ? 'X' : '' }}</td>
        <td>TRASLADO EN CALIDAD DE ASIGNACION</td>

        <td class="checkbox-cell">{{ $movType == 'PRESTAMO FUERA USC' ? 'X' : '' }}</td>
        <td>PRESTAMO FUERA DE LAS INSTALACIONES DE LA USC</td>
      </tr>
      <tr>
        <td class="checkbox-cell">{{ $movType == 'REPARACION DENTRO USC' ? 'X' : '' }}</td>
        <td>TRASLADO EN CALIDAD DE REPARACION DENTRO DE LA USC</td>

        <td class="checkbox-cell">{{ $movType == 'ASIGNACION INICIAL' ? 'X' : '' }}</td>
        <td>ASIGNACION INICIAL</td>
      </tr>
      <tr>
        <td class="checkbox-cell">{{ $movType == 'REPARACION FUERA USC' ? 'X' : '' }}</td>
        <td>TRASLADO EN CALIDAD DE REPARACION FUERA DE LA USC</td>

        <td class="checkbox-cell">{{ $movType == 'OTRO' ? 'X' : '' }}</td>
        <td>OTRO: <span class="otro-line">{{ $movType == 'OTRO' ? 'X' : '' }}</span></td>
      </tr>
      <tr>
        <td class="checkbox-cell">{{ $movType == 'PRESTAMO DENTRO USC' ? 'X' : '' }}</td>
        <td colspan="3">PRESTAMO DENTRO DE LAS INSTALACIONES DE LA USC</td>
      </tr>
    </table>

    @php
      $sede = strtoupper($assets->first()->assignments->where('acta_number', $actaNumber)->first()->headquarters ?? '');
    @endphp

    <div class="section-header">SEDE</div>

    <table class="sede-table">
      <tr>
        <td style="font-weight:bold; font-size:9.5px;">PAMPALINDA (CALI)</td>
        <td class="sede-checkbox">{{ $sede == 'PAMPALINDA' ? 'X' : '' }}</td>

        <td style="font-weight:bold; font-size:9.5px;">CENTRO (CALI)</td>
        <td class="sede-checkbox">{{ $sede == 'CENTRO' ? 'X' : '' }}</td>

        <td style="font-weight:bold; font-size:9.5px;">PALMIRA</td>
        <td class="sede-checkbox">{{ $sede == 'PALMIRA' ? 'X' : '' }}</td>

        <td style="font-weight:bold; font-size:9.5px;">OTRO</td>
        <td style="width:35%; font-weight:bold; padding-left:5px;">
          @if(!in_array($sede, ['PAMPALINDA', 'CENTRO', 'PALMIRA']) && $sede != '')
            {{ $sede }}
          @endif
        </td>
      </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed;">
      <tr style="background-color: #000; color: #fff; text-align: center;">
        <td colspan="4" style="padding: 4px; font-weight: bold; font-size: 10px;">ÁREA DE ORIGEN (ENTREGA)</td>
        <td colspan="4" style="padding: 4px; font-weight: bold; font-size: 10px;">ÁREA DE DESTINO (RECIBE)</td>
      </tr>

      <tr>
        <td colspan="4" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">RESPONSABLE</span>
          <div class="data-value">{{ $responsableAnterior->full_name ?? 'UNIDAD DE ACTIVOS FIJOS' }}</div>
        </td>
        <td colspan="4" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">RESPONSABLE</span>
          <div class="data-value">{{ $nuevoResponsable->full_name ?? 'N/A' }}</div>
        </td>
      </tr>

      <tr>
        <td colspan="2" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">DEPENDENCIA</span>
          <div class="data-value">
            {{ $responsableAnterior->dependency->name ?? 'N/A' }}
          </div>
        </td>
        <td colspan="2" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">C. COSTO (CC)</span>
          <div class="data-value">{{ $responsableAnterior->cost_center ?? '-' }}</div>
        </td>
        <td colspan="2" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">DEPENDENCIA</span>
          <div class="data-value">
            {{ $nuevoResponsable->dependency->name ?? 'N/A' }}
          </div>
        </td>
        <td colspan="2" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">C. COSTO (CC)</span>
          <div class="data-value">{{ $nuevoResponsable->cost_center ?? '-' }}</div>
        </td>
      </tr>

      <tr>
        <td style="border: 1px solid #000; padding: 4px; width: 12%;">
          <span class="field-label">BLOQUE</span>
          <div class="data-value">{{ $salaOrigen->building->name ?? '-' }}</div>
        </td>
        <td style="border: 1px solid #000; padding: 4px; width: 8%;">
          <span class="field-label">PISO</span>
          <div class="data-value">{{ $salaOrigen->floor ?? '-' }}</div>
        </td>
        <td colspan="2" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">UBICACIÓN</span>
          <div class="data-value">{{ $salaOrigen->nomenclatura ?? '-' }}</div>
        </td>
        <td style="border: 1px solid #000; padding: 4px; width: 12%;">
          <span class="field-label">BLOQUE</span>
          <div class="data-value">{{ $salaDestino->building->name ?? 'N/A' }}</div>
        </td>
        <td style="border: 1px solid #000; padding: 4px; width: 8%;">
          <span class="field-label">PISO</span>
          <div class="data-value">{{ $salaDestino->floor ?? 'N/A' }}</div>
        </td>
        <td colspan="2" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">UBICACIÓN</span>
          <div class="data-value">{{ $salaDestino->nomenclatura ?? 'N/A' }}</div>
        </td>
      </tr>

      <tr>
        <td colspan="4" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">TELÉFONO - EXTENSIÓN</span>
          <div class="data-value">{{ $responsableAnterior->extension ?? 'N/A' }}</div>
        </td>
        <td colspan="4" style="border: 1px solid #000; padding: 4px;">
          <span class="field-label">TELÉFONO - EXTENSIÓN</span>
          <div class="data-value">{{ $nuevoResponsable->extension ?? 'N/A' }}</div>
        </td>
      </tr>
    </table>

    <div class="section-header">INFORMACION DE LOS ACTIVOS FIJOS</div>

    <table class="activos-table" style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
      <thead>
        <tr style="background-color: #eee;">
          <th style="border: 1px solid #000; padding: 5px; width: 20%;">No. Sticker</th>
          <th style="border: 1px solid #000; padding: 5px; width: 60%;">DESCRIPCION DEL ACTIVO FIJO</th>
          <th style="border: 1px solid #000; padding: 5px; width: 20%;">No. Serial</th>
        </tr>
      </thead>
      <tbody>
        @if(count($assets) > 5)
          {{-- Si son más de 5, SIGMA escribe la alerta institucional y delega la visualización al anexo --}}
          <tr>
            <!-- <td colspan="3" style="border: 1px solid #000; padding: 12px; text-align: center; font-weight: bold; color: #000; background-color: #f9fafb;">
              MOVIMIENTO MASIVO DETECTADO ({{ count($assets) }} EQUIPOS)<br>
              <span style="font-size: 8.5px; font-weight: normal; margin-top: 4px; display: block; color: #444;">
                Debido al volumen de hardware de esta transacción, SIGMA ha estructurado la relación pormenorizada de marcas, modelos, placas y seriales de forma automatizada en la hoja técnica adjunta como <strong>ANEXO TÉCNICO</strong> de este documento.
              </span>
            </td> -->
          </tr>
          {{-- Relleno estético para cuadrar el alto del formato original --}}
          @for($i = 1; $i < 5; $i++)
            <tr>
              <td style="border: 1px solid #000; padding: 5px; color: transparent;">&nbsp;</td>
              <td style="border: 1px solid #000; padding: 5px;">&nbsp;</td>
              <td style="border: 1px solid #000; padding: 5px;">&nbsp;</td>
            </tr>
          @endfor
        @else
          {{-- Flujo tradicional estándar de 1 a 5 equipos --}}
          @foreach($assets as $asset)
          <tr>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold;">{{ $asset->internal_code }}</td>
            <td style="border: 1px solid #000; padding: 5px;">{{ $asset->hostname ?? 'EQUIPO DE CÓMPUTO' }} - {{ $asset->cpu ?? 'HARDWARE' }} ({{ $asset->model }})</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold;">{{ $asset->serial_number }}</td>
          </tr>
          @endforeach

          {{-- Relleno para mantener las proporciones exactas del diseño institucional si faltan filas --}}
          @for($i = count($assets); $i < 5; $i++)
            <tr>
              <td style="border: 1px solid #000; padding: 5px; color: transparent;">&nbsp;</td>
              <td style="border: 1px solid #000; padding: 5px;">&nbsp;</td>
              <td style="border: 1px solid #000; padding: 5px;">&nbsp;</td>
            </tr>
          @endfor
        @endif
      </tbody>
    </table>

    <div class="section-header">CLAUSULA DE COMPROMISO</div>

    <div class="clausula-text">
      <p>Como funcionario de la Universidad Santiago de Cali declaro que los activos relacionados en el presente documento están bajo mi responsabilidad, por lo cual les daré un uso adecuado al desempeño de mis funciones y a la destinación Institucional prevista para cada uno de ellos. En consecuencia, serán asumidos por mí, el daño o la pérdida de los mismos debidos a mi negligencia o incumplimiento de los instructivos relacionados con su uso y conservación.</p>
      <br>
      <p>Me comprometo a informar oportunamente a la oficina de Vicerrectoría Administrativa cualquier desplazamiento, traslado temporal o definitivo de dichos activos mediante la tramitación de los formatos respectivos y sobre cualquier situación que ponga en inminente riesgo los bienes relacionados.</p>
      <br>
      <p>Dado que la omisión de estas disposiciones se considera falta por el reglamento interno de trabajo, asumo las consecuencias económicas que conlleven el o la pérdida de los bienes mencionados si ocurren por mi negligencia o incumplimiento de los Instructivos correspondientes, y en tal evento autorizo a la Universidad Santiago de Cali a efectuar el descuento correspondiente al valor de reposición del bien afectado, deduciéndolo de mis salarios y prestaciones sociales o eventuales indemnizaciones a mi favor.</p>
    </div>

    <div class="section-header">OBSERVACIONES ADICIONALES</div>

    <div class="obs-container" style="border: 1px solid #000; border-top: 0; padding: 7px; font-size: 9px; line-height: 20px; min-height: 70px; background-image: linear-gradient(#ccc 1px, transparent 1px); background-size: 100% 20px; font-weight: bold;">
      {{ strip_tags($assets->first()->assignments->where('acta_number', $actaNumber)->first()->observations ?? 'Movimiento masivo gestionado en SIGMA') }}
    </div>

    <table class="firmas-table" style="margin-top: 5px;">
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

  {{-- ════════════════════════════ ANEXO AUTOMÁTICO DE EQUIPOS (>5) ════════════════════════════ --}}
  @if(count($assets) > 5)
    <div class="page page-break">
      
      <table class="anexo-title-table">
        <tr>
          <td style="width: 70%;">
            <h2 style="margin: 0; font-size: 18px; font-weight: bold; letter-spacing: 0.5px;">SIGMA - ANEXO TÉCNICO</h2>
            <p style="margin: 3px 0 0 0; font-size: 10px; color: #555; text-transform: uppercase; font-weight: bold;">Relación detallada de Hardware por volumen de movimiento masivo</p>
          </td>
          <td style="width: 30%; text-align: right; font-size: 10px; line-height: 1.4;">
            <strong>CONSECUTIVO:</strong> {{ $actaNumber }}<br>
            <strong>FECHA:</strong> {{ date('d/m/Y H:i') }}
          </td>
        </tr>
      </table>

      <div style="border-top: 2px solid #000; margin-bottom: 15px;"></div>

      <table class="anexo-table">
        <thead>
          <tr>
            <th style="width: 5%;">Item</th>
            <th style="width: 25%;">No. Sticker / Placa</th>
            <th style="width: 45%;">Descripción / Modelo de Hardware</th>
            <th style="width: 25%;">Número de Serial</th>
          </tr>
        </thead>
        <tbody>
          @foreach($assets as $index => $asset)
            <tr>
              <td style="color: #555;">{{ $index + 1 }}</td>
              <td style="font-weight: bold; font-size: 10.5px;">{{ $asset->internal_code ?? 'S/N' }}</td>
              <td style="text-align: left; padding-left: 10px;">
                <strong>{{ $asset->hostname ?? 'COMPUTADOR' }}</strong> - {{ $asset->cpu ?? 'PROCESADOR' }} ({{ $asset->model }})
              </td>
              <td style="font-weight: bold; font-size: 10.5px;">{{ $asset->serial_number }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div style="margin-top: 25px; border: 1px solid #000; padding: 10px; font-size: 8.5px; line-height: 1.5; text-align: justify; background-color: #fafafa;">
        <strong>NOTIFICACIÓN DE AUDITORÍA DE INVENTARIOS USC:</strong> Este documento anexo forma parte integral e indivisible del Acta de Movimiento N° <strong>{{ $actaNumber }}</strong>. Los activos y componentes de hardware aquí descritos han sido validados y cruzados de forma nativa por la base de datos central de <strong>SIGMA</strong>, vinculados al inicio de sesión y registro del técnico operario actual de Activos Fijos. Exime al personal de soporte del diligenciamiento e impresión de libros u hojas de cálculo secundarias en Excel.
      </div>

    </div>
  @endif

</body>

</html>