<?php
// Se incluye la clase del modelo.
require_once ('../../modelos/data/venta_data.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $venta = new ventaData;
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
                } elseif ($result['dataset'] = $venta->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            // Leer todos
            case 'readAll':
                if ($result['dataset'] = $venta->readAll1()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No hay ventas registrados';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$venta->setFecha($_POST['fecha_venta']) or
                    !$venta->setObservacion($_POST['observacion_venta']) or
                    !$venta->setEstado(isset($_POST['estado_venta'])? 1 : 0) or
                    !$venta->setIdProveedor($_POST['id_cliente'])
                ) {
                    $result['error'] = $venta->getDataError();
                } elseif ($venta->createRow1()) {
                    $result['status'] = 1;
                    $result['message'] = 'venta creada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear la venta';
                }
                break;
            case 'readOne':
                if (!$venta->setId($_POST['id_venta'])) {
                    $result['error'] = $venta->getDataError();
                } elseif ($result['dataset'] = $venta->readOne1()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Venta inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$venta->setId($_POST['id_venta']) or
                    !$venta->setFecha($_POST['fecha_venta']) or
                    !$venta->setObservacion($_POST['observacion_venta']) or
                    !$venta->setEstado(isset($_POST['estado_venta'])? 1 : 0) or
                    !$venta->setIdProveedor($_POST['id_cliente'])
                ) {
                    $result['error'] = $venta->getDataError();
                } elseif ($venta->updateRow1()) {
                    $result['status'] = 1;
                    $result['message'] = 'venta modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la venta';
                }
                break;
            case 'deleteRow':
                if (
                    !$venta->setId($_POST['id_venta'])
                    // !$categoria->setFilename()
                ) {
                    $result['error'] = $venta->getDataError();
                } elseif ($venta->deleteRow1()) {
                    $result['status'] = 1;
                    $result['message'] = 'venta eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la venta';
                }
                break;
            // Estado
            case 'changeState':
                if (
                    !$venta->setId($_POST['id_venta']) or
                    !$venta->setEstado($_POST)
                ) {
                    $result['error'] = $venta->getDataError();
                } elseif ($venta->changeState()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estado del venta cambiado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al alterar el estado del venta';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
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
