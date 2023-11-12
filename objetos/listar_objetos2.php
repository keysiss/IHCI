<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Objetos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <!-- Incluye Font Awesome para los íconos -->
    <script src="https://kit.fontawesome.com/8ea5d7998a.js" crossorigin="anonymous"></script>
    <!-- Incluye jQuery para el manejo de eventos y AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluye jsPDF para la exportación a PDF -->
    <!-- Agrega esto en el archivo listar_objetos.php -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

  <!-- Botón para alternar el menú -->
  <button id="toggle-menu-btn"><i class="fas fa-bars"></i></button>

<!-- Menú lateral retráctil -->
<div class="sidebar">
    <h2>Menú</h2>
    <ul>
        <?php
        
        // Obtén la lista de objetos desde la base de datos
        include 'db_connect.php';
        $stmt = $conn->query("SELECT * FROM tbl_objetos");
        $objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Itera a través de los objetos y muestra cada uno en el menú
        foreach ($objetos as $objeto) {
            echo "<li>";
            echo "<a href='#'>";
            echo "<i class='fas fa-object-group'></i>"; // Ícono de FontAwesome
            echo $objeto['NOMBRE_OBJETO'];
            echo "</a>";
            echo "</li>";
        }
        ?>
    </ul>
</div>
    <div class="top-right">
        <!-- Enlace para crear un nuevo objeto en la parte superior derecha -->
        <a href="crear_objeto.php" class="create-new-object-link">Crear Nuevo Objeto</a>
    </div>


    <!-- Campo de búsqueda y botón -->
    <div class="search-container">
        <input type="text" id="search" name="search" placeholder="Buscar por nombre">
        <button onclick="searchObject()"><i class="fas fa-search"></i></button>
    </div>
</div>



    <div class="content">
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
                    include 'db_connect.php';

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
                        echo "<a href='editar_objeto.php?id=" . $objeto['ID_OBJETO'] . "' class='edit-link'><i class='fas fa-pencil-alt'></i></a>";
                        echo "<a href='eliminar_objeto.php?id=" . $objeto['ID_OBJETO'] . "' class='delete-link'><i class='fas fa-trash'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
