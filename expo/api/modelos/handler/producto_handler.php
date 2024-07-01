<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
/*
 *	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
 */
class ProductoHandler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $fecha = null;
    protected $descripcion = null;
    protected $existencias = null;
    protected $tipo_presentacion = null;
    protected $categoria = null;
    protected $marca = null;
    protected $usuario = null;

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Barra de de busqueda, buscar producto.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT p.nombre_producto, p.fecha_vencimiento, p.descripcion, p.existencias_producto, p.id_tipo_presentacion, tipo_presentacion, p.id_categoria, nombre_categoria, p.id_marca, nombre_marca
                FROM tb_productos p
                INNER JOIN tipo_presentaciones USING(id_tipo_presentacion)
                INNER JOIN tb_categorias USING(id_categoria)
                INNER JOIN tb_marcas USING(id_marca)
                WHERE nombre_producto LIKE ? OR fecha_vencimiento LIKE ?
                ORDER BY nombre_producto';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para crear un producto.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_productos(nombre_producto, fecha_vencimiento, descripcion, existencias_producto, id_tipo_presentacion, id_categoria, id_marca, id_usuario)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->fecha, $this->descripcion, $this->existencias, $this->tipo_presentacion, $this->categoria,  $this->marca, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    // Fucnión para leer todos los productos. 
    public function readAll()
    {
        $sql = 'SELECT p.nombre_producto, p.fecha_vencimiento, p.descripcion, p.existencias_producto, p.id_tipo_presentacion, tipo_presentacion, p.id_categoria, nombre_categoria, p.id_marca, nombre_marca
                FROM tb_productos p
                INNER JOIN tipo_presentaciones USING(id_tipo_presentacion)
                INNER JOIN tb_categorias USING(id_categoria)
                INNER JOIN tb_marcas USING(id_marca)
                ORDER BY nombre_producto';
        return Database::getRows($sql);
    }

    // Función para leer un producto.
    public function readOne()
    {
        $sql = 'SELECT p.nombre_producto, p.fecha_vencimiento, p.descripcion, p.existencias_producto, p.id_tipo_presentacion, tipo_presentacion, p.id_categoria, nombre_categoria, p.id_marca, nombre_marca
                FROM tb_productos p
                INNER JOIN tipo_presentaciones USING(id_tipo_presentacion)
                INNER JOIN tb_categorias USING(id_categoria)
                INNER JOIN tb_marcas USING(id_marca)
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para actualizar producto.
    public function updateRow()
    {
        $sql = 'UPDATE tb_productos
                SET nombre_producto = ?, fecha_vencimiento = ?, descripcion = ?, existencias_producto = ?, id_tipo_presentacion = ?, id_categoria = ?, id_marca = ?
                WHERE id_producto = ?';
        $params = array($this->nombre, $this->fecha, $this->descripcion, $this->existencias, $this->tipo_presentacion, $this->categoria, $this->marca, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para eliminar un producto.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para visualizar gficas.
    // public function readProductosCategoria()
    // {
    //     $sql = 'SELECT p.id_producto, p.nombre_producto, p.id_categoria, p.precio_unitario, p.descripcion, p.id_marca, nombre_marca, p.imagen AS IMAGEN
    //             FROM tb_productos p
    //             INNER JOIN tb_categorias USING(id_categoria)
    //             INNER JOIN tb_marcas USING(id_marca)
    //             WHERE id_categoria = ?
    //             ORDER BY p.nombre_producto';
    //     $params = array($this->categoria);
    //     return Database::getRows($sql, $params);
    // }
    
    // public function searchRowsPublic()
    // {
    //     $value = '%' . Validator::getSearchValue() . '%';
    //     $sql = 'SELECT p.id_producto, p.nombre_producto, p.id_categoria, p.precio_unitario, p.descripcion, p.id_marca, nombre_marca, p.imagen AS IMAGEN
    //             FROM tb_productos p
    //             INNER JOIN tb_categorias USING(id_categoria)
    //             INNER JOIN tb_marcas USING(id_marca)
    //             WHERE p.id_categoria = ? AND p.nombre_producto LIKE ?
    //             ORDER BY p.nombre_producto';
    //     $params = array($this->categoria, $value);
    //     return Database::getRows($sql, $params);
    // }

    // /*
    //  *   Métodos para generar gráficos.
    //  */
    // public function cantidadProductosCategoria()
    // {
    //     $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
    //             FROM tb_productos
    //             INNER JOIN tb_categorias USING(id_categoria)
    //             GROUP BY nombre_categoria ORDER BY cantidad DESC LIMIT 5';
    //     return Database::getRows($sql);
    // }

    // public function porcentajeProductosCategoria()
    // {
    //     $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM tb_productos)), 2) porcentaje
    //             FROM tb_productos
    //             INNER JOIN tb_categorias USING(id_categoria)
    //             GROUP BY nombre_categoria ORDER BY porcentaje DESC';
    //     return Database::getRows($sql);
    // }

    // /*
    //  *   Métodos para generar reportes.
    //  */
    // public function productosCategoria()
    // {
    //     $sql = 'SELECT nombre_producto, precio_producto, estado_producto
    //             FROM tb_productos
    //             INNER JOIN tb_categorias USING(id_categoria)
    //             WHERE id_categoria = ?
    //             ORDER BY nombre_producto';
    //     $params = array($this->categoria);
    //     return Database::getRows($sql, $params);
    // }
}
