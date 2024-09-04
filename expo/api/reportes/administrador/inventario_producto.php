<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../modelos/data/producto_data.php');
require_once('../../modelos/data/marca_data.php');
require_once('../../modelos/data/categoria_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Inventario de productos');
// Se instancia el módelo Marca para obtener los datos.
$marca = new MarcaData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($datamarca = $marca->readAll()) {                
    // Colores para el encabezado
    $pdf->setFillColor(128, 211, 126); // Verde oscuro
    $pdf->setTextColor(0, 0, 0); // Blanco
    $pdf->setDrawColor(0, 0, 0); // Negro

    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(15, 10, 'ID', 1, 0, 'C', 1);
    $pdf->cell(70, 10, 'Producto', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Presentacion', 1, 0, 'C', 1);
    $pdf->cell(30, 10, 'Existencias', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Categoria', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los productos.
    $pdf->setFillColor(255, 255, 255);
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Negro

    // Se recorren los registros fila por fila.
    foreach ($datamarca as $rowmarca) {
        // Se imprime una celda con el nombre de la marca.
        $pdf->cell(190, 10, $pdf->encodeString('Marca: ' . $rowmarca['nombre_marca']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        $producto = new ProductoData;
        // Se establece la marca para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($producto->setMarca($rowmarca['id_marca'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $producto->inventory()) {
                // Se recorren los registros fila por fila.
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(15, 10, $rowProducto['id_producto'], 1, 0, 'C', 1);
                    $pdf->cell(70, 10, $pdf->encodeString($rowProducto['nombre_producto']), 1, 0, 'L', 1);
                    $pdf->cell(35, 10, $rowProducto['tipo_presentacion'], 1, 0, 'C', 1);
                    $pdf->cell(30, 10, $rowProducto['existencias_producto'], 1, 0, 'C', 1);
                    $pdf->cell(40, 10, $rowProducto['nombre_categoria'], 1, 1, 'C', 1);
                }
            } else {
                $pdf->setFillColor(128, 211, 126);
                $pdf->cell(190, 10, $pdf->encodeString('No hay productos registrados en esta marca'), 1, 1, 'C', 1);
            }
        } else {
            $pdf->cell(190, 10, $pdf->encodeString('Marca incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(190, 10, $pdf->encodeString('No hay productos para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Inventario.pdf');

