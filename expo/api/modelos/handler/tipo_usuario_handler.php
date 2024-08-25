<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class TiposUsuariosHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id_tipo = null;
    protected $tipo_usuario = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    //Función para buscar tipos de usuarios
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT * FROM tb_tipousuarios
                WHERE tipo_usuario LIKE ?
                ORDER BY tipo_usuario;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    //Función para crear un rol
    public function createRow()
    {
        $sql = 'INSERT INTO tb_tipousuarios (tipo_usuario)
                VALUES(?);';
        $params = array($this->tipo_usuario);
        return Database::executeRow($sql, $params);
    }

    //Función para mostrar todos los tipos de usuarios
    public function readAll()
    {
        $sql = 'SELECT id_tipo, tipo_usuario FROM tb_tipousuarios
                ORDER BY tipo_usuario;';
        return Database::getRows($sql);
    }

    public function graficaTipo()
    {
        $sql = 'SELECT 
    CASE 
        WHEN u.estado_usuario = 1 THEN "Activo"
        ELSE "Inactivo"
        END AS Estado_Usuario, 
        COUNT(u.id_usuario) AS Cantidad_Usuarios
        FROM tb_usuarios u
        WHERE 
        u.id_tipo = ? -- Reemplaza ? con el id_tipo deseado
        GROUP BY 
        u.estado_usuario;';
         $params = array($this->id_tipo);
         return Database::getRows($sql, $params);
    }

    //Función para mostrar uno de los tipos de usuarios
    public function readOne()
    {
        $sql = 'SELECT id_tipo, tipo_usuario FROM tb_tipousuarios
                WHERE id_tipo = ?';
        $params = array($this->id_tipo);
        return Database::getRow($sql, $params);
    }

    //Función para actualizar un rol
    public function updateRow()
    {
        $sql = 'UPDATE tb_tipousuarios SET tipo_usuario = ?
                WHERE id_tipo = ?;';
        $params = array($this->tipo_usuario, $this->id_tipo);
        return Database::executeRow($sql, $params);
    }

    //Función para eliminar un rol
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_tipousuarios
                WHERE id_tipo = ?;';
        $params = array($this->id_tipo);
        return Database::executeRow($sql, $params);
    }
}
