<?php
session_start();
// Incluir la conexión y la lógica de seguridad
require_once('db_connection.php');

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// URL de redirección: siempre al dashboard y a la pestaña de productos
$redirect_url = "dashboard.php#v-pills-products"; 

// ==========================================================
// 1. LÓGICA DE CREAR Y ACTUALIZAR (POST)
// ==========================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_product'])) {
    
    // 1. Sanitizar y obtener datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $id_tipo_producto = (int)$_POST['id_tipo_producto']; 

    // Si id_producto está presente, es una actualización; si no, es una creación.
    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    
    if ($id_producto > 0) {
        // UPDATE
        $sql = "UPDATE producto SET 
                    nombre = ?, 
                    descripcion = ?, 
                    precio = ?, 
                    stock = ?, 
                    id_tipo_producto = ? 
                WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $id_tipo_producto, $id_producto);
        $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $id_tipo_producto, $id_producto);
        
        $operation = "actualizado";
    } else {
        // CREATE
        $sql = "INSERT INTO producto (nombre, descripcion, precio, stock, id_tipo_producto) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id_tipo_producto); 
        $operation = "creado";
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "El producto **'$nombre'** se ha $operation correctamente."
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => "Error al intentar guardar el producto: " . $stmt->error
        ];
    }
    $stmt->close();
} 

// ==========================================================
// 2. LÓGICA DE ELIMINAR (GET)
// ==========================================================
elseif (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    
    $id_producto = (int)$_GET['id'];
    $sql = "DELETE FROM producto WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
             $_SESSION['message'] = [
                'type' => 'success',
                'text' => "Producto eliminado correctamente."
            ];
        } else {
             $_SESSION['message'] = [
                'type' => 'warning',
                'text' => "No se encontró el Producto para eliminar."
            ];
        }
    } else {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => "Error al intentar eliminar el producto: " . $stmt->error
        ];
    }
    $stmt->close();
}

$conn->close();
header("Location: " . $redirect_url);
exit;
?>
