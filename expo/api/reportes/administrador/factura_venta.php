<?php
// Se incluye la clase para generar reportes en PDF.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte en PDF.
$pdf = new Report;

// Se verifica si se ha recibido un ID de venta para generar la factura.
if (isset($_GET['id_venta'])) {
    // Se incluye el modelo para obtener los datos de la venta.
    require_once('../../modelos/data/venta_data.php');

    // Se instancia el modelo para obtener los datos de la venta.
    $venta = new VentaData;
    
    // Se inicia el reporte con el título "Factura de Venta".
    $pdf->startReport('Factura de Venta');

    // Se verifica si se puede establecer el ID de la venta.
    if ($venta->setId($_GET['id_venta'])) {
        // Se obtienen los datos de la venta y sus detalles.
        if ($dataFactura = $venta->Facturacion()) {
            // Establecer colores para el texto y los bordes.
            $pdf->setTextColor(0, 0, 0); // Negro para el texto.
            $pdf->setDrawColor(0, 0, 0); // Negro para los bordes.

            // Sección: Datos del cliente.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el encabezado.
            $pdf->cell(190, 10, 'Datos del Cliente', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255); // Fondo blanco para los datos.
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(95, 10, 'Nombre: ' . $dataFactura[0]['nombre_cliente'] . ' ' . $dataFactura[0]['apellido_cliente'], 1, 0, 'L', 1);
            $pdf->cell(95, 10, 'DUI: ' . $dataFactura[0]['dui_cliente'], 1, 1, 'L', 1);
            $pdf->cell(95, 10, 'Telefono: ' . $dataFactura[0]['telefono_cliente'], 1, 0, 'L', 1);
            $pdf->cell(95, 10, 'Correo: ' . $dataFactura[0]['correo_cliente'], 1, 1, 'L', 1);
            $pdf->cell(190, 10, 'Direccion: ' . $dataFactura[0]['direccion_cliente'], 1, 1, 'L', 1);

            // Sección: Detalles de la venta.
            $pdf->ln(5); // Espacio entre secciones.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el encabezado.
            $pdf->cell(190, 10, 'Detalles de la Venta', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255); // Fondo blanco para los datos.
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(60, 10, 'Fecha de Venta: ' . $dataFactura[0]['fecha_venta'], 1, 0, 'L', 1);
            $pdf->cell(130, 10, 'Observacion: ' . $dataFactura[0]['observacion_venta'], 1, 1, 'L', 1);

            // Sección: Detalles de los productos vendidos.
            $pdf->ln(5); // Espacio entre secciones.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el encabezado.
            $pdf->cell(80, 10, 'Producto', 1, 0, 'C', 1);
            $pdf->cell(30, 10, 'Cantidad', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Precio Unitario', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Total', 1, 1, 'C', 1);
            $pdf->setFont('Arial', '', 11);
            $pdf->setFillColor(255, 255, 255); // Fondo blanco para los datos.

            // Se calcula y muestra el total general de la venta.
            $totalGeneral = 0;
            foreach ($dataFactura as $detalle) {
                $pdf->cell(80, 10, $detalle['nombre_producto'], 1, 0, 'C', 1);
                $pdf->cell(30, 10, $detalle['cantidad_venta'], 1, 0, 'C', 1);
                $pdf->cell(40, 10, '$' . number_format($detalle['precio_venta'], 2), 1, 0, 'C', 1);
                $pdf->cell(40, 10, '$' . number_format($detalle['total_producto'], 2), 1, 1, 'C', 1);
                $totalGeneral += $detalle['total_producto']; // Se acumula el total de cada producto.
            }

            // Sección: Total General.
            $pdf->ln(5); // Espacio antes del total general.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->cell(150, 10, 'Total General', 1, 0, 'C', 1);
            $pdf->cell(40, 10, '$' . number_format($totalGeneral, 2), 1, 1, 'C', 1);
        } else {
            // Si no se encuentran datos para la venta, se muestra un mensaje.
            $pdf->setFillColor(128, 211, 126); // Fondo verde oscuro para el mensaje.
            $pdf->cell(190, 10, 'No se encontraron datos para la venta seleccionada', 1, 0, 'C', 1);
        }
        // Se genera y envía el PDF al navegador.
        $pdf->output('I', 'Factura.pdf');
    } else {
        // Si el ID de la venta no es válido, se muestra un mensaje.
        print('Venta inexistente');
    }
} else {
    // Si no se proporciona un ID de venta, se muestra un mensaje.
    print('Debe seleccionar una venta');
}
