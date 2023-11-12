<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Objetos</title>
    <script src="https://kit.fontawesome.com/8ea5d7998a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Lista de Objetos</h1>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID de Objeto</th>
                    <th>Nombre del Objeto</th>
                    <th>Descripción</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Modificación</th>
                    <th>Creado por</th>
                    <th>Modificado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../objetos/db_connect.php';

                // Consulta SQL para obtener la lista de objetos desde la base de datos
                $stmt = $conn->query("SELECT * FROM tbl_objetos");
                $objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($objetos as $objeto) {
                    echo "<tr>";
                    echo "<td>" . $objeto['ID_OBJETO'] . "</td>";
                    echo "<td>" . $objeto['NOMBRE_OBJETO'] . "</td>";
                    echo "<td>" . $objeto['DESCRIPCION'] . "</td>";
                    echo "<td>" . $objeto['FECHA_CREACION'] . "</td>";
                    echo "<td>" . $objeto['FECHA_MODIFICACION'] . "</td>";
                    echo "<td>" . $objeto['CREADO_POR'] . "</td>";
                    echo "<td>" . $objeto['MODIFICADO_POR'] . "</td>";
                    echo "<td>";
                    echo "<a href='editar_objeto.php?id=" . $objeto['ID_OBJETO'] . "'>Editar</a> | ";
                    echo "<a href='eliminar_objeto.php?id=" . $objeto['ID_OBJETO'] . "'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <br>
    <a href="crear_objeto.php">Crear Nuevo Objeto</a>
</body>
</html>
