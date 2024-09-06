<?php
// Se incluye la clase con las plantillas necesarias para generar reportes en PDF.
require_once('../../ayudantes/reportes.php');
// Se incluye el modelo para acceder a los datos de los usuarios.
require_once('../../modelos/data/usuario_data.php');

// Se instancia la clase para crear un nuevo reporte en PDF.
$pdf = new Report;
// Se inicia el reporte con el título "Inventario de usuarios por estado".
$pdf->startReport('Inventario de usuarios por estado');

// Se instancia el modelo Usuario para obtener los datos de los usuarios.
$administrador = new UsuarioData;

// Se obtienen todos los usuarios registrados.
$dataUsuarios = $administrador->readAll();

// Se agrupan los usuarios en dos listas: activos e inactivos (bloqueados).
$usuariosActivos = [];
$usuariosInactivos = [];
foreach ($dataUsuarios as $usuario) {
    // Se clasifica el usuario en activo o bloqueado, ignorando mayúsculas/minúsculas.
    if (strtolower($usuario['ESTADO']) === 'activo') {
        $usuariosActivos[] = $usuario;
    } else {
        $usuariosInactivos[] = $usuario;
    }
}

// Configuración del estilo para los encabezados de la tabla (colores y fuente).
$pdf->setFillColor(128, 211, 126); // Color de fondo verde oscuro para el encabezado.
$pdf->setTextColor(0, 0, 0); // Texto en color negro.
$pdf->setDrawColor(0, 0, 0); // Borde en color negro.
$pdf->setFont('Arial', 'B', 12); // Fuente en negrita para el encabezado.

// Se imprimen los encabezados de las columnas para los usuarios.
$pdf->cell(40, 10, 'Nombres', 1, 0, 'C', 1);
$pdf->cell(40, 10, 'Apellidos', 1, 0, 'C', 1);
$pdf->cell(65, 10, 'Correo electronico', 1, 0, 'C', 1);
$pdf->cell(45, 10, 'Tipo usuario', 1, 1, 'C', 1);

// Configuración del estilo para los datos de los usuarios.
$pdf->setFillColor(255, 255, 255); // Color blanco para las celdas de los datos.
$pdf->setFont('Arial', '', 11); // Fuente regular para los datos.
$pdf->setTextColor(0, 0, 0); // Texto en color negro para los datos.

// Función para imprimir los usuarios en el PDF, separados por su estado.
function imprimirUsuarios($pdf, $usuarios, $estado) {
    // Se verifica si la lista de usuarios no está vacía.
    if (!empty($usuarios)) {
        // Se imprime una fila que indica el estado de los usuarios (Activos o Bloqueados).
        $pdf->cell(190, 10, $pdf->encodeString('Estado: ' . $estado), 1, 1, 'C', 1);
        // Se imprimen los datos de cada usuario.
        foreach ($usuarios as $usuario) {
            $pdf->cell(40, 10, $usuario['nombre_usuario'], 1, 0, 'C', 1);
            $pdf->cell(40, 10, $pdf->encodeString($usuario['apellido_usuario']), 1, 0, 'C', 1);
            $pdf->cell(65, 10, $usuario['correo_usuario'], 1, 0, 'C', 1);
            $pdf->cell(45, 10, $pdf->encodeString($usuario['tipo_usuario']), 1, 1, 'C', 1);
        }
    } else {
        // Si no hay usuarios en este estado, se imprime un mensaje indicando que no hay registros.
        $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el mensaje.
        $pdf->cell(190, 10, $pdf->encodeString('No hay usuarios registrados con estado ' . $estado), 1, 1, 'C', 1);
    }
}

// Imprime los usuarios activos.
imprimirUsuarios($pdf, $usuariosActivos, 'Activo(s)');

// Imprime los usuarios inactivos (bloqueados).
imprimirUsuarios($pdf, $usuariosInactivos, 'Bloqueado(s)');

// Se envía el reporte generado al navegador para ser visualizado o descargado.
$pdf->output('I', 'Usuarios por estado.pdf');

