<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla usuario.
 */
class VendedorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $telefono = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar los usuarioes.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_proveedor, nombre_proveedor, apellido_proveedor, telefono_proveedor, correo_proveedor
                FROM tb_proveedores
                WHERE nombre_proveedor LIKE ? OR telefono_proveedor LIKE ?
                ORDER BY id_proveedor';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un admministrador.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_proveedores(nombre_proveedor, apellido_proveedor, telefono_proveedor, correo_proveedor)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->telefono, $this->correo);
        return Database::executeRow($sql, $params);
    }

    // Función para leer usuarioes.
    public function readAll()
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor, apellido_proveedor, telefono_proveedor, correo_proveedor
                FROM tb_proveedores
                ORDER BY id_proveedor';
        return Database::getRows($sql);
    }

    // Función para leer un usuario.
    public function readOne()
    {
        $sql = 'SELECT id_proveedor, nombre_proveedor, apellido_proveedor, telefono_proveedor, correo_proveedor
                FROM tb_proveedores
                WHERE id_proveedor = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para eliminar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_proveedores
                SET nombre_proveedor = ?, apellido_proveedor = ?, telefono_proveedor = ?, correo_proveedor = ?
                WHERE id_proveedor = ?';
        $params = array($this->nombre, $this->apellido, $this->telefono, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_proveedores
                WHERE id_proveedor = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
