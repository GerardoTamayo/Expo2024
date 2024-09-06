<?php
// Se incluyen las clases para la generación de reportes y acceso a datos.
require_once('../../ayudantes/reportes.php');
require_once('../../modelos/data/producto_data.php');
require_once('../../modelos/data/marca_data.php');
require_once('../../modelos/data/categoria_data.php');

// Se instancia la clase para crear el reporte en PDF.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Inventario de productos');

// Se instancia el modelo Marca para obtener los datos de las marcas.
$marca = new MarcaData;

// Se verifica si existen registros de marcas para mostrar, de lo contrario se imprime un mensaje.
if ($datamarca = $marca->readAll()) {                
    // Colores para el encabezado de la tabla.
    $pdf->setFillColor(128, 211, 126); // Verde oscuro para el fondo.
    $pdf->setTextColor(0, 0, 0); // Negro para el texto.
    $pdf->setDrawColor(0, 0, 0); // Negro para los bordes.

    // Se establece la fuente para los encabezados de las tablas.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados de la tabla.
    $pdf->cell(15, 10, 'ID', 1, 0, 'C', 1);
    $pdf->cell(70, 10, 'Producto', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Presentacion', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Existencias', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Categoria', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los productos.
    $pdf->setFillColor(255, 255, 255); // Fondo blanco para las filas de datos.
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Texto en negro.

    // Se recorren los registros de las marcas fila por fila.
    foreach ($datamarca as $rowmarca) {
        // Se imprime una fila con el nombre de la marca.
        $pdf->cell(190, 10, $pdf->encodeString('Marca: ' . $rowmarca['nombre_marca']), 1, 1, 'C', 1);

        // Se instancia el modelo Producto para obtener los productos de la marca actual.
        $producto = new ProductoData;

        // Se establece la marca actual para obtener los productos asociados.
        if ($producto->setMarca($rowmarca['id_marca'])) {
            // Se verifica si existen productos para la marca actual.
            if ($dataProductos = $producto->inventory()) {
                // Se recorren los registros de productos de la marca actual fila por fila.
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen los datos de cada producto en la fila correspondiente.
                    $pdf->cell(15, 10, $rowProducto['id_producto'], 1, 0, 'C', 1);
                    $pdf->cell(70, 10, $pdf->encodeString($rowProducto['nombre_producto']), 1, 0, 'L', 1);
                    $pdf->cell(35, 10, $rowProducto['tipo_presentacion'], 1, 0, 'C', 1);
                    $pdf->cell(30, 10, $rowProducto['existencias_producto'], 1, 0, 'C', 1);
                    $pdf->cell(40, 10, $rowProducto['nombre_categoria'], 1, 1, 'C', 1);
                }
            } else {
                // Si no hay productos para la marca actual, se imprime un mensaje.
                $pdf->setFillColor(128, 211, 126);
                $pdf->cell(190, 10, $pdf->encodeString('No hay productos registrados en esta marca'), 1, 1, 'C', 1);
            }
        } else {
            // Si la marca no es válida, se imprime un mensaje de error.
            $pdf->cell(190, 10, $pdf->encodeString('Marca incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    // Si no existen marcas, se imprime un mensaje indicando que no hay productos para mostrar.
    $pdf->cell(190, 10, $pdf->encodeString('No hay productos para mostrar'), 1, 1);
}

// Se genera el archivo PDF y se envía al navegador para su visualización o descarga.
$pdf->output('I', 'Inventario.pdf');

