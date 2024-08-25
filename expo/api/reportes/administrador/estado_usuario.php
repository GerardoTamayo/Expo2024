<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../modelos/data/usuario_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Inventario de usuarios por estado');

// Se instancia el modelo Usuario para obtener los datos.
$administrador = new UsuarioData;

// Se obtiene todos los usuarios.
$dataUsuarios = $administrador->readAll();

// Se agrupan los usuarios por estado.
$usuariosActivos = [];
$usuariosInactivos = [];
foreach ($dataUsuarios as $usuario) {
    // Depuración: imprimir el valor del estado
    // echo 'Estado: ' . $usuario['ESTADO'] . "<br>";

    // Comparación insensible a mayúsculas/minúsculas
    if (strtolower($usuario['ESTADO']) === 'activo') {
        $usuariosActivos[] = $usuario;
    } else {
        $usuariosInactivos[] = $usuario;
    }
}

// Colores para el encabezado
$pdf->setFillColor(0, 128, 0); // Verde oscuro
$pdf->setTextColor(255, 255, 255); // Blanco
$pdf->setDrawColor(0, 0, 0); // Negro

// Se establece la fuente para los encabezados.
$pdf->setFont('Arial', 'B', 12);

// Se imprimen las celdas con los encabezados.
$pdf->cell(40, 10, 'Nombres', 1, 0, 'C', 1);
$pdf->cell(40, 10, 'Apellidos', 1, 0, 'C', 1);
$pdf->cell(65, 10, 'Correo electronico', 1, 0, 'C', 1);
$pdf->cell(45, 10, 'Tipo usuario', 1, 1, 'C', 1);

// Se establece la fuente para los datos de los usuarios.
$pdf->setFillColor(255, 255, 255);
$pdf->setFont('Arial', '', 11);
$pdf->setTextColor(0, 0, 0); // Negro

// Función para imprimir usuarios.
function imprimirUsuarios($pdf, $usuarios, $estado) {
    if (!empty($usuarios)) {
        $pdf->cell(190, 10, $pdf->encodeString('Rol: ' . $estado), 1, 1, 'C', 1);
        foreach ($usuarios as $usuario) {
            $pdf->cell(40, 10, $usuario['nombre_usuario'], 1, 0, 'C', 1);
            $pdf->cell(40, 10, $pdf->encodeString($usuario['apellido_usuario']), 1, 0, 'C', 1);
            $pdf->cell(65, 10, $usuario['correo_usuario'], 1, 0, 'C', 1);
            $pdf->cell(45, 10, $pdf->encodeString($usuario['tipo_usuario']), 1, 1, 'C', 1);
        }
    } else {
        $pdf->cell(190, 10, $pdf->encodeString('No hay usuarios registrados con estado ' . $estado), 1, 1, 'C', 1);
    }
}

// Imprime los usuarios activos
imprimirUsuarios($pdf, $usuariosActivos, 'Activo');

// Imprime los usuarios inactivos
imprimirUsuarios($pdf, $usuariosInactivos, 'Inactivo');

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Usuarios por estado.pdf');
