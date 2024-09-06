<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte
$pdf = new Report;

// Se verifica si existe un valor para la categoría (id_proveedor), de lo contrario se muestra un mensaje.
if (isset($_GET['id_proveedor'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../modelos/data/compra_data.php');
    require_once('../../modelos/data/vendedor_data.php');

    // Se instancian las entidades correspondientes.
    $vendedor = new VendedorData;
    $compra = new CompraData;
    
    // Se valida si el proveedor es correcto y se establece el proveedor para las compras.
    if ($vendedor->setId($_GET['id_proveedor']) && $compra->setIdProveedor($_GET['id_proveedor'])) {
        // Se obtiene la información del proveedor.
        if ($rowProveedor = $vendedor->readOne()) {
            // Se inicia el reporte con el encabezado, incluyendo el nombre del proveedor.
            $pdf->startReport('Reporte de compras de proveedor: ' . $rowProveedor['nombre_proveedor']);

            // Se verifica si existen registros de compras.
            if ($data = $compra->vendedorCompra()) {
                // Configuración de los colores del encabezado.
                $pdf->setFillColor(128, 211, 126); // Verde oscuro
                $pdf->setTextColor(0, 0, 0); // Negro
                $pdf->setDrawColor(0, 0, 0); // Negro para el borde.

                // Se establece la fuente para los encabezados de las columnas.
                $pdf->setFont('Arial', 'B', 12);

                // Se imprimen los encabezados de la tabla.
                $pdf->cell(40, 10, 'Fecha de compra', 1, 0, 'C', 1);
                $pdf->cell(45, 10, 'Cantidad comprada', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Precio de compra', 1, 0, 'C', 1);
                $pdf->cell(60, 10, 'Producto', 1, 1, 'C', 1);

                // Se establece la fuente para los datos.
                $pdf->setFont('Arial', '', 11);
                $pdf->setTextColor(0, 0, 0); // Texto negro.

                // Alternar colores para filas.
                $fill = false;
                foreach ($data as $rowProducto) {
                    // Se alternan los colores de fondo para las filas.
                    $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco.
                    $pdf->cell(40, 10, $rowProducto['fecha_compra'], 1, 0, 'C', $fill);
                    $pdf->cell(45, 10, $rowProducto['cantidad_compra'], 1, 0, 'C', $fill);
                    $pdf->cell(40, 10, '$' . $rowProducto['precio_compra'], 1, 0, 'C', $fill);
                    $pdf->cell(60, 10, $rowProducto['nombre_producto'], 1, 1, 'C', $fill);
                    // Cambia el valor de $fill para alternar el color de las siguientes filas.
                    $fill = !$fill;
                }
            } else {
                // Si no hay productos registrados, se imprime un mensaje.
                $pdf->setFillColor(128, 211, 126);
                $pdf->cell(190, 10, $pdf->encodeString('No hay productos registrados'), 1, 1, 'C', 1);
            }

            // Se genera el archivo PDF y se envía al navegador.
            $pdf->output('I', 'Compras por proveedor.pdf');
        } else {
            // Si el proveedor no existe, se muestra un mensaje.
            print('Proveedor inexistente');
        }
    } else {
        // Si el ID del proveedor es incorrecto, se muestra un mensaje de error.
        print('Proveedor incorrecto');
    }
} else {
    // Si no se ha seleccionado un proveedor, se muestra un mensaje.
    print('Debe seleccionar un proveedor');
}
?>
