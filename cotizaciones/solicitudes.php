<?php
$host = 'localhost';
$dbname = 'gestion_compras2';
$username = 'root';
$password = '';

// Conexión a la base de datos
$conexion = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];

    // Buscar el ID del usuario por su nombre
    $consulta_usuario = "SELECT id_usuario FROM tbl_ms_usuario WHERE nombre_usuario = ?";
    $statement_usuario = $conexion->prepare($consulta_usuario);
    $statement_usuario->bind_param("s", $nombre_usuario);
    $statement_usuario->execute();
    $resultado_usuario = $statement_usuario->get_result();
    $row_usuario = $resultado_usuario->fetch_assoc();

    if ($row_usuario) {
        $usuario_id = $row_usuario['id_usuario'];

        // Consultar las solicitudes del usuario con detalles adicionales
        $consulta_solicitudes = "SELECT s.*, d.nombre_departamento, c.categoria FROM tbl_solicitudes s
                                INNER JOIN tbl_departamentos d ON s.idDepartamento = d.id_departamento
                                INNER JOIN tbl_categorias c ON s.categoria_productos = c.id
                                WHERE s.usuario_id = ?";
        $statement_solicitudes = $conexion->prepare($consulta_solicitudes);
        $statement_solicitudes->bind_param("i", $usuario_id);
        $statement_solicitudes->execute();
        $resultado_solicitudes = $statement_solicitudes->get_result();
        $solicitudes = $resultado_solicitudes->fetch_all(MYSQLI_ASSOC);
    } else {
        $mensaje = "Usuario no encontrado.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Solicitudes</title>
</head>
<body>
    <h1>Búsqueda de Solicitudes</h1>
    <form method="POST">
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($mensaje) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if (isset($solicitudes)) : ?>
        <h2>Solicitudes del Usuario</h2>
        <?php if (count($solicitudes) > 0) : ?>
            <table>
                <tr>
                    <th>Descripción</th>
                    <th>Departamento</th>
                    <th>Fecha de Ingreso</th>
                    <th>Categoría de Productos</th>
                    <th>Estado</th>
                </tr>
                <?php foreach ($solicitudes as $solicitud) : ?>
                    <tr>
                        <td><?php echo $solicitud['descripcion']; ?></td>
                        <td><?php echo $solicitud['nombre_departamento']; ?></td>
                        <td><?php echo $solicitud['fecha_ingreso']; ?></td>
                        <td><?php echo $solicitud['categoria']; ?></td>
                        <td><?php echo $solicitud['estado']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No hay solicitudes para este usuario.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

