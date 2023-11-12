<!DOCTYPE html>
<html>
<head>
    <title>Orden de Compra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .solicitud-table {
            border-collapse: collapse;
            width: 100%;
        }

        .solicitud-table th, .solicitud-table td {
            border: 1px solid #ddd;
            padding: 9px;
            text-align: left;
        }

        .solicitud-table th {
            background-color: #f2f2f2;
        }

        .btn-eliminar {
    color: red;
    border: none; /* Esta propiedad quita los bordes de los botones */
    background-color: white;
    
  }

  /* Estilo para el botón "Agregar" */
  .print-button {
    float: right;
    margin-left: 10px;
    text-decoration: none;
    padding: 10px 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
    </style>
</head>
<body>
    <h2>Orden de Compra</h2>
    <a href="../compras/compras.php" class="print-button plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></a>
   
    <table class="solicitud-table">
        <tr>
            <th>Número de Orden</th>
            <th>Fecha</th>
            <th>Proveedor</th>
            <th>Contacto</th>
            <th>Total Factura</th>
            <th>Acciones</th>
        </tr>

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

        // Consulta para obtener los datos de la tabla tbl_orden_compra
        $sql = "SELECT * FROM tbl_orden_compra";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["NUMERO_ORDEN"] . '</td>';
                echo '<td>' . $row["FECHA_ORDEN"] . '</td>';

                // Recupera el proveedor
                $idProveedor = $row["ID_PROVEEDOR"];
                $sqlProveedor = "SELECT NOMBRE FROM tbl_proveedores WHERE ID_PROVEEDOR = $idProveedor";
                $resultProveedor = $conn->query($sqlProveedor);
                if ($resultProveedor->num_rows > 0) {
                    $proveedor = $resultProveedor->fetch_assoc();
                    echo '<td>' . $proveedor["NOMBRE"] . '</td>';
                } else {
                    echo '<td></td>';
                }

                // Recupera el nombre del contacto directamente de la tabla tbl_contactos_proveedores
                $idContacto = $row["ID_CONTACTO"];
                if ($idContacto !== null) {
                    $sqlContacto = "SELECT NOMBRE FROM tbl_contactos_proveedores WHERE ID_CONTACTO_PROVEEDOR = $idContacto";
                    $resultContacto = $conn->query($sqlContacto);
                    if ($resultContacto->num_rows > 0) {
                        $contacto = $resultContacto->fetch_assoc();
                        echo '<td>' . $contacto["NOMBRE"] . '</td>';
                    } else {
                        echo '<td></td>';
                    }
                } else {
                    echo '<td></td>';
                }

                // Muestra el Total Factura solo si existe un valor
                echo '<td>';
                if ($row["MONTO_TOTAL"] !== null) {
                    echo $row["MONTO_TOTAL"];
                }
                echo '</td>';

                echo '<td>';
                echo '<a href="ver_orden.php?numero_orden=' . $row["NUMERO_ORDEN"] . '" class="btn btn-primary"><i class="fas fa-eye"></i></a>';
                echo '<button class="btn  btn-eliminar" onclick="eliminarSolicitud(' . $row["NUMERO_ORDEN"] . ')"><i class="fas fa-trash"></i></button>';
                echo '</td>';
                echo '</tr>';
                

                

echo '</tr>';

                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6">No se encontraron órdenes de compra en la base de datos.</td></tr>';
        }

        // Cierra la conexión con la base de datos
        $conn->close();
        ?>
    </table>

    <script>
        

        function eliminarSolicitud(id) {
            var confirmarEliminar = confirm("¿Estás seguro de que deseas eliminar la orden con ID " + id + "?");

            if (confirmarEliminar) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../compras/eliminar.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var respuesta = xhr.responseText;
                        if (respuesta === "Orden eliminada exitosamente") {
                            location.reload();
                        } else {
                            alert(respuesta);
                        }
                    } else if (xhr.readyState === 4 && xhr.status !== 200) {
                        alert("Error al eliminar la orden");
                    }
                };

                xhr.send("id=" + id);
            }
        }
    </script>
</body>
</html>

