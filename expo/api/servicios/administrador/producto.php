<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/producto_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $producto = new ProductoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])  and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $producto->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$producto->setNombre($_POST['nombre_producto']) or
                    !$producto->setFecha($_POST['fecha_producto']) or
                    !$producto->setDescripcion($_POST['descripcion_producto']) or
                    !$producto->setExistencias($_POST['existencias_producto']) or
                    !$producto->setPresentacion($_POST['presentacion_producto']) or
                    !$producto->setCategoria($_POST['categoria_producto']) or
                    !$producto->setMarca($_POST['marca_producto'])
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el producto';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $producto->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen productos registrados';
                }
                break;
                case 'graficaProducto':
                    if ($result['dataset'] = $producto->graficaProducto()) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                    } else {
                        $result['error'] = 'No existen productos registrados';
                    }
                    break;

                case 'countAllProducts':
                    if ($result['dataset'] = $producto->countAllProducts()) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'No existen productos registrados';
                    }
                    break;
            case 'readOne':
                if (!$producto->setId($_POST['id_producto'])) {
                    $result['error'] = $producto->getDataError();
                } elseif ($result['dataset'] = $producto->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Producto inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$producto->setId($_POST['id_producto']) or
                    !$producto->setNombre($_POST['nombre_producto']) or
                    !$producto->setFecha($_POST['fecha_producto']) or
                    !$producto->setDescripcion($_POST['descripcion_producto']) or
                    !$producto->setPresentacion($_POST['presentacion_producto']) or
                    !$producto->setCategoria($_POST['categoria_producto']) or
                    !$producto->setMarca($_POST['marca_producto']) or
                    !$producto->setExistencias($_POST['existencias_producto'])
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el producto';
                }
                break;
            case 'deleteRow':
                if (
                    !$producto->setId($_POST['id_producto'])
                ) {
                    $result['error'] = $producto->getDataError();
                } elseif ($producto->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el producto';
                }
                break;
            // case 'cantidadProductosCategoria':
            //     if ($result['dataset'] = $producto->cantidadProductosCategoria()) {
            //         $result['status'] = 1;
            //     } else {
            //         $result['error'] = 'No hay datos disponibles';
            //     }
            //     break;
            // case 'porcentajeProductosCategoria':
            //     if ($result['dataset'] = $producto->porcentajeProductosCategoria()) {
            //         $result['status'] = 1;
            //     } else {
            //         $result['error'] = 'No hay datos disponibles';
            //     }
            //     break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
