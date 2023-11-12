
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

        .edit-link {
          background-color: #28a745; /* Estilo para Editar */
          color: #fff;
          padding: 5px 10px;
          text-decoration: none;
          border-radius: 5px;
          margin-right: 5px; /* Agregamos margen derecho para separar los enlaces */
        }

        .delete-link {
          background-color: #dc3545; /* Estilo para Eliminar */
           color: #fff;
           padding: 5px 10px;
           text-decoration: none;
          border-radius: 5px;
        }

        /* Estilos al pasar el ratón sobre los enlaces */
       .edit-link:hover {
          background-color: #1e7e34;
        }

        .delete-link:hover {
         background-color: #c82333;
        }

      
    </style>
</head>
<body>
   <div class="content">
        <?php
         include 'db_connect.php';

          // Procesar la búsqueda
           if (isset($_GET['busqueda'])) {
               $busqueda = $_GET['busqueda'];
                $stmt = $conn->prepare("SELECT * FROM tbl_categorias WHERE id = :busqueda OR categoria LIKE :busqueda");
                $stmt->bindValue(':busqueda', $busqueda);
                $stmt->execute();
                $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
             // Si no se ha realizado una búsqueda, muestra todas las categorías
              $stmt = $conn->query("SELECT * FROM tbl_categorias");
               $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        ?>

        <h1><span class="fas fa-folder"></span>Categorías</h1>
        <div class="search-bar" style="margin-top: -25px; margin-bottom: -10px; text-align: right;">
             <!-- Formulario de búsqueda -->
            <form action="" method="get">
              <label for="busqueda"></label>
              <input type="text" id="busqueda" name="busqueda" placeholder="Ingrese ID o Categoría">
              <button type="submit" class="fas fa-search"></button>
            </form>
            <a href="crear_categoria.php" class="print-button plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i>Crear</a>
        </div>
   
   
   
        <table>
          <tr>
             <th>ID</th>
             <th>Categoría</th>
             <th>Creado</th>
             <th>Fecha de Creación</th>
             <th>Fecha de Modificación</th>
             <th>Modificado</th>
             <th>Acciones</th>
            </tr>
           <?php foreach ($categorias as $categoria) { ?>
               <tr>
                 <td><?php echo $categoria['id']; ?></td>
                 <td><?php echo $categoria['categoria']; ?></td>
                 <td><?php echo $categoria['creado']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime($categoria['fecha_creacion'])); ?></td>
                 <td><?php echo date("d/m/Y", strtotime($categoria['fecha_modificacion'])); ?></td>
                 <td><?php echo $categoria['modificado']; ?></td>
                 <td>
                    <a href="editar_categoria.php?id=<?php echo $categoria['id']; ?>" class="edit-link"><i class="fas fa-edit"></i></a>
                    <a href="eliminar_categoria.php?id=<?php echo $categoria['id']; ?>" class="delete-link"><i class="fas fa-trash-alt"></i></a>
                 </td>
                </tr>
            <?php } ?>
        </table>

    </div> 
</body>
</html>
