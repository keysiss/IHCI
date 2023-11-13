<?php
require_once '../administrador/libreria/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Función para obtener los datos de la tabla de compras desde la base de datos
function obtenerDatostbl_cotizacion()
{
    $servidor = 'localhost';
    $bd = 'gestion_compras2';
    $usuario = 'root';
    $contrasena = '';

    
    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$bd", $usuario, $contrasena);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Realizar la consulta para obtener solo algunos datos específicos de la tabla de compras
        $consulta = "SELECT ID_COTIZACION,NUMERO_COTIZACION, DEPARTAMENTO,
        FECHA_COTIZACION, NOMBRE_PROVEEDOR, DESCRIPCION,
         CANTIDAD, PRECIO, TOTAL FROM tbl_cotizacion";
        $stmt = $conexion->prepare($consulta);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $datos;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        exit;
    }
}


     // Generar la tabla HTML a partir de los datos
function generateTableHTML($data)
{
    $html = '<table class="table table-bordered table-striped">'; // Agregar clases de Bootstrap a la tabla
    $html .= '<thead class="thead-light">'; // Clase para el encabezado de la tabla en Bootstrap
    $html .= '<tr>';
    $html = '<table style="width: 100%; border-collapse: collapse;">'; // Establecer el ancho de la tabla al 100%
    $html .= '<thead style="background-color: #f2f2f2;">';
    $html .= '<tr>';

    // Encabezado personalizado de la tabla
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">ID </th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Número Cotizacion</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Departamento</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Fecha Cotizacion</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Proveedor</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Descripcion</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Cantidad</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Precio</th>';
    $html .= '<th style="padding: 8px; border: 1px solid #ddd; font-weight: bold; text-align: left;">Total</th>';
    $html .= '</tr>';
    $html .= '</thead>';

    $html .= '<tbody>';
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            if (is_array($cell)) {
                $html .= '<td style="padding: 8px; border: 1px solid #ddd; text-align: left;">' . htmlspecialchars(implode(', ', $cell)) . '</td>';
            } else {
                $html .= '<td style="padding: 8px; border: 1px solid #ddd; text-align: left;">' . htmlspecialchars($cell) . '</td>';
            }
        }
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    return $html;
}

// Crear una instancia de Dompdf
$dompdf = new Dompdf();

// Obtener los datos de la tabla de compras desde la base de datos
$datosTablaCotizacion = obtenerDatostbl_cotizacion();


// Generar el contenido HTML del PDF

$html = '<style>';
$html .= 'body { font-family: "Arial", sans-serif; }';
$html .= 'h1 { color: #1a237e; font-size: 24px; margin-bottom: 20px; }';
$html .= 'table { width: 100%; border-collapse: collapse; }';
$html .= 'th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }';
$html .= 'thead { background-color: #f2f2f2; font-weight: bold; }';
$html .= '@page { size: letter; margin: 20px; border: 4px solid #1a237e; }';
$html .= '.center { text-align: center; }';
$html .= '.left { float: left; }'; // Establecer la imagen para flotar a la izquierda
$html .= 'table { clear: both; }'; //  estilo para que la tabla se muestre debajo de la imagen
$html .= '</style>';


$html .= '<div class="center">'; // Agregar la clase "center" para centrar la tabla
$html .= '<img class="left" src="administrador/logoo.jpg" alt="" style="height: 80px; margin-right: 20px;">';
$html .= '<h1>REPORTE COTIZACION</h1>';
$html .= '</div>';

$html .= generateTableHTML($datosTablaCotizacion);

// Cargar el contenido HTML en Dompdf
$dompdf->loadHtml($html);

// Opcional: ajustes de formato y tamaño de papel
$dompdf->setPaper('A4', 'landscape');

// Renderizar el contenido HTML en un PDF
$dompdf->render();

// Opcional: Mostrar el PDF en el navegador antes de descargarlo
$dompdf->stream('tabla_cotizacion.pdf', ['Attachment' => false]);

// Guardar el PDF en el servidor
$dompdf->output(['isRemoteEnabled' => true]);

?>