<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
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
    protected $precio_venta = null;
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
        $sql = 'SELECT id_venta AS ID, 
            fecha_venta AS FECHA, 
            observacion_venta AS OBSERVACION, 
            estado_venta AS ESTADO, 
            id_cliente,
            nombre_cliente AS CLIENTE,
                CASE 
                    WHEN estado_venta = 1 THEN "Cancelado"
                    WHEN estado_venta = 0 THEN "No cancelado"
                    ELSE "Otro estado"
                    END AS ESTADO_FINAL
                        FROM tb_ventas
                        INNER JOIN tb_clientes USING(id_cliente)
                        ORDER BY FECHA;';
        return Database::getRows($sql);
    }

    public function graficoState()
    {
        $sql = 'SELECT estado_venta AS ESTADO, COUNT(*) AS CANTIDAD
                FROM tb_ventas
                GROUP BY estado_venta;';
        return Database::getRows($sql);
    }


    public function readOne1()
    {
        $sql = 'SELECT id_venta, fecha_venta, observacion_venta, estado_venta, id_cliente, nombre_cliente
                FROM tb_ventas
                INNER JOIN tb_clientes USING(id_cliente)
                WHERE id_venta = ?;';
        $params = array($this->id_venta);
        return Database::getRow($sql, $params);
    }

    public function createRow1()
    {
        $sql = 'INSERT INTO tb_ventas(fecha_venta, observacion_venta, estado_venta, id_cliente)
                VALUES(?, ?, ?, ?)';
        $params = array($this->fecha_venta, $this->observacion_venta, $this->estado_venta, $this->id_cliente);
        return Database::executeRow($sql, $params);
    }

    // Función para actualizar marca.
    public function updateRow1()
    {
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
        $sql = 'SELECT id_detalle_venta AS ID, cantidad_venta AS CANTIDAD, precio_venta AS PRECIO, id_producto, nombre_producto AS PRODUCTO, id_venta as VENTA FROM tb_detalle_ventas
        INNER JOIN tb_ventas USING(id_venta)
        INNER JOIN tb_productos USING(id_producto)
        WHERE id_venta = ?
        ORDER BY VENTA;';
        $params = array($this->id_venta);
        return Database::getRows($sql, $params);
    }

    public function graficaVentas()
    {
        $sql = 'SELECT nombre_producto, SUM(cantidad_venta) AS total_vendido 
        FROM tb_detalle_ventas 
        INNER JOIN tb_productos 
        USING (id_producto) 
        WHERE id_producto = ? 
        HAVING total_vendido;';
        $params = array($this->id_producto);
        return Database::getRows($sql, $params);
    }




    public function readOne()
    {
        $sql = 'SELECT id_detalle_venta AS ID, cantidad_venta AS CANTIDAD, precio_venta AS PRECIO, id_producto AS ID_PRODUCTO, nombre_producto AS PRODUCTO, id_venta as VENTA FROM tb_detalle_ventas
        INNER JOIN tb_ventas USING(id_venta)
        INNER JOIN tb_productos USING(id_producto)
        WHERE id_detalle_venta = ?';
        $params = array($this->id_detalle_venta);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_detalle_ventas(cantidad_venta, precio_venta, id_producto, id_venta)
                VALUES(?, ?, ?, ?)';
        $params = array($this->cantidad_venta, $this->precio_venta, $this->id_producto, $this->id_venta);
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


    public function totalVenta()
    {
        $sql = 'SELECT DATE_FORMAT(v.fecha_venta,  "%Y-%m" ) AS mes, SUM(dv.cantidad_venta * dv.precio_venta) AS total_ventas
        FROM tb_ventas v JOIN tb_detalle_ventas dv ON v.id_venta = dv.id_venta
        WHERE YEAR(v.fecha_venta) = YEAR(CURDATE());';
        return Database::getRows($sql);
    }

    // Metodos para detalle ventas agregar, actualuzar y eliminar
    //actualizar un producto de una venta

    public function agregarVenta()
    {
        $sql = 'CALL insertar_detalle_venta(?, ?, ?, ?);';
        $params = array($this->id_venta, $this->cantidad_venta, $this->id_producto, $this->precio_venta);
        return Database::executeRow($sql, $params);
    }

    public function actualizarVenta()
    {
        $sql = 'CALL actualizar_detalle_venta(?, ?, ?, ?, ?);';
        $params = array($this->id_venta, $this->id_detalle_venta, $this->cantidad_venta, $this->id_producto, $this->precio_venta);
        return Database::executeRow($sql, $params);
    }

    public function eliminarVenta()
    {
        $sql = 'CALL eliminar_detalle_venta(?, ?);';
        $params = array($this->id_detalle_venta, $this->id_venta);
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
    // Función para leer todos los pedido
    public function ventaReports()
    {
        $sql = 'SELECT fecha_venta, nombre_cliente, apellido_cliente, cantidad_venta, precio_venta, 
            (cantidad_venta * precio_venta) AS total
            FROM tb_ventas 
            INNER JOIN tb_clientes USING (id_cliente) 
            INNER JOIN tb_detalle_ventas USING (id_venta)
            ORDER BY fecha_venta DESC;';
        return Database::getRows($sql);
    }

    public function Facturacion()
    {
        $sql = 'SELECT id_venta, fecha_venta, observacion_venta, nombre_cliente, apellido_cliente, telefono_cliente, 
        correo_cliente, dui_cliente, direccion_cliente, cantidad_venta, precio_venta, nombre_producto, 
        (cantidad_venta * precio_venta) AS total_producto 
        FROM tb_ventas 
        JOIN tb_clientes USING (id_cliente) 
        JOIN tb_detalle_ventas USING (id_venta)
        JOIN tb_productos USING (id_producto)
        WHERE id_venta = ?;';
        $params = array($this->id_venta);
        return Database::getRows($sql, $params);
    }
}
