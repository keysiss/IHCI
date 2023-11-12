<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Objetos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
   
    <!-- Incluye Font Awesome para los íconos -->
    <script src="https://kit.fontawesome.com/8ea5d7998a.js" crossorigin="anonymous"></script>
    <!-- Incluye jQuery para el manejo de eventos y AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
    <style>
   /* Estilos generales */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}



/* Estilo para el menú lateral retráctil */
.sidebar {
    width: 250px;
    height: 100%;
    background-color: #1e3a5d;
    color: #fff;
    position: fixed;
    left: -250px;
    top: 0;
    transition: left 0.3s;
    overflow-y: auto;
    padding: 20px;
    z-index: 1000;
}

.sidebar h2 {
    margin-bottom: 20px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar li {
    margin-bottom: 10px;
}

.sidebar a {
    text-decoration: none;
    color: #fff;
}

.sidebar i {
    margin-right: 10px;
}

body.menu-visible .sidebar {
    left: 0;
}

/* Contenido principal */
.content {
    margin-left: 50px;
    padding: 20px;
}

/* Estilos para la tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #007bff;
    color: #fff;
}

/* Estilo para el contenedor de la parte superior derecha */
.top-right {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-right: 20px; /* Ajusta el margen derecho según sea necesario */
}

/* Estilo para el botón de búsqueda (ícono) */
.search-container button {
    padding: 5px;
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
}

/* Estilo para el contenedor de búsqueda */
.search-container {
    display: flex;
    align-items: center;
    margin-right: 10px; /* Espacio entre el botón de búsqueda y "Crear" */
}

/* Estilo para el botón "Crear Nuevo Objeto" */
.create-new-object-link {
    padding: 5px;
    text-decoration: none;
    background-color: #007bff;
    color: #fff;
    border-radius: 5px;
    margin-right: 10px;
}

/* Estilo para el botón "Descargar CSV" */
.download-link {
    padding: 5px 10px;
    text-decoration: none;
    background-color: #007bff;
    color: #fff;
    border-radius: 5px;
    display: inline-flex; /* Alinea el ícono y el texto en línea */
    align-items: center; /* Alinea verticalmente */
    margin-left: 10px; /* Ajusta el margen izquierdo según sea necesario */
}

/* Estilo para el ícono de Excel */
.download-link i {
    margin-right: 5px;
}


/* Estilos para el título y el ícono de objetos */
.title-container {
    display: flex;
    align-items: center;
}

h1 {
    text-align: left;
    margin: 0;
    font-size: 36px; /* Ajusta el tamaño del título según sea necesario */
    margin-right: 10px; /* Espacio entre el título y el icono */
}

/* Estilo para el ícono de objetos (ajusta la clase según corresponda) */
.title-container i {
    font-size: 24px; /* Ajusta el tamaño del icono según sea necesario */
}



/* Estilos para los enlaces "Editar" y "Eliminar" */
a.edit-link {
    color: #007bff; /* Color azul */
    text-decoration: none;
    margin-right: 10px; /* Espacio entre los enlaces */
}

a.edit-link:hover {
    text-decoration: underline; /* Subraya el enlace al pasar el mouse */
}

a.delete-link {
    color: #d9534f; /* Color rojo */
    text-decoration: none;
}

a.delete-link:hover {
    text-decoration: underline; /* Subraya el enlace al pasar el mouse */
}


/* Añade más estilos para otros colores personalizados según sea necesario */


    </style>
</head>
<body>
   
<div class="content">
    <h1><i class="fas fa-cube"></i> Objetos</h1>
    <div class="top-right">
        <!-- Campo de búsqueda y botón -->
        <div class="search-container">
            <input type="text" id="search" name="search" placeholder="Buscar por nombre">
            <button onclick="searchObject()"><i class="fas fa-search"></i></button>
        </div>
        <a href="crear_objeto.php" class="create-new-object-link">Crear +</a>
        <a href="exportar_csv.php" class="download-link">
            <i class="fas fa-download"></i>Excel
        </a>
    </div>
 
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
            <tbody class="object-list">
                <?php
                include '../objetos/db_connect.php';
                
                

                // Paginación
                $registrosPorPagina = 10;
                $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                $inicio = ($paginaActual - 1) * $registrosPorPagina;

                // Consulta SQL para obtener la lista de objetos paginada
                $stmt = $conn->prepare("SELECT * FROM tbl_objetos LIMIT :inicio, :registrosPorPagina");
                $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
                $stmt->bindParam(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
                $stmt->execute();
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
                    echo "<a href='editar_objeto.php?id=" . $objeto['ID_OBJETO'] . "' class='edit-link custom-color-1'><i class='fas fa-pencil-alt'></i></a>";
echo "<a href='eliminar_objeto.php?id=" . $objeto['ID_OBJETO'] . "' class='delete-link custom-color-2'><i class='fas fa-trash'></i></a>";
echo "<a href='estadisticas_objetos.php?id=" . $objeto['ID_OBJETO'] . "' class='view-link custom-color-3'><i class='fas fa-eye'></i></a>";


                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <?php
    // Calcula el número total de registros en la tabla
    $totalRegistros = $conn->query("SELECT COUNT(*) FROM tbl_objetos")->fetchColumn();
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

    echo '<div class="pagination">';
    if ($paginaActual > 1) {
        echo '<a href="?pagina=1">Primera</a>';
        echo '<a href="?pagina=' . ($paginaActual - 1) . '">Anterior</a>';
    }
    for ($i = 1; $i <= $totalPaginas; $i++) {
        echo '<a href="?pagina=' . $i . '"';
        if ($i == $paginaActual) {
            echo ' class="active"';
        }
        echo '>' . $i . '</a>';
    }
    if ($paginaActual < $totalPaginas) {
        echo '<a href="?pagina=' . ($paginaActual + 1) . '">Siguiente</a>';
        echo '<a href="?pagina=' . $totalPaginas . '">Última</a>';
    }
    echo '</div>';
    ?>
</div>

<script>
    function searchObject() {
        var searchTerm = $('#search').val(); // Obtiene el término de búsqueda del campo de entrada

        // Realiza la solicitud AJAX para buscar objetos por nombre
        $.ajax({
            type: 'POST',
            url: 'buscar_objetos.php', // Crea un archivo PHP para manejar la búsqueda
            data: { search: searchTerm },
            success: function (data) {
                // Actualiza la lista de objetos con los resultados de la búsqueda
                $('.object-list').html(data);
            }
        });
    }
</script>
</body>
</html>

