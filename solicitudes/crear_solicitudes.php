<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    
    <style>
    .menu li {
        margin-bottom: -1px;
    }

    .menu li a {
        padding: 0px 0px;
    }


</style>

    
    <title>Crear Solicitud</title>
    <style>
        .invoice {
            border-collapse: collapse;
            width: 100%;
        }
        .invoice th, .invoice td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        #descripcion {
            width: 100%;
            height: 150px; /* Ajusta la altura según sea necesario */
        }

        /* Estilo para el contenido principal */
.content {
    margin-left: 10%; /* Ajusta el margen izquierdo al 20% (o el valor que prefieras) */
    transition: margin-left 0.5s;
    padding: 0px;
    width: 80%; /* Ajusta el ancho del contenido principal al 80% (o el valor que prefieras) */
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
   
}

.custom-button {
    width: 150px; /* Establece el ancho deseado para los botones */
    height: 50px; /* Establece el alto deseado para los botones */
}


    </style>
</head>
<body>

<div class="content">
    <h2>Solicitud</h2>
    <form method="post" action="procesar_solicitud.php">
        <table class="invoice">
            <tr>
                <th>Información de Solicitud</th>
                <th>Fecha y Estado</th>
            </tr>
            <tr>
                <td>
                    <label for="idDepartamento">Departamento:</label>
                    <select id="idDepartamento" name="idDepartamento" required>
                    <option value="">Seleccione</option>
                    <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "gestion_compras2";
            
            // Hacer una consulta para obtener los departamentos desde la base de datos
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "SELECT id_departamento, nombre_departamento FROM tbl_departamentos";
            $result = $conn->query($sql);
            
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["id_departamento"] . '">' . $row["nombre_departamento"] . '</option>';
            }
            
            $conn->close();
        ?>
                    </select><br><br>
                    
                    <label for="usuario_nombre">Usuario:</label>
                    <input type="text" id="usuario_nombre" name="usuario_nombre" required><br><br>
                    
                    <label for="categoria_productos">Categoría de Productos:</label>
                    <select id="categoria_productos" name="categoria_productos" required>
                    <option value="">Seleccione</option>
                    <?php
            // Hacer una consulta para obtener las categorías desde la base de datos
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            $sql = "SELECT id, categoria FROM tbl_categorias";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["id"] . '">' . $row["categoria"] . '</option>';
            }

            $conn->close();
        ?>
                    </select><br><br>

                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" required><br><br>
                </td>
                <td>
                    <label for="codigo">Código:</label>
                    <input type="text" name="codigo" required><br><br>
                    
                    <label for="fecha_ingreso">Fecha de Ingreso:</label>
                    <input type="text" name="fecha_ingreso" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly><br><br>
                    
                    <label for="estado">Estado:</label>
                    <input type="text" name="estado"  required><br><br>
                </td>
            </tr>
        </table>
        
        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" required></textarea><br><br>
        
        <input type="submit" value="Crear" class="custom-button">
        <input type="button" value="Cancelar" class="btn btn-primary custom-button" onclick="window.location.href='solicitudes.php';">


    </form>
     
</div>
</body>
</html>





