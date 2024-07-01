<?php
// Se incluye la clase del modelo.
require_once ('../../modelos/data/presentacion_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $presentacion = new presentacionData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $presentacion->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$presentacion->setNombre($_POST['tipo_presentacion'])
                ) {
                    $result['error'] = $presentacion->getDataError();
                } elseif ($presentacion->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Presentación creada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el tipo de presentación';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $presentacion->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen presentaciones registradas';
                }
                break;
            case 'readOne':
                if (!$presentacion->setId($_POST['id_presentacion'])) {
                    $result['error'] = $presentacion->getDataError();
                } elseif ($result['dataset'] = $presentacion->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Tipo de presentación inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$presentacion->setId($_POST['id_presentacion']) or
                    !$presentacion->setNombre($_POST['tipo_presentacion'])
                ) {
                    $result['error'] = $presentacion->getDataError();
                } elseif ($presentacion->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Tipo de presentación modificada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar la categoría';
                }
                break;
            case 'deleteRow':
                if (
                    !$presentacion->setId($_POST['id_presentacion']) 
                    // !$presentacion->setFilename()
                ) {
                    $result['error'] = $presentacion->getDataError();
                } elseif ($presentacion->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Presentación eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar la categoría';
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
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
