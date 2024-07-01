<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../ayudantes/base_datos.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class VentaHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id_venta = null;
    protected $id_detalle_venta = null;
    protected $cantidad_venta = null;
    protected $precio_venta= null;
    protected $id_producto = null;
    protected $fecha_venta = null;
    protected $observacion_venta = null;
    protected $estado_venta = null;
    protected $id_cliente = null;


    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Función para buscar un pedido 
    public function searchRows1()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_venta AS ID, fecha_venta AS FECHA, observacion_venta  AS OBSERVACIÓN VENTA, estado_venta as ESTADO, id_cliente as CLIENTE, CASE 
        WHEN estado_venta = 1 THEN "Cancelada"
        WHEN estado_venta = 0 THEN "No cancelada"
        END AS ESTADO FROM tb_ventas
                WHERE fecha_venta LIKE ?
                ORDER BY FECHA;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los pedido
    public function readAll1()
    {
        $sql = 'SELECT id_venta AS ID, fecha_venta AS FECHA, observacion_venta AS OBSERVACION, estado_venta AS ESTADO, id_cliente AS CLIENTE FROM tb_ventas;';
        return Database::getRows($sql);
    }

    public function readOne1()
    {
        $sql = 'SELECT id_venta, fecha_venta, observacion_venta, estado_venta, id_cliente
                FROM tb_ventas
                WHERE id_venta = ?';
        $params = array($this->id_venta);
        return Database::getRow($sql, $params);
    }

    public function createRow1()
    {
        $this->estado_venta = 1;
        $sql = 'INSERT INTO tb_ventas(fecha_venta, observacion_venta, estado_venta, id_cliente)
                VALUES(?, ?, ?, ?)';
        $params = array($this->fecha_venta, $this->observacion_venta, $this->estado_venta,  $this->id_cliente);
        return Database::executeRow($sql, $params);
    }

    // Función para actualizar marca.
    public function updateRow1()
    {
        $this->estado_venta = 1;
        $sql = 'UPDATE tb_ventas
                    SET fecha_venta = ? , observacion_venta = ? , estado_venta = ? , id_cliente = ?
                    WHERE id_venta = ?';
        $params = array($this->fecha_venta, $this->observacion_venta, $this->estado_venta, $this->id_cliente, $this->id_venta);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar marca.
    public function deleteRow1()
    {
        $sql = 'DELETE FROM tb_ventas
                    WHERE id_venta = ?';
        $params = array($this->id_venta);
        return Database::executeRow($sql, $params);
    }

        //Función para cambiar el estado de la Venta.
    public function changeState()
    {
            $sql = 'UPDATE tb_ventas SET estado_venta = NOT estado_venta WHERE id_venta = ?;';
            $params = array($this->id_venta);
            return Database::executeRow($sql, $params);
    }

    // Función para leer todos los pedido
    public function readAll()
    {
        $sql = 'SELECT id_detalle_venta AS ID, cantidad_venta AS CANTIDAD, precio_venta AS PRECIO, id_producto AS PRODUCTO, id_venta as Venta FROM tb_detalle_ventas
        ORDER BY Venta;';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_detalle_venta AS ID, cantidad_venta AS CANTIDAD, precio_venta AS PRECIO, id_producto AS PRODUCTO, id_venta as Venta 
                FROM tb_detalle_ventas
                WHERE id_detalle_venta = ?';
        $params = array($this->id_detalle_venta);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_detalle_ventas(cantidad_venta, precio_venta, id_producto, id_venta)
                VALUES(?, ?, ?, ?)';
        $params = array($this->fecha_venta, $this->observacion_venta, $this->estado_venta, $this->id_cliente);
        return Database::executeRow($sql, $params);
    }

    // Función para actualizar marca.
    public function updateRow()
    {
        $sql = 'UPDATE tb_detalle_ventas
                    SET cantidad_venta = ? , precio_venta = ? , id_producto = ? , id_venta = ?
                    WHERE id_detalle_venta = ?';
        $params = array($this->cantidad_venta, $this->precio_venta, $this->id_producto, $this->id_venta, $this->id_detalle_venta);
        return Database::executeRow($sql, $params);
    }

    // Función para eliminar marca.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_detalle_ventas
                    WHERE id_detalle_venta = ?';
        $params = array($this->id_detalle_venta);
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
