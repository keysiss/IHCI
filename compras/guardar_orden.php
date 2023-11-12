<?php
// Verifica si se ha enviado una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecta a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Recupera los datos del formulario
    $idProveedor = $_POST["proveedor"];
    $numeroOrden = $_POST["numero_orden"]; // Aquí obtienes el número de orden
    $fechaOrden = date("Y-m-d", strtotime($_POST["fecha_orden"])); // Asegúrate de que la fecha esté en el formato correcto
    $montoTotal = $_POST["total_factura"];

    //Obtén los valores adicionales
    $idContacto = $_POST["contacto"];
    $subtotal = $_POST["subtotal"];
    $isv = $_POST["isv"];

   

     // Prepara la consulta SQL para insertar la orden de compra
     $sql = "INSERT INTO tbl_orden_compra (ID_PROVEEDOR, NUMERO_ORDEN, FECHA_ORDEN, MONTO_TOTAL, ID_CONTACTO, SUBTOTAL, ISV) VALUES (?, ?, ?, ?, ?, ?, ?)";

     // Prepara la sentencia SQL
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("issdidd", $idProveedor, $numeroOrden, $fechaOrden, $montoTotal, $idContacto, $subtotal, $isv);

    // Ejecuta la sentencia SQL
    if ($stmt->execute()) {
        // Redirige a una página de agradecimiento o a donde desees
        header("Location: ../compras/ordenes_compras.php");
        exit; // Asegúrate de que el script se detenga después de redirigir
    } else {
        echo "Error al guardar la orden de compra: " . $stmt->error;
    }

    // Cierra la conexión con la base de datos
    $stmt->close();
    $conn->close();
} else {
    echo "Acceso no autorizado";
}
?>
