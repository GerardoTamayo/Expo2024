<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla CATEGORIA.
 */
class MarcaHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $imagen = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../imagenes/marcas/';

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

     // Función para buscar marcas.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_marca, nombre_marca, imagen
                FROM tb_marcas
                WHERE nombre_marca LIKE ?
                ORDER BY id_marca';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Función para crear marcas.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_marcas(nombre_marca, imagen)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    // Función para leer las marcas.
    public function readAll()
    {
        $sql = 'SELECT id_marca, nombre_marca, imagen
                FROM tb_marcas
                ORDER BY id_marca';
        return Database::getRows($sql);
    }

    // Función para leer una marca.
    public function readOne()
    {
        $sql = 'SELECT id_marca, nombre_marca, imagen
                FROM tb_marcas
                WHERE id_marca = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para leer un archivo.
    public function readFilename()
    {
        $sql = 'SELECT imagen
                FROM tb_marcas
                WHERE id_marca = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para actualizar marca.
    public function updateRow()
    {
        $sql = 'UPDATE tb_marcas
                SET nombre_marca = ? , imagen = ?
                WHERE id_marca = ?';
        $params = array($this->nombre, $this->imagen, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar marca.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_marcas
                WHERE id_marca = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
