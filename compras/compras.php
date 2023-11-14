
<!DOCTYPE html>
<html>
<head>
    <title>Orden de Compra</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Estilos para la cabecera de la orden de compra */
        .order-header {
            margin-right: 100px;
            float: left;
            margin-top: 20px;
        }

        /* Estilos para la tabla de la orden de compra */
        .order-table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
        }

       .order-table th, .order-table td {
         border: 1px solid black;
         padding: 8px;
         text-align: center;
         white-space: nowrap; /* Evitar que el texto se ajuste automáticamente a la siguiente línea */
         overflow: hidden;
         text-overflow: ellipsis; /* Mostrar puntos suspensivos (...) cuando el texto es demasiado largo */
        }

        .order-table th:first-child,
        .order-table td:first-child {
          width: 5%; /* Ajusta el ancho de la primera columna según tus necesidades */
        }

        .order-table th:nth-child(2),
        .order-table td:nth-child(2) {
         width: 40%; /* Ajusta el ancho de la segunda columna según tus necesidades */
        }

       .order-table th:nth-child(3),
       .order-table td:nth-child(3),
       .order-table th:nth-child(4),
       .order-table td:nth-child(4) {
          width: 15%; /* Ajusta el ancho de las columnas 3 y 4 según tus necesidades */
        }

       .order-table th:last-child,
       .order-table td:last-child {
         width: 20%; /* Ajusta el ancho de la última columna según tus necesidades */
        }

        /* Estilos para la firma del solicitante */
        .order-signature {
            margin-top: 20px;
        } 
    </style>

    <style>
    
      /* Estilos para la tabla de orden total */
      .order-total {
            margin: 20px auto; /* Centra la tabla horizontalmente */
            max-width: 298px; /* Establece un ancho máximo */
            margin-top: 0px;
            margin-right: 233px; /* Ajusta la distancia hacia la derecha */
        }

        .order-total table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0px;
        }

        .order-total td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .order-total td:first-child {
            width: 5%;
        }

        .order-total td:nth-child(2) {
            width: 34%;
        }

        .order-total td:nth-child(3),
        .order-total td:nth-child(4) {
            width: 34%;
        }

        .order-total td:last-child {
            width: 34%;
        }
       .centered-content {
         display: flex;
         flex-direction: column;
         justify-content: center;
         align-items: center;
         height: 10vh;     
        }

       .botones-container {
         display: flex;
        }

      .boton-azul,
      .boton-verde {
         background-color: blue;
         color: white;
         padding: 10px 15px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         text-decoration: none; /* Para quitar el subrayado en el enlace */
         margin-right: 10px; /* Espaciado entre los botones */
        }

       .boton-verde {
         background-color: green;
        }

    </style>
</head>
<body>
    <div style="text-align: center; position: relative;">
         
      <img src="../imagen/IHCIS.jpg" alt="Logo 1" style="width: 60px; position: absolute; top: 0; left: 0; margin-left: 30px;">

      <div class="centered-content" style="display: inline-block; text-align: center; margin: 0; padding: 0;">
         <p style="margin: 0;"><strong>INSTITUTO HONDUREÑO DE CULTURA INTERAMERICANA</strong></p>
         <p style="margin: 0;"><strong>RTN 08019995223469</strong></p>
          <p style="margin: 0; color: blue;"><strong>SOLICITUD COMPRAS</strong></p>
       </div>


       <img src="../imagen/IHCI1.jpg" alt="Logo 2" style="width: 70px; position: absolute; top: 0; right: 0; margin-right: 140px;">

    </div>

    <div class="order-header">
        <?php
         // Conecta a la base de datos
         $servername = "localhost";
         $username = "root";
         $password = "";
         $dbname = "gestion_compras2";

          $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Error de conexión: " . $conn->connect_error);
            }

          // Consulta para obtener el número de orden más alto
          $sql = "SELECT MAX(NUMERO_ORDEN) as max_numero_orden FROM tbl_orden_compra";
           $result = $conn->query($sql);
  
            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $max_numero_orden = $row["max_numero_orden"];
              $nuevo_numero_orden = str_pad((intval($max_numero_orden) + 1), 4, '0', STR_PAD_LEFT);
            } else {
              // Si no hay órdenes en la base de datos, comienza con 0001
               $nuevo_numero_orden = "0001";
            }
        ?>
        
        <form action="guardar_orden.php" method="POST">
            <p style="display: flex; justify-content: space-between;">
              <span>Nº de Pedido: <?php echo $nuevo_numero_orden; ?></span>
            </p>
           <input type="hidden" name="numero_orden" value="<?php echo $nuevo_numero_orden; ?>">

           <p style="display: flex; align-items: center;"> <!-- Utilizamos flexbox para alinear verticalmente los elementos -->
             <span style="width: 71px;">Fecha:</span> <!-- Establecemos un ancho fijo para el label de la fecha -->
             <input type="text" name="fecha_orden" style="margin-left: 10px; width: 188px;" value="<?php echo date("Y-m-d"); ?>">
            </p>

            <p style="display: flex; align-items: center;"> <!-- Utilizamos flexbox para alinear verticalmente los elementos -->
              <span style="width: 80px;">Proveedor:</span> <!-- Establecemos un ancho fijo para el label de proveedor -->
              <select name="proveedor" id="proveedorSelect" onchange="cargarCotizaciones()" >
                 <option value="">Selecciona un proveedor</option>
                  <?php
                     $servername = "localhost";
                      $username = "root";
                      $password = "";
                      $dbname = "gestion_compras2";

                     // Establece la conexión con la base de datos
                     $conn = new mysqli($servername, $username, $password, $dbname);
                      if ($conn->connect_error) {
                         die("Error de conexión: " . $conn->connect_error);
                        }

                       $sql = "SELECT ID_PROVEEDOR, NOMBRE FROM tbl_proveedores WHERE ESTADO_PROVEEDOR = 'A'";
                       $result = $conn->query($sql);

                       if ($result === false) {
                         die("Error en la consulta: " . $conn->error);
                        }

                       if ($result->num_rows > 0) {
                         while ($proveedor = $result->fetch_assoc()) {
                              echo '<option value="' . $proveedor["ID_PROVEEDOR"] . '">' . $proveedor["NOMBRE"] . '</option>';
                            }
                        } else {
                         echo "No se encontraron proveedores activos en la base de datos.";
                        }

                      // Cierra la conexión con la base de datos
                      $conn->close();
                   ?>
               </select>
           </p>

          <p style="display: flex; align-items: center;"> <!-- Utilizamos flexbox para alinear verticalmente los elementos -->
              <span style="width: 80px;">Contacto:</span> <!-- Establecemos un ancho fijo para el label de contacto -->
              <select name="contacto" id="contactoSelect" style="width: 195px;">
                 <option value="">Selecciona un contacto</option>
               </select>
           </p>

    </div>
    <!-- Tabla para mostrar detalles de cotización -->
    <table class="order-table">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Descripción del Artículo</th>
                <th>Precio Unitario</th>
                <th>Valor Total</th>
                <th>Exento</th>
            </tr>
        </thead>
        <tbody id="cotizacionesTable">
            <!-- Aquí irán los datos de la tabla -->
        </tbody>
    </table>

  <!-- Calcular Sub-Total, 15% ISV y Total Factura debajo de la tabla -->
  <div class="order-total">
        <table>
            <tbody>
                <tr>
                    <td style=" border: none;  text-align: right; font-weight: bold;"><label>Sub-Total:</label></td>
                    <td><span id="subtotalValue" style="font-weight: bold;">0.00</span></td>
                    <input type="hidden" name="subtotal" id="subtotalInput" value="">
                </tr>
                <tr>
                    <td style=" border: none;  text-align: right; font-weight: bold;"><label>15% ISV:</label></td>
                    <td><span id="isvValue" style="font-weight: bold;">0.00</span></td>
                    <input type="hidden" name="isv" id="isvInput" value="">
                </tr>
                <tr>
                    <td style=" border: none;  text-align: right; font-weight: bold;"><label>Total Factura:</label></td>
                    <td><span id="totalValue" style="font-weight: bold;">0.00</span></td>
                    <input type="hidden" id="total_factura_input" name="total_factura" value="0.00">
                </tr>
            </tbody>
        </table>
    </div>

    <p style="font-weight: bold;">Observaciones:</p>

    <br><br><br>
    <div class="order-signature">
        <p style="font-weight: bold;">Firma del solicitante: _________________________________</p>
    </div>
    <br><br>
    <div class="botones-container">
      <form action="tu_accion.php" method="post">
         <input type="submit" class="boton-verde" value="Guardar">
      </form>

      <a href="ordenes_compras.php" class="boton-azul">Regresar</a>
    </div>
    </form>

    <br><br><br>
    <!-- Pie de página -->
    <footer>
      <div style="text-align: center; margin-top: 20px;">
         <div style="display: inline-block; text-align: left; width: 30%;">
             <p style="margin: 0; font-size: 11px;">IHCI Comayagüela:</p>
             <p style="margin: 0; font-size: 11px;">2da. Avenida, Calle Real, entre 5ta y 6ta calle</p>
             <p style="margin: 0; font-size: 11px;">Tel: 2220-1393</p>
           </div>
           <div style="display: inline-block; text-align: center; width: 30%;">
             <p style="margin: 0; font-size: 11px;">IHCI Tegucigalpa:</p>
             <p style="margin: 0; font-size: 11px;">Col. Matamoros Ave. Santader</p>
             <p style="margin: 0; font-size: 11px;">Tel: 2221-1406</p>
           </div>
           <div style="display: inline-block; text-align: right; width: 30%;">
              <p style="margin: 0; font-size: 11px;">IHCI Comayagua:</p>
              <p style="margin: 0; font-size: 11px;">Calle hacia el Comayagua Golf Club</p>
              <p style="margin: 0; font-size: 11px;">Tel: 2772-7772</p>
          </div> 
       </div>
    </footer>

    <script>
      $(document).ready(function() {
            $("#proveedorSelect").on("change", function() {
             var proveedorId = $("#proveedorSelect").val();
              cargarContactos(proveedorId); // Llama a la función cargarContactos con el proveedorId seleccionado
            });
        });

       function cargarContactos(proveedorId) {
            if (!proveedorId) {
              return;
            }

            $.ajax({
              type: "POST",
              url: "obtener_contactos.php",
              data: { proveedorId: proveedorId },
              success: function(response) {
                 console.log(response); // Agrega esta línea para verificar la respuesta
                  $("#contactoSelect").html(response);
                }
            });
        }
   </script>

   <script>
       function cargarCotizaciones() {
         var proveedorId = $("#proveedorSelect").val();
     
           if (!proveedorId) {
              return;
            }

           $.ajax({
              type: "POST",
              url: "obtener_cotizaciones.php",
               data: { proveedorId: proveedorId },
                success: function(response) {
                  console.log(response); // Agrega esta línea para verificar la respuesta
                  $("#cotizacionesTable").html(response);
                  calcularTotales(); // Llama a la función para calcular los totales
                },
               error: function(xhr, status, error) {
                  console.error("Error en la solicitud AJAX:", status, error);
                }
            });
        }


        function calcularTotales() {
          var subtotal = 0;
          var isv = 0;
          var totalFactura = 0;

            // Recorre las filas de la tabla de cotizaciones y suma los valores
            $("#cotizacionesTable tr").each(function() {
                var valorTotal = parseFloat($(this).find("td:eq(3)").text());
                if (!isNaN(valorTotal)) {
                    subtotal += valorTotal;
                }
           });

           // Calcula el ISV (15% del subtotal)
            isv = subtotal * 0.15;

          // Calcula el total de la factura (subtotal + ISV)
          totalFactura = subtotal + isv;

           // Actualiza los valores en la página
           $("#subtotalValue").text(subtotal.toFixed(2));
           $("#isvValue").text(isv.toFixed(2));
           $("#totalValue").text(totalFactura.toFixed(2));

           // Actualiza el campo oculto con el valor total de la factura
           $("#total_factura_input").val(totalFactura.toFixed(2));
           $("#subtotalInput").val(subtotal.toFixed(2));
           $("#isvInput").val(isv.toFixed(2));
        }
    </script>

</body>
</html>





