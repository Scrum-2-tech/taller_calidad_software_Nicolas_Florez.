<?php
session_start();
// 1. Si el admin ya está logueado, redirigir al dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Incluir el archivo de conexión a la BD
require_once 'db_connection.php'; //NOSONAR

$error = ''; // Variable para almacenar errores de login

// 2. Procesar datos del formulario al hacer POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Contraseña sin escapar aún (se verifica hasheada)

    // Consultar el usuario en la tabla
    $sql = "SELECT id, username, password FROM admin_users WHERE username = ?";
    
    // Usamos sentencias preparadas para prevenir inyección SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Usuario encontrado, verificar la contraseña
        $user = $result->fetch_assoc();
        
        // password_verify() compara la contraseña en texto plano con el hash de la BD
        if (password_verify($password, $user['password'])) {
            // ¡Login Exitoso! Crear variables de sesión
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            // Redirigir al dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta.";
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado.";
    }

    $stmt->close();
}

// La conexión se puede cerrar aquí si el script termina, pero la dejaremos abierta para el dashboard.
// $conn->close(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Golazo Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>
<body class="bg-dark">

    <div class="login-container">
        <div class="card card-custom login-card shadow-lg">
            <div class="card-body">
                <h3 class="card-title text-center text-warning mb-4">
                    <i class="bi bi-person-circle me-2"></i> Acceso Admin
                </h3>
                
                <?php 
                // Mostrar el mensaje de error si existe
                if (!empty($error)) {
                    echo '<div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i>' . $error . '</div>';
                }
                ?>

                <form action="login.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary text-white border-0"><i class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary text-white border-0"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-purple w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar al Dashboard
                    </button>
                    
                </form>

                <hr class="text-muted mt-4">

                <div class="text-center mt-3">
                    <a href="index.php" class="text-secondary small">
                        <i class="bi bi-arrow-left me-1"></i> Volver a la Tienda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
