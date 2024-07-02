<?php
// Se incluye la clase del modelo.
require_once ('../../modelos/data/compra_data.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $compra = new compraData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
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
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$compra->setFecha($_POST['fecha_compra']) or
                    !$compra->setCorrelativo($_POST['numero_correlativo']) or
                    !$compra->setEstado(isset($_POST['estado_compra'])? 1 : 0) or
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
                    !$compra->setEstado(isset($_POST['estado_compra'])? 1 : 0) or
                    !$compra->setIdProveedor($_POST['id_vendedor'] )
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
            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print (json_encode($result));
    } else {
        print (json_encode('Acceso denegado'));
    }
} else {
    print (json_encode('Recurso no disponible'));
}
