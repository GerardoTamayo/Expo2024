<?php
// Se incluye la clase para generar reportes en PDF.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear un reporte en PDF.
$pdf = new Report;

// Se verifica si se ha recibido un ID de compra para generar la factura.
if (isset($_GET['id_compra'])) {
    // Se incluye el modelo para obtener los datos de la compra.
    require_once('../../modelos/data/compra_data.php');

    // Se instancia el modelo para obtener los datos de la compra.
    $compra = new CompraData;
    
    // Se inicia el reporte con el título "Factura de Compra".
    $pdf->startReport('Factura de Compra');

    // Se verifica si se puede establecer el ID de la compra.
    if ($compra->setId($_GET['id_compra'])) {
        // Se obtienen los datos de la compra y sus detalles.
        if ($dataFactura = $compra->compraFactura()) {
            // Establecer colores para el texto y los bordes.
            $pdf->setTextColor(0, 0, 0); // Negro para el texto.
            $pdf->setDrawColor(0, 0, 0); // Negro para los bordes.

            // Sección: Datos del proveedor.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el encabezado.
            $pdf->cell(190, 10, 'Datos del Proveedor', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255); // Fondo blanco para los datos.
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(95, 10, 'Nombre: ' . $dataFactura[0]['nombre_proveedor'] . ' ' . $dataFactura[0]['apellido_proveedor'], 1, 0, 'L', 1);
            $pdf->cell(95, 10, 'Telefono: ' . $dataFactura[0]['telefono_proveedor'], 1, 1, 'L', 1);
            $pdf->cell(190, 10, 'Correo: ' . $dataFactura[0]['correo_proveedor'], 1, 1, 'L', 1);

            // Sección: Detalles de la compra.
            $pdf->ln(5); // Espacio entre secciones.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el encabezado.
            $pdf->cell(190, 10, 'Detalles de la Compra', 1, 1, 'C', 1);
            $pdf->setFillColor(255, 255, 255); // Fondo blanco para los datos.
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(60, 10, 'Fecha de Compra: ' . $dataFactura[0]['fecha_compra'], 1, 0, 'L', 1);
            $pdf->cell(130, 10, 'Numero Correlativo: ' . $dataFactura[0]['numero_correlativo'], 1, 1, 'L', 1);

            // Estado de la compra: Se convierte el valor numérico a texto.
            $estadoCompra = $dataFactura[0]['estado_compra'] == 1 ? 'Cancelada' : 'No Cancelada';
            $pdf->cell(190, 10, 'Estado: ' . $estadoCompra, 1, 1, 'L', 1);

            // Sección: Detalles de los productos comprados.
            $pdf->ln(5); // Espacio entre secciones.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el encabezado.
            $pdf->cell(60, 10, 'Producto', 1, 0, 'C', 1);
            $pdf->cell(50, 10, 'Cantidad Comprada', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Precio Unitario', 1, 0, 'C', 1);
            $pdf->cell(40, 10, 'Total', 1, 1, 'C', 1);
            $pdf->setFont('Arial', '', 11);
            $pdf->setFillColor(255, 255, 255); // Fondo blanco para los datos.

            // Se calcula y muestra el total general de la compra.
            $totalGeneral = 0;
            foreach ($dataFactura as $detalle) {
                $pdf->cell(60, 10, $detalle['nombre_producto'], 1, 0, 'C', 1);
                $pdf->cell(50, 10, $detalle['cantidad_compra'], 1, 0, 'C', 1);
                $pdf->cell(40, 10, '$' . number_format($detalle['precio_compra'], 2), 1, 0, 'C', 1);
                $pdf->cell(40, 10, '$' . number_format($detalle['total_producto'], 2), 1, 1, 'C', 1);
                $totalGeneral += $detalle['total_producto']; // Se acumula el total de cada producto.
            }

            // Sección: Total General.
            $pdf->ln(5); // Espacio antes del total general.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->cell(150, 10, 'Total General', 1, 0, 'C', 1);
            $pdf->cell(40, 10, '$' . number_format($totalGeneral, 2), 1, 1, 'C', 1);
        } else {
            // Si no se encuentran datos para la compra, se muestra un mensaje.
            $pdf->setFillColor(128, 211, 126); // Fondo verde oscuro para el mensaje.
            $pdf->cell(190, 10, 'No se encontraron datos para la compra seleccionada', 1, 0, 'C', 1);
        }
        // Se genera y envía el PDF al navegador.
        $pdf->output('I', 'Factura.pdf');
    } else {
        // Si el ID de la compra no es válido, se muestra un mensaje.
        print('Compra inexistente');
    }
} else {
    // Si no se proporciona un ID de compra, se muestra un mensaje.
    print('Debe seleccionar una compra');
}

