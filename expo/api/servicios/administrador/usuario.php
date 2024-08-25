<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/usuario_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $administrador = new UsuarioData;
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
                } elseif ($result['dataset'] = $administrador->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombre_usuario']) or
                    !$administrador->setApellido($_POST['apellido_usuario']) or
                    !$administrador->setCorreo($_POST['correo_usuario']) or
                    !$administrador->setClave($_POST['clave_usuario']) or
                    !$administrador->setTipo($_POST['tipo_usuario'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['clave_usuario'] != $_POST['repetir_contra']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el administrador';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $administrador->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen administradores registrados';
                }
                break;
                case 'graficaUsuario':
                    if ($result['dataset'] = $administrador->graficaUsuario()) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                    } else {
                        $result['error'] = 'No existen administradores registrados';
                    }
                    break;
            case 'readOne':
                if (!$administrador->setId($_POST['id_usuario'])) {
                    $result['error'] = 'Administrador incorrecto';
                } elseif ($result['dataset'] = $administrador->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Administrador inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setId($_POST['id_usuario']) or
                    !$administrador->setNombre($_POST['nombre_usuario']) or
                    !$administrador->setApellido($_POST['apellido_usuario']) or
                    !$administrador->setCorreo($_POST['correo_usuario']) or
                    !$administrador->setTipo($_POST['tipo_usuario'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el administrador';
                }
                break;
            case 'deleteRow':
                if ($_POST['id_usuario'] == $_SESSION['id_usuario']) {
                    $result['error'] = 'No se puede eliminar a sí mismo';
                } elseif (!$administrador->setId($_POST['id_usuario'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el administrador';
                }
                break;
                // Cambiar el estado del usuario
            case 'changeState':
                if ($_POST['id_usuario'] == $_SESSION['id_usuario']) {
                    $result['error'] = 'No se puede cambiar el estado a sí mismo';
                } elseif (!$administrador->setId($_POST['id_usuario'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->changeState()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estado del usuario cambiado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar el estado del usuario';
                }
                break;
            case 'getUser':
                if (isset($_SESSION['id_usuario'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['id_usuario'];
                } else {
                    $result['error'] = 'Nombre de administrador indefinido';
                }
                break;
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $administrador->readProfile()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ocurrió un problema al leer el perfil';
                }
                break;
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombre_perfil']) or
                    !$administrador->setApellido($_POST['apellido_perfil']) or
                    !$administrador->setCorreo($_POST['correo_perfil'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el perfil';
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$administrador->checkPassword($_POST['contra_actual'])) {
                    $result['error'] = 'Contraseña actual incorrecta';
                } elseif ($_POST['contra_reciente'] != $_POST['repetir_contra']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$administrador->setClave($_POST['contra_reciente'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($administrador->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Debe autenticarse para ingresar';
                } else {
                    $result['error'] = 'Debe crear un administrador para comenzar';
                }
                break;
            case 'registrarse':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombre_usuario']) or
                    !$administrador->setApellido($_POST['apellido_usuario']) or
                    !$administrador->setCorreo($_POST['correo_usuario']) or
                    !$administrador->setClave($_POST['clave_usuario'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['clave_usuario'] != $_POST['repetir_contra']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->firstUser()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador registrado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar el administrador';
                }
                break;
            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                if (!$administrador->checkUser($_POST['correo'], $_POST['contra'])) {
                    $result['error'] = 'Credenciales incorrectas';
                } elseif ($administrador->checkStatus()) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                } else {
                    $result['error'] = 'Su cuenta ha sido desactivada';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
