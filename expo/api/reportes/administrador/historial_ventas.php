<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte con orientación horizontal.
$pdf = new Report;

// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../modelos/data/cliente_data.php');
require_once('../../modelos/data/venta_data.php');

// Se instancian las entidades correspondientes para manejar los datos de clientes y ventas.
$cliente = new ClienteData;
$venta = new VentaData;

// Se inicia el reporte con el encabezado personalizado del documento.
$pdf->startReport2('Historial de ventas');

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($data = $venta->ventaReports()) {
    // Colores para el encabezado.
    $pdf->setFillColor(128, 211, 126); // Verde oscuro.
    $pdf->setTextColor(0, 0, 0); // Texto negro.
    $pdf->setDrawColor(0, 0, 0); // Bordes negros.

    // Se establece la fuente para los encabezados de la tabla.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados de la tabla.
    $pdf->cell(40, 10, 'Fecha de venta', 1, 0, 'C', 1);
    $pdf->cell(50, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(50, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Precio($)', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Cantidad', 1, 0, 'C', 1);
    $pdf->cell(40, 10, 'Total', 1, 1, 'C', 1);

    // Se establece la fuente para los datos de las ventas.
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Texto negro.

    // Alternar colores para filas.
    $fill = false; // Variable para alternar el color de las filas.

    // Ciclo para imprimir cada fila de datos.
    foreach ($data as $rowProducto) {
        // Establece el color de fondo según la alternancia.
        $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco.

        // Imprime los datos de cada columna en una fila.
        $pdf->cell(40, 10, $rowProducto['fecha_venta'], 1, 0, 'C', $fill);
        $pdf->cell(50, 10, $rowProducto['nombre_cliente'], 1, 0, 'C', $fill);
        $pdf->cell(50, 10, $rowProducto['apellido_cliente'], 1, 0, 'C', $fill);
        $pdf->cell(35, 10, $rowProducto['precio_venta'], 1, 0, 'C', $fill);
        $pdf->cell(35, 10, $pdf->encodeString($rowProducto['cantidad_venta']), 1, 0, 'C', $fill);
        $pdf->cell(40, 10, $rowProducto['total'], 1, 1, 'C', $fill);

        // Alterna el color para la siguiente fila.
        $fill = !$fill;
    }
} else {
    // Si no hay datos de ventas, se muestra un mensaje en la tabla.
    $pdf->setFillColor(128, 211, 126);
    $pdf->cell(0, 10, $pdf->encodeString('No hay productos registrados'), 1, 1, 'C');
}

// Se genera y envía el PDF al navegador.
$pdf->output('I', 'clientes.pdf');

