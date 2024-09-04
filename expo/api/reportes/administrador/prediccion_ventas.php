<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte con orientación horizontal.
$pdf = new Report;

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../modelos/data/cliente_data.php');
require_once('../../modelos/data/venta_data.php');

// Se instancian las entidades correspondientes.
$cliente = new ClienteData;
$venta = new VentaData;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Predicción de ventas');

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($data = $venta->predictEarnings()) {
    // Colores para el encabezado
    $pdf->setFillColor(128, 211, 126); // Verde oscuro
    $pdf->setTextColor(0, 0, 0); // Blanco
    $pdf->setDrawColor(0, 0, 0); // Negro

    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(90, 10, 'Fecha de venta', 1, 0, 'C', 1);
    $pdf->cell(90, 10, 'Ganancia estimada', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Negro

    // Alternar colores para filas
    $fill = false;
    foreach ($data as $rowProducto) {
        // Alternar color de fondo
        $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco
        $pdf->cell(90, 10, $pdf->encodeString($rowProducto['fecha']), 1, 0, 'C', $fill);
        $pdf->cell(90, 10, '$'. $rowProducto['ganancias'], 1, 1, 'C', $fill);
        $fill = !$fill;
    }
} else {
    $pdf->setFillColor(128, 211, 126);
    $pdf->cell(0, 10, $pdf->encodeString('No hay productos registrados'), 1, 1, 'C', 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'clientes.pdf');

