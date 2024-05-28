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
  imagen VARCHAR(25)
);

CREATE TABLE tb_tipousuarios (
  id_tipo INT AUTO_INCREMENT PRIMARY KEY,
  tipo_usuario VARCHAR(25) NOT NULL
);

CREATE TABLE tb_usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre_usuario VARCHAR(50) NOT NULL,
  apellido_usuario VARCHAR(50) NOT NULL,
  usuario VARCHAR(25) NOT NULL,
  clave_usuario VARCHAR(25) NOT NULL,
  correo_usuario VARCHAR(100) NOT NULL,
  id_tipo INT NOT NULL
);

CREATE TABLE tb_productos (
  id_producto INT AUTO_INCREMENT PRIMARY KEY,
  nombre_producto VARCHAR(50) NOT NULL,
  fecha_vencimiento DATETIME NOT NULL,
  precio_compra DECIMAL(10,2) NOT NULL,
  CONSTRAINT chk_precio_compra CHECK (precio_compra >= 0),
  precio_venta DECIMAL(10,2) NOT NULL,
  CONSTRAINT chk_precio_venta CHECK (precio_venta >= 0),
  descripcion VARCHAR(100)NOT NULL,
  existencias_producto INT NOT NULL,
  CONSTRAINT chk_existencias_producto CHECK (existencias_producto >= 0),
  id_tipo_presentacion INT NOT NULL,
  id_categoria INT NOT NULL,
  id_marca INT NOT NULL,
  id_usuario INT NOT NULL
);

CREATE TABLE tb_compras (
  id_compra INT AUTO_INCREMENT PRIMARY KEY,
  fecha_compra DATETIME NOT NULL,
  numero_correlativo INT NOT NULL,
  CONSTRAINT chk_numero_correlativo CHECK (numero_correlativo >= 0),
  estado_compra ENUM('Cancelada','No cancelada') NOT NULL,
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
  fecha_venta DATETIME NOT NULL,
  observacion_venta VARCHAR(100),
  id_cliente INT
);

CREATE TABLE tb_detalle_ventas (
  id_detalle_venta INT AUTO_INCREMENT PRIMARY KEY,
  cantidad_venta INT,
  CONSTRAINT chk_cantidad_venta CHECK (cantidad_venta >= 0),
  precio_venta DECIMAL(10,2) NOT NULL,
  CONSTRAINT chk_precio_venta CHECK (precio_venta >= 0),
  id_producto INT,
  id_venta INT
);

CREATE TABLE tb_detalle_compras (
  id_detalle_compra INT AUTO_INCREMENT PRIMARY KEY,
  cantidad_compra INT,
  CONSTRAINT chk_cantidad_compra CHECK (cantidad_compra >= 0),
  precio_compra DECIMAL(10,2) NOT NULL,
  CONSTRAINT chk_precio_compra CHECK (precio_compra >= 0),
  id_producto INT,
  id_compra INT 
);

ALTER TABLE tb_proveedores
ADD UNIQUE (correo_proveedor);

ALTER TABLE tb_usuarios
ADD UNIQUE (correo_usuario);

ALTER TABLE tb_clientes
ADD UNIQUE (correo_cliente, dui_cliente);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_categoria FOREIGN KEY (id_categoria) REFERENCES tb_categorias (id_categoria);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_tipo_presentacion FOREIGN KEY (id_tipo_presentacion) REFERENCES tipo_presentaciones (id_tipo_presentacion);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_marca FOREIGN KEY (id_marca) REFERENCES tb_marcas (id_marca);

ALTER TABLE tb_compras ADD CONSTRAINT fk_id_proveedor FOREIGN KEY (id_proveedor) REFERENCES tb_proveedores (id_proveedor);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_usuario FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario);

ALTER TABLE tb_ventas ADD CONSTRAINT fk_id_cliente FOREIGN KEY (id_cliente) REFERENCES tb_clientes (id_cliente);

ALTER TABLE tb_usuarios ADD CONSTRAINT fk_id_tipo FOREIGN KEY (id_tipo) REFERENCES tb_tipousuarios (id_tipo);

ALTER TABLE tb_detalle_compras ADD CONSTRAINT fk_id_producto FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);

ALTER TABLE tb_detalle_compras ADD CONSTRAINT fk_id_compra FOREIGN KEY (id_compra) REFERENCES tb_compras (id_compra);

ALTER TABLE tb_detalle_ventas ADD CONSTRAINT fk_id_venta FOREIGN KEY (id_venta) REFERENCES tb_ventas (id_venta);

ALTER TABLE tb_detalle_ventas ADD CONSTRAINT fk_id_producto_detalle_ventas FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);



/*relaciones anteriores de eliminar tabla inventarios y agregar tabla detalle compra y venta y modifcar talba vendedores a proveedores
CREATE TABLE tb_inventarios (
  id_inventario INT AUTO_INCREMENT PRIMARY KEY,
  id_producto INT NOT NULL,
  existencias_producto INT NOT NULL,
  id_venta INT NOT NULL,
  id_compra INT NOT NULL
);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_categoria FOREIGN KEY (id_categoria) REFERENCES tb_categorias (id_categoria);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_tipo_presentacion FOREIGN KEY (id_tipo_presentacion) REFERENCES tipo_presentaciones (id_tipo_presentacion);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_marca FOREIGN KEY (id_marca) REFERENCES tb_marcas (id_marca);

ALTER TABLE tb_usuarios ADD CONSTRAINT fk_id_tipo FOREIGN KEY (id_tipo) REFERENCES tb_tipousuarios (id_tipo);

ALTER TABLE tb_compras ADD CONSTRAINT fk_id_vendedor FOREIGN KEY (id_vendedor) REFERENCES tb_vendedores (id_vendedor);

ALTER TABLE tb_productos ADD CONSTRAINT fk_id_usuario FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario);

ALTER TABLE tb_ventas ADD CONSTRAINT fk_id_cliente FOREIGN KEY (id_cliente) REFERENCES tb_clientes (id_cliente);

ALTER TABLE tb_compras ADD FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);

ALTER TABLE tb_ventas ADD FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);

ALTER TABLE tb_inventarios ADD CONSTRAINT fk_id_producto FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);

ALTER TABLE tb_inventarios ADD FOREIGN KEY (id_compra) REFERENCES tb_compras (id_compra);

ALTER TABLE tb_inventarios ADD FOREIGN KEY (id_venta) REFERENCES tb_ventas (id_venta); */