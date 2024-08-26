<?php
// Se incluye la clase del modelo.
require_once ('../../modelos/data/vendedor_data.php');
// Se crea un objeto de la clase del modelo.
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $vendedor = new VendedorData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $vendedor->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$vendedor->setNombre($_POST['nombre_proveedor']) or
                    !$vendedor->setApellido($_POST['apellido_proveedor']) or
                    !$vendedor->setTelefono($_POST['telefono_proveedor']) or
                    !$vendedor->setCorreo($_POST['correo_proveedor'])
                ) {
                    $result['error'] = $vendedor->getDataError();
                } elseif ($vendedor->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Vendedor creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el vendedor';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $vendedor->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen vendedores registrados';
                }
                break;
            case 'readOne':
                if (!$vendedor->setId($_POST['id_proveedor'])) {
                    $result['error'] = $vendedor->getDataError();
                } elseif ($result['dataset'] = $vendedor->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Vendedor inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$vendedor->setId($_POST['id_proveedor']) or
                    !$vendedor->setNombre($_POST['nombre_proveedor']) or
                    !$vendedor->setApellido($_POST['apellido_proveedor']) or
                    !$vendedor->setTelefono($_POST['telefono_proveedor']) or
                    !$vendedor->setCorreo($_POST['correo_proveedor'])
                ) {
                    $result['error'] = $vendedor->getDataError();
                } elseif ($vendedor->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Vendedor modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el vendedor';
                }
                break;
            case 'deleteRow':
                if (
                    !$vendedor->setId($_POST['id_proveedor'])
                    // !$categoria->setFilename()
                ) {
                    $result['error'] = $vendedor->getDataError();
                } elseif ($vendedor->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Vendedor eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el vendedor';
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

