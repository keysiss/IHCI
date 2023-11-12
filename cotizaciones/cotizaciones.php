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
        /* Estilo para el botón */
#btnSolicitarAprobacion {
    padding: 8px 16px;
    background-color: #007bff; /* Color de fondo azul */
    color: #f2f2f2; /* Color de letra blanco */
    border: none; /* Sin borde */
    cursor: pointer; /* Cambiar el cursor al pasar sobre el botón */
    border-radius: 5px; /* Bordes redondeados */
    font-size: 16px; /* Tamaño de letra */
    float: right; /* Alineación del botón a la derecha */
}

/* Estilo para el botón al pasar el cursor sobre él */
#btnSolicitarAprobacion:hover {
    background-color: #0056b3; /* Color de fondo azul más oscuro al pasar el cursor */
}
    </style>

<style>
    .menu li {
        margin-bottom: -1px;
    }

    .menu li a {
        padding: 0px 0px;
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


    <h2 class="text-center">COTIZACIONES</h2>
    <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
        <div class="content" role="group" aria-label="First group">
        <a href="../administrador/reporte_cotizacion.php" target="_blank" style="text-decoration: none;">
        <button style="padding: 8px 16px;  background-color: #FF0000; color: #FFFFFF;">
            <i class="fas fa-file-pdf"  style="margin-right: 5px;"></i> 
        </button>
    </a>

    <a href="../administrador/reporte_excel_cotizacion.php" target="_blank" style="text-decoration: none;">
    <button style="padding: 8px 16px; background-color: #006400; color: #fff;">
        <i class="fas fa-file-excel" style="margin-right: 5px;"></i> 
    </button>
</a>

<a href="../administrador/imprimir_compra.php" target="_blank" style="text-decoration: none;">
<button  style="padding: 8px 16px; background-color: #808080; color: #fff;" >
    <i class="fas fa-print" style="margin-right: 5px;"></i> 
</button>
</a>
<a href="../cotizaciones/agregar_cotizacion.php"button class="print-button" class="plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></a>
        </div>

        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <link rel="stylesheet" href="styles.css">
    <a href="../cotizaciones/aprobacion_cotizaciones.php">
        <button id="btnSolicitarAprobacion">Solicitar Aprobación</button>
    </a>
</a>
<br>
<br>
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
        function mostrarMensaje($mensaje, $tipo) {
            echo '<div class="alert alert-' . $tipo . '">' . $mensaje . '</div>';
        }

        // Función para limpiar los datos enviados por el formulario
        function limpiarDatos($datos) {
            global $conexion;
            $datos = mysqli_real_escape_string($conexion, $datos);
            $datos = htmlspecialchars($datos);
            return $datos;
        }


        // Leer registros
        $sql = "SELECT * FROM tbl_cotizacion";
        $resultados = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($resultados) > 0) {
            echo '<table class="table table-striped">
                    <tr>
                    <th>ID_COTIZACION</th>
                    <th>NUMERO_COTIZACION</th>
                    <th>DEPARTAMENTO</th>
                    <th>FECHA COTIZACION</th>
                    <th>PROVEEDOR</th>
                    <th>DESCRIPCION</th>
                    <th>CANTIDAD</th>
                    <th>PRECIO U</th>
                    <th>TOTAL</th>
                    <th>OPCIONES</th>
            
                    </tr>';

            while ($row = mysqli_fetch_assoc($resultados)) {
                echo '<tr>
                        <td>' . $row['ID_COTIZACION'] . '</td>
                        <td>' . $row['NUMERO_COTIZACION'] . '</td>
                        <td>' . $row['DEPARTAMENTO'] . '</td>
                        <td>' . $row['FECHA_COTIZACION'] . '</td>
                        <td>' . $row['NOMBRE_PROVEEDOR'] . '</td>
                        <td>' . $row['DESCRIPCION'] . '</td>
                        <td>' . $row['CANTIDAD'] . '</td>
                        <td>' . $row['PRECIO'] . '</td>
                        <td>' . $row['TOTAL'] . '</td>
                        <td>
                        <a href="../cotizaciones/editar_cotizacion.php?id=' . $row['ID_COTIZACION'].'"class="btn btn-warning" ><i class="fas fa-edit"></i> </a>
                        <a href="../cotizaciones/eliminar_cotizacion.php?id=' . $row['ID_COTIZACION'] . '"class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
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
<h4>Agregar Nueva Cotización</h4>

<form action="../cotizaciones/agregar_cotizacion.php" method="POST">

    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Numero de Cotización</span>
        <input type="text" class="form-control" id="NUMERO_COTIZACION" name="NUMERO_COTIZACION" required>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Departamento</span>
        <input type="text" class="form-control" id="DEPARTAMENTO" name="DEPARTAMENTO" required>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Fecha Cotización</span>
        <input type="date" class="form-control" id="FECHA_COTIZACION" name="FECHA_COTIZACION" required>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Proveedor</span>
        <input type="text" class="form-control" id="NOMBRE_PROVEEDOR" name="NOMBRE_PROVEEDOR" required>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Descripción</span>
        <input type="text" class="form-control" id="DESCRIPCION" name="DESCRIPCION" required>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Cantidad</span>
        <input type="text" class="form-control" id="CANTIDAD" name="CANTIDAD" required>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Precio Unitario</span>
        <input type="text" class="form-control" id="PRECIO" name="PRECIO" required>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">Total</span>
        <input type="text" class="form-control" id="TOTAL" name="TOTAL" required>
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
            $idcotizacion = $_POST['ID_COTIZACION'];
            
            $numerocotizacion = limpiarDatos($_POST['NUMERO_COTIZACION']);
            $departamento = limpiarDatos($_POST['DEPARTAMENTO']);
            $fechacotizacion = limpiarDatos($_POST['FECHA_COTIZACION']);
            $proveedor = limpiarDatos($_POST['NOMBRE_PROVEEDOR']);
            $descripcion = limpiarDatos($_POST['DESCRIPCION']);
            $cantidad = limpiarDatos($_POST['CANTIDAD']);
            $precio = limpiarDatos($_POST['PRECIO']);
            $total = limpiarDatos($_POST['TOTAL']);

            $sql = "UPDATE tbl_cotizacion SET  NUMERO_COTIZACION='$numerocotizacion', DEPARTAMENTO='$departamento',
    FECHA_COTIZACION='$fechacotizacion', NOMBRE_PROVEEDOR='$proveedor', DESCRIPCION='$descripcion',
     CANTIDAD= '$cantidad', PRECIO= '$precio', TOTAL= '$total' WHERE ID_COTIZACION='$idcotizacion'";

    if (mysqli_query($conexion, $sql)) {
        mostrarMensaje("Registro actualizado exitosamente.", "success");
    } else {
        mostrarMensaje("Error al actualizar el registro: " . mysqli_error($conexion), "danger");
    }
}

        // Verificar si se recibió el ID del registro a eliminar
        if (isset($_GET['ID_COTIZACION'])) {
            $idcotizacion = $_GET['ID_COTIZACION'];

            $sql = "DELETE FROM tbl_cotizacion WHERE ID_COTIZACION='$idcotizacion'";

            if (mysqli_query($conexion, $sql)) {
                mostrarMensaje("Registro eliminado exitosamente.", "success");
            } else {
                mostrarMensaje("Error al eliminar el registro: " . mysqli_error($conexion), "danger");
            }
        }
        ?>
    </div>
    </div>
    <script src="../Js/script.js"></script>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script src="../Js/script.js"></script>


    <div class="floating-form" id="floatingForm" style="display: none;">

    


</body>


</html>