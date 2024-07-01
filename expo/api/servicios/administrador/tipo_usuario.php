<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/tipo_usuario_data.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $tipo = new TipoUsuariosData;
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
                } elseif ($result['dataset'] = $tipo->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
                // Crear
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$tipo->setTipo($_POST['tipo_usuario'])
                ) {
                    $result['error'] = $tipo->getDataError();
                } elseif ($tipo->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'tipo creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el tipo';
                }
                break;
                // Leer todos
            case 'readAll':
                if ($result['dataset'] = $tipo->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen tipoes registrados';
                }
                break;
                // Leer uno
            case 'readOne':
                if (!$tipo->setId($_POST['idTipo'])) {
                    $result['error'] = $tipo->getDataError();
                } elseif ($result['dataset'] = $tipo->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'tipo inexistente';
                }
                break;
                // Actualizar
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$tipo->setId($_POST['idTipo']) or
                    !$tipo->setTipo($_POST['tipo_usuario'])
                ) {
                    $result['error'] = $tipo->getDataError();
                } elseif ($tipo->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'tipo modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el tipo';
                }
                break;
                // Eliminar
            case 'deleteRow':
                if (
                    !$tipo->setId($_POST['idTipo'])
                ) {
                    $result['error'] = $tipo->getDataError();
                } elseif ($tipo->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'tipo eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el tipo';
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
