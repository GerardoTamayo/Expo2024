<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte
$pdf = new Report;

// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['id_proveedor'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../modelos/data/compra_data.php');
    require_once('../../modelos/data/vendedor_data.php');

    // Se instancian las entidades correspondientes.
    $vendedor = new VendedorData;
    $compra = new CompraData;
    
    if ($vendedor->setId($_GET['id_proveedor']) && $compra->setIdProveedor($_GET['id_proveedor'])) {
        if ($rowProveedor = $vendedor->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Reporte de compras de proveedor: ' . $rowProveedor['nombre_proveedor']);

            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($data = $compra->vendedorCompra()) {
                // Colores para el encabezado
                $pdf->setFillColor(0, 128, 0); // Verde oscuro
                $pdf->setTextColor(255, 255, 255); // Blanco
                $pdf->setDrawColor(0, 0, 0); // Negro

                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 12);

                // Se imprimen las celdas con los encabezados.
                $pdf->cell(40, 10, 'Fecha de compra', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Cantidad comprada', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Precio de compra', 1, 0, 'C', 1);
                $pdf->cell(55, 10, 'Producto', 1, 1, 'C', 1);

                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                $pdf->setTextColor(0, 0, 0); // Negro

                // Alternar colores para filas
                $fill = false;
                foreach ($data as $rowProducto) {
                    // Alternar color de fondo
                    $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco
                    $pdf->cell(40, 10, $rowProducto['fecha_compra'], 1, 0, 'C', $fill);
                    $pdf->cell(40, 10, $rowProducto['cantidad_compra'], 1, 0, 'C', $fill);
                    $pdf->cell(40, 10, $rowProducto['precio_compra'], 1, 0, 'C', $fill);
                    $pdf->cell(55, 10, $rowProducto['nombre_producto'], 1, 1, 'C', $fill);
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay productos registrados'), 1, 1);
            }

            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'Compras por proveedor.pdf');
        } else {
            print('Proveedor inexistente');
        }
    } else {
        print('Proveedor incorrecto');
    }
} else {
    print('Debe seleccionar un proveedor');
}
?>
