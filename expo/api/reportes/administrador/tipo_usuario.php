<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../modelos/data/usuario_data.php');
require_once('../../modelos/data/tipo_usuario_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Inventario de usuarios por niveles');
// Se instancia el módelo Categoría para obtener los datos.
$tipo_usuario = new TipoUsuariosData;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($datatipo = $tipo_usuario->readAll()) {                
    // Colores para el encabezado
    $pdf->setFillColor(0, 128, 0); // Verde oscuro
    $pdf->setTextColor(255, 255, 255); // Blanco
    $pdf->setDrawColor(0, 0, 0); // Negro

    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(60, 10, 'Nombres', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Apellidos', 1, 0, 'C', 1);
    $pdf->cell(70, 10, 'Correo electronico', 1, 1, 'C', 1); // Cambiado el valor de la última columna a 1 para saltar línea

    // Se establece la fuente para los datos de los productos.
    $pdf->setFillColor(255, 255, 255);
    $pdf->setFont('Arial', '', 11);
    $pdf->setTextColor(0, 0, 0); // Negro

    // Se recorren los registros fila por fila.
    foreach ($datatipo as $rowtipo) {
        // Se imprime una celda con el nombre de la categoría.
        $pdf->cell(190, 10, $pdf->encodeString('Rol: ' . $rowtipo['tipo_usuario']), 1, 1, 'C', 1);
        // Se instancia el módelo Producto para procesar los datos.
        $administrador = new UsuarioData;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($administrador->setTipo($rowtipo['id_tipo'])) {
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataAdministrador = $administrador->readAllTipo()) {
                // Se recorren los registros fila por fila.
                foreach ($dataAdministrador as $rowAdministrador) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(60, 10, $rowAdministrador['nombre_usuario'], 1, 0, 'C', 1);
                    $pdf->cell(60, 10, $pdf->encodeString($rowAdministrador['apellido_usuario']), 1, 0, 'C', 1);
                    $pdf->cell(70, 10, $rowAdministrador['correo_usuario'], 1, 1, 'C', 1); // Cambiado el valor de la última columna a 1 para saltar línea
                }
            } else {
                $pdf->cell(190, 10, $pdf->encodeString('No hay usuarios registrados con este rol'), 1, 0, 'C', 1);
            }
        } else {
            $pdf->cell(190, 10, $pdf->encodeString('Usuario incorrecto o inexistente'), 1, 0, 'C', 1);
        }
    }
} else {
    $pdf->cell(190, 10, $pdf->encodeString('No hay usuarios para mostrar'), 1, 0, 'C', 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Usuarios.pdf');
