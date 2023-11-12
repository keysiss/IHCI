<?php
include 'db_connection.php';

$solicitud_id = $_GET['id']; // Obtener el ID de la solicitud desde la URL

// Consulta para recuperar detalles de la solicitud
$sqlSolicitud = "SELECT * FROM tbl_solicitudes WHERE id = ?";
$stmtSolicitud = $conn->prepare($sqlSolicitud);

if ($stmtSolicitud) {
    $stmtSolicitud->bind_param("i", $solicitud_id);
    $stmtSolicitud->execute();
    $resultSolicitud = $stmtSolicitud->get_result();

    // Consulta para recuperar información de la cotización aprobada
    $sqlCotizacionAprobada = "SELECT c.ID_COTIZACION, p.NOMBRE AS NOMBRE_PROVEEDOR, c.DESCRIPCION, c.CANTIDAD, c.ESTADO, c.PRECIO FROM tbl_cotizacion c
                             INNER JOIN tbl_proveedores p ON c.ID_PROVEEDOR = p.ID_PROVEEDOR
                             WHERE c.id = ? AND c.ESTADO = 'Aprobada'";
    $stmtCotizacionAprobada = $conn->prepare($sqlCotizacionAprobada);

    if ($stmtCotizacionAprobada) {
        $stmtCotizacionAprobada->bind_param("i", $solicitud_id);
        $stmtCotizacionAprobada->execute();
        $resultCotizacionAprobada = $stmtCotizacionAprobada->get_result();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalle de Solicitud</title>

    <style>
          body {
            text-align: center;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10); /* Cambia el valor de "0.7" para ajustar la transparencia */
            background-image: url('../imagen/background.jpg'); /* Reemplaza con la ruta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
           
        
        }

        .container {
            display: inline-block;
            text-align: left;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
            background-color: powderblue; /* Color de fondo azul claro (cielo) */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            opacity: 0.9; /* Valor de opacidad (menos transparente) */
        }

        .table-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .table {
            width: 100%; /* El 2% es para el espacio entre las tablas */
            box-sizing: border-box; /* Para evitar que los bordes agreguen más ancho */
            background-color: cornsilk; /* Color de fondo  para las tablas */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            
        }

        th {
            background-color: bisque;
        }

        .button-row {
            text-align: center;
            margin-top: 20px;
        }

       

        /* Estilo del botón */
        button {
            background-color: #4CAF50; /* Color verde para el botón */
            color: white; /* Texto en color blanco */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049; /* Cambia el color al pasar el mouse sobre el botón */
        }
  /* Estilo del botón */
  button {
            background-color: #4CAF50; /* Color verde para el botón */
            color: white; /* Texto en color blanco */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

<style>
    .red-button {
        background-color: red;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .red-button:hover {
        background-color: darkred;
    }
</style>

</head>
<body>
<div class="container">
    
<div class="table">
    <h2>Detalle de Solicitud</h2>
    <?php
    if ($resultSolicitud->num_rows > 0) {
        $rowSolicitud = $resultSolicitud->fetch_assoc();
        echo "<p>Número: " . $rowSolicitud['id'] . "</p>";
        echo "<p>Descripción: " . $rowSolicitud['descripcion'] . "</p>";
        // Agrega aquí más detalles de la solicitud según tu estructura de base de datos
    } else {
        echo "Solicitud no encontrada.";
    }
    ?>


    <h2 style="text-align: center;">Cotización Aprobada</h2>
<table border="1">
    <tr>
        <th>ID de Cotización</th>
        <th>Proveedor</th>
        <th>Descripción</th>
        <th>Cantidad</th>
        <th>Estado</th>
        <th>Precio</th>
    </tr>
    <?php
    if ($resultCotizacionAprobada->num_rows > 0) {
        $rowCotizacionAprobada = $resultCotizacionAprobada->fetch_assoc();
        echo "<tr>";
        echo "<td>" . $rowCotizacionAprobada['ID_COTIZACION'] . "</td>";
        echo "<td>" . $rowCotizacionAprobada['NOMBRE_PROVEEDOR'] . "</td>";
        echo "<td>" . $rowCotizacionAprobada['DESCRIPCION'] . "</td>";
        echo "<td>" . $rowCotizacionAprobada['CANTIDAD'] . "</td>";
        echo "<td>" . $rowCotizacionAprobada['ESTADO'] . "</td>";
        echo "<td> L" . $rowCotizacionAprobada['PRECIO'] . "</td>";
        echo "</tr>";

         // Agregar un botón para generar la orden de compra
         echo '<form method="post" action="generar_orden_compra.php">';
         echo '<input type="hidden" name="cotizacion_id" value="' . $rowCotizacionAprobada['ID_COTIZACION'] . '">';
         
         echo '</form>';
    } else {
        echo "No hay cotizaciones aprobadas para esta solicitud.";
    }
    
    ?> 
    </table>
    
</div>
<br>
<td  colspan='2' class='right-align'><button type='submit'>Orden de Compra</button></td>
<a href="../solicitudes/solicitudes.php" class="red-button">Cancelar</a>

</div>
</body>
</html>
