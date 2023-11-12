<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <link rel="stylesheet" href="../css/estilosProducto.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>

    <style>
  

    /* Estilo para el ícono de llave */
    .green-icon {
        color: green; /* Cambia el color aquí */
    }

    /* Estilo para el contenedor de botones */
   .button-container {
     display: flex;
     /*text-align: right; /* Alinea el contenido a la derecha */
     float: right; /* Alinea los elementos a la derecha */
     margin-right: 25px; /* Ajusta el espacio a la derecha según tus necesidades */
    }

   /* Hover (opcional): Cambiar los colores cuando el mouse esté sobre el botón */
   .delete-button:hover {
     background-color: #ff3333; /* Color de fondo rojo más claro */
     color: #ffffff; /* Color de texto blanco */
    }

    /* Estilo para el botón Asignar Permisos */
    .assign-button {
     background-color: yellowgreen; /* Color de fondo verde */
     color: #fff; /* Color del texto blanco */
    }

    
    .search-bar {
        margin-bottom: 20px;
    }

    .search-bar {
    margin-bottom: 20px; /* Ajusta la cantidad de margen según tus necesidades */
}

</style>
</head>
<body>
    <div class="content">
        <h1><span class="fas fa-key"></span>Roles</h1>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Buscar">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
            <a href="../roles/agregar_roles.php" class="print-button plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></a>
            <a href="pdf_roles.php" class="pdf-button"><i class="fas fa-file-pdf"></i></a>
            <button class="fa-download"><i class="fas fa-download"></i></button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Rol</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Modificación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexión a la base de datos
                $conexion = mysqli_connect("localhost", "root", "", "gestion_compras2");

                // Consulta para obtener los roles
                $consulta = mysqli_query($conexion, "SELECT * FROM tbl_ms_roles");

                // Arreglo para mapear las letras a las palabras completas
                $estados = array(
                    'A' => 'Activo',
                    'I' => 'Inactivo',
                    'B' => 'Bloqueado'
                );

                // Recorremos los resultados
                while ($rol = mysqli_fetch_assoc($consulta)) {
                    echo "<tr>";
                    echo "<td style='padding: 10px;'>" . $rol['ID_ROL'] . "</td>";
                    echo "<td>" . $rol['NOMBRE_ROL'] . "</td>";
                    echo "<td>" . $rol['FECHA_CREACION'] . "</td>";
                    echo "<td>" . $rol['FECHA_MODIFICACION'] . "</td>";
                    // Verifica si $rol['ESTADO_ROL'] es una clave válida en el array $estados
                    $estado = isset($estados[$rol['ESTADO_ROL']]) ? $estados[$rol['ESTADO_ROL']] : 'Desconocido';
                    echo "<td>" . $estado . "</td>";

                    echo "<td class='button-container'>";
                    echo "<a href='../roles/editar_roles.php?id=" . $rol['ID_ROL'] . "' class='styled-button edit-button'><i class='fas fa-edit'></i></a>";
                    echo "<form method='post' action='eliminar_roles.php'>";
                    echo "<input type='hidden' name='ID_ROL' value='" . $rol['ID_ROL'] . "'>";
                    echo "<button type='submit' name='eliminar' class='styled-button delete-button' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este rol?')\"><i class='fas fa-trash'></i></button>";
                    echo "</form>";
                    echo "<a href='../roles/asignar_permisos.php?id_rol=" . $rol['ID_ROL'] . "' class='styled-button assign-button'><i class='fas fa-key key-icon'></i></a>";
                    echo "</td>";

                    echo "</tr>";
                }

                // Cerramos la conexión
                mysqli_close($conexion);
                ?>
            </tbody>
        </table>
        <br>
        <button class="styled-button" onclick="window.location.href='../setting/ajustes.php'" style="background-color: #007bff; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">Regresar</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const searchButton = document.getElementById("searchButton");
            const rows = document.querySelectorAll("tbody tr");

            searchButton.addEventListener("click", function () {
                const searchText = searchInput.value.toLowerCase();

                rows.forEach(function (row) {
                    const cells = row.getElementsByTagName("td");
                    let rowMatch = false;

                    for (let i = 0; i < cells.length; i++) {
                        const cellText = cells[i].textContent.toLowerCase();
                        if (cellText.includes(searchText)) {
                            rowMatch = true;
                            break;
                        }
                    }

                    if (rowMatch) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>
</html>
