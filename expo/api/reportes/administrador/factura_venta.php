<?php
// Se incluye la clase para generar reportes en PDF
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte
$pdf = new Report;

// Se obtiene el ID de la venta para la cual se generará la factura
if (isset($_GET['id_venta'])) {
    require_once('../../modelos/data/venta_data.php');

    // Se instancia el modelo para obtener los datos de la venta
    $venta = new VentaData;
    $pdf->startReport('Factura de Venta');

    if ($venta->setId($_GET['id_venta'])) {
        if ($dataFactura = $venta->Facturacion()) {
            // Colores para el encabezado
            $pdf->setTextColor(0, 0, 0); // Blanco
            $pdf->setDrawColor(0, 0, 0); // Negro

            // Información del cliente (esto se debe mostrar solo una vez)
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126);
            $pdf->cell(190, 10, 'Datos del Cliente', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255);
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(95, 10, 'Nombre: ' . $dataFactura[0]['nombre_cliente'] . ' ' . $dataFactura[0]['apellido_cliente'], 1, 0, 'L', 1);
            $pdf->cell(95, 10, 'DUI: ' . $dataFactura[0]['dui_cliente'], 1, 1, 'L', 1);
            $pdf->cell(95, 10, 'Telefono: ' . $dataFactura[0]['telefono_cliente'], 1, 0, 'L', 1);
            $pdf->cell(95, 10, 'Correo: ' . $dataFactura[0]['correo_cliente'], 1, 1, 'L', 1);
            $pdf->cell(190, 10, 'Direccion: ' . $dataFactura[0]['direccion_cliente'], 1, 1, 'L', 1);

            // Información de la venta (también se muestra solo una vez)
            $pdf->ln(5);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126);
            $pdf->cell(190, 10, 'Detalles de la Venta', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255);
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(60, 10, 'Fecha de Venta: ' . $dataFactura[0]['fecha_venta'], 1, 0, 'L', 1);
            $pdf->cell(130, 10, 'Observacion: ' . $dataFactura[0]['observacion_venta'], 1, 1, 'L', 1);

            // Detalle de productos vendidos (esto se muestra para cada producto)
            $pdf->ln(5);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126);
            $pdf->cell(80, 10, 'Producto', 1, 0, 'C', 1);
            $pdf->cell(30, 10, 'Cantidad', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Precio Unitario', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Total', 1, 1, 'C', 1);
            $pdf->setFont('Arial', '', 11);
            $pdf->setFillColor(255, 255, 255);

            $totalGeneral = 0;
            foreach ($dataFactura as $detalle) {
                $pdf->cell(80, 10, $detalle['nombre_producto'], 1, 0, 'C', 1);
                $pdf->cell(30, 10, $detalle['cantidad_venta'], 1, 0, 'C', 1);
                $pdf->cell(40, 10, '$' . number_format($detalle['precio_venta'], 2), 1, 0, 'C', 1);
                $pdf->cell(40, 10, '$' . number_format($detalle['total_producto'], 2), 1, 1, 'C', 1);
                $totalGeneral += $detalle['total_producto'];
            }

            // Total general
            $pdf->ln(5);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->cell(150, 10, 'Total General', 1, 0, 'C', 1);
            $pdf->cell(40, 10, '$' . number_format($totalGeneral, 2), 1, 1, 'C', 1);
        } else {
            $pdf->setFillColor(128, 211, 126);
            $pdf->cell(190, 10, 'No se encontraron datos para la venta seleccionada', 1, 0, 'C', 1);
        }
        // Se llama implícitamente al método footer() y se envía el documento al navegador web.
        $pdf->output('I', 'Factura.pdf');
    } else {
        print('Venta inexistente');
    }
} else {
    print('Debe seleccionar una venta');
}
