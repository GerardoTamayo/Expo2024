<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla usuario.
 */
class UsuarioHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $clave = null;

    protected $tipo = null;

    protected $estado = null;

    /*
     *  Métodos para gestionar la cuenta del usuario.
     */

    // Función para el login.
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_usuario, nombre_usuario, correo_usuario, clave_usuario, estado_usuario
                FROM tb_usuarios
                WHERE  correo_usuario = ?';
        $params = array($username);
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['clave_usuario'])) {
            $this->id = $data['id_usuario'];
            $this->estado = $data['estado_usuario'];
            $_SESSION['nombre_usuario'] = $data['nombre_usuario'];
            return true;
        } else {
            return false;
        }
    }

    //Función para chequear el estado de la cuenta del usuario
    public function checkStatus()
    {
        //se verifica si el estado es activo
        if ($this->estado) {
            //se crea variable de sesión llamada idCliente, para verificar que exista una sesión iniciada
            $_SESSION['id_usuario'] = $this->id;
            //se crea variable de sesión llamada correoCliente para alguna verificación que se pueda utilizar con esta
            //ya sea para el perfil o alguna otra cosa mas
            $_SESSION['correo_usuario'] = $this->correo;
            //se retorna true si es correcta la verificación del estado
            return true;
        }
        //en caso que el estado sea inactivo o bloqueado
        else {
            //se retorna falso y no se dejara iniciar sesión
            return false;
        }
    }

    // Función para comprobar la contraseña.
    public function checkPassword($password)
    {
        $sql = 'SELECT clave_usuario
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['id_usuario']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_usuario'])) {
            return true;
        } else {
            return false;
        }
    }

    // Función para cambiar contraseña.
    public function changePassword()
    {
        $sql = 'UPDATE tb_usuarios
                SET clave_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->clave, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    // Función para buscar los tipos de usuarios.
    public function readProfile()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario
                FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['id_usuario']);
        return Database::getRow($sql, $params);
    }

    // Función para leer perfil.
    public function editProfile()
    {
        $sql = 'UPDATE tb_usuarios
                SET nombre_usuario = ?, apellido_usuario = ?, correo_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar los usuarioes.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, id_tipo, tipo_usuario
                FROM tb_usuarios
                INNER JOIN tb_tipousuarios USING (id_tipo)
                WHERE nombre_usuario LIKE ? OR apellido_usuario LIKE ?
                ORDER BY id_usuario';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un admministrador.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_usuarios(nombre_usuario, apellido_usuario, correo_usuario, clave_usuario, id_tipo, estado_usuario)
                VALUES(?, ?, ?, ?, ?, 1)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->clave, $this->tipo);
        return Database::executeRow($sql, $params);
    }

    // Función para primer uso.
    public function firstUser()
    {
        $sql = 'INSERT INTO tb_usuarios(nombre_usuario, apellido_usuario, clave_usuario, correo_usuario, id_tipo, estado_usuario)
                VALUES(?, ?, ?, ?, 1, 1)';
        $params = array($this->nombre, $this->apellido, $this->clave, $this->correo);
        return Database::executeRow($sql, $params);
    }

    // Función para leer usuarioes.
    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, id_tipo, tipo_usuario,
                CASE 
                WHEN estado_usuario = 1 THEN "Activo"
                WHEN estado_usuario = 0 THEN "Bloqueado"
                END AS ESTADO
                FROM tb_usuarios
                INNER JOIN tb_tipousuarios USING (id_tipo)
                ORDER BY id_usuario';
        return Database::getRows($sql);
    }

    // public function graficaUsuario()
    // {
    //     $sql = 'SELECT 
    // CASE 
    //     WHEN u.estado_usuario = 1 THEN "Activo"
    //     ELSE "Inactivo"
    //     END AS Estado_Usuario, 
    //     COUNT(u.id_usuario) AS Cantidad_Usuarios
    //     FROM tb_usuarios u
    //     WHERE 
    //     u.id_tipo = ? -- Reemplaza ? con el id_tipo deseado
    //     GROUP BY 
    //     u.estado_usuario;';
    //     $params  = array($this->tipo);
    //     return Database::getRows($sql, $params);
    // }



    // Función para leer un usuario.
    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, id_tipo, tipo_usuario
                FROM tb_usuarios
                INNER JOIN tb_tipousuarios USING (id_tipo)
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para eliminar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_usuarios
                SET nombre_usuario = ?, apellido_usuario = ?, correo_usuario = ?, id_tipo = ?
                WHERE id_usuario = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->tipo, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Función para cambiar el estado de un cliente.
    public function changeState()
    {
        $sql = 'CALL cambiar_estado_usuario(?);';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readEstado()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, correo_usuario, tipo_usuario, 
        CASE WHEN estado_usuario = 1 THEN "Activo"
        ELSE "Inactivo"
        END AS estado
        FROM tb_usuarios 
        INNER JOIN tb_tipousuarios USING (id_tipo);';
        return Database::getRows($sql);
    }
}
