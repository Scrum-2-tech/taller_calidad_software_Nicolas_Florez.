<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golazo Shop | Tu Tienda de Fútbol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="bi bi-trophy-fill me-2 fs-4 text-warning"></i>
                <span class="fw-bold fs-3">Golazo Shop</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-house-door-fill me-1"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-shirt-fill me-1"></i> Camisetas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-balloon-fill me-1"></i> Balones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-bag-fill me-1"></i> Mi Carrito (0)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <header class="bg-dark text-white text-center py-5 mb-4 shadow-lg" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('placeholder-imagen-fondo.jpg'); background-size: cover; background-position: center; min-height: 400px;">
        <div class="container d-flex flex-column justify-content-center align-items-center h-100">
            <i class="bi bi-lightning-fill text-warning fs-1 mb-3"></i>
            <h1 class="display-4 fw-bold">El GOLAZO te espera</h1>
            <p class="lead">Las mejores camisetas, balones y accesorios de fútbol, con envío rápido.</p>
            <a href="#" class="btn btn-purple btn-lg mt-3"><i class="bi bi-shop-window me-2"></i> Ver Ofertas Ahora</a>
        </div>
        
    
    </header>

    <main class="container">
        <h2 class="text-center mb-5 text-uppercase fw-light border-bottom pb-2 border-primary">🔥 Productos Destacados</h2>
        
        <div class="row row-cols-1 row-cols-md-4 g-4">
            
            <div class="col">
                <div class="card card-custom h-100 shadow">
                    <img src="home_madrid.jpg" class="card-img-top" alt="Camiseta de Equipo">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning">Camiseta Home Madrid</h5>
                        <p class="card-text">Versión jugador, la más reciente temporada.</p>
                        <h4 class="text-purple mt-auto mb-2 fw-bold">$120.000</h4>
                        <a href="#" class="btn btn-purple mt-2"><i class="bi bi-cart-plus me-1"></i> Agregar al Carrito</a>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card card-custom h-100 shadow">
                    <img src="balon_liga.jpg" class="card-img-top" alt="Balón Profesional">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning">Balón Oficial Liga</h5>
                        <p class="card-text">Máxima calidad para juego profesional.</p>
                        <h4 class="text-purple mt-auto mb-2 fw-bold">$85.000</h4>
                        <a href="#" class="btn btn-purple mt-2"><i class="bi bi-cart-plus me-1"></i> Agregar al Carrito</a>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card card-custom h-100 shadow">
                    <img src="guayos_x.jpg" class="card-img-top" alt="Guayos">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning">Guayos Velocidad X</h5>
                        <p class="card-text">Suela FG/AG, ligeros y potentes.</p>
                        <h4 class="text-purple mt-auto mb-2 fw-bold">$190.000</h4>
                        <a href="#" class="btn btn-purple mt-2"><i class="bi bi-cart-plus me-1"></i> Agregar al Carrito</a>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card card-custom h-100 shadow">
                    <img src="anti.jpg" class="card-img-top" alt="Medias">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning">Medias Antideslizantes</h5>
                        <p class="card-text">Mejora el agarre dentro del guayo.</p>
                        <h4 class="text-purple mt-auto mb-2 fw-bold">$25.000</h4>
                        <a href="#" class="btn btn-purple mt-2"><i class="bi bi-cart-plus me-1"></i> Agregar al Carrito</a>
                    </div>
                </div>
            </div>
            
        </div>
        
    </main>

    <footer class="text-center text-lg-start mt-5">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase text-warning">Golazo Shop</h5>
                    <p>
                        Somos tu tienda online #1 de artículos de fútbol. Calidad y pasión en cada producto.
                    </p>
                </div>
                
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0 text-md-end">
                    <a href="login.php" class="text-decoration-none admin-login-link text-secondary">
                        <i class="bi bi-gear-fill me-2"></i> Área de Administración
                    </a>
                    <p class="small mt-2 text-muted">
                        &copy; 2025 Golazo Shop. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>