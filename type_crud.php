<?php
session_start();
// Incluir la conexión y la lógica de seguridad
require_once 'db_connection.php'; //NOSONAR

// Redirigir si el usuario no está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// URL de redirección: siempre al dashboard y a la pestaña de categorías
$redirect_url = "dashboard.php#v-pills-categories"; 

// ==========================================================
// 1. LÓGICA DE CREAR Y ACTUALIZAR (POST)
// ==========================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_type'])) {
    
    $nombre_tipo = $conn->real_escape_string($_POST['nombre_tipo']);
    // Si id_tipo está presente, es una actualización; si no, es una creación.
    $id_tipo = isset($_POST['id_tipo']) ? (int)$_POST['id_tipo'] : 0;
    
    if ($id_tipo > 0) {
        // UPDATE
        $sql = "UPDATE tipo_producto SET nombre_tipo = ? WHERE id_tipo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nombre_tipo, $id_tipo);
        $operation = "actualizado";
    } else {
        // CREATE
        $sql = "INSERT INTO tipo_producto (nombre_tipo) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre_tipo);
        $operation = "creado";
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "Tipo de producto **'$nombre_tipo'** se ha $operation correctamente."
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => "Error al intentar guardar: " . $stmt->error
        ];
    }
    $stmt->close();
} 

// ==========================================================
// 2. LÓGICA DE ELIMINAR (GET)
// ==========================================================
elseif (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    
    $id_tipo = (int)$_GET['id'];
    $sql = "DELETE FROM tipo_producto WHERE id_tipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tipo);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
             $_SESSION['message'] = [
                'type' => 'success',
                'text' => "Tipo de producto eliminado correctamente."
            ];
        } else {
             $_SESSION['message'] = [
                'type' => 'warning',
                'text' => "No se encontró el Tipo de Producto para eliminar."
            ];
        }
    } else {
        // Error 1451 es Foreign Key Constraint (MySQL)
         if ($conn->errno == 1451) {
             $_SESSION['message'] = [
                'type' => 'danger',
                'text' => "Error: No se puede eliminar este Tipo de Producto porque tiene **productos asociados**. Elimina los productos primero."
            ];
         } else {
             $_SESSION['message'] = [
                'type' => 'danger',
                'text' => "Error al intentar eliminar: " . $stmt->error
            ];
         }
    }
    $stmt->close();
}

$conn->close();
header("Location: " . $redirect_url);
exit;
?>
