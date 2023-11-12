<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
    body {
        text-align: center;
        font-family: Arial, sans-serif;
        background: rgba(255, 255, 255, 0.10);
        background-image: url('../imagen/background.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center; /* Centra horizontalmente */
        align-items: center; /* Centra verticalmente */
        min-height: 100vh;
    }

    .container {
        text-align: center;
        border: 1px solid #ccc;
        padding: 20px;
        background-color: powderblue;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        opacity: 0.9;
        max-width: 800px; /* Ajusta el ancho máximo según tus necesidades */
    }

    .table {
        width: 100%;
        box-sizing: border-box;
        background-color: cornsilk;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .centered-message {
        text-align: center;
        margin-bottom: 20px;
    }

    .btn-container {
        display: flex;
        justify-content: center; /* Centra horizontalmente */
        margin-top: 10px;
    }

    .btn {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 0 10px; /* Espacio entre botones */
    }

    .form-group {
        width: 90%;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-group label {
        
        flex: 1;
        text-align: right;
        margin-left: 40px;
        font-weight: bold;
    }

    .form-group input, .form-group select, .form-group textarea {
        flex: 2;
        width: 80%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
</style>
</head>
<body>
<div class="container">
    <div class="table">
        <?php
        // Reemplaza estos valores con tus credenciales de base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gestion_compras2";

        function obtenerNombreUsuario($conn, $usuario_id) {
            $sql = "SELECT nombre_usuario FROM tbl_ms_usuario WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["nombre_usuario"];
        }

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
            $solicitud_id = $_GET["id"];

            // Crear una conexión a la base de datos
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            // Obtener los datos de la solicitud a editar
            $sql = "SELECT * FROM tbl_solicitudes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $solicitud_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();

                // Obtener los datos de la solicitud
                $idDepartamento = $row["idDepartamento"];
                $usuario_id = $row["usuario_id"];
                $categoria_productos_id = $row["categoria_productos"];
                $codigo = $row["codigo"];
                $cantidad = $row["cantidad"];
                $descripcion = $row["descripcion"];
                $estado = $row["estado"];

                // Consultas para obtener información de departamentos y categorías
                $sql_departamentos = "SELECT id_departamento, nombre_departamento FROM tbl_departamentos";
                $result_departamentos = $conn->query($sql_departamentos);

                $sql_categorias = "SELECT id, categoria FROM tbl_categorias";
                $result_categorias = $conn->query($sql_categorias);

                // Generar el formulario para editar la solicitud
                echo "<form method='post' action='guardar_edicion_solicitud.php'>";
                echo '<div class="centered-message"><label>SOLICITUD</label></div>';
                echo "<input type='hidden' name='solicitud_id' value='$solicitud_id'>";

                echo "<div class='form-group'><label for='codigo'>Código:</label><input type='text' name='codigo' value='$codigo' required></div>";
                echo "<div class='form-group'><label for='idDepartamento'>Departamento:</label><select id='idDepartamento' name='idDepartamento' required>";

                while ($row_departamento = $result_departamentos->fetch_assoc()) {
                    $selected = ($row_departamento["id_departamento"] == $idDepartamento) ? "selected" : "";
                    echo "<option value='" . $row_departamento["id_departamento"] . "' $selected>" . $row_departamento["nombre_departamento"] . "</option>";
                }

                echo "</select></div>";

                $usuario_nombre = obtenerNombreUsuario($conn, $usuario_id);
                echo "<div class='form-group'><label for='usuario_nombre'>Usuario:</label><input type='text' id='usuario_nombre' name='usuario_nombre' value='$usuario_nombre' required></div>";

                echo "<div class='form-group'><label for='categoria_productos'>Categoría:</label><select id='categoria_productos' name='categoria_productos' required>";

                while ($row_categoria = $result_categorias->fetch_assoc()) {
                    $selected = ($row_categoria["id"] == $categoria_productos_id) ? "selected" : "";
                    echo "<option value='" . $row_categoria["id"] . "' $selected>" . $row_categoria["categoria"] . "</option>";
                }

                echo "</select></div>";

                echo "<div class='form-group'><label for='cantidad'>Cantidad:</label><input type='text' name='cantidad' value='$cantidad' required></div>";
                echo "<div class='form-group'><label for='estado'>Estado:</label><input type='text' name='estado' value='$estado' required></div>";

                echo "<div class='form-group'><label for='descripcion'>Descripción:</label><textarea name='descripcion' cols='50' rows='5' required>$descripcion</textarea></div>";

                echo '<div class="btn-container">';
                echo '<input type="submit" value="Guardar" class="btn btn-primary" style="width: 20%;" >';
                echo '<a href="solicitudes.php" class="btn btn-secondary" style="width: 20%; background-color: gray; color: white;">Cancelar</a>';
                echo '</div>';

                echo "</form>";
            } else {
                echo "Solicitud no encontrada.";
            }

            // Cerrar la conexión
            $conn->close();
        } else {
            echo "ID de solicitud no proporcionado.";
        }
        ?>
    </div>
</div>
</body>
</html>

