<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>

    <!-- Carga de Bootstrap 5 y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.11/dist/interact.min.js"></script>

    <style>
    /* Estilos para el contenedor de los cuadros */
    .content {
        display: flex;
        flex-wrap: wrap;
    }

    /* Estilos para los cuadros arrastrables */
    .draggable-box {
        width: 150px;
        height: 150px;
        margin: 10px;
        color: #fff;
        border: 1px solid #2980b9;
        border-radius: 5px;
        text-align: center;
        cursor: pointer; /* Cambia el cursor a mano */
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        user-select: none; /* Evita la selección de texto al arrastrar */
    }

    /* Estilos para el cuadro azul */
    .blue {
        background-color: #3498db;
    }

    /* Estilos para el cuadro verde */
    .green {
        background-color: #4CAF50;
    }

    /* Estilos para el cuadro amarillo */
    .yellow {
        background-color: #FFD700; /* Amarillo (código de color) */
        color: #000; /* Color de texto */
    }

    /* Estilos para los íconos dentro de los cuadros */
    .draggable-box i {
        font-size: 48px;
        display: block; /* Para centrar verticalmente el ícono */
        margin: 0 auto; /* Para centrar horizontalmente el ícono */
    }

    /* Estilos para el nombre del ícono */
    .draggable-box p {
        font-size: 16px;
        margin: 0;
    }

    /* Estilos para los enlaces (anular los estilos predeterminados) */
    a {
        text-decoration: none; /* Eliminar el subrayado del enlace */
        color: inherit; /* Mantener el color del texto original */
    }

    /* Estilos para el candado */
    .lock-icon {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 16px;
        color: #fff;
        cursor: pointer;
    }
    </style>
</head>
<body>
    <div class="content">
        <div class="draggable-box blue" id="box1">
            <div class="icon-container">
                <i class="fas fa-user-shield"></i>
            </div>
            <i class="fas fa-lock lock-icon" id="lock1"></i>
            <p>Roles y Permisos</p>
        </div>
        <div class="draggable-box green" id="box2">
            <div class="icon-container">
                <i class="fas fa-user"></i>
            </div>
            <i class="fas fa-lock lock-icon" id="lock2"></i>
            <p>Usuario</p>
        </div>
        <div class="draggable-box yellow" id="box3">
            <div class="icon-container">
                <i class="fas fa-cube"></i>
            </div>
            <i class="fas fa-lock lock-icon" id="lock3"></i>
            <p>Objetos</p>
        </div>
    </div>

    <script>
    // Habilitar la interacción de arrastrar con Interact.js
    interact('.draggable-box')
        .draggable({
            // Límites del área de arrastre
            restrict: {
                restriction: 'parent',
                elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
            },
            onstart: function (event) {
                // Verificar si el candado está bloqueado
                var lockIcon = document.getElementById('lock' + event.target.id);
                if (lockIcon.classList.contains('locked')) {
                    event.preventDefault(); // Evita el inicio del arrastre
                }
            }
        });

    // Alternar el estado del candado al hacer clic en él
    interact('.lock-icon')
        .on('tap', function (event) {
            var lockIcon = event.target;
            if (lockIcon.classList.contains('locked')) {
                lockIcon.classList.remove('locked');
            } else {
                lockIcon.classList.add('locked');
            }
        });
    </script>
</body>
</html>
