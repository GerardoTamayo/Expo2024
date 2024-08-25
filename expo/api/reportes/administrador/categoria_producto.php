<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['id_categoria'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../modelos/data/categoria_data.php');
    require_once('../../modelos/data/producto_data.php');
    // Se instancian las entidades correspondientes.
    $categoria = new CategoriaData;
    $producto = new ProductoData;
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($categoria->setId($_GET['id_categoria']) && $producto->setCategoria($_GET['id_categoria'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowCategoria = $categoria->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Productos de la categoría: ' . $rowCategoria['nombre_categoria']);
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $producto->productosCategoria()) {
                // Colores para el encabezado
                $pdf->setFillColor(0, 128, 0); // Verde oscuro
                $pdf->setTextColor(255, 255, 255); // Blanco
                $pdf->setDrawColor(0, 0, 0); // Negro
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 12);
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(20, 10, 'ID', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Producto', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Existencias', 1, 0, 'C', 1);
                $pdf->cell(50, 10, 'Descripcion', 1, 0, 'C', 1);
                $pdf->cell(50, 10, 'Fecha Vencimiento', 1, 1, 'C', 1);

                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                $pdf->setTextColor(0, 0, 0); // Negro
                // Alternar colores para filas
                $fill = false;
                foreach ($dataProductos as $rowProducto) {
                    // Alternar color de fondo
                    $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco
                    $pdf->cell(20, 8, $rowProducto['id_producto'], 1, 0, 'C', $fill);
                    $pdf->cell(40, 8, $pdf->encodeString($rowProducto['nombre_producto']), 1, 0, 'L', $fill);
                    $pdf->cell(30, 8, $pdf->encodeString($rowProducto['existencias_producto']), 1, 0, 'C', $fill);
                    $pdf->cell(50, 8, $pdf->encodeString($rowProducto['descripcion']), 1, 0, 'L', $fill);
                    $pdf->cell(50, 8, $pdf->encodeString($rowProducto['fecha_vencimiento']), 1, 1, 'C', $fill);
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(0, 8, $pdf->encodeString('No hay productos registrados en esta categoría'), 1, 1);
            }
            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'categorias.pdf');
        } else {
            print('Categoría inexistente');
        }
    } else {
        print('Categoría incorrecta');
    }
} else {
    print('Debe seleccionar una categoría');
}
