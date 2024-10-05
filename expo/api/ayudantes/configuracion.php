<?php
// Encabezado para permitir solicitudes de cualquier origen.
header('Access-Control-Allow-Origin: *');
// Se establece la zona horaria local para la fecha y hora del servidor.
date_default_timezone_set('America/El_Salvador');
// Constantes para establecer las credenciales de conexión con el servidor de bases de datos.
define('SERVER', 'localhost');
define('DATABASE', 'quickstock');
define('USERNAME', 'distribuidora_tmg');  //distribuidora_tmg root
define('PASSWORD', 'expo_2024_tmg'); //expo_2024_tmg 12345678
?>
