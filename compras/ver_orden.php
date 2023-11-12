
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Detalle de Orden</title>

    <style>
        /* Estilos para el contenedor principal */
    .main-container {
        margin: 20px;
        padding: 20px;
        border: 0px solid #ddd;
        border-radius: 10px;
        background-color: white;
    }
        /* Estilos para la cabecera de la orden de compra */
        .order-header {
            margin-right: 100px;
            float: left;
            margin-top: 20px;
        }

        /* Estilos para la tabla de la orden de compra */
        .order-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.order-table th, .order-table td {
    border: 1px solid black;
    padding: 8px;
    text-align: center;
    white-space: nowrap; /* Evitar que el texto se ajuste automáticamente a la siguiente línea */
    overflow: hidden;
    text-overflow: ellipsis; /* Mostrar puntos suspensivos (...) cuando el texto es demasiado largo */
}

.order-table th:first-child,
.order-table td:first-child {
    width: 5%; /* Ajusta el ancho de la primera columna según tus necesidades */
}

.order-table th:nth-child(2),
.order-table td:nth-child(2) {
    width: 40%; /* Ajusta el ancho de la segunda columna según tus necesidades */
}

.order-table th:nth-child(3),
.order-table td:nth-child(3),
.order-table th:nth-child(4),
.order-table td:nth-child(4) {
    width: 15%; /* Ajusta el ancho de las columnas 3 y 4 según tus necesidades */
}

.order-table th:last-child,
.order-table td:last-child {
    width: 20%; /* Ajusta el ancho de la última columna según tus necesidades */
}

        /* Estilos para la firma del solicitante */
        .order-signature {
            margin-top: 20px;
        }

        .order-total {
            
            width: 65%;
            text-align: right;
            margin-left: 453px;
            
        }

        .order-total table {
            text-align: right;
            width: 75%; /* Ajusta el ancho según tus necesidades */
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }

        .order-total td {
            text-align: center;
            padding: 2px;
            border: 1px solid #000;
            width: 45%; /* Ajusta el ancho según tus necesidades */
        }

        .order-total label {
           
            font-weight: bold;
        }

        .centered-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 10vh;
        }

         /* Estilos para el contenedor de la información de la orden */
    .order-info {
        text-align: center;
        margin-bottom: -40px;
    }

    .order-info p {
        display: inline-block; /* Hace que los elementos de párrafo estén en línea */
        margin-top: 10px;
        margin-left: -900px; /* Ajusta el margen izquierdo según tus necesidades */
    }

    .pdf-button {
        margin-top: 10px;
        margin-left: 400px; /* Ajusta el margen izquierdo según tus necesidades */
        font-size: 24px; /* Tamaño del icono, ajusta según tus necesidades */
    }

    </style>
</head>
<body>
<div class="main-container">
    <div style="text-align: center; position: relative;">
         
    <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents('../imagen/IHCIS.jpg')); ?>" alt="Logo 1" style="width: 80px; position: absolute; top: 0; left: 0; margin-left: 30px;">

   
         <div class="centered-content" style="display: inline-block; text-align: center;">
            <p style="margin-bottom: -20px;"><strong>INSTITUTO HONDUREÑO DE CULTURA INTERAMERICANA</strong></p>
            <p ><strong>RTN 08019995223469</strong></p>
            <p ><strong>Solicitud de Compra</strong></p>
          </div>
   
          <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents('../imagen/IHCI1.jpg')); ?>" alt="Logo 2" style="width: 80px; position: absolute; top: 0; right: 0; margin-right: 90px;">
          
   
    </div>

    <br><br><br>

    <?php
    // Conecta a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtiene el número de orden desde el parámetro de la URL
    $numeroOrden = $_GET['numero_orden'];

    // Consulta para obtener los detalles de la orden
    $sql = "SELECT * FROM tbl_orden_compra WHERE NUMERO_ORDEN = $numeroOrden";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
       // Mostrar el número de orden y el enlace para generar PDF en la misma línea
    echo '<div class="order-info">';
    echo '<p>Número de Orden: ' . $row["NUMERO_ORDEN"] . '</p>';
    echo '<div class="pdf-button"><a href="pdf.php?numero_orden=' . $row["NUMERO_ORDEN"] . '" class="pdf-button"><i class="fas fa-file-pdf"></i></a></div>';
    echo '</div>';
  
       
        echo '<p>Fecha: ' . $row["FECHA_ORDEN"] . '</p>';

        // Recupera el proveedor
        $idProveedor = $row["ID_PROVEEDOR"];
        $sqlProveedor = "SELECT NOMBRE FROM tbl_proveedores WHERE ID_PROVEEDOR = $idProveedor";
        $resultProveedor = $conn->query($sqlProveedor);
        if ($resultProveedor->num_rows > 0) {
            $proveedor = $resultProveedor->fetch_assoc();
            echo '<p>Proveedor: ' . $proveedor["NOMBRE"] . '</p>';
        }

         // Recupera el nombre del contacto directamente de la tabla tbl_contactos_proveedores
         $idContacto = $row["ID_CONTACTO"];
         if ($idContacto !== null) {
             $sqlContacto = "SELECT NOMBRE FROM tbl_contactos_proveedores WHERE ID_CONTACTO_PROVEEDOR = $idContacto";
             $resultContacto = $conn->query($sqlContacto);
             if ($resultContacto->num_rows > 0) {
                 $contacto = $resultContacto->fetch_assoc();
                 echo '<p>Contacto: ' . $contacto["NOMBRE"] . '</p>';
             }
         }


        // Consulta para obtener las cotizaciones aprobadas del proveedor
        $sqlCotizaciones = "SELECT CANTIDAD, DESCRIPCION, PRECIO, (CANTIDAD * PRECIO) AS VALOR_TOTAL FROM tbl_cotizacion WHERE ID_PROVEEDOR = $idProveedor AND ESTADO = 'Aprobada'";
        $resultCotizaciones = $conn->query($sqlCotizaciones);

        if ($resultCotizaciones->num_rows > 0) {
            echo '<table class="order-table">';
            echo '<tr><th>Cantidad</th><th>Descripción</th><th>Precio</th><th>Valor Total</th></tr>';
            
            $subtotal = 0; // Inicializa el subtotal

            while ($cotizacion = $resultCotizaciones->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $cotizacion["CANTIDAD"] . '</td>';
                echo '<td>' . $cotizacion["DESCRIPCION"] . '</td>';
                echo '<td>' . $cotizacion["PRECIO"] . '</td>';
                echo '<td>' . $cotizacion["VALOR_TOTAL"] . '</td>';
                echo '</tr>';

                // Suma al subtotal
                $subtotal += $cotizacion["VALOR_TOTAL"];
            }

            echo '</table>';

            echo '<div class="order-total">';
            echo    '<table>';
            
           // Muestra el subtotal
         echo '<tr>
         <td style=" border: none;  text-align: right;"><label >Subtotal:</label></td>
         <td>' . number_format($subtotal, 2) . '</td>
         </tr>';

          // Calcula el ISV (Impuesto sobre Ventas)
         $isv = $subtotal * 0.15;
         echo '<tr><td style=" border: none;  text-align: right;"><label>ISV (15%):</label></td><td>' . number_format($isv, 2) . '</td></tr>';

         // Muestra el total factura
         $totalFactura = $subtotal + $isv;
         echo '<tr><td style=" border: none;  text-align: right;"><label>Total Factura:</label></td><td>' . number_format($totalFactura, 2) . '</td></tr>';

            echo '</table>';
            echo '</div>';
        } else {
            echo '<p>No hay cotizaciones aprobadas para este proveedor.</p>';
        }
       
    } else {
        echo "No se encontró la orden de compra con el número: $numeroOrden";
    }
    
    // Cierra la conexión con la base de datos
    $conn->close();
    ?>

    <br><br><br>
    <p>Observaciones:</p>

    <div class="order-signature">
        <p>Firma del solicitante: _________________________________</p>
    </div>
    <p><a href="../compras/ordenes_compras.php">Regresar</a></p>


    </div>  
</body>
</html>

