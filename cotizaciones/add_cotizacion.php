<?php
include 'db_connection.php';

$idSolicitud = $_GET['id']; // Obtener el ID de solicitud desde la URL



// Realiza una consulta para contar cuántas cotizaciones hay para esta solicitud
$sqlCountCotizaciones = "SELECT COUNT(*) as count FROM tbl_cotizacion WHERE ID = $idSolicitud";
$resultCountCotizaciones = $conn->query($sqlCountCotizaciones);

if ($resultCountCotizaciones->num_rows > 0) {
    $rowCountCotizaciones = $resultCountCotizaciones->fetch_assoc();
    $numCotizaciones = $rowCountCotizaciones['count'];

    if ($numCotizaciones >= 3) {
        // Redirigir al usuario a la segunda pantalla cuando se han agregado 3 cotizaciones
        header("Location: mostrar_cotizacion.php?id=$idSolicitud");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesa el envío del formulario y agrega la cotización a la base de datos
    $idProveedor = $_POST['id_proveedor'];
    $numeroCotizacion = $_POST['numero_cotizacion'];
    $departamento = $_POST['departamento'];
    $fechaCotizacion = $_POST['fecha_cotizacion'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $estado = $_POST['ESTADO']; // Nuevo campo de estado
    $precio = $_POST['precio'];

    // Aquí debes agregar la cotización a la base de datos, incluyendo el campo de estado

    // Agrega la cotización a la base de datos
 $sql = "INSERT INTO tbl_cotizacion (ID, ID_PROVEEDOR, NUMERO_COTIZACION, DEPARTAMENTO, FECHA_COTIZACION, DESCRIPCION, CANTIDAD, ESTADO, PRECIO)
 VALUES ('$idSolicitud', '$idProveedor', '$numeroCotizacion', '$departamento', '$fechaCotizacion', '$descripcion', '$cantidad', '$estado' , '$precio')";


if ($conn->query($sql) === TRUE) {
    // La cotización se ha agregado con éxito
    header("Location: add_cotizacion.php?id=$idSolicitud");
    exit;
} else {
    // Si ocurre un error al agregar la cotización, puedes mostrar un mensaje de error
    echo "Error al agregar la cotización: " . $conn->error;
}

  
   
}

// Recupera y muestra información de la solicitud
$sqlSolicitud = "SELECT * FROM tbl_solicitudes WHERE id = $idSolicitud";
$resultSolicitud = $conn->query($sqlSolicitud);

if ($resultSolicitud->num_rows > 0) {
    $rowSolicitud = $resultSolicitud->fetch_assoc();
    $numeroSolicitud = $rowSolicitud['id']; // Agrega el número de solicitud
    $descripcionSolicitud = $rowSolicitud['descripcion']; // Agrega la descripción de la solicitud

    // Recupera la cantidad de la solicitud
    $sqlCantidadSolicitud = "SELECT CANTIDAD FROM tbl_solicitudes WHERE id = $idSolicitud";
    $resultCantidadSolicitud = $conn->query($sqlCantidadSolicitud);
    if ($resultCantidadSolicitud->num_rows > 0) {
        $rowCantidadSolicitud = $resultCantidadSolicitud->fetch_assoc();
        $cantidadSolicitud = $rowCantidadSolicitud['CANTIDAD'];
    } else {
        // Manejo de error si la cantidad no se encuentra en la base de datos
        // Puedes definir una cantidad predeterminada o manejar el error de otra manera
        $cantidadSolicitud = "Cantidad no disponible";
    }


   // Antes de mostrar el formulario para agregar la cotización, obtén el nombre del departamento de la solicitud.
$sqlDepartamentoSolicitud = "SELECT D.nombre_departamento
FROM tbl_solicitudes AS S
INNER JOIN tbl_departamentos AS D ON S.idDepartamento = D.id_departamento
WHERE S.id = $idSolicitud";
$resultDepartamentoSolicitud = $conn->query($sqlDepartamentoSolicitud);

if ($resultDepartamentoSolicitud->num_rows > 0) {
$rowDepartamentoSolicitud = $resultDepartamentoSolicitud->fetch_assoc();
$nombreDepartamentoSolicitud = $rowDepartamentoSolicitud['nombre_departamento'];
} else {
// Manejo de error si el departamento no se encuentra en la base de datos
$nombreDepartamentoSolicitud = "Departamento no disponible";
}


    // Puedes ejecutar otra consulta para recuperar y mostrar las cotizaciones asociadas aquí
    $sqlCotizaciones = "SELECT * FROM tbl_cotizacion WHERE id = $idSolicitud";
    $resultCotizaciones = $conn->query($sqlCotizaciones);


} else {
    echo "Solicitud no encontrada.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Cotización</title>
    <style>
        body {
            text-align: left;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10);
            background-image: url('../imagen/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            text-align: left;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
            background-color: powderblue;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .table {
        flex: 3; /* 48% para cada tabla con espacio entre ellas */
        margin: -5px;
       
        margin: right 1px; /* Ajusta el margen izquierdo negativo para mover las tablas a la izquierda */
        box-sizing: border-box;
        background-color: cornsilk;
    }

        table {
            width: 100%;
            border-collapse: collapse;
            
        }
        th, td {
            border: 0px solid black;
            padding: 8px;
        }



        th {
            background-color: bisque;
        }

        button-row {
            text-align: center;
            margin-top: 20px;
        }

        .table-title {
            text-align: center;
            font-weight: bold;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-field {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            padding: 5px;
        }

        .form-field label {
            width: 150px;
            text-align: right;
            padding-right: 10px;
        }

        .form-field input,
        .form-field select,
        .form-field textarea {
            flex: 1;
        }

        .right-align {
    text-align: left;
}

    </style>
</head>
<body>
<div class="container">
    <div class="table">
        <form method="post" action="">
            <table style="width: 40%; margin: 0 auto;">
                <h2 style="text-align: center;">Información de Solicitud</h2>
                <tr>
                    <th>Número:</th>
                    <td><?php echo $numeroSolicitud; ?></td>
                </tr>
                <tr>
                    <th>Descripción:</th>
                    <td><?php echo $descripcionSolicitud; ?></td>
                </tr>
                <tr>
                    <th>Cantidad:</th>
                    <td><?php echo $cantidadSolicitud; ?></td>
                </tr>
                <tr>
                    <th>Departamento:</th>
                    <td><?php echo $nombreDepartamentoSolicitud; ?></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="table">
        <h2 style="text-align: center;">Cotización</h2>
        <form method="post" action="">
            <table>
                <tr>
                    <th>Proveedor:</th>
                    <td>
                        <select name="id_proveedor" required style="max-width: 150px;">
                            <option value="">Seleccionar un proveedor</option>
                            <?php
                            // Ajusta la consulta para buscar proveedores con estado "A" (activo)
                            $sql = "SELECT ID_PROVEEDOR, NOMBRE FROM tbl_proveedores WHERE ESTADO_PROVEEDOR = 'A'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["ID_PROVEEDOR"] . "'>" . $row["NOMBRE"] . "</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No hay proveedores activos disponibles</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <th>Número de Cotización:</th>
                    <td><input type="text" name="numero_cotizacion" style="max-width: 100px;"></td>
                </tr>
                <tr>
                    <th>Departamento:</th>
                    <td><input type="text" name="departamento" value="<?php echo $nombreDepartamentoSolicitud; ?>" required style="max-width: 150px;"></td>
                    <th>Fecha de Cotización:</th>
                    <td><input type="date" name="fecha_cotizacion" style="max-width: 100px;"></td>
                </tr>
                
                <tr>
                    <th>Cantidad:</th>
                    <td><input type="text" name="cantidad" value="<?php echo $cantidadSolicitud; ?>" readonly style="max-width: 100px;"></td>
                    <th>Estado:</th>
                    <td><input type="text" name="ESTADO" required style="max-width: 100px;"></td>
                </tr>
                <tr>
                    <th>Monto:</th>
                    <td><input type="text" name="precio" required style="max-width: 100px;"></td>
                </tr>
                <tr>
                    <th>Descripción:</th>
                    <td colspan="3">
                        <textarea name="descripcion" required style="width: 100%; min-height: 150px;"></textarea>
                    </td>
                    <td >
                    <button type="submit">Agregar</button>
                    </td>
                    
                </tr>   
            </table>
            
        </form>
    </div>



</div>
</body>
</html>


