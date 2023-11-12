<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Orden</title>
</head>
<body>
   
    <div style="text-align: center; position: relative;">
         
         <img src="../imagen/IHCIS.jpg" alt="Logo 1" style="width: 60px; position: absolute; top: 0; left: 0; margin-left: 30px;">
   
         <div class="centered-content" style="display: inline-block; text-align: center;">
            <p style="margin-bottom: -20px;"><strong>INSTITUTO HONDUREÑO DE CULTURA INTERAMERICANA</strong></p>
            <p><strong>RTN 08019995223469</strong></p>
          </div>
   
          <img src="../imagen/IHCI1.jpg" alt="Logo 2" style="width: 70px; position: absolute; top: 0; right: 0; margin-right: 140px;">
   
       </div>
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
        echo '<p>Número de Orden: ' . $row["NUMERO_ORDEN"] . '</p>';
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

        // Muestra el Total Factura solo si existe un valor
        if ($row["MONTO_TOTAL"] !== null) {
            echo '<p>Total Factura: ' . $row["MONTO_TOTAL"] . '</p>';
        }
    } else {
        echo "No se encontró la orden de compra con el número: $numeroOrden";
    }

    // Cierra la conexión con la base de datos
    $conn->close();
    ?>

    <p><a href="../compras/ordenes_compras.php">Regresar</a></p>
</body>
</html>