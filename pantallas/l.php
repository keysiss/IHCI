<?php
session_start(); // Inicializa la sesión

// Incluye la configuración de la base de datos
include("../views/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION["usuarioId"])) {
    $usuarioId = $_SESSION["usuarioId"];
    
    // Obtén el nombre del usuario desde la base de datos
    $sql = "SELECT nombre_usuario FROM tbl_ms_usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usuarioNombre = $row["nombre_usuario"];
        $_SESSION["usuarioNombre"] = $usuarioNombre; // Configura la variable de sesión
    } else {
        // Manejar el caso en que no se encuentra el nombre del usuario
        $usuarioNombre = "Nombre de Usuario Desconocido"; // Puedes establecer un valor por defecto
        $_SESSION["usuarioNombre"] = $usuarioNombre;
    }
} else {
    // El usuario no ha iniciado sesión, puedes redirigirlo a la página de inicio de sesión
    header("Location: index.php");
    exit();
}


// Función para verificar permisos
function tienePermiso($usuarioId, $objetoId, $conn) {
    // Obtener el ID del rol del usuario
    $sql = "SELECT rol FROM tbl_ms_usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rolUsuario = $row["rol"];

        // Consultar la tabla tbl_roles_permisos para verificar si el rol tiene permiso para el objeto
        $sql = "SELECT rp.id_permiso FROM tbl_roles_permisos rp
                INNER JOIN tbl_permisos p ON rp.id_permiso = p.id_permiso
                WHERE rp.id_rol = ? AND rp.id_objeto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $rolUsuario, $objetoId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    return false; // Si no se encuentra el usuario, devuelve false
}

// Función para obtener el ID de un objeto según su nombre
function obtenerIdObjetoSegunNombre($nombreObjeto, $conn) {
    $sql = "SELECT ID_OBJETO FROM tbl_objetos WHERE NOMBRE_OBJETO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombreObjeto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["ID_OBJETO"];
    }

    return null; // Devuelve null si no se encuentra el objeto
}

// Variable de estado para controlar la visibilidad del menú
$mostrarMenu = true;

// Lógica para ocultar/mostrar el menú (por ejemplo, cuando se hace clic en el botón)
if (isset($_POST["toggleMenu"])) {
    $mostrarMenu = !$mostrarMenu;
}

// Definir un arreglo con las rutas de las vistas permitidas
$rutasVistas = array(
   
    "Productos" => "../views/productos.php",
    "Proveedores" => "../pantallas/proveedores.php",
    "Solicitudes" => "../solicitudes/solicitudes.php",
    "Órdenes de compra" => "../pantallas/ordenes_de_compra.php",
    "Seguridad" => "../setting/ajustes.php",
    "Crear Solicitud" => "../solicitudes/crear_solicitudes.php",
    "Cotizacion" => "../pantallas/cotizacion.php",
    "Reportes" => "../pantallas/reportes.php",
    "Orden de Pago" => "../pantallas/orden_de_pago.php"
);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <!-- Agrega la biblioteca Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
   <style>

body {
   overflow: hidden;  /*quitar barra de desplazmiento lado derecho de la pagina.*/
}

.menu li {
    margin-bottom: -1px;
}

.menu li a {
    padding: 0px 0px;
}

/* Estilo para ocultar el menú al inicio */
.sidebar {
    width: 15%; /* Ajusta el ancho del menú al 15% */
    overflow: hidden;
    transition: 0.5s;
    position: fixed;
    height: 100%;
    background-color: #0047ab;
    z-index: 1;
    left: -15%; /* Inicialmente oculto a la izquierda */
}

/* Estilo para el contenido principal */
.content {
    margin-left: 15%; /* Ajusta el margen izquierdo a 0 (sin menú visible) */
    transition: margin-left 0.5s;
    padding: 0px;
    width: 85%; /* Ajusta el ancho del contenido al 100% */
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0; /* Sin margen izquierdo inicialmente */
  
}

 /* Estilo para ocultar la imagen de fondo cuando el menú está visible */
 .menu-visible .content {
    background-image: none; /* Oculta la imagen de fondo cuando el menú está visible */
}

/* Estilo para el botón de hamburguesa */
.menu-toggle {
    position: absolute;
    top: 10px;
    left: 20px; /* Ajusta la posición a la derecha */
    cursor: pointer;
    z-index: 2;
    color: black; /* Cambia el color a azul */
}

/* Estilos para el logotipo */
#logo {
    max-width: 30%; /* Ajusta el ancho máximo según tu preferencia */
    height: auto;
    display: block;
    margin: 30px 0 10px 20px; /* Ajusta el margen según tu preferencia */
}

/* Estilo para el iframe */
iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Estilo para mostrar el menú cuando se hace clic en el botón */
.show {
    left: 0; /* Muestra el menú desplazándolo a la izquierda */
}

/* Estilo para mostrar el contenido principal con margen izquierdo */
.menu-visible {
    margin-left: 15%; /* Ajusta el margen izquierdo al 15% */
}
</style>
    
    <style>
        /* Estilo para el icono de persona */
        .user-icon {
            font-size: 24px;
            color: #333;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 20px;
            z-index: 999; /* Coloca el icono en la capa superior */
        }

        /* Estilo para el menú desplegable de perfil */
        .user-menu {
            display: none;
            position: absolute;
            top: 40px;
            right: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 1000; /* Coloca el menú en la capa superior */
        }

        .user-menu a {
            display: block;
            margin-bottom: 5px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Icono de persona -->
    <i class="fas fa-user user-icon" id="userIcon"></i>

    <!-- Menú desplegable de perfil -->
    <div class="user-menu" id="userMenu" style="background-color: LightGray; padding: 10px;">
    <a href="#" style="text-align: center;"><strong><?php echo $usuarioNombre; ?></strong></a>
    <a href="../pantallas/contraseña_perfil.php"><strong>Cambiar Contraseña</strong></a>
    <a href="../pantallas/preguntas.php"><strong>Preguntas de Seguridad</strong></a>
    <a href="../index.php"><strong>Salir</strong></a>
    </div>

    <!-- Botón de hamburguesa -->
   <div class="menu-toggle" id="menu-toggle"  color: black;">
     <i class="fas fa-bars"></i>

   </div>

<div class="sidebar<?php echo $mostrarMenu ? ' show' : ''; ?>" id="sidebar">
    <ul class="menu">
        <img src="../imagen/ihci.jfif" alt="Logo de la empresa" id="logo">
        <li><a href="javascript:void(0);" onclick="window.location.reload();"><i class="fas fa-home"></i> Inicio</a></li>

        <?php
        // Iterar a través del arreglo de rutas de vistas permitidas y mostrarlas en el menú
        foreach ($rutasVistas as $nombreVista => $rutaVista) {
            // Asigna una clase de Font Awesome a cada vista
            $icono = '';
            if ($nombreVista === 'Productos') {
                $icono = 'fas fa-shopping-cart'; // Icono para Productos
            } elseif ($nombreVista === 'Proveedores') {
                $icono = 'fas fa-truck'; // Icono para Proveedores
            } elseif ($nombreVista === 'Solicitudes') {
                $icono = 'fas fa-list-alt'; // Icono para Solicitudes
            } elseif ($nombreVista === 'Órdenes de compra') {
                $icono = 'fas fa-file-invoice'; // Icono para Orden de Compra
            } elseif ($nombreVista === 'Seguridad') {
                $icono = 'fas fa-cog'; // Icono para Settings
            } elseif ($nombreVista === 'Crear Solicitud') {
                $icono = 'fas fa-file-alt'; // Icono para Crear Solicitud
            } elseif ($nombreVista === 'Cotizacion') {
                $icono = 'fas fa-file-invoice-dollar'; // Icono para Cotización
            } elseif ($nombreVista === 'Reportes') {
                $icono = 'fas fa-chart-line'; // Icono para Reportes
            } elseif ($nombreVista === 'Orden de Pago') {
                $icono = 'fas fa-coins'; // Icono para Cotización
            }
            
            // Verificar si el usuario tiene permiso para acceder a la vista
            $objetoId = obtenerIdObjetoSegunNombre($nombreVista, $conn);
            if (tienePermiso($usuarioId, $objetoId, $conn)) {
                // Genera el enlace con el icono y el nombre de la vista
                echo '<li><a href="' . $rutaVista . '" target="contenido"><i class="' . $icono . '"></i> ' . $nombreVista . '</a></li>';
            }
        }
        ?>
        <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i><span> Salir</span></a></li>
    </ul>
</div>



<!-- Contenido principal -->
<div class="content" id="content">
   
    <!-- Aquí se cargará la vista seleccionada en el iframe -->
    <iframe name="contenido" id="contenido" frameborder="0" scrolling="auto"></iframe>
    
  
</div>

<!-- Script para mostrar/ocultar el menú -->
<script>
        const userIcon = document.getElementById('userIcon');
        const userMenu = document.getElementById('userMenu');
        let menuVisible = false;

        userIcon.addEventListener('click', () => {
            if (!menuVisible) {
                userMenu.style.display = 'block';
                menuVisible = true;
            } else {
                userMenu.style.display = 'none';
                menuVisible = false;
            }
        });

        // Cerrar el menú cuando se haga clic en cualquier otro lugar de la página
        document.addEventListener('click', (e) => {
            if (menuVisible && e.target !== userIcon && e.target !== userMenu) {
                userMenu.style.display = 'none';
                menuVisible = false;
            }
        });
    </script>

<script>
    // Obtén el iframe por su ID
    var iframe = document.getElementById("contenido");

    // Agrega un evento "load" al iframe para cambiar el título cuando se carga una nueva página
    iframe.onload = function() {
        // Cambia el título de la página principal
        document.title = iframe.contentDocument.title;
    };

    // Establece la fuente del iframe
    iframe.src = "../imagen/imagen.html";
</script>


<script>
    // Función JavaScript para mostrar/ocultar el menú al hacer clic en el botón de hamburguesa
    document.getElementById('menu-toggle').addEventListener('click', function() {
       var sidebar = document.getElementById('sidebar');
       var content = document.getElementById('content');
        
        sidebar.classList.toggle('show');
        content.classList.toggle('menu-visible');
    });
</script>
</body>
</html>

