<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Aviso PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px; /* Reducir el tamaño de la letra */
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }

        .header {
            top: 0px;
        }

        .footer {
            bottom: 0px;
        }

        .content {
            margin-top: 180px; /* Ajusta este valor según sea necesario */
            margin-bottom: 50px;
        }

        .img-class {
            width: 100%;
        }

        .header-text {
            position: absolute;
            top: 100px;
            right: 20px;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px; /* Reducir el espacio de padding */
            text-align: left;
        }

        thead {
            background-color: #f1b500;
        }

        tbody tr:first-child td {
            border-top: none;
        }

        tbody tr td:first-child {
            background-color: #d2dcdf;
        }

        ul {
            margin-top: 20px; /* Ajusta este valor según sea necesario */
            padding-left: 12px; /* Alineación con la tabla */
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/header.png'))) }}"
            alt="Header" class="img-class">
        <div class="header-text">
            <p>DM, Quito</p>
            <p>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</p>
        </div>
    </div>
    <div class="footer">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/footer.png'))) }}"
            alt="Footer" class="img-class">
    </div>
    <div class="content">

        <table>
            <thead>
                <tr>
                    <th colspan="2" style="width: 70%">PRE-AVISO DE LLEGADA CARGA AÉREO</th>
                    <th>No AVISO</th>
                    <th>{{ $aviso->nro_anviso }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 35%">ESTIMADO CLIENTE:</td>
                    <td colspan="3">{{ $aviso->cliente->nombre }}</td>
                </tr>
                <tr>
                    <td>VUELO:</td>
                    <td colspan="3">{{ $aviso->nro_vuelo }}</td>
                </tr>
                <tr>
                    <td>LÍNEA:</td>
                    <td colspan="3">{{ $aviso->linea->nombre }}</td>
                </tr>
                <tr>
                    <td>ETA APROX:</td>
                    <td colspan="3">{{ $aviso->eta_aprox }}</td>
                </tr>
                <tr>
                    <td>HAWB:</td>
                    <td colspan="3">{{ $aviso->hawg }}</td>
                </tr>
                <tr>
                    <td>ORIGEN:</td>
                    <td colspan="3">{{ $aviso->origen }}</td>
                </tr>
                <tr>
                    <td>DESTINO:</td>
                    <td colspan="3">{{ $aviso->destino }}</td>
                </tr>
                <tr>
                    <td>PROVEEDOR:</td>
                    <td colspan="3">{{ $aviso->proveedor->nombre }}</td>
                </tr>
                <tr>
                    <td>CONTENIDO:</td>
                    <td colspan="3">{{ $aviso->contenido }}</td>
                </tr>
                <tr>
                    <td>BULTOS:</td>
                    <td colspan="3">{{ $aviso->bultos }}</td>
                </tr>
                <tr>
                    <td>PESO:</td>
                    <td colspan="3">{{ $aviso->peso }}</td>
                </tr>
                <tr>
                    <td>FLETE TERM:</td>
                    <td colspan="3">{{ $aviso->tipo_flete_term_nombre }}</td>
                </tr>
            </tbody>
        </table>
        <ul>
            <li>Presentar carta de autorización en hoja membretada firmada y sellada por el representante legal o delegado del área de comercio exterior, indicando nombre y documento de identidad de la persona o empresa autorizada a recoger los documentos de embarque.</li>
            <li>Favor informar los datos completos; razón social, RUC, dirección a quien se debe emitir la factura de gastos locales, caso contrario la misma será emitida a nombre de quien figure como consignatario en la HAWB.</li>
        </ul>
    </div>
</body>

</html>
