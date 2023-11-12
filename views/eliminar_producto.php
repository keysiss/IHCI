<?php
if (isset($_POST['eliminar']) && isset($_POST['id'])) {
    $ID = $_POST['id'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para eliminar el usuario
    $sql = "DELETE FROM tbl_productos WHERE id = $ID";
    if ($conn->query($sql) === TRUE) {
        // Redireccionar o mostrar un mensaje de éxito
        header("Location: ../views/productos.php"); // Cambia "roles.php" por la página donde se muestra la lista de roles
        exit();

        
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }

    $conn->close();
}
?>

