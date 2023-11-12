<?php
session_start();
    include('db.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['NOMBRE'];
        $direccion = $_POST['DIRECCION'];
        $telefono = $_POST['TELEFONO'];
        $correo = $_POST['CORREO_ELECTRONICO'];
        $estado_proveedor = $_POST['ESTADO_PROVEEDOR'];
    
        $query = "INSERT INTO tbl_proveedores (NOMBRE, DIRECCION, TELEFONO, CORREO_ELECTRONICO, ESTADO_PROVEEDOR) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssss", $nombre, $direccion, $telefono, $correo, $estado_proveedor);
    
        if ($stmt->execute()) {
            // Obtener el ID del proveedor recién insertado
            $id_proveedor = $conexion->insert_id;
    
            // Almacenar el nombre y el ID del proveedor en la sesión
            $_SESSION['NOMBRE'] = $nombre;
            $_SESSION['ID_PROVEEDOR'] = $id_proveedor;
    
            header("Location: crear_cuenta.php");
            exit;
        } else {
            echo "Error al guardar el proveedor: " . $stmt->error;
        }
        
        
    
        $stmt->close();
    }
    
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Nuevo Proveedor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10); /* Cambia el valor de "0.7" para ajustar la transparencia */
            background-image: url('../imagen/background.jpg'); /* Reemplaza con la ruta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
           
        
        }

        /* Estilos para centrar el título */
h2 {
    text-align: center; /* Centrar el texto horizontalmente */
}

        /* Estilos para el contenedor del formulario (el cuadro) */
.form-container {
    width: 80%; /* Ancho del contenedor */
    margin: 0 auto; /* Centrar horizontalmente en la página */
    padding: 20px; /* Espacio interno alrededor del formulario */
    border: 1px solid #ccc; /* Borde del cuadro */
    border-radius: 5px; /* Bordes redondeados */
    background-color: powderblue; /* Color de fondo del cuadro */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
    opacity: 0.9; /* Valor de opacidad (menos transparente) */
}


       

/* Estilos para la columna del formulario */
.form-column {
    float: left; /* Flotar las columnas a la izquierda */
    width: 50%; /* Ancho del 50% para ambas columnas */
    box-sizing: border-box; /* Incluir el relleno y el borde en el ancho */
    padding: 10px; /* Espacio interno entre elementos dentro de la columna */
}

/* Estilos para los campos de entrada (input) en la columna */
.form-column input {
    width: 90%; /* Ancho del 100% para llenar la columna completa */
    padding: 10px; /* Espaciado dentro de los campos de entrada */
    margin-bottom: 10px; /* Espacio inferior entre campos de entrada */
}

/* Estilos para las etiquetas (label) en la columna */
.form-column label {
    display: block; /* Mostrar las etiquetas en una nueva línea */
    margin-bottom: 5px; /* Espacio inferior entre etiquetas */
}

select{
            width: 95%;
            padding: 10px;
            margin-top: 1px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

/* Estilos para la sección de botones */
.button-section {
    clear: both; /* Limpiar el flotado para que los elementos debajo no floten */
    text-align: left; /* Alinear los botones a la izquierda */
    margin-top: 20px; /* Espacio superior para separar de las columnas */
}

/* Estilos para los botones */
button, .custom-button {
    padding: 10px 20px; /* Espaciado dentro de los botones */
    background-color: #007bff; /* Color de fondo del botón principal */
    color: #fff; /* Color de texto para el botón principal */
    border: none; /* Sin borde */
    cursor: pointer;
}

.cancel-button {
    background-color: #dc3545; /* Color de fondo para el botón de cancelar */
}

/* Agregar estilos de botón cuando se pasa el mouse sobre ellos */
button:hover, .custom-button:hover {
    background-color: #0056b3;
}



    </style>
</head>
<body>
<br><br>
<div class="form-container">
    <h2 style="text-align: center;">Proveedor</h2>

    <form method="POST" action="crear_proveedor.php">
        <div class="form-column">
          <label>Nombre:</label>
          <input type="text" name="NOMBRE" required><br>
          <label>Dirección:</label>
          <input type="text" name="DIRECCION"><br>
          <label>Teléfono:</label>
          <input type="text" name="TELEFONO"><br>
        </div>
        
        <div class="form-column">
        <label>Correo Electrónico:</label>
        <input type="email" name="CORREO_ELECTRONICO"><br>

        <label>Estado:</label>
        <select name="ESTADO_PROVEEDOR" required>
          <option value="">--Seleccione--</option>
          <option value="A">Activo</option>
          <option value="I">Inactivo</option>
          <option value="B">Bloqueado</option>
        </select><br>
        </div>

        
        <div class="button-section">
         <button type="submit">Guardar</button>
         <a href="listar_proveedores.php" class="custom-button cancel-button">Cancelar</a>
        </div>
        
    </form>
</div>
</body>
</html>
