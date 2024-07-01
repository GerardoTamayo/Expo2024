<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla usuario.
 */
class ClienteHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $telefono = null;
    protected $correo = null;
    protected $dui = null;
    protected $direccion = null;



    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar los usuarioes.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, telefono_cliente, correo_cliente, dui_cliente, direccion_cliente
                FROM tb_clientes
                WHERE nombre_cliente LIKE ? OR dui_cliente LIKE ?
                ORDER BY nombre_cliente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un admministrador.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_clientes(nombre_cliente, apellido_cliente, telefono_cliente, correo_cliente, dui_cliente, direccion_cliente)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->telefono, $this->correo, $this->dui, $this->direccion);
        return Database::executeRow($sql, $params);
    }

    // Función para leer usuarioes.
    public function readAll()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, telefono_cliente, correo_cliente, dui_cliente, direccion_cliente
                FROM tb_clientes
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

    // Función para leer un usuario.
    public function readOne()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, telefono_cliente, correo_cliente, dui_cliente, direccion_cliente
                FROM tb_clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para eliminar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_clientes
                SET nombre_cliente = ?, apellido_cliente = ?, telefono_cliente = ?, correo_cliente = ?, dui_cliente = ?, direccion_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->telefono, $this->correo, $this->dui, $this->direccion, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}

