<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilosProducto.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../estilos.js"></script>
    <title>Listado de Categorías</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Agregamos margen superior a la tabla */
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Estilos para el enlace de edición */
.edit-link {
    color: #007bff; /* Color del texto azul */
    text-decoration: none; /* Quitar el subrayado del enlace */
    margin-right: 10px; /* Espacio entre enlaces */
}

/* Estilos para el enlace de eliminación */
.delete-link {
    color: #dc3545; /* Color del texto rojo */
    text-decoration: none; /* Qitar el subrayado del enlace */
}

      
    </style>
</head>
<body>
   <div class="content">

   <?php
    $estados = array(
        'A' => 'Activo',
        'I' => 'Inactivo',
        'B' => 'Bloqueado',
        'N' => 'Nuevo'
      );
include 'db_connect.php';

// Inicializa la variable de búsqueda
$buscar = "";

// Manejo de la solicitud de búsqueda
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buscar = $_POST["buscar"];

    // Consulta SQL basada en la búsqueda en múltiples campos
    $sql = "SELECT d.id_departamento, e.nombre_empresa, d.nombre_departamento, d.estado_departamento, d.creado, d.fecha_creacion, d.fecha_modificacion, d.modificado_por 
            FROM tbl_departamentos d 
            JOIN tbl_empresa e ON d.id_empresa = e.id_empresa 
            WHERE d.id_departamento LIKE :buscar 
            OR d.nombre_departamento LIKE :buscar 
            OR e.nombre_empresa LIKE :buscar";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":buscar", "%$buscar%", PDO::PARAM_STR);
    $stmt->execute();
    $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Consulta predeterminada sin filtros de búsqueda
    $stmt = $conn->query("SELECT d.id_departamento, e.nombre_empresa, d.nombre_departamento, d.estado_departamento, d.creado, d.fecha_creacion, d.fecha_modificacion, d.modificado_por FROM tbl_departamentos d JOIN tbl_empresa e ON d.id_empresa = e.id_empresa");
    $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>

    <h2>Departamentos</h2>
        <div class="search-bar" style="margin-top: -25px; margin-bottom: -10px; text-align: right;">
             <!-- Formulario de búsqueda -->
            <form action="" method="POST">
              <label for="buscar"></label>
               <input type="text" name="buscar" value="<?php echo $buscar; ?>">
               <button type="submit" class="fas fa-search"></button>
            </form>
            <a href="crear_departamento.php" class="print-button plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i>Crear</a>
        </div>


<table >
    <tr>
        <th>ID Departamento</th>
        <th>Empresa</th>
        <th>Departamento</th>
        <th>Estado</th>
        <th>Creado por</th>
        <th>Fecha Creación</th>
        <th>Fecha Modificación</th>
        <th>Modificado por</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($departamentos as $departamento) { ?>
        <tr>
            <td><?php echo $departamento['id_departamento']; ?></td>
            <td><?php echo $departamento['nombre_empresa']; ?></td>
            <td><?php echo $departamento['nombre_departamento']; ?></td>
            <td><?php echo $estados[$departamento['estado_departamento']]; ?></td>
            <td><?php echo $departamento['creado']; ?></td>
            <td><?php echo date('d/m/Y', strtotime($departamento['fecha_creacion'])); ?></td>
            <td><?php echo date('d/m/Y', strtotime($departamento['fecha_modificacion'])); ?></td>
            <td><?php echo $departamento['modificado_por']; ?></td>
            <td>
                <a href="editar_departamento.php?id=<?php echo $departamento['id_departamento']; ?>" class="edit-link"><i class="fas fa-edit"></i></a>
                <a href="eliminar_departamento.php?id=<?php echo $departamento['id_departamento']; ?>" class="delete-link"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
    <?php } ?> 
</table>


</div> 
</body>
</html>
