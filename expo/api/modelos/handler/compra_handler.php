<?php

use Phpml\Regression\LeastSquares;

require('C:/xampp/htdocs/Expo2024/vendor/autoload.php');
// Se incluye la clase para trabajar con la base de datos.
require_once('../../ayudantes/base_datos.php');
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
        $sql = 'SELECT id_compra AS ID, fecha_compra AS FECHA, numero_correlativo AS CORRELATIVO, estado_compra as ESTADO, id_proveedor, nombre_proveedor as PROVEEDOR, CASE 
        WHEN estado_compra = 1 THEN "Cancelada"
        WHEN estado_compra = 0 THEN "No cancelada"
        END AS ESTADO FROM tb_compras
                    INNER JOIN tb_proveedores USING(id_proveedor)
                WHERE numero_correlativo LIKE ?
                ORDER BY FECHA;';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    // Función para leer todos los pedido
    public function readAll1()
    {
        $sql = 'SELECT id_compra AS ID,  numero_correlativo AS "CORRELATIVO", fecha_compra AS FECHA, estado_compra AS ESTADO, id_proveedor, nombre_proveedor AS PROVEEDOR, 
		CASE 
		WHEN estado_compra = 1 THEN "Cancelada"
        WHEN estado_compra = 0 THEN "No cancelada"
        ELSE "Otro estado"
        END AS ESTADO
            FROM tb_compras
            INNER JOIN tb_proveedores USING(id_proveedor)
            ORDER BY FECHA;';
        return Database::getRows($sql);
    }

    public function graficaCompras()
    {
        $sql = 'SELECT p.nombre_proveedor, COUNT(c.id_compra) AS total_compras
                FROM tb_compras c
                INNER JOIN tb_proveedores p ON c.id_proveedor = p.id_proveedor
                GROUP BY p.nombre_proveedor
                LIMIT 5;';
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

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_detalle_compra AS ID, cantidad_compra AS CANTIDAD, precio_compra AS PRECIO, id_producto, nombre_producto AS PRODUCTO, id_compra as COMPRA, numero_correlativo AS CORRELATIVO 
        FROM tb_detalle_compras
        INNER JOIN tb_compras USING(id_compra)
        INNER JOIN tb_productos USING(id_producto)
        ORDER BY COMPRA;';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Función para leer todos los pedido
    public function readAll()
    {
        $sql = 'SELECT id_detalle_compra AS ID, cantidad_compra AS CANTIDAD, precio_compra AS PRECIO, id_producto, nombre_producto AS PRODUCTO, id_compra as COMPRA, numero_correlativo AS CORRELATIVO 
        FROM tb_detalle_compras 
        INNER JOIN tb_compras USING(id_compra)
        INNER JOIN tb_productos USING(id_producto)
        WHERE id_compra = ?
        ORDER BY COMPRA;';
        $params = array($this->id_compra);
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_detalle_compra AS ID, cantidad_compra AS CANTIDAD, precio_compra AS PRECIO, id_producto AS ID_PRODUCTO, nombre_producto AS PRODUCTO, id_compra AS ID_COMPRA, numero_correlativo AS CORRELATIVO 
                FROM tb_detalle_compras
                INNER JOIN tb_compras USING(id_compra)
                INNER JOIN tb_productos USING(id_producto)
                WHERE id_detalle_compra = ?';
        $params = array($this->id_detalle_compra);
        return Database::getRow($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_detalle_compras(cantidad_compra, precio_compra, id_producto, id_compra)
                VALUES(?, ?, ?, ?)';
        $params = array($this->cantidad_compra, $this->precio_compra, $this->id_producto, $this->id_compra);
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

    public function totalCompra()
    {
        $sql = 'SELECT DATE_FORMAT(c.fecha_compra, "%Y-%m") AS mes, SUM(dc.cantidad_compra * dc.precio_compra) AS total_compras
        FROM tb_compras c JOIN tb_detalle_compras dc ON c.id_compra = dc.id_compra 
        WHERE YEAR(c.fecha_compra) = YEAR(CURDATE());';
        return Database::getRows($sql);
    }

    // Metodos para detalle compra agregar, actualuzar y eliminar
    //actualizar un producto de una compra
    public function actualizarCompra()
    {
        $sql = 'CALL actualizar_detalle_compra(?, ?, ?, ?, ?);';
        $params = array($this->id_compra, $this->id_detalle_compra, $this->cantidad_compra, $this->id_producto, $this->precio_compra);
        return Database::executeRow($sql, $params);
    }

    public function agregarCompra()
    {
        $sql = 'CALL insertar_orden_validado(?, ?, ?, ?);';
        $params = array($this->id_compra, $this->cantidad_compra, $this->id_producto, $this->precio_compra);
        return Database::executeRow($sql, $params);
    }

    public function eliminarCompra()
    {
        $sql = 'CALL eliminar_detalle_compra(?, ?);';
        $params = array($this->id_detalle_compra, $this->id_compra);
        return Database::executeRow($sql, $params);
    }

    public function vendedorCompra()
    {
        $sql = 'SELECT fecha_compra, cantidad_compra, precio_compra, nombre_producto
        FROM tb_compras 
        JOIN tb_detalle_compras USING (id_compra)
        JOIN tb_productos USING (id_producto)
        WHERE id_proveedor = ?;';
        $params = array($this->id_proveedor);
        return Database::getRows($sql, $params);
    }

    public function compraFactura()
    {
        $sql = '    SELECT 
        id_compra, 
        fecha_compra, 
        numero_correlativo, 
        estado_compra, 
        nombre_proveedor, 
        apellido_proveedor, 
        telefono_proveedor, 
        correo_proveedor, 
        cantidad_compra, 
        precio_compra, 
        nombre_producto, 
        (cantidad_compra * precio_compra) AS total_producto 
    FROM 
        tb_compras 
    JOIN 
        tb_proveedores USING (id_proveedor)
    JOIN 
        tb_detalle_compras USING (id_compra)
    JOIN 
        tb_productos USING (id_producto)
    WHERE 
        id_compra = ?;';
        $params = array($this->id_compra);
        return Database::getRows($sql, $params);
    }

    public function predictExpense()
    {
        // Consulta para obtener las ganancias diarias
        $sql = 'SELECT v.fecha_compra AS FECHA, ROUND(SUM(dc.cantidad_compra * dc.precio_compra),2) AS GASTOS 
            FROM tb_compras v
            INNER JOIN tb_detalle_compras dc ON v.id_compra = dc.id_compra
            WHERE v.estado_compra = 1
            GROUP BY v.fecha_compra
            ORDER BY v.fecha_compra ASC;';
        $rows = Database::getRows($sql);

        if (empty($rows)) {
            return [];
        }

        // Preparar datos para la regresión
        $dates = [];
        $earnings = [];

        foreach ($rows as $row) {
            $date = new DateTime($row['FECHA']);
            $dates[] = $date->getTimestamp(); // Convertir fecha a timestamp
            $earnings[] = $row['GASTOS'];
        }

        $predictions = [];
        // Calcular la regresión para cada día de la próxima semana
        for ($i = 1; $i <= 7; $i++) {
            $X = array_slice($dates, 0, count($dates));
            $y = array_slice($earnings, 0, count($earnings));

            // Crear el modelo de regresión lineal
            $regression = new LeastSquares();
            $regression->train(array_map(function ($timestamp) {
                return [$timestamp];
            }, $X), $y);

            // Predecir las ganancias para el día
            $timestamp = end($dates) + $i * 24 * 60 * 60; // Sumar días en segundos
            $predictedEarnings = $regression->predict([$timestamp]);

            // Redondear el resultado a 2 decimales
            $predictedEarnings = round($predictedEarnings, 2);
            // Convertir timestamp a fecha
            $date = (new DateTime())->setTimestamp($timestamp)->format('d F Y');

            $predictions[] = [
                'fecha' => $date,
                'gastos' => $predictedEarnings
            ];
        }

        return $predictions;
    }
}
