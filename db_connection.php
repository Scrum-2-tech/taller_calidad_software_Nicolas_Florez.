<?php
// Configuración de la Base de Datos
define('DB_SERVER', 'localhost'); 
define('DB_USERNAME', 'root');   
define('DB_PASSWORD', '');        
define('DB_NAME', 'tienda_futbol'); 


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("ERROR al conectar con la base de datos: " . $conn->connect_error);
}

// 3. Establecer el juego de caracteres a utf8 (importante para tildes y ñ)
$conn->set_charset("utf8");

// Nota: Esta variable $conn es la que usaremos para todas las consultas.

// Opcional: Crear una función para cerrar la conexión al final de un script.
// function close_db_connection($conn) {
//     $conn->close();
// }

?>