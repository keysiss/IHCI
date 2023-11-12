<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "gestion_compras2");

// Verificar la conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Inicializar la variable de búsqueda y cantidad de productos por página
$buscar_codigo = '';
$productosPorPagina = 10;

// Obtener la página actual
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}

// Verificar si se ha enviado el formulario para cambiar la cantidad de productos por página
if (isset($_GET['cambiarProductosPorPagina'])) {
    $productosPorPagina = (int)$_GET['productosPorPagina'];
}

// Calcular el inicio de la consulta y la cantidad total de páginas
$inicio = ($pagina - 1) * $productosPorPagina;

// Consulta SQL para obtener la cantidad total de productos
$sqlTotal = "SELECT COUNT(*) AS total FROM tbl_productos";
$resultTotal = $conn->query($sqlTotal);
$rowTotal = $resultTotal->fetch_assoc();
$totalProductos = $rowTotal['total'];
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// Consulta SQL base
$sql = "SELECT id, codigo, descripcion, estado 
        FROM tbl_productos";

// Verificar si se ha enviado el formulario de búsqueda
if (isset($_GET["buscar"])) {
    // Obtener el valor del campo de código
    $buscar_codigo = $_GET["codigo"];

    // Validar que el código de búsqueda tenga un máximo de 10 caracteres
    if (strlen($buscar_codigo) > 10) {
        echo "El código de búsqueda no puede tener más de 10 caracteres.";
        exit(); // Detener la ejecución si el código es demasiado largo
    }

    // Consulta SQL para buscar por código
    $sql .= " WHERE codigo LIKE '%" . $buscar_codigo . "%'";
}

// Consulta SQL para obtener productos paginados con la cantidad ajustada por el usuario
$sql .= " LIMIT $inicio, $productosPorPagina";
$result = $conn->query($sql);

// Verificar si se produjo un error en la consulta SQL
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

?>

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

    <style>
    .menu li {
        margin-bottom: -1px;
    }

    .menu li a {
        padding: 0px 0px;
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
</style>
</head>
<body>
    <div class="content">
        <!-- Formulario de búsqueda y selección de productos por página -->
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h2 class="text-center">LISTA DE PRODUCTOS</h2>
        
            <div class="search-bar">
                <input type="text" name="codigo" placeholder="Buscar por código..." value="<?php echo $buscar_codigo; ?>">
                <button type="submit" name="buscar"><i class="fas fa-search"></i></button>
                <a href="pdf.php" class="pdf-button"><i class="fas fa-file-pdf"></i></a>
                <button class="excel-button"><i class="fas fa-file-excel"></i></button>
                <a href="../views/agregar_producto.php"button class="print-button" class="plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></a>
            </div>
            <label for="productosPorPagina">Productos por página:</label>
            <select name="productosPorPagina" id="productosPorPagina">
                <option value="10" <?php echo ($productosPorPagina == 10) ? "selected" : ""; ?>>10</option>
                <option value="20" <?php echo ($productosPorPagina == 20) ? "selected" : ""; ?>>20</option>
                <option value="30" <?php echo ($productosPorPagina == 30) ? "selected" : ""; ?>>30</option>
            </select>
            <button type="submit" name="cambiarProductosPorPagina" class="change-button">Cambiar</button>
        </form>

        <!-- Tabla de productos -->
        <table>
            <tr>
                <th></th>
                <th>Id</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php
            // Mostrar los productos en la tabla
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='productos_eliminar[]' value='" . $row["id"] . "'></td>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["codigo"] . "</td>";
                    echo "<td>" . $row["descripcion"] . "</td>";
                    echo "<td>" . $row["estado"] . "</td>";
                    echo "<td>";
                    echo "<a href='../views/editar_producto.php?id=" . $row['id'] . "' class='styled-button edit-button'><i class='fas fa-edit'></i></a>";

                        echo " <button type='submit' name='eliminar' class='styled-button delete-button'><i class='fas fa-trash'></i></button>";
                        echo "<a href='ver_producto.php'><buttonstyle='background-color: green; color: white;'><i class='fas fa-eye'></i></button></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No se encontraron productos</td></tr>";
            }
            ?>
        </table>
        
        <!-- Numeración de páginas debajo de la tabla -->
        <div class="pagination">
            <?php
            for ($i = 1; $i <= $totalPaginas; $i++) {
                echo "<a href='?pagina=$i&codigo=$buscar_codigo'";
                if ($i == $pagina) {
                    echo " class='active'";
                }
                echo ">$i</a>";
            }
            ?>
        </div>
    </div>
</body>
</html>