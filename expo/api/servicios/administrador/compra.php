<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/compra_data.php');
// Se crea un objeto de la clase.
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $compra = new compraData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])  and Validator::validateSessionTime()) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                // Buscar
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $compra->searchRows1()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
                // Leer todos
            case 'readAll':
                if ($result['dataset'] = $compra->readAll1()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No hay compras registrados';
                }
                break;
                case 'graficaCompras':
                    if ($result['dataset'] = $compra->graficaCompras()) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                    } else {
                        $result['error'] = 'No hay compras registrados';
                    }
                    break;
                    case 'predictiva':
                        if ($result['dataset'] = $compra->predictExpense()) {
                            $result['status'] = 1;
                            $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                        } else {
                            $result['error'] = 'No hay ventas registrados';
                        }
                        break;
            case 'totalCompra':
                if ($result['dataset'] = $compra->totalCompra()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No hay compras registrados';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setFecha($_POST['fecha_compra']) or
                    !$compra->setCorrelativo($_POST['numero_correlativo']) or
                    !$compra->setEstado(isset($_POST['estado_compra']) ? 1 : 0) or
                    !$compra->setIdProveedor($_POST['id_vendedor'])
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->createRow1()) {
                    $result['status'] = 1;
                    $result['message'] = 'Compra agregada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear la compra';
                }
                break;
            case 'readOne':
                if (!$compra->setId($_POST['id_compra'])) {
                    $result['error'] = $compra->getDataError();
                } elseif ($result['dataset'] = $compra->readOne1()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Compra inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setId($_POST['id_compra']) or
                    !$compra->setFecha($_POST['fecha_compra']) or
                    !$compra->setCorrelativo($_POST['numero_correlativo']) or
                    !$compra->setEstado(isset($_POST['estado_compra']) ? 1 : 0) or
                    !$compra->setIdProveedor($_POST['id_vendedor'])
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->updateRow1()) {
                    $result['status'] = 1;
                    $result['message'] = 'Compra modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la compra';
                }
                break;
            case 'deleteRow':
                if (
                    !$compra->setId($_POST['id_compra'])
                    // !$categoria->setFilename()
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->deleteRow1()) {
                    $result['status'] = 1;
                    $result['message'] = 'Compra eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la compra';
                }
                break;
                // Estado
            case 'changeState':
                if (
                    !$compra->setId($_POST['id_compra']) or
                    !$compra->setEstado($_POST)
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->changeState()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estado de la compra cambiado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al alterar el estado de la compra';
                }
                break;
            case 'searchRows1':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $compra->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'readAll1':
                if (!$compra->setId($_POST['id_compra'])) {
                    $result['error'] = $compra->getDataError();
                } elseif ($result['dataset'] = $compra->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No hay productos registrados';
                }
                break;
            case 'createRow1':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setId($_POST['id_compra']) or
                    !$compra->setCantidad($_POST['cantidad']) or
                    !$compra->setPrecio($_POST['precio']) or
                    !$compra->setProducto($_POST['producto'])
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Detalle compra agregado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el detalle';
                }
                break;
            case 'readOne1':
                if (!$compra->setIdDetalle($_POST['id_detalle_compra'])) {
                    $result['error'] = $compra->getDataError();
                } elseif ($result['dataset'] = $compra->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Compra inexistente';
                }
                break;
            case 'updateRow1':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setIdDetalle($_POST['id_detalle_compra']) or
                    !$compra->setId($_POST['id_compra']) or
                    !$compra->setCantidad($_POST['cantidad']) or
                    !$compra->setPrecio($_POST['precio']) or
                    !$compra->setProducto($_POST['producto'])
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Detalle compra modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el detalle';
                }
                break;
            case 'deleteRow1':
                if (
                    !$compra->setIdDetalle($_POST['id_detalle_compra'])
                    // !$categoria->setFilename()
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Detalle compra eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el detalle';
                }
                break;
            case 'actualizarCompra':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setIdDetalle($_POST['id_detalle_compra']) or
                    !$compra->setCantidad($_POST['cantidad']) or
                    !$compra->setPrecio($_POST['precio']) or
                    !$compra->setProducto($_POST['producto']) or
                    !$compra->setId($_POST['id_compra'])
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->actualizarCompra()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cantidad modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la cantidad';
                }
                break;
            case 'agregarCompra':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setCantidad($_POST['cantidad']) or
                    !$compra->setPrecio($_POST['precio']) or
                    !$compra->setId($_POST['id_compra']) or
                    !$compra->setProducto($_POST['producto'])
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->agregarCompra()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto agregado a la compra correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al agregar la cantidad';
                }
                break;
            case 'eliminarCompra':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setIdDetalle($_POST['id_detalle_compra']) or
                    !$compra->setId($_POST['id_compra'])
                ) {
                    $result['error'] = $compra->getDataError();
                } elseif ($compra->eliminarCompra()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto eliminado de la compra correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el producto';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
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
