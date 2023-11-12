<?php
include 'db_connection.php';

$idSolicitud = $_GET['id']; // Obtener el ID de solicitud desde la URL

$sqlSolicitud = "SELECT id, descripcion, cantidad FROM tbl_solicitudes WHERE id = $idSolicitud";
$resultSolicitud = $conn->query($sqlSolicitud);

if ($resultSolicitud->num_rows > 0) {
    $rowSolicitud = $resultSolicitud->fetch_assoc();
    $numeroSolicitud = $rowSolicitud['id']; // Agregar el número de solicitud
    $descripcionSolicitud = $rowSolicitud['descripcion']; // Agregar la descripción de la solicitud
    $cantidadSolicitud = $rowSolicitud['cantidad']; // Agregar la cantidad de la solicitud
} else {
    // Manejo de errores si la solicitud no se encuentra
    echo "Error: Solicitud no encontrada.";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProveedor = $_POST['id_proveedor'];
    $numeroCotizacion = $_POST['numero_cotizacion'];
    $departamento = $_POST['departamento'];
    $fechaCotizacion = $_POST['fecha_cotizacion'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    // Realiza una subconsulta para obtener el nombre del proveedor
    $sql = "INSERT INTO tbl_cotizacion (ID_PROVEEDOR, NUMERO_COTIZACION, DEPARTAMENTO, FECHA_COTIZACION, NOMBRE_PROVEEDOR, DESCRIPCION, CANTIDAD, PRECIO, id)
            VALUES ('$idProveedor', '$numeroCotizacion', '$departamento', '$fechaCotizacion', (SELECT NOMBRE FROM tbl_proveedores WHERE ID_PROVEEDOR = '$idProveedor'), '$descripcion', $cantidad, $precio, $idSolicitud)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Cotización agregada con éxito.";
        // Redirige a read_cotizacion.php después de 2 segundos
    header("refresh:2;url=read_cotizacion.php");
    
    // Opcional: puedes mostrar un mensaje de redirección
    echo "Redireccionando a la página de cotizaciones...";
} else {
        echo "Error al agregar la cotización: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Cotización</title>
</head>
<body>
<h2>Información de Solicitud</h2>
    <p>Número: <?php echo $numeroSolicitud; ?></p>
    <p>Descripción: <?php echo $descripcionSolicitud; ?></p>

    <h2>Agregar Cotización</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $idSolicitud; ?>">
        
        <!-- Agregar una lista desplegable para el proveedor -->
        <label>Proveedor:</label>
        <select name="id_proveedor" required>
            <option value="">Seleccionar un proveedor</option>
            <?php
            $sql = "SELECT ID_PROVEEDOR, NOMBRE FROM tbl_proveedores WHERE ESTADO_PROVEEDOR = 1"; // Suponemos que ESTADO_PROVEEDOR 1 significa activo
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["ID_PROVEEDOR"] . "'>" . $row["NOMBRE"] . "</option>";
                }
            }
            ?>
        </select><br><br>

        <!-- Campos de la cotización (similares a create_cotizacion.php) -->
        <label>Número de Cotización:</label>
        <input type="text" name="numero_cotizacion"><br><br>

        <label>Departamento:</label>
        <input type="text" name="departamento" required><br><br>

        <label>Fecha de Cotización:</label>
        <input type="date" name="fecha_cotizacion"><br><br>

        <label>Descripción:</label>
        <input type="text" name="descripcion" required><br><br>

        <label>Cantidad:</label>
        <input type="text" name="cantidad" value="<?php echo $cantidadSolicitud; ?>" readonly><br><br>

        <label>Monto:</label>
        <input type="text" name="precio" required><br><br>

        <input type="submit" value="Agregar Cotización">
        
    </form>
    <a href="read_solicitud.php" class="btn btn-primary">Regresar</a>
</body>
</html>

