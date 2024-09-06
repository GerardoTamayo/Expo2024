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

// Se instancia el modelo TipoUsuariosData para obtener los datos.
$tipo_usuario = new TipoUsuariosData;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($datatipo = $tipo_usuario->readAll()) {
    // Colores para el encabezado
    $pdf->setFillColor(128, 211, 126); // Verde oscuro
    $pdf->setTextColor(0, 0, 0); // Negro
    $pdf->setDrawColor(0, 0, 0); // Negro

    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 12);

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(60, 10, 'Nombres', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Apellidos', 1, 0, 'C', 1);
    $pdf->cell(70, 10, 'Correo electronico', 1, 1, 'C', 1); // Saltar línea al final

    // Se establece la fuente para los datos de los usuarios.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($datatipo as $rowtipo) {
        // Imprimir el rol
        $pdf->cell(190, 10, $pdf->encodeString('Rol: ' . $rowtipo['tipo_usuario']), 1, 1, 'C', 0);

        // Se instancia el modelo UsuarioData para obtener los usuarios del rol.
        $administrador = new UsuarioData;

        // Se establece el tipo de usuario para obtener los registros.
        if ($administrador->setTipo($rowtipo['id_tipo'])) {
            if ($dataAdministrador = $administrador->readAllTipo()) {
                // Se recorren los registros de usuarios.
                foreach ($dataAdministrador as $rowAdministrador) {
                    // Imprimir los datos de cada usuario.
                    $pdf->cell(60, 10, $rowAdministrador['nombre_usuario'], 1, 0, 'C', 0);
                    $pdf->cell(60, 10, $pdf->encodeString($rowAdministrador['apellido_usuario']), 1, 0, 'C', 0);
                    $pdf->cell(70, 10, $rowAdministrador['correo_usuario'], 1, 1, 'C', 0); // Saltar línea al final
                }
            } else {
                // Mensaje si no hay usuarios en el rol.
                $pdf->cell(190, 10, 'No hay usuarios registrados con este rol', 1, 1, 'C', 0);
            }
        } else {
            // Mensaje de error si no se pudo establecer el tipo de usuario.
            $pdf->cell(190, 10, 'Usuario incorrecto o inexistente', 1, 1, 'C', 0);
        }
    }
} else {
    // Mensaje si no hay registros de tipo de usuario.
    $pdf->cell(190, 10, 'No hay usuarios para mostrar', 1, 1, 'C', 0);
}

// Se envía el documento al navegador.
$pdf->output('I', 'Usuarios.pdf');
