<?php
// Se incluye la clase para generar reportes en PDF
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte
$pdf = new Report;

// Se obtiene el ID de la compra para la cual se generará la factura
if (isset($_GET['id_compra'])) {
    require_once('../../modelos/data/compra_data.php');

    // Se instancia el modelo para obtener los datos de la compra
    $compra = new CompraData;
    $pdf->startReport('Factura de Compra');

    if ($compra->setId($_GET['id_compra'])) {
        if ($dataFactura = $compra->compraFactura()) {
            // Colores para el encabezado
            $pdf->setTextColor(0, 0, 0); // Blanco
            $pdf->setDrawColor(0, 0, 0); // Negro

            // Información del proveedor (esto se debe mostrar solo una vez)
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126);
            $pdf->cell(190, 10, 'Datos del Proveedor', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255);
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(95, 10, 'Nombre: ' . $dataFactura[0]['nombre_proveedor'] . ' ' . $dataFactura[0]['apellido_proveedor'], 1, 0, 'L', 1);
            $pdf->cell(95, 10, 'Telefono: ' . $dataFactura[0]['telefono_proveedor'], 1, 1, 'L', 1);
            $pdf->cell(190, 10, 'Correo: ' . $dataFactura[0]['correo_proveedor'], 1, 1, 'L', 1);

            // Información de la compra (también se muestra solo una vez)
            $pdf->ln(5);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126);
            $pdf->cell(190, 10, 'Detalles de la Compra', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255);
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(60, 10, 'Fecha de Compra: ' . $dataFactura[0]['fecha_compra'], 1, 0, 'L', 1);
            $pdf->cell(130, 10, 'Numero Correlativo: ' . $dataFactura[0]['numero_correlativo'], 1, 1, 'L', 1);
            
            // Convertir el estado de la compra
            $estadoCompra = $dataFactura[0]['estado_compra'] == 1 ? 'Cancelada' : 'No Cancelada';
            $pdf->cell(190, 10, 'Estado: ' . $estadoCompra, 1, 1, 'L', 1);

            // Detalle de productos comprados (esto se muestra para cada producto)
            $pdf->ln(5);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126);
            $pdf->cell(60, 10, 'Producto', 1, 0, 'C', 1);
            $pdf->cell(50, 10, 'Cantidad comprada', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Precio Unitario', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Total', 1, 1, 'C', 1);
            $pdf->setFont('Arial', '', 11);
            $pdf->setFillColor(255, 255, 255);

            $totalGeneral = 0;
            foreach ($dataFactura as $detalle) {
                $pdf->cell(60, 10, $detalle['nombre_producto'], 1, 0, 'C', 1);
                $pdf->cell(50, 10, $detalle['cantidad_compra'], 1, 0, 'C', 1);
                $pdf->cell(40, 10, '$' . number_format($detalle['precio_compra'], 2), 1, 0, 'C', 1);
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
            $pdf->cell(190, 10, 'No se encontraron datos para la compra seleccionada', 1, 0, 'C', 1);
        }
        // Se llama implícitamente al método footer() y se envía el documento al navegador web.
        $pdf->output('I', 'Factura.pdf');
    } else {
        print('Compra inexistente');
    }
} else {
    print('Debe seleccionar una compra');
}
