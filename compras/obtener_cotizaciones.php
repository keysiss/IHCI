<?php
if (isset($_POST["proveedorId"])) {
    $proveedorId = $_POST["proveedorId"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }


    $sql = "SELECT * FROM tbl_cotizacion WHERE ID_PROVEEDOR = $proveedorId AND ESTADO = 'Aprobada'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
      

        while ($cotizacion = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $cotizacion["CANTIDAD"] . '</td>';
            echo '<td>' . $cotizacion["DESCRIPCION"] . '</td>';
            echo '<td>' . $cotizacion["PRECIO"] . '</td>';
            $valorTotal = $cotizacion["CANTIDAD"] * $cotizacion["PRECIO"];
            $valorTotalFormateado = number_format($valorTotal, 2, '.', '');
            echo '<td>' . $valorTotalFormateado . '</td>';

            echo '<td>' . ($cotizacion["EXCENTO"] ? 'Sí' : 'No') . '</td>';
            echo '</tr>';

           
        }

    } else {
        echo '<tr><td colspan="5">No hay cotizaciones aprobadas para este proveedor.</td></tr>';
    }

    $conn->close();
} else {
    echo '<tr><td colspan="5">Selecciona un proveedor para ver cotizaciones.</td></tr>';
}
?>
