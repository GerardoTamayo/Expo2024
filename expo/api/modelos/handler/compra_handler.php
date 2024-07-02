<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class CompraHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id_compra = null;
    protected $id_detalle_compra = null;
    protected $cantidad_compra = null;
    protected $precio_compra = null;
    protected $id_producto = null;
    protected $fecha_compra = null;
    protected $numero_correlativo = null;
    protected $estado_compra = null;
    protected $id_proveedor = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un pedido 
    public function searchRows1()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_compra AS ID, fecha_compra AS FECHA, numero_correlativo AS CORRELATIVO, estado_compra as ESTADO, id_proveedor as PROVEEDOR, CASE 
        WHEN estado_compra = 1 THEN "Cancelada"
        WHEN estado_compra = 0 THEN "No cancelada"
        END AS ESTADO FROM tb_compras
                WHERE numero_correlativo LIKE ? or estado_compra like ?
                ORDER BY FECHA;';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los pedido
    public function readAll1()
    {
        $sql = 'SELECT 
    id_compra AS ID, 
    fecha_compra AS FECHA, 
    numero_correlativo AS "CORRELATIVO", 
    estado_compra AS ESTADO, 
    id_proveedor, 
    nombre_proveedor AS PROVEEDOR,
    CASE 
        WHEN estado_compra = 1 THEN "Cancelada"
        WHEN estado_compra = 0 THEN "No cancelada"
        ELSE "Otro estado"
    END AS ESTADO_DESC
FROM tb_compras
INNER JOIN tb_proveedores USING(id_proveedor)
ORDER BY FECHA;';
        return Database::getRows($sql);
    }

    public function readOne1()
    {
        $sql = 'SELECT id_compra, fecha_compra, numero_correlativo, estado_compra, id_proveedor, nombre_proveedor
                FROM tb_compras
                INNER JOIN tb_proveedores USING(id_proveedor)
                WHERE id_compra = ?';
        $params = array($this->id_compra);
        return Database::getRow($sql, $params);
    }

    public function createRow1()
    {
        $sql = 'INSERT INTO tb_compras(fecha_compra, numero_correlativo, estado_compra, id_proveedor)
                VALUES(?, ?, ?, ?)';
        $params = array($this->fecha_compra, $this->numero_correlativo, $this->estado_compra, $this->id_proveedor);
        return Database::executeRow($sql, $params);
    }

    // Función para actualizar marca.
    public function updateRow1()
    {
        $sql = 'UPDATE tb_compras
                    SET fecha_compra = ? , numero_correlativo = ? , estado_compra = ? , id_proveedor = ?
                    WHERE id_compra = ?';
        $params = array($this->fecha_compra, $this->numero_correlativo, $this->estado_compra, $this->id_proveedor, $this->id_compra);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar marca.
    public function deleteRow1()
    {
        $sql = 'DELETE FROM tb_compras
                    WHERE id_compra = ?';
        $params = array($this->id_compra);
        return Database::executeRow($sql, $params);
    }

    //Función para cambiar el estado de la compra.
    public function changeState()
    {
        $sql = 'UPDATE tb_compras SET estado_compra = NOT estado_compra WHERE id_compra = ?;';
        $params = array($this->id_compra);
        return Database::executeRow($sql, $params);
    }

    // Función para leer todos los pedido
    public function readAll()
    {
        $sql = 'SELECT id_detalle_compra AS ID, cantidad_compra AS CANTIDAD, precio_compra AS PRECIO, id_producto AS PRODUCTO, id_compra as COMPRA FROM tb_detalle_compras
        ORDER BY COMPRA;';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_detalle_compra AS ID, cantidad_compra AS CANTIDAD, precio_compra AS PRECIO, id_producto AS PRODUCTO, id_compra as COMPRA 
                FROM tb_detalle_compras
                WHERE id_detalle_compra = ?';
        $params = array($this->id_detalle_compra);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_detalle_compras(cantidad_compra, precio_compra, id_producto, id_compra)
                VALUES(?, ?, ?, ?)';
        $params = array($this->fecha_compra, $this->numero_correlativo, $this->estado_compra, $this->id_proveedor);
        return Database::executeRow($sql, $params);
    }

    // Función para actualizar marca.
    public function updateRow()
    {
        $sql = 'UPDATE tb_detalle_compras
                    SET cantidad_compra = ? , precio_compra = ? , id_producto = ? , id_compra = ?
                    WHERE id_detalle_compra = ?';
        $params = array($this->cantidad_compra, $this->precio_compra, $this->id_producto, $this->id_compra, $this->id_detalle_compra);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar marca.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_detalle_compras
                    WHERE id_detalle_compra = ?';
        $params = array($this->id_detalle_compra);
        return Database::executeRow($sql, $params);
    }

    // //Funcion de buscador
    // public function readAllPublic()
    // {
    //     $sql = 'SELECT id_pedido AS ID, fecha_venta AS FECHA, direccion_pedido AS DIRECCION, CASE 
    //     WHEN estado_pedido = 1 THEN "Entregado"
    //     WHEN estado_pedido = 0 THEN "Cancelado"
    //     END AS ESTADO FROM tb_pedidos
    //     ORDER BY FECHA;';
    //     return Database::getRows($sql);
    // }


}
