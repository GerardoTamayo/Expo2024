<?php

// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
// Se incluye la clase para enviar el correo.
require_once ('../../ayudantes/correo.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla tipos de jugadas.
 */

class RecuperacionHandler
{

    //Declaracion de variables aqui
    protected $idUsuario = null;
    protected $nivel = null;
    protected $fechaRegistro = null;
    protected $hash = null;
    protected $correo = null;
    protected $nombre = null;

    protected $contrasena = null;


    // Crea una funcion que consule la variable nivel y dependiendo de su valor, se mande a llamar readOne1, readOne2 o readOne3 y devuelva el resultado.
    public function readIdUsuario()
    {
        if ($this->nivel == 1) {
            return $this->readOne1();
        }
    }

    public function envioCorreo()
    {
        $titulo = '¡Bienvenid@ ' . $this->nombre . ', estamos aquí para ayudarte!';
        $mailSubject = 'Recuperación de contraseña';	
        $mailAltBody = 'Cambia tu contraseña con un solo click';
        $link = 'http://localhost/Expo2024/expo/vistas/recuperacion.html?c=' . urlencode($this->hash) . '&id=' . urlencode($this->idUsuario) . '&n=' . urlencode($this->nivel);
        $bodytwo = '¡ingresa aquí!';
        $message = 'Si no has solicitado este cambio, por favor ignora este mensaje. Este link solo estará habilitado durante los siguientes 15 minutos, después de este tiempo deberás solicitar un nuevo cambio de contraseña. Recuerda que tu seguridad es nuestra prioridad.';
        $footer = 'Copyright &copy; ' . date("Y") . ' Distribuidora TMG. Todos los derechos reservados.';
        // Cargar plantilla HTML
        $template = file_get_contents('../../ayudantes/email/emailRecuperacion.html');
        // Reemplazar marcadores de posición con co1ntenido dinámico
        $mailBody = str_replace(
            ['{{subject}}', '{{title}}', '{{body}}', '{{link}}', '{{bodytwo}}', '{{message}}', '{{footer}}'],
            [$mailSubject, $titulo, $mailAltBody, $link, $bodytwo, $message, $footer],
            $template
        );        
        return Props::sendMail($this->correo, $mailSubject, $mailBody);
    }
    public function readHash()
    {
        if ($this->nivel == 1) {
            return $this->readHash1();
        }
    }
    public function updateHash()
    {
        if ($this->nivel == 1) {
            return $this->updateRow1();
        }
    }

    
    public function updatePassword()
    {
        $this->hash = '0000';
        if ($this->nivel == 1) {
            return $this->updatePassword1();
        }
    }

    //Función para crear el has
    public function createHash()
{   
    // Crea una variable que contenga un string aleatorio de 10 caracteres.
    $this->hash = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    // Crea una variable que contenga la variable hash y la variable fechaRegistro, separadas por un $.
    $this->hash = $this->hash . 'QQQ' . $this->fechaRegistro;
    return $this->hash;
} 

    //Función para leer id en base a su correo, versión admin.
    public function readOne1()
    {
        $sql = 'SELECT id_usuario, nombre_usuario FROM tb_usuarios
        WHERE correo_usuario = ?;';
        $params = array($this->correo);
        $this->idUsuario = Database::getRow($sql, $params);
        //id usuario contiene un arreglo con el nombre id_administrador, quiero que solo contenga el valor de id_administrador.
        $this->nombre = $this->idUsuario['nombre_usuario'];
        $this->idUsuario = $this->idUsuario['id_usuario'];
        return $this->idUsuario;
    }

    //Función para actualizar el hash en la tabla administradores.
    public function updateRow1()
    {

        $sql = 'UPDATE tb_usuarios SET recovery_code = ? WHERE id_usuario = ?;';
        $params = array(
            $this->hash,
            $this->idUsuario
        );
        return Database::executeRow($sql, $params);
    }

    //Función para actualizar la contraseña en la tabla administradores.
    public function updatePassword1()
    {
        $sql = 'UPDATE tb_usuarios SET clave_usuario = ?, recovery_code = ? WHERE id_usuario = ?;';
        $params = array(
            $this->contrasena,
            $this->hash,
            $this->idUsuario
        );
        return Database::executeRow($sql, $params);
    }

    //Función para leer el hash en la tabla administradores.
    public function readHash1()
    {
        $sql = 'SELECT recovery_code FROM tb_usuarios
        WHERE id_usuario = ?;';
        $params = array($this->idUsuario);
        $this->hash = Database::getRow($sql, $params);
        $this->hash = $this->hash['recovery_code'];
        return $this->hash;
    }
}

