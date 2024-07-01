<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla CATEGORIA.
 */
class PresentacionHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

     // Función para buscar categorías.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_tipo_presentacion, tipo_presentacion
                FROM tipo_presentaciones
                WHERE tipo_presentacion LIKE ?
                ORDER BY tipo_presentacion';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Función para crear una categoría.
    public function createRow()
    {
        $sql = 'INSERT INTO tipo_presentaciones(tipo_presentacion)
                VALUES(?)';
        $params = array($this->nombre);
        return Database::executeRow($sql, $params);
    }

    // Función para leer categorías.
    public function readAll()
    {
        $sql = 'SELECT id_tipo_presentacion, tipo_presentacion
                FROM tipo_presentaciones
                ORDER BY tipo_presentacion';
        return Database::getRows($sql);
    }

    // Función para leer una categoría.
    public function readOne()
    {
        $sql = 'SELECT id_tipo_presentacion, tipo_presentacion
                FROM tipo_presentaciones
                WHERE id_tipo_presentacion = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para actualizar una categoría.
    public function updateRow()
    {
        $sql = 'UPDATE tipo_presentaciones
                SET tipo_presentacion = ?
                WHERE id_tipo_presentacion = ?';
        $params = array($this->nombre, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar una categoria.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tipo_presentaciones
                WHERE id_tipo_presentacion = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
