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

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
      color: #000;
      background: #fff;
    }

    .page {
      width: 216mm;
      min-height: 279mm;
      margin: 0 auto;
      padding: 8mm 8mm 8mm 8mm;
      background: #fff;
    }

    /* ── HEADER ── */
    .header-table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #000;
      margin-bottom: 0;
    }

    .header-table td {
      border: 1px solid #000;
      vertical-align: middle;
    }

    .header-logo {
      width: 90px;
      height: 90px;
      text-align: center;
      padding: 4px;
      vertical-align: middle;
    }

    .logo-box {
      width: 82px;
      height: 82px;
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
      padding: 6px 10px;
      vertical-align: middle;
    }

    .header-title p {
      font-size: 13px;
      font-weight: bold;
      text-transform: uppercase;
      line-height: 1.6;
    }

    .header-right {
      width: 150px;
      padding: 0;
      vertical-align: top;
    }

    .consecutivo-block {
      border-bottom: 1px solid #000;
      padding: 3px 5px;
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
      height: 30px;
      border: 1px solid #000;
      margin-top: 3px;
      display: block;
    }

    .fecha-block {
      padding: 3px 5px;
    }

    .fecha-row {
      display: flex;
      align-items: center;
      gap: 3px;
      margin-top: 4px;
    }

    .fecha-label {
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
      margin-right: 3px;
    }

    .fecha-casilla {
      flex: 1;
      text-align: center;
    }

    .fecha-casilla span {
      font-size: 7px;
      font-weight: bold;
      display: block;
    }

    .fecha-input-box {
      border: 1px solid #000;
      height: 18px;
      width: 100%;
    }

    .version-text {
      font-size: 6.5px;
      text-align: right;
      padding: 2px 5px;
      color: #000;
    }

    /* ── SECTION HEADERS (black bar) ── */
    .section-header {
      background-color: #000;
      color: #fff;
      font-weight: bold;
      font-size: 10px;
      text-transform: uppercase;
      text-align: center;
      padding: 4px 5px;
      border: 1px solid #000;
      letter-spacing: 0.5px;
    }

    /* ── GENERAL TABLE ── */
    .form-table {
      width: 100%;
      border-collapse: collapse;
    }

    .form-table td,
    .form-table th {
      border: 1px solid #000;
      padding: 3px 5px;
      vertical-align: middle;
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
    }

    /* ── AREAS ── */
    .areas-table {
      width: 100%;
      border-collapse: collapse;
    }

    .areas-table td {
      border: 1px solid #000;
      padding: 3px 5px;
      vertical-align: middle;
      font-size: 9px;
    }

    .area-label {
      background-color: #000;
      color: #fff;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 9.5px;
      text-align: center;
      padding: 4px 5px;
    }

    .field-label {
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .field-value {
      border-bottom: 1px solid #999;
      min-height: 14px;
      display: block;
      width: 100%;
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

    .activos-table .col-sticker {
      width: 15%;
      text-align: center;
    }

    .activos-table .col-desc {
      width: 65%;
    }

    .activos-table .col-serial {
      width: 20%;
      text-align: center;
    }

    .activos-row-empty {
      height: 18px;
    }

    /* ── CLAUSULA ── */
    .clausula-text {
      border: 1px solid #000;
      border-top: 0;
      padding: 5px 7px;
      font-size: 7.5px;
      line-height: 1.4;
      text-align: justify;
      min-height: 120px;
    }

    /* ── OBSERVACIONES ── */
    .obs-lines {
      border: 1px solid #000;
      border-top: 0;
    }

    .obs-line {
      border-bottom: 1px solid #ccc;
      height: 18px;
      padding: 2px 7px;
    }

    .obs-line:last-child {
      border-bottom: none;
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
      height: 90px;
      display: block;
    }

    /* ── PRINT ── */
    @media print {
      body {
        margin: 0;
      }

      .page {
        width: 216mm;
        padding: 8mm;
        margin: 0;
      }
    }
  </style>
</head>

<body>
  <div class="page">

    <!-- ════════════════════════════ HEADER ════════════════════════════ -->
    <table class="header-table">
      <tr>
        <!-- Logo -->
        <td class="header-logo">
          <div class="logo-box">
            <span class="logo-usc">USC</span>
            <span class="logo-universidad">UNIVERSIDAD<br>SANTIAGO<br>DE CALI</span>
          </div>
        </td>

        <!-- Título central -->
        <td class="header-title">
          <p>FORMATO UNICO PARA</p>
          <p>EL MOVIMIENTO</p>
          <p>DE ACTIVOS</p>
        </td>

        <!-- Consecutivo + Fecha -->
        <td class="header-right">
          <div class="consecutivo-block">
            <span class="consecutivo-label">CONSECUTIVO No.</span>
            <div class="consecutivo-box"></div>
          </div>
          <div class="fecha-block">
            <table style="width:100%; border-collapse:collapse;">
              <tr>
                <td style="border:1px solid #000; padding:2px 4px; font-size:8px; font-weight:bold; text-transform:uppercase; white-space:nowrap;">FECHA</td>
                <td style="border:1px solid #000; padding:2px; text-align:center; width:28px;">
                  <span style="font-size:7px; font-weight:bold; display:block;">DD</span>
                  <div style="height:14px;"></div>
                </td>
                <td style="border:1px solid #000; padding:2px; text-align:center; width:28px;">
                  <span style="font-size:7px; font-weight:bold; display:block;">MM</span>
                  <div style="height:14px;"></div>
                </td>
                <td style="border:1px solid #000; padding:2px; text-align:center; width:28px;">
                  <span style="font-size:7px; font-weight:bold; display:block;">AA</span>
                  <div style="height:14px;"></div>
                </td>
              </tr>
            </table>
          </div>
          <div class="version-text">R-AF001/Versión 2/12 de junio de 2019</div>
        </td>
      </tr>
    </table>

    <!-- ════════════════════════════ TIPO DE MOVIMIENTO ════════════════════════════ -->
    @php
    $movType = $assets->first()->assignments->first()->movement_type ?? '';
    @endphp

    <table class="tipo-table">
      <tr>
        <td class="checkbox-cell">{{ $movType == 'TRASLADO ASIGNACION' ? 'X' : '' }}</td>
        <td>TRASLADO EN CALIDAD DE ASIGNACION</td>

        <td class="checkbox-cell">{{ $movType == 'PRESTAMO FUERA USC' ? 'X' : '' }}</td>
        <td>PRESTAMO FUERA DE LAS INSTLACIONES DE LA USC</td>
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
        <td>OTRO: <span class="otro-line">{{ $movType == 'OTRO' ? '' : '' }}</span></td>
      </tr>
      <tr>
        <td class="checkbox-cell">{{ $movType == 'PRESTAMO DENTRO USC' ? 'X' : '' }}</td>
        <td colspan="3">PRESTAMO DENTRO DE LA INSTALACIONES DE LA USC</td>
      </tr>
    </table>

    <!-- ════════════════════════════ SEDE ════════════════════════════ -->
    @php
    $sede = strtoupper($assets->first()->assignments->first()->headquarters ?? '');
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
        <td style="width:35%;">
          @if(!in_array($sede, ['PAMPALINDA', 'CENTRO', 'PALMIRA']) && $sede != '')
          {{ $sede }}
          @endif
        </td>
      </tr>
    </table>
    <!-- ════════════════════════════ AREAS ════════════════════════════ -->
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
    <!-- ════════════════════════════ ACTIVOS FIJOS ════════════════════════════ -->
    <div class="section-header">INFORMACION DE LOS ACTIVOS FIJOS</div>

    <table class="activos-table" style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
      <thead>
        <tr style="background-color: #eee;">
          <th style="border: 1px solid #000; padding: 5px;">No. Sticker</th>
          <th style="border: 1px solid #000; padding: 5px;">DESCRIPCION DEL ACTIVO FIJO</th>
          <th style="border: 1px solid #000; padding: 5px;">No. Serial</th>
        </tr>
      </thead>
      <tbody>
        {{-- Datos dinámicos --}}
        @foreach($assets as $asset)
        <tr>
          <td style="border: 1px solid #000; padding: 5px; text-align: center;">{{ $asset->internal_code }}</td>
          <td style="border: 1px solid #000; padding: 5px;">{{ $asset->hostname }} - {{ $asset->cpu }}</td>
          <td style="border: 1px solid #000; padding: 5px; text-align: center;">{{ $asset->serial_number }}</td>
        </tr>
        @endforeach

        {{-- Relleno para mantener el diseño (si hay menos de 6 activos) --}}
        @for($i = count($assets); $i < 6; $i++)
          <tr>
          <td style="border: 1px solid #000; padding: 5px; color: transparent;">&nbsp;</td>
          <td style="border: 1px solid #000; padding: 5px;">&nbsp;</td>
          <td style="border: 1px solid #000; padding: 5px;">&nbsp;</td>
          </tr>
          @endfor
      </tbody>
    </table>

    <!-- ════════════════════════════ CLAUSULA DE COMPROMISO ════════════════════════════ -->
    <div class="section-header">CLAUSULA DE COMPROMISO</div>

    <div class="clausula-text">
      <p>Como funcionario de la Universidad Santiago de Cali declaro que los activos relacionen en el presente documento están bajo mi responsabilidad, por lo cual les daré un uso adecuado al desempeño de mis funciones y a la destinación Institucional prevista para cada uno de ellos. En consecuencia, serán asumidos por mí, el daño o la pérdida de los mismos debidos a mi negligencia o incumplimiento de los instructivos relacionados con su uso y conservación.</p>
      <br>
      <p>Me comprometo a informar oportunamente a la oficina de Vicerrectoría Administrativa cualquier desplazamiento, traslado temporal o definitivo de dichos activos media la tramitación de los formatos respectivos y sobre cualquier situación que ponga en inminente riesgo los bienes relacionados.</p>
      <br>
      <p>Dado que la omisión de estas disposiciones se considera falta por el reglamento interno de trabajo, asumo las consecuencias económicas que conlleven el o la pérdida de los bienes mencionados si ocurren por mi negligencia o incumplimiento de los Instructivos correspondientes, y en tal evento autorizo a la Universidad Santiago de Cali a efectuar el descuento correspondiente al valor de reposición del bien afectado, deduciéndolo de mis salarios y prestaciones sociales o eventuales indemnizaciones a mi favor.</p>
    </div>

    <!-- ════════════════════════════ OBSERVACIONES ADICIONALES ════════════════════════════ -->
    <div class="section-header">OBSERVACIONES ADICIONALES</div>

    <div class="obs-container" style="border: 1px solid #000; border-top: 0; padding: 7px; font-size: 9px; line-height: 20px; min-height: 80px; background-image: linear-gradient(#ccc 1px, transparent 1px); background-size: 100% 20px;">
      {{ $assets->first()->assignments->where('acta_number', $actaNumber)->first()->observations }}
    </div>

    <!-- ════════════════════════════ FIRMAS ════════════════════════════ -->
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
</body>

</html>