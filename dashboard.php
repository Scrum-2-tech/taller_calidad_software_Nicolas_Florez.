<?php
session_start();

// LÓGICA DE AUTENTICACIÓN
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Incluir la conexión a la base de datos
include('db_connection.php');

// Obtener el nombre de usuario de la sesión para el saludo
$admin_username = $_SESSION['admin_username'] ?? 'Administrador'; 

// Revisar y mostrar mensajes de estado (CRUD)
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Limpiar el mensaje después de mostrarlo
}

// =================================================================
// 1. LÓGICA DE CATEGORÍAS (READ y PRE-EDICIÓN)
// =================================================================
$tipos_producto = [];
$edit_tipo = null; // Variable para almacenar datos si se solicita una edición de tipo

// A. Obtener todos los tipos para la tabla y el <select> de productos
$result_tipos = $conn->query("SELECT id_tipo, nombre_tipo FROM tipo_producto ORDER BY nombre_tipo ASC");
if ($result_tipos) {
    while ($row = $result_tipos->fetch_assoc()) {
        $tipos_producto[] = $row;
    }
}

// B. Determinar la pestaña activa por defecto
$active_tab_id = 'v-pills-products'; 

// C. Verificar si se solicita una edición de TIPO (para pre-llenar el formulario)
if (isset($_GET['action']) && $_GET['action'] == 'edit_type' && isset($_GET['id'])) {
    $id_edit = (int)$_GET['id'];
    $sql_edit = "SELECT id_tipo, nombre_tipo FROM tipo_producto WHERE id_tipo = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $id_edit);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    
    if ($result_edit->num_rows == 1) {
        $edit_tipo = $result_edit->fetch_assoc();
    }
    $stmt_edit->close();
    $active_tab_id = 'v-pills-categories'; // Activar pestaña de categorías
}


// =================================================================
// 2. LÓGICA DE PRODUCTOS (READ y PRE-EDICIÓN)
// =================================================================
$productos = [];
$edit_producto = null; // Variable para almacenar datos si se solicita una edición de producto

// A. Obtener todos los productos para la tabla
$sql_productos = "SELECT p.id_producto, p.nombre, p.precio, p.stock, p.descripcion, p.id_tipo_producto, tp.nombre_tipo 
                  FROM producto p 
                  LEFT JOIN tipo_producto tp ON p.id_tipo_producto = tp.id_tipo 
                  ORDER BY p.id_producto DESC";
$result_productos = $conn->query($sql_productos); 
if ($result_productos) {
    while ($row = $result_productos->fetch_assoc()) {
        $productos[] = $row;
    }
}

// B. Verificar si se solicita una edición de PRODUCTO (para pre-llenar el formulario)
if (isset($_GET['action']) && $_GET['action'] == 'edit_product' && isset($_GET['id'])) {
    $id_edit = (int)$_GET['id'];
    // Usamos el array ya cargado para no hacer otra consulta, pero si quieres la descripción, 
    // es mejor hacer una consulta específica o ya cargarla en el array de productos (como está arriba).
    $edit_producto = array_values(array_filter($productos, fn($p) => $p['id_producto'] == $id_edit))[0] ?? null;
    
    // Si se encontró el producto a editar, aseguramos que la pestaña de productos esté activa
    if ($edit_producto) {
         $active_tab_id = 'v-pills-products';
    }
}


// Si se usó un hash para mantener la pestaña activa
if (isset($_GET['active_tab']) && $_GET['active_tab'] == 'categories') {
    $active_tab_id = 'v-pills-categories';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Golazo Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="dashboard.css">
</head>
<body class="bg-dark">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-dashboard">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shield-lock-fill me-2 text-warning"></i> 
                DASHBOARD ADMIN
            </a>
            
            <div class="d-flex ms-auto">
                <span class="navbar-text me-3 text-white-50">
                    Bienvenido, **<?php echo htmlspecialchars($admin_username); ?>**
                </span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        </div>
    </nav>


    <div class="dashboard-container">
        
        <div class="sidebar">
            <div class="sidebar-header">
                <h5 class="text-warning mb-4">Módulos de Gestión</h5>
            </div>
            
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                
                <a class="nav-link <?php echo ($active_tab_id == 'v-pills-products' ? 'active' : ''); ?>" id="v-pills-products-tab" data-bs-toggle="pill" data-bs-target="#v-pills-products" type="button" role="tab" aria-controls="v-pills-products" aria-selected="<?php echo ($active_tab_id == 'v-pills-products' ? 'true' : 'false'); ?>">
                    <i class="bi bi-box-seam-fill me-2"></i> Gestión de Productos
                </a>
                
                <a class="nav-link <?php echo ($active_tab_id == 'v-pills-categories' ? 'active' : ''); ?>" id="v-pills-categories-tab" data-bs-toggle="pill" data-bs-target="#v-pills-categories" type="button" role="tab" aria-controls="v-pills-categories" aria-selected="<?php echo ($active_tab_id == 'v-pills-categories' ? 'true' : 'false'); ?>">
                    <i class="bi bi-tags-fill me-2"></i> Gestión de Tipos
                </a>
                
                <hr class="text-white-50 my-3">
                
                <a class="nav-link" href="index.php">
                    <i class="bi bi-shop me-2"></i> Volver a la Tienda
                </a>
            </div>
        </div>
        

        <div class="main-content">
            <h1 class="mb-4 text-warning">Dashboard Administrativo</h1>
            
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $message['text']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <p class="lead text-white-50">
                Utiliza las opciones de la barra lateral para administrar el inventario y las categorías de la tienda.
            </p>

            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade <?php echo ($active_tab_id == 'v-pills-products' ? 'show active' : ''); ?>" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab" tabindex="0">
                    <div class="card gestion-card p-4">
                        <h3 class="text-white mb-4"><i class="bi bi-box-seam-fill me-2"></i> CRUD de Productos</h3>
                        
                        <div id="product-form-section" class="mb-5">
                            <h4 class="text-warning mb-3"><?php echo ($edit_producto ? 'Editar Producto: ' . htmlspecialchars($edit_producto['nombre']) : 'Crear Nuevo Producto'); ?></h4>
                            
                            <form action="product_crud.php" method="POST">
                                <?php if ($edit_producto): ?>
                                <input type="hidden" name="id_producto" value="<?php echo $edit_producto['id_producto']; ?>">
                                <?php endif; ?>
                                <input type="hidden" name="save_product" value="1">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre del Producto</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nombre" 
                                               name="nombre" 
                                               value="<?php echo $edit_producto ? htmlspecialchars($edit_producto['nombre']) : ''; ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tipo" class="form-label">Tipo de Producto</label>
                                        <select class="form-select" id="tipo" name="id_tipo_producto" required>
                                            <option value="">-- Seleccione Tipo --</option>
                                            <?php foreach ($tipos_producto as $tipo): 
                                                // Pre-seleccionar la categoría si estamos editando
                                                $selected = ($edit_producto && $edit_producto['id_tipo_producto'] == $tipo['id_tipo']) ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $tipo['id_tipo']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($tipo['nombre_tipo']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="precio" class="form-label">Precio ($)</label>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control" 
                                               id="precio" 
                                               name="precio" 
                                               value="<?php echo $edit_producto ? number_format($edit_producto['precio'], 2, '.', '') : ''; ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="stock" class="form-label">Stock</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="stock" 
                                               name="stock" 
                                               value="<?php echo $edit_producto ? (int)$edit_producto['stock'] : ''; ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" 
                                                  id="descripcion" 
                                                  name="descripcion" 
                                                  rows="3"><?php echo $edit_producto ? htmlspecialchars($edit_producto['descripcion']) : ''; ?></textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-purple"><i class="bi bi-save me-2"></i> <?php echo ($edit_producto ? 'Actualizar Producto' : 'Guardar Producto'); ?></button>
                                        
                                        <?php if ($edit_producto): ?>
                                            <a href="dashboard.php#v-pills-products" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i> Cancelar Edición</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <hr class="text-muted">

                        <h4 class="text-warning mb-3 mt-5">Lista de Productos</h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($productos)): ?>
                                    <?php foreach ($productos as $producto): ?>
                                    <tr>
                                        <td><?php echo $producto['id_producto']; ?></td>
                                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nombre_tipo'] ?? 'Sin Tipo'); ?></td>
                                        <td>$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                                        <td><?php echo $producto['stock']; ?></td>
                                        <td>
                                            <a href="dashboard.php?action=edit_product&id=<?php echo $producto['id_producto']; ?>#v-pills-products" class="btn btn-sm btn-outline-warning me-2"><i class="bi bi-pencil"></i></a>
                                            <a href="product_crud.php?action=delete&id=<?php echo $producto['id_producto']; ?>" 
                                               onclick="return confirm('¿Estás seguro de que quieres eliminar el producto «<?php echo htmlspecialchars($producto['nombre']); ?>»?');"
                                               class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Aún no hay productos en la base de datos.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
                
                <div class="tab-pane fade <?php echo ($active_tab_id == 'v-pills-categories' ? 'show active' : ''); ?>" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab" tabindex="0">
                    <div class="card gestion-card p-4">
                        <h3 class="text-white mb-4"><i class="bi bi-tags-fill me-2"></i> CRUD de Tipos de Producto</h3>
                        
                        <div id="type-form-section" class="mb-5">
                            <h4 class="text-warning mb-3"><?php echo ($edit_tipo ? 'Editar Tipo: ' . htmlspecialchars($edit_tipo['nombre_tipo']) : 'Crear Nuevo Tipo'); ?></h4>
                            
                            <form action="type_crud.php" method="POST">
                                <?php if ($edit_tipo): ?>
                                <input type="hidden" name="id_tipo" value="<?php echo $edit_tipo['id_tipo']; ?>">
                                <?php endif; ?>
                                <input type="hidden" name="save_type" value="1">
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nombre_tipo" class="form-label">Nombre del Tipo/Categoría</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nombre_tipo" 
                                               name="nombre_tipo" 
                                               value="<?php echo $edit_tipo ? htmlspecialchars($edit_tipo['nombre_tipo']) : ''; ?>"
                                               required>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-purple"><i class="bi bi-save me-2"></i> <?php echo ($edit_tipo ? 'Actualizar' : 'Guardar'); ?> Tipo</button>
                                        
                                        <?php if ($edit_tipo): ?>
                                            <a href="dashboard.php#v-pills-categories" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i> Cancelar Edición</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <hr class="text-muted">
                        
                        <h4 class="text-warning mb-3 mt-5">Lista de Tipos de Producto</h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Tipo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($tipos_producto)): ?>
                                    <?php foreach ($tipos_producto as $tipo): ?>
                                    <tr>
                                        <td><?php echo $tipo['id_tipo']; ?></td>
                                        <td><?php echo htmlspecialchars($tipo['nombre_tipo']); ?></td>
                                        <td>
                                            <a href="dashboard.php?action=edit_type&id=<?php echo $tipo['id_tipo']; ?>#v-pills-categories" class="btn btn-sm btn-outline-warning me-2"><i class="bi bi-pencil"></i></a>
                                            <a href="type_crud.php?action=delete&id=<?php echo $tipo['id_tipo']; ?>" 
                                               onclick="return confirm('¿Estás seguro de que quieres eliminar el tipo «<?php echo htmlspecialchars($tipo['nombre_tipo']); ?>»?');"
                                               class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Aún no hay tipos de producto creados.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var activeTabId = '<?php echo $active_tab_id; ?>';
            var activeTab = document.getElementById(activeTabId + '-tab');
            if (activeTab) {
                new bootstrap.Tab(activeTab).show();
            }
            // Mover el foco al formulario después de una acción si hay un #hash en la URL
            if (window.location.hash) {
                var hashTarget = document.querySelector(window.location.hash + ' .card');
                if (hashTarget) {
                    hashTarget.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
</body>
</html>
<?php 
// Cerramos la conexión a la BD al final del script.
$conn->close();
?>