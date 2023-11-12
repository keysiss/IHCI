<?php
// Reemplaza estos valores con tus credenciales de base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idDepartamento = $_POST["idDepartamento"];
    $usuario_nombre = $_POST["usuario_nombre"]; // Cambiado para almacenar el nombre del usuario
    $categoria_productos_id = $_POST["categoria_productos"];
    $cantidad = $_POST["cantidad"]; // Recuperar la cantidad del formulario
    $codigo = $_POST["codigo"];
    $descripcion = $_POST["descripcion"];
    $fecha_ingreso = $_POST["fecha_ingreso"];
    $estado = $_POST["estado"];

    // Crear una conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Verificar si el nombre de usuario ya existe en la base de datos
    $sql_check_user = "SELECT id_usuario FROM tbl_ms_usuario WHERE nombre_usuario = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("s", $usuario_nombre);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    // Si el nombre de usuario ya existe, obtener su ID
    if ($result_check_user->num_rows > 0) {
        $row_user = $result_check_user->fetch_assoc();
        $usuario_id = $row_user["id_usuario"];
    } else {
        // Si el nombre de usuario no existe, insertarlo en la base de datos
        $sql_insert_user = "INSERT INTO tbl_ms_usuario (nombre_usuario) VALUES (?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->bind_param("s", $usuario_nombre);
        $stmt_insert_user->execute();

        // Obtener el ID del nuevo usuario insertado
        $usuario_id = $stmt_insert_user->insert_id;
    }

    // Insertar la solicitud en la base de datos
    $sql_insert_solicitud = "INSERT INTO tbl_solicitudes (usuario_id, idDepartamento, categoria_productos, cantidad, codigo, descripcion, fecha_ingreso, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_solicitud = $conn->prepare($sql_insert_solicitud);
    $stmt_insert_solicitud->bind_param("iiisssss", $usuario_id, $idDepartamento, $categoria_productos_id, $cantidad, $codigo, $descripcion, $fecha_ingreso, $estado);
    $stmt_insert_solicitud->execute();

    // Cerrar la conexión
    $conn->close();

    header("Location: solicitudes.php");
    exit(); // Asegura que el script se detenga después de la redirección   
}
?>
