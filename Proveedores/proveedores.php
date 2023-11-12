<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../Js/estilos.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    
    <style>
    .menu li {
        margin-bottom: -1px;
    }

    .menu li a {
        padding: 0px 0px;
    }
</style>
    <style>
        /* Estilos adicionales */
        .floating-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .floating-form .close-button {
            position: relative;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #dc3545;
        }

        .floating-form .close-button:hover {
            color: #721c24;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            justify-content: right;
        }
    </style>
</head>


<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <element class="logo">
                <img src="../imagen/ihci.jfif">
            </element>
            <button class="menu-toggle" onclick="toggleSidebar()">&#9776;
                <span class="menu-icon"></span>
            </button>
        </div>
        <ul class="menu">
        <li><a href="../pantallas/admin.php"><i class="fas fa-home"></i><span> Inicio </span></a></li>
            <li><a href="../cotizaciones/solicitudes.php"><i class="fas fa-envelope"></i><span> Solicitudes</span></a></li>
            <li><a href="./views/crear_solicitud.php"><i class="fas fa-plus"></i><span> Crear Solicitud</span></a></li>
            <li><a href="../cotizaciones/cotizaciones.php"><i class="fas fa-file-alt"></i><span> Cotizaciones</span></a></li>
            <li><a href="../proveedores/proveedores.php"><i class="fas fa-users"></i><span> Proveedores</span></a></li>
            <li><a href="../views/productos.php"><i class="fas fa-cubes"></i><span> Productos</span></a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i><span> Reportes</span></a></li>
            <li><a href="../setting/ajustes.php"><i class="fas fa-cog"></i><span> Settings</span></a></li>
            <li><a href="#"><i class="fas fa-bell"></i><span> Mis Notificaciones</span></a></li>
            <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i><span> Salir</span></a></li>
            <li><div><a href="#"><i class="fas fa-question-circle"></i><span> Help</span></a></div></li>
        </ul>
    </div>


    <h2 class="text-center">PROVEEDORES</h2>

    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="content" role="group" aria-label="First group">

            <a href="../administrador/reporte_proveedores.php" target="_blank" style="text-decoration: none;">
                <button style="padding: 8px 16px;  background-color: #FF0000; color: #FFFFFF;">
                    <i class="fas fa-file-pdf" style="margin-right: 5px;"></i>
                </button>
            </a>

            <a href="../administrador/reporte_excel_proveedores.php" target="_blank" style="text-decoration: none;">
                <button style="padding: 8px 16px; background-color: #006400; color: #fff;">
                    <i class="fas fa-file-excel" style="margin-right: 5px;"></i>
                </button>
            </a>

            <a href="../administrador/imprimir_proveedores.php" target="_blank" style="text-decoration: none;">
                <button style="padding: 8px 16px; background-color: #808080; color: #fff;" onclick=imprimirTabla()>
                    <i class="fas fa-print" style="margin-right: 5px;"></i>
                </button>
            </a>

            <button class="plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></button>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="content">
        <div class="table-container">

            <tbody>

                <br>
                <?php
                // Conexión a la base de datos
                $conexion = mysqli_connect("localhost", "root", "", "gestion_compras2");

                // Verificar la conexión
                if (!$conexion) {
                    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
                }

                // Función para mostrar mensajes de éxito o error
                function mostrarMensaje($mensaje, $tipo)
                {
                    echo '<div class="alert alert-' . $tipo . '">' . $mensaje . '</div>';
                }

                // Función para limpiar los datos enviados por el formulario
                function limpiarDatos($datos)
                {
                    global $conexion;
                    $datos = mysqli_real_escape_string($conexion, $datos);
                    $datos = htmlspecialchars($datos);
                    return $datos;
                }


                // Leer registros
                $sql = "SELECT * FROM tbl_proveedores";
                $resultados = mysqli_query($conexion, $sql);

                if (mysqli_num_rows($resultados) > 0) {
                    echo '<table class="table table-striped">
                    <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Correo Electronico</th>
                    <th>Creado por</th>
                    <th>Fecha Creacion</th>
                    <th>Modificado por</th>
                    <th>Fec Modificacion</th>
                    <th>Opciones</th>
            
                    </tr>';

                    while ($row = mysqli_fetch_assoc($resultados)) {
                        echo '<tr>
                        <td>' . $row['ID_PROVEEDOR'] . '</td>
                        <td>' . $row['NOMBRE'] . '</td>
                        <td>' . $row['DIRECCION'] . '</td>
                        <td>' . $row['TELEFONO'] . '</td>
                        <td>' . $row['CORREO_ELECTRONICO'] . '</td>
                        <td>' . $row['CREADO_POR'] . '</td>
                        <td>' . $row['FECHA_CREACION'] . '</td>
                        <td>' . $row['MODIFICADO_POR'] . '</td>
                        <td>' . $row['FECHA_MODIFICACION'] . '</td>
                        <td>
                        <a href="../proveedores/editar_proveedores.php?id=' . $row['ID_PROVEEDOR'] . '"class="btn btn-warning" >
                        <i class="fas fa-edit"></i> 
                        </a>
                        <a href="../proveedores/eliminar_proveedores.php?id=' . $row['ID_PROVEEDOR'] . '"class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i>
                    </a>
            
                    

                        </td>
                    </tr>';
                    }

                    echo '</table>';
                } else {
                    mostrarMensaje("No se encontraron registros.", "info");
                }

                // Cerrar conexión a la base de datos
                mysqli_close($conexion);
                ?>

                <!-- ... -->
                <form method="POST" action="" enctype="multipart/form-data">
                    <!-- ... -->
                </form>

                <div class="content">
                    <div class="table-container">
                        <table>
                            <!-- Contenido de la tabla -->
                        </table>
                    </div>

                    <?php
                    // Datos de ejemplo para la paginación
                    $totalSolicitudes = 50; // Total de registros en la base de datos
                    $registrosPorPagina = 10; // Número de registros por página
                    $totalPaginas = ceil($totalSolicitudes / $registrosPorPagina); // Cálculo del número total de páginas

                    // Obtener el número de página actual (por ejemplo, a través de parámetro GET)
                    $paginaActual = isset($_GET['page']) ? $_GET['page'] : 1;

                    // Calcular el índice de inicio del primer registro en la página actual
                    $indiceInicio = ($paginaActual - 1) * $registrosPorPagina;

                    // Obtener los registros de la página actual de la base de datos (puedes ajustar esto según tu lógica y estructura de datos)
                    // $registros = obtenerRegistrosDesdeLaBaseDeDatos($indiceInicio, $registrosPorPagina);

                    // Mostrar los registros en la tabla
                    // foreach ($registros as $registro) {
                    //     // Generar filas de la tabla
                    //     // ...
                    // }
                    ?>

                    <div class="pagination">
                        <?php
                        // Botón de retroceso "<"
                        if ($paginaActual > 1) {
                            echo '<a href="?page=' . ($paginaActual - 1) . '"><i class="fas fa-chevron-left"></i></a>';
                        }

                        // Generar enlaces de paginación
                        for ($i = 1; $i <= $totalPaginas; $i++) {
                            echo '<a href="?page=' . $i . '" ' . ($i == $paginaActual ? 'class="current"' : '') . '>' . $i . '</a>';
                        }

                        // Botón de avance ">"
                        if ($paginaActual < $totalPaginas) {
                            echo '<a href="?page=' . ($paginaActual + 1) . '"><i class="fas fa-chevron-right"></i></a>';
                        }
                        ?>
                    </div>
                </div>
        </div>
    </div>
    <div class="floating-form" id="floatingForm" style="display: none;">

        <h4>Agregar Proveedores</h4>

        <form action="/Proveedores/agregar_proveedores.php" method="POST">

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Nombre</span>
                <input type="text" class="form-control" id="CREADO_POR" name="nombre" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Direccion</span>
                <input type="texto" class="form-control" id="DIRECCION" name="direccion" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Telefono</span>
                <input type="text" class="form-control" id="TELEFONO" name="telefono" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Correo Electronico</span>
                <input type="text" class="form-control" id="CORREO_ELECTRONICO" name="correo_electronico" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Creado Por</span>
                <input type="text" class="form-control" id="CREADO_POR" name="creado_por" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Fecha Creacion</span>
                <input type="date" class="form-control" id="FECHA_CREACION" name="fecha_creacion" required>
            </div>
            <!-- Aquí puedes agregar más campos del formulario según tus necesidades -->

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Agregar</button>
                <button class="btn btn-danger ml-auto" onclick="toggleFloatingForm()">Cerrar</button>
            </div>
        </form>

        <?php
        // Verificar si se envió la solicitud de actualización

        if (isset($_POST['actualizar'])) {
            $idproveedor = $_POST['id_proveedor'];
            $nombre = limpiarDatos($_POST['nombre']);
            $direccion = limpiarDatos($_POST['direccion']);
            $telefono = limpiarDatos($_POST['telefono']);
            $correoelectronico = limpiarDatos($_POST['correo_electronico']);
            $fechacreacion = limpiarDatos($_POST['fecha_creacion']);
            $creadopor = limpiarDatos($_POST['creado_por']);

            $sql = "UPDATE tbl_proveedores SET NOMBRE='$nombre', DIRECCION='$direccion',
            TELEFONO='$telefono', CORREO_ELECTRONICO='$correoelectronico', FECHA_CREACION='$fechacreacion', CREADO_POR='$creadopor' WHERE ID_PROVEEDOR='$idproveedor'";

            if (mysqli_query($conexion, $sql)) {
                mostrarMensaje("Registro actualizado exitosamente.", "success");
            } else {
                mostrarMensaje("Error al actualizar el registro: " . mysqli_error($conexion), "danger");
            }
        }

        // Verificar si se recibió el ID del registro a eliminar
        if (isset($_GET['id'])) {
            $idproveedor = $_GET['id'];

            $sql = "DELETE FROM tbl_proveedores WHERE ID_PROVEEDOR='$idproveedor'";

            if (mysqli_query($conexion, $sql)) {
                mostrarMensaje("Registro eliminado exitosamente.", "success");
            } else {
                mostrarMensaje("Error al eliminar el registro: " . mysqli_error($conexion), "danger");
            }
        }
        ?>
    </div>
    <script src="../Js/script.js"></script>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script src="../Js/script.js"></script>
</body>


</html>