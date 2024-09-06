<?php
// Se incluye la clase con las plantillas necesarias para generar reportes en PDF.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear un nuevo reporte en PDF.
$pdf = new Report;

// Se verifica si el parámetro 'id_categoria' ha sido pasado a través de la URL.
if (isset($_GET['id_categoria'])) {
    // Se incluyen los modelos que permiten acceder a los datos de categorías y productos.
    require_once('../../modelos/data/categoria_data.php');
    require_once('../../modelos/data/producto_data.php');
    
    // Se crean instancias de las clases para manejar las operaciones de la base de datos.
    $categoria = new CategoriaData;
    $producto = new ProductoData;
    
    // Se asigna el valor de la categoría y se verifica si el valor es válido.
    if ($categoria->setId($_GET['id_categoria']) && $producto->setCategoria($_GET['id_categoria'])) {
        
        // Se verifica si existe la categoría en la base de datos.
        if ($rowCategoria = $categoria->readOne()) {
            
            // Se inicia el reporte con el título que incluye el nombre de la categoría.
            $pdf->startReport('Productos de la categoría: ' . $rowCategoria['nombre_categoria']);
            
            // Se recuperan los productos de la categoría. Si hay datos, se imprimen.
            if ($dataProductos = $producto->productosCategoria()) {
                
                // Configuración del estilo para los encabezados del reporte (colores y fuente).
                $pdf->setFillColor(128, 211, 126); // Color de fondo verde oscuro para el encabezado.
                $pdf->setTextColor(0, 0, 0); // Texto en color negro.
                $pdf->setDrawColor(0, 0, 0); // Borde en color negro.
                $pdf->setFont('Arial', 'B', 12); // Fuente en negrita para el encabezado.
                
                // Se imprimen los encabezados de las columnas.
                $pdf->cell(20, 10, 'ID', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Producto', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Existencias', 1, 0, 'C', 1);
                $pdf->cell(50, 10, 'Descripcion', 1, 0, 'C', 1);
                $pdf->cell(50, 10, 'Fecha Vencimiento', 1, 1, 'C', 1);
                
                // Se cambia la fuente para el contenido de las filas (productos).
                $pdf->setFont('Arial', '', 11);
                $pdf->setTextColor(0, 0, 0); // Texto en color negro para los productos.
                
                // Alternar colores de fondo entre las filas.
                $fill = false;
                foreach ($dataProductos as $rowProducto) {
                    // Se alterna el color de fondo de las filas: gris claro y blanco.
                    $pdf->setFillColor($fill ? 230 : 255);
                    $pdf->cell(20, 8, $rowProducto['id_producto'], 1, 0, 'C', $fill);
                    $pdf->cell(40, 8, $pdf->encodeString($rowProducto['nombre_producto']), 1, 0, 'L', $fill);
                    $pdf->cell(30, 8, $pdf->encodeString($rowProducto['existencias_producto']), 1, 0, 'C', $fill);
                    $pdf->cell(50, 8, $pdf->encodeString($rowProducto['descripcion']), 1, 0, 'L', $fill);
                    $pdf->cell(50, 8, $pdf->encodeString($rowProducto['fecha_vencimiento']), 1, 1, 'C', $fill);
                    $fill = !$fill; // Se alterna el valor de $fill para cambiar el color de la siguiente fila.
                }
            } else {
                // Si no se encuentran productos en la categoría, se muestra un mensaje.
                $pdf->setFillColor(128, 211, 126); // Color verde oscuro para el mensaje.
                $pdf->cell(0, 8, $pdf->encodeString('No hay productos registrados en esta categoría'), 1, 1, 'C', 1);
            }
            
            // Se envía el reporte generado al navegador para ser visualizado o descargado.
            $pdf->output('I', 'categorias.pdf');
        } else {
            // Si la categoría no existe en la base de datos, se muestra un mensaje.
            print('Categoría inexistente');
        }
    } else {
        // Si el ID de la categoría es inválido o no se puede establecer, se muestra un mensaje.
        print('Categoría incorrecta');
    }
} else {
    // Si no se ha pasado un ID de categoría, se muestra un mensaje indicando que es necesario seleccionarlo.
    print('Debe seleccionar una categoría');
}
