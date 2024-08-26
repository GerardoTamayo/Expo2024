<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte con orientación horizontal.
$pdf = new Report;

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../modelos/data/vendedor_data.php');
require_once('../../modelos/data/compra_data.php');

// Se instancian las entidades correspondientes.
$vendedor = new VendedorData;
$compra = new CompraData;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Predicción de compras');

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($data = $compra->predictExpense()) {
    // Colores para el encabezado
    $pdf->setFillColor(0, 128, 0); // Verde oscuro
    $pdf->setTextColor(255, 255, 255); // Blanco
    $pdf->setDrawColor(0, 0, 0); // Negro

    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(90, 10, 'Fecha de compra', 1, 0, 'C', 1);
    $pdf->cell(90, 10, 'Gasto estimada', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Negro

    // Alternar colores para filas
    $fill = false;
    foreach ($data as $rowProducto) {
        // Alternar color de fondo
        $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco
        $pdf->cell(90, 10, $pdf->encodeString($rowProducto['fecha']), 1, 0, 'C', $fill);
        $pdf->cell(90, 10, '$'. $rowProducto['gastos'], 1, 1, 'C', $fill);
        $fill = !$fill;
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay productos registrados'), 1, 1);
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Gastos estimados.pdf');

