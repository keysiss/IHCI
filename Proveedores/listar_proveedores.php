<?php
include('db.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM tbl_proveedores WHERE 
          (ID_PROVEEDOR = ? OR NOMBRE LIKE ? OR DIRECCION LIKE ? OR TELEFONO = ? OR CORREO_ELECTRONICO LIKE ?)";

if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("issss", $search, $searchParam, $searchParam, $search, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conexion->query("SELECT * FROM tbl_proveedores");
}

// Resto de tu código para mostrar los resultados




?>



<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="../estilos.js"></script>
<title>Listado de Proveedores</title>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: left;
            color: black;
            padding: 10px;
            margin-left: 110px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #0078d4;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #0078d4;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #0078d4;
            color: #fff;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            display: inline-block;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button-container {
            display: flex;
            justify-content: flex-end; /* Cambia space-between a flex-end */
            align-items: center;
            margin: -20px 0; /* Ajusta el margen superior para alinear más cerca de la tabla */
            padding: 10px;
            margin-right: 110px;
           
            
        }

        .button-container label {
            margin-right: 190px;
        }

        .button-container input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: -0px;
        }

        .button-container form {
            display: flex;
            align-items: center;
            margin-right: 1px;
        }

        .button-container button {
    margin-left: -0px; /* Ajusta el margen izquierdo */
    border-radius: 20px; /* Ajusta el valor del border-radius para dar forma ovalada */
    
}

/* Estilo específico para el botón "Crear Nuevo Proveedor" */
.btn-crear {
    background-color: #4CAF50; /* Cambia el color del botón a verde */
    color: #fff; /* Cambia el color del texto a blanco */
    padding: 10px 20px; /* Ajusta el relleno para darle espacio al ícono y el texto */
    border: none; /* Quita el borde para que se vea como un botón simple */
    border-radius: 20px; /* Da forma ovalada al botón */
}




        .action-link-blue,
    .action-link-green,
    .action-link-red {
        text-decoration: none;
    }

    .action-link-blue i,
    .action-link-green i,
    .action-link-red i {
        margin-right: 5px; /* Añade un espacio entre el icono y el texto */
    }

    .action-link-blue {
        color: #0078d4; /* Azul para "Ver" */
    }

    .action-link-green {
        color: #4CAF50; /* Verde para "Editar" */
    }

    .action-link-red {
        color: #FF0000; /* Rojo para "Eliminar" */
    }

    .action-link-blue:hover,
    .action-link-green:hover,
    .action-link-red:hover {
        text-decoration: underline;
    }

    /* Estilo para el selector de cantidad de proveedores por página */
.select-por-pagina {
    padding: -10px;
    border: 1px solid #ccc;
    border-radius: 1px;
    margin-left: -5px;
}

/* Estilo para el label "Proveedores por página" */
label[for="por_pagina"] {
    display: inline-block; /* Alinea el label en línea con el select */
    font-weight: bold; /* Hace que el texto sea más destacado */
    margin-left: 120px; /* Añade espacio del label */
    color: #0078d4; /* Cambia el color del texto a azul */
}

/* Estilo para centrar la paginación */
.pagination-center {
    text-align: center; /* Centra el contenido horizontalmente */
    margin-right: -900px; /* Agrega margen superior para alinear más cerca de la tabla */
}


    </style>

</head>
<body>
<h2><i class="fas fa-truck"></i> Proveedores</h2>

    <div class="button-container">

       <div class="search-container">
          <form method="GET">
              <label for="search"></label>
              <input type="text" id="search" name="search" placeholder=" búsqueda" value="<?php echo $search; ?>">
              <button type="submit" name="submit" class="fas fa-search"></button>

            </form>
        </div>  
        <a href="crear_proveedor.php"><button class="btn-crear"><i class="fas fa-plus"></i> Crear</button></a>
    </div>
   
   
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Correo Electrónico</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php
      
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['ID_PROVEEDOR'] . '</td>';
                    echo '<td>' . $row['NOMBRE'] . '</td>';
                    echo '<td>' . $row['DIRECCION'] . '</td>';
                    echo '<td>' . $row['TELEFONO'] . '</td>';
                    echo '<td>' . $row['CORREO_ELECTRONICO'] . '</td>';
                    // Mapeo de la letra a la palabra correspondiente para ESTADO_PROVEEDOR
                    $estado_proveedor = '';
                    if ($row['ESTADO_PROVEEDOR'] == 'A') {
                        $estado_proveedor = 'Activo';
                    } elseif ($row['ESTADO_PROVEEDOR'] == 'I') {
                        $estado_proveedor = 'Inactivo';
                    } elseif ($row['ESTADO_PROVEEDOR'] == 'B') {
                        $estado_proveedor = 'Bloqueado';
                    }
                    echo '<td>' . $estado_proveedor . '</td>';
                    echo "<td><a href='ver_proveedor.php?id=" . $row['ID_PROVEEDOR'] . "' class='action-link-blue'><i class='fas fa-eye'></i></a>";
                    echo "<a href='actualizar_proveedor.php?id=" . $row['ID_PROVEEDOR'] . "' class='action-link-green'><i class='fas fa-edit'></i></a>";
                    echo "<a href='eliminar_proveedor.php?id=" . $row['ID_PROVEEDOR'] . "' class='action-link-red'><i class='fas fa-trash-alt'></i></a></td>";
                    echo '</tr>';
                }
          // Verifica si no se encontraron resultados
        if ($result->num_rows === 0) {
            echo '<tr><td colspan="7">No se encontraron resultados.</td></tr>';
        }
           
     

    ?>
    </table>
    
    </div>

    

</body>
</html>











