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

    /*
     *  Métodos para gestionar la cuenta del usuario.
     */

     // Función para el login.
    public function checkUser($username, $password)
    {
        $sql = 'SELECT id_usuario, clave_usuario
                FROM tb_usuarios
                WHERE  correo_usuario = ?';
        $params = array($username);
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['clave_usuario'])) {
            $_SESSION['id_usuario'] = $data['id_usuario'];
            return true;
        } else {
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
        $sql = 'INSERT INTO tb_usuarios(nombre_usuario, apellido_usuario, correo_usuario, clave_usuario,id_tipo)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->clave, $this->tipo);
        return Database::executeRow($sql, $params);
    }

    // Función para primer uso.
    public function firstUser()
    {
        $sql = 'INSERT INTO tb_usuarios(nombre_usuario, apellido_usuario, clave_usuario, correo_usuario, id_tipo)
                VALUES(?, ?, ?, ?, 1)';
        $params = array($this->nombre, $this->apellido, $this->clave, $this->correo);
        return Database::executeRow($sql, $params);
    }

    // Función para leer usuarioes.
    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombre_usuario, apellido_usuario, clave_usuario, correo_usuario, id_tipo, tipo_usuario
                FROM tb_usuarios
                INNER JOIN tb_tipousuarios USING (id_tipo)
                ORDER BY id_usuario';
        return Database::getRows($sql);
    }

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
}
