<?php
// Se incluye la clase del modelo.
require_once ('../../modelos/data/cliente_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new ClienteData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])  and Validator::validateSessionTime()) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $cliente->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$cliente->setNombre($_POST['nombre_cliente']) or
                    !$cliente->setApellido($_POST['apellido_cliente']) or
                    !$cliente->setTelefono($_POST['telefono_cliente']) or
                    !$cliente->setCorreo($_POST['correo_cliente']) or
                    !$cliente->setDUI($_POST['dui_cliente']) or
                    !$cliente->setDireccion($_POST['direccion_cliente']) 
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'cliente creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el cliente';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $cliente->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen clientees registrados';
                }
                break;
                case 'countAll':
                    if ($result['dataset'] = $cliente->countAll()) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'No existen clientees registrados';
                    }
                    break;
            case 'readOne':
                if (!$cliente->setId($_POST['id_cliente'])) {
                    $result['error'] = 'cliente incorrecto';
                } elseif ($result['dataset'] = $cliente->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'cliente inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$cliente->setId($_POST['id_cliente']) or
                    !$cliente->setNombre($_POST['nombre_cliente']) or
                    !$cliente->setApellido($_POST['apellido_cliente']) or
                    !$cliente->setTelefono($_POST['telefono_cliente']) or
                    !$cliente->setCorreo($_POST['correo_cliente']) or
                    !$cliente->setDUI($_POST['dui_cliente']) or
                    !$cliente->setDireccion($_POST['direccion_cliente']) 
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'cliente modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el cliente';
                }
                break;
                case 'deleteRow':
                    if (
                        !$cliente->setId($_POST['id_cliente'])
                    ) {
                        $result['error'] = $cliente->getDataError();
                    } elseif ($cliente->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Cliente eliminado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el cliente';
                    }
                    break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } 
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print (json_encode($result));
} else {
    print (json_encode('Recurso no disponible'));
}
