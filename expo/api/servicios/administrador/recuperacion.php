<?php
// Se incluye la clase del modelo.
require_once('../../modelos/data/recuperacion_data.php');
// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $recuperacion = new RecuperacionData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //crear
                case 'envioCorreo':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$recuperacion->setFecha($_POST['fecha']) or
                        !$recuperacion->setNivel($_POST['nivel']) or
                        !$recuperacion->setCorreo($_POST['correo'])
                    ) {
                        $result['error'] = $recuperacion->getDataError();
                    } elseif ($recuperacion->readIdUsuario()) {
                        if ($recuperacion->createHash()) {
                            if ($recuperacion->updateHash()){
                                //aqui pondré lo del envio del correo y allí acaba el proceso
                                if ($recuperacion->envioCorreo()) {
                                    $result['status'] = 1;
                                    $result['message'] = 'El correo fue enviado correctamente';
                                } else {
                                    $result['error'] = 'Ocurrió un problema al enviar el correo';
                                }
                            } else {
                                $result['error'] = 'Ocurrió un problema al agregar a la base el hash';
                            }
                        } else {
                            $result['error'] = 'Ocurrió un problema al crear el hash';
                        }
                    } else {
                        $result['error'] = 'Ocurrió un problema al leer el id del usuario';
                    }
                    break;
                case 'updatePass':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$recuperacion->setNivel($_POST['nivel']) or
                        !$recuperacion->setClave($_POST['clave']) or
                        !$recuperacion->setIdUsuario($_POST['idUsuario'])
                    ) {
                        $result['error'] = $recuperacion->getDataError();
                    } elseif ($recuperacion->updatePassword()) {
                            $result['status'] = 1;
                            $result['message'] = 'La contraseña fue actualizada correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al leer el hash';
                    }
                    break;
                case 'readHash':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$recuperacion->setIdUsuario($_POST['idUsuario']) or
                        !$recuperacion->setNivel($_POST['nivel'])
                    ) {
                        $result['error'] = $recuperacion->getDataError();
                    } elseif ($result['message'] = $recuperacion->readHash()) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'Ocurrió un problema al leer el hash';
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
    print(json_encode('Recurso no disponible'));
}