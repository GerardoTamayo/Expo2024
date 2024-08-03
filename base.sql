DROP DATABASE if EXISTS quickstock;

CREATE DATABASE quickstock;

USE quickstock;

CREATE TABLE tb_categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(25) NOT NULL
);

CREATE TABLE tipo_presentaciones (
    id_tipo_presentacion INT AUTO_INCREMENT PRIMARY KEY,
    tipo_presentacion VARCHAR(25) NOT NULL
);

CREATE TABLE tb_proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_proveedor VARCHAR(50) NOT NULL,
    apellido_proveedor VARCHAR(50) NOT NULL,
    telefono_proveedor VARCHAR(10) NOT NULL,
    correo_proveedor VARCHAR(100) NOT NULL
);

CREATE TABLE tb_marcas (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre_marca VARCHAR(25) NOT NULL,
    imagen VARCHAR(50) NULL
);

CREATE TABLE tb_tipousuarios (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    tipo_usuario VARCHAR(25) NOT NULL
);

CREATE TABLE tb_usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    apellido_usuario VARCHAR(50) NOT NULL,
    clave_usuario VARCHAR(100) NOT NULL,
    correo_usuario VARCHAR(100) NOT NULL,
    id_tipo INT NOT NULL,
    estado_usuario BOOLEAN NOT NULL
);

CREATE TABLE tb_productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(50) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    descripcion VARCHAR(100) NOT NULL,
    existencias_producto INT NOT NULL,
    CONSTRAINT chk_existencias_producto CHECK (existencias_producto >= 0),
    id_tipo_presentacion INT NOT NULL,
    id_categoria INT NOT NULL,
    id_marca INT NOT NULL,
    id_usuario INT NOT NULL
);

CREATE TABLE tb_compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    fecha_compra DATE NOT NULL,
    numero_correlativo INT NOT NULL,
    CONSTRAINT chk_numero_correlativo CHECK (numero_correlativo >= 0),
    estado_compra BOOLEAN NOT NULL,
    id_proveedor INT NOT NULL
);

CREATE TABLE tb_clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cliente VARCHAR(50) NOT NULL,
    apellido_cliente VARCHAR(50) NOT NULL,
    telefono_cliente VARCHAR(10),
    correo_cliente VARCHAR(100),
    dui_cliente VARCHAR(100),
    direccion_cliente VARCHAR(100)
);

CREATE TABLE tb_ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_venta DATE NOT NULL,
    observacion_venta VARCHAR(100),
    estado_venta BOOLEAN NOT NULL,
    id_cliente INT
);

CREATE TABLE tb_detalle_ventas (
    id_detalle_venta INT AUTO_INCREMENT PRIMARY KEY,
    cantidad_venta INT,
    CONSTRAINT chk_cantidad_venta CHECK (cantidad_venta >= 0),
    precio_venta DECIMAL(10, 2) NOT NULL,
    CONSTRAINT chk_precio_venta CHECK (precio_venta >= 0),
    id_producto INT,
    id_venta INT
);

CREATE TABLE tb_detalle_compras (
    id_detalle_compra INT AUTO_INCREMENT PRIMARY KEY,
    cantidad_compra INT,
    CONSTRAINT chk_cantidad_compra CHECK (cantidad_compra >= 0),
    precio_compra DECIMAL(10, 2) NOT NULL,
    CONSTRAINT chk_precio_compra CHECK (precio_compra >= 0),
    id_producto INT,
    id_compra INT
);

ALTER TABLE
    tb_proveedores
ADD
    UNIQUE (correo_proveedor);

ALTER TABLE
    tb_usuarios
ADD
    UNIQUE (correo_usuario);

ALTER TABLE
    tb_clientes
ADD
    UNIQUE (correo_cliente);

ALTER TABLE
    tb_clientes
ADD
    UNIQUE (dui_cliente);

ALTER TABLE
    tb_productos
ADD
    CONSTRAINT fk_id_categoria FOREIGN KEY (id_categoria) REFERENCES tb_categorias (id_categoria);

ALTER TABLE
    tb_productos
ADD
    CONSTRAINT fk_id_tipo_presentacion FOREIGN KEY (id_tipo_presentacion) REFERENCES tipo_presentaciones (id_tipo_presentacion);

ALTER TABLE
    tb_productos
ADD
    CONSTRAINT fk_id_marca FOREIGN KEY (id_marca) REFERENCES tb_marcas (id_marca);

ALTER TABLE
    tb_compras
ADD
    CONSTRAINT fk_id_proveedor FOREIGN KEY (id_proveedor) REFERENCES tb_proveedores (id_proveedor);

ALTER TABLE
    tb_productos
ADD
    CONSTRAINT fk_id_usuario FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario);

ALTER TABLE
    tb_ventas
ADD
    CONSTRAINT fk_id_cliente FOREIGN KEY (id_cliente) REFERENCES tb_clientes (id_cliente);

ALTER TABLE
    tb_usuarios
ADD
    CONSTRAINT fk_id_tipo FOREIGN KEY (id_tipo) REFERENCES tb_tipousuarios (id_tipo);

ALTER TABLE
    tb_detalle_compras
ADD
    CONSTRAINT fk_id_producto FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);

ALTER TABLE
    tb_detalle_compras
ADD
    CONSTRAINT fk_id_compra FOREIGN KEY (id_compra) REFERENCES tb_compras (id_compra);

ALTER TABLE
    tb_detalle_ventas
ADD
    CONSTRAINT fk_id_venta FOREIGN KEY (id_venta) REFERENCES tb_ventas (id_venta);

ALTER TABLE
    tb_detalle_ventas
ADD
    CONSTRAINT fk_id_producto_detalle_ventas FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);
 
INSERT INTO tb_tipousuarios (tipo_usuario)
VALUES ('Administrador');

-- procedimiento para cambiar estado al usuario

DELIMITER $$

CREATE PROCEDURE cambiar_estado_usuario(IN usuario_id INT)
BEGIN
    DECLARE usuario_estado BOOLEAN;

    -- Obtener el estado actual del usuario
    SELECT estado_usuario INTO usuario_estado
    FROM tb_usuarios
    WHERE id_usuario = usuario_id;

    -- Actualizar el estado del usuario
    IF usuario_estado = 1 THEN
        UPDATE tb_usuarios
        SET estado_usuario = 0
        WHERE id_usuario = usuario_id;
    ELSE
        UPDATE tb_usuarios
        SET estado_usuario = 1
        WHERE id_usuario = usuario_id;
    END IF;
END $$

DELIMITER $$

-- procedimiento para sumar a las existencias

DELIMITER $$
CREATE PROCEDURE insertar_orden_validado(
    IN p_id_compra INT,
    IN p_cantidad_comprada INT,
    IN p_id_producto INT, 
    IN p_precio DECIMAL(10, 2)
)
BEGIN
    DECLARE mensaje VARCHAR(255);

    -- Insertar el detalle de la compra
    INSERT INTO tb_detalle_compras (id_compra, cantidad_compra, precio_compra, id_producto)
    VALUES (p_id_compra, p_cantidad_comprada, p_precio , p_id_producto); -- precio_compra se puede ajustar según sea necesario
    SET mensaje = 'Producto agregado a la compra correctamente.';

    -- Ajustar las existencias en la tabla tb_productos
    UPDATE tb_productos
    SET existencias_producto = existencias_producto + p_cantidad_comprada
    WHERE id_producto = p_id_producto;

    SELECT mensaje;
END $$
DELIMITER ;

-- CALL insertar_orden_validado(18, 30, 1);

-- actualizar cantidad

DELIMITER $$

DROP PROCEDURE IF EXISTS actualizar_detalle_compra;
CREATE PROCEDURE actualizar_detalle_compra(
    IN p_id_compra INT,
    IN p_id_detalle_compra INT,
    IN p_nueva_cantidad INT,
    IN p_id_producto INT,
    IN p_precio DECIMAL(10, 2)
)
BEGIN
    DECLARE p_cantidad_previa INT;
    DECLARE diferencia INT;
    DECLARE mensaje VARCHAR(255);

    -- Obtener la cantidad previa del detalle de compra
    SELECT cantidad_compra INTO p_cantidad_previa
    FROM tb_detalle_compras
    WHERE id_detalle_compra = p_id_detalle_compra
    AND id_compra = p_id_compra
    LIMIT 1;

    -- Calcular la diferencia
    SET diferencia = p_nueva_cantidad - p_cantidad_previa;

    -- Actualizar la cantidad comprada en tb_detalle_compras
    UPDATE tb_detalle_compras
    SET cantidad_compra = p_nueva_cantidad, precio_compra = p_precio
    WHERE id_detalle_compra = p_id_detalle_compra
    AND id_compra = p_id_compra;

    -- Ajustar las existencias en la tabla tb_productos
    UPDATE tb_productos
    SET existencias_producto = existencias_producto + diferencia
    WHERE id_producto = p_id_producto;

    SET mensaje = 'Detalle de compra actualizado correctamente.';
    SELECT mensaje;
END $$

DELIMITER ;

/*CALL actualizar_detalle_compra(3, 10, 20, 5, 15.50);*/

-- procedimiento para eliminar detalle compra

DELIMITER $$

DROP PROCEDURE IF EXISTS eliminar_detalle_compra;
CREATE PROCEDURE eliminar_detalle_compra(
    IN p_id_detalle_compra INT,
    IN p_id_compra INT
)
BEGIN
    DECLARE p_cantidad_previa INT;
    DECLARE p_id_producto INT;

    -- Obtener la cantidad previa y el id_producto del detalle de compra a eliminar
    SELECT dc.cantidad_compra, dc.id_producto INTO p_cantidad_previa, p_id_producto
    FROM tb_detalle_compras dc
    WHERE dc.id_detalle_compra = p_id_detalle_compra
      AND dc.id_compra = p_id_compra
    LIMIT 1;

    -- Ajustar las existencias en la tabla tb_productos
    UPDATE tb_productos
    SET existencias_producto = existencias_producto - p_cantidad_previa
    WHERE id_producto = p_id_producto;

    -- Eliminar el detalle de la compra
    DELETE FROM tb_detalle_compras
    WHERE id_detalle_compra = p_id_detalle_compra
      AND id_compra = p_id_compra;

    -- Mensaje de confirmación
    SELECT CONCAT('El detalle de la compra con ID ', p_id_detalle_compra, ' ha sido eliminado y ', p_cantidad_previa, ' unidades han sido devueltas al inventario.') AS mensaje;
END $$

DELIMITER ;

/*CALL eliminar_detalle_compra(10, 5);*/

-- procedimiento para agregar productos a venta

DELIMITER $$

CREATE PROCEDURE insertar_detalle_venta(
    IN p_id_venta INT,
    IN p_cantidad_venta INT,
    IN p_id_producto INT,
    IN p_precio DECIMAL(10, 2)
)
BEGIN
    DECLARE mensaje VARCHAR(255);
    DECLARE existencias_actuales INT;

    -- Obtener las existencias actuales del producto
    SELECT existencias_producto INTO existencias_actuales
    FROM tb_productos
    WHERE id_producto = p_id_producto
    LIMIT 1;

    -- Verificar si hay suficientes existencias para la venta
    IF p_cantidad_venta <= existencias_actuales THEN
        -- Insertar el detalle de la venta
        INSERT INTO tb_detalle_ventas (id_venta, cantidad_venta, precio_venta, id_producto)
        VALUES (p_id_venta, p_cantidad_venta, p_precio, p_id_producto);
        SET mensaje = 'Producto agregado a la venta correctamente.';

        -- Ajustar las existencias en la tabla tb_productos
        UPDATE tb_productos
        SET existencias_producto = existencias_producto - p_cantidad_venta
        WHERE id_producto = p_id_producto;
    ELSE
        SET mensaje = 'No hay suficientes existencias para completar la venta.';
    END IF;

    SELECT mensaje;
END $$

DELIMITER ;


-- CALL insertar_detalle_venta(1, 5, 3, 100.00);

-- procedimiento para actualizar un detalle venta

DELIMITER $$

CREATE PROCEDURE actualizar_detalle_venta(
    IN p_id_venta INT,
    IN p_id_detalle_venta INT,
    IN p_nueva_cantidad INT,
    IN p_id_producto INT,
    IN p_precio DECIMAL(10, 2)
)
BEGIN
    DECLARE p_cantidad_previa INT;
    DECLARE diferencia INT;
    DECLARE mensaje VARCHAR(255);
    DECLARE existencias_actuales INT;

    -- Obtener la cantidad previa del detalle de venta
    SELECT cantidad_venta INTO p_cantidad_previa
    FROM tb_detalle_ventas
    WHERE id_detalle_venta = p_id_detalle_venta
    AND id_venta = p_id_venta
    LIMIT 1;

    -- Calcular la diferencia
    SET diferencia = p_nueva_cantidad - p_cantidad_previa;

    -- Obtener las existencias actuales del producto
    SELECT existencias_producto INTO existencias_actuales
    FROM tb_productos
    WHERE id_producto = p_id_producto
    LIMIT 1;

    -- Verificar si hay suficientes existencias para la nueva cantidad
    IF diferencia <= existencias_actuales THEN
        -- Actualizar la cantidad vendida en tb_detalle_ventas
        UPDATE tb_detalle_ventas
        SET cantidad_venta = p_nueva_cantidad, precio_venta = p_precio
        WHERE id_detalle_venta = p_id_detalle_venta
        AND id_venta = p_id_venta;

        -- Ajustar las existencias en la tabla tb_productos
        UPDATE tb_productos
        SET existencias_producto = existencias_producto - diferencia
        WHERE id_producto = p_id_producto;

        SET mensaje = 'Detalle de venta actualizado correctamente.';
    ELSE
        SET mensaje = 'No hay suficientes existencias para la nueva cantidad.';
    END IF;

    SELECT mensaje;
END $$

DELIMITER ;

-- CALL actualizar_detalle_venta(1, 2, 10, 3, 150.00);

-- procedimiento para eliminar un detalle venta

DELIMITER $$
CREATE PROCEDURE eliminar_detalle_venta(
    IN p_id_detalle_venta INT,
    IN p_id_venta INT
)
BEGIN
    DECLARE p_cantidad_previa INT;
    DECLARE p_id_producto INT;

    -- Obtener la cantidad previa y el id_producto del detalle de venta a eliminar
    SELECT dv.cantidad_venta, dv.id_producto INTO p_cantidad_previa, p_id_producto
    FROM tb_detalle_ventas dv
    WHERE dv.id_detalle_venta = p_id_detalle_venta
      AND dv.id_venta = p_id_venta
    LIMIT 1;

    -- Ajustar las existencias en la tabla tb_productos
    UPDATE tb_productos
    SET existencias_producto = existencias_producto + p_cantidad_previa
    WHERE id_producto = p_id_producto;

    -- Eliminar el detalle de la venta
    DELETE FROM tb_detalle_ventas
    WHERE id_detalle_venta = p_id_detalle_venta
      AND id_venta = p_id_venta;

    -- Mensaje de confirmación
    SELECT CONCAT('El detalle de la venta con ID ', p_id_detalle_venta, ' ha sido eliminado y ', p_cantidad_previa, ' unidades han sido devueltas al inventario.') AS mensaje;
END $$

DELIMITER ;

-- CALL eliminar_detalle_venta(1, 2);

/*
SELECT 
    id_compra AS ID, 
    fecha_compra AS FECHA, 
    numero_correlativo AS "NUMERO CORRELATIVO", 
    estado_compra AS ESTADO, 
    id_proveedor AS PROVEEDOR, 
    CASE 
        WHEN estado_compra = 1 THEN 'Cancelada'
        WHEN estado_compra = 0 THEN 'No cancelada'
        ELSE 'Otro estado'
    END AS ESTADO_DESC
FROM tb_compras
ORDER BY FECHA;

SELECT id_venta AS ID, fecha_venta AS FECHA, observacion_venta AS OBSERVACION, estado_venta AS ESTADO, id_cliente AS CLIENTE FROM tb_ventas
        ORDER BY FECHA

*/

SELECT * FROM tb_detalle_ventas;
SELECT * FROM tb_ventas;
