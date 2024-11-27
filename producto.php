<?php
// Incluir el archivo de conexión a la base de datos
include('php/conexionBD.php');

// Verificar si el parámetro id_producto está presente en la URL
if (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];

    // Consulta SQL para obtener los detalles del producto
    $query = "SELECT p.id_producto, p.nombre, p.descripcion, p.foto, p.precio, p.cantidad, f.nombre_fabricante, o.pais_origen 
              FROM productos p
              JOIN fabricantes f ON p.id_fabricante = f.id_fabricante
              JOIN origen o ON p.id_origen = o.id_origen
              WHERE p.id_producto = $id_producto";
    
    // Ejecutar la consulta
    $result = mysqli_query($con, $query);

    // Verificar si la consulta devolvió resultados
    if ($result && mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
    } else {
        // Si no se encuentra el producto, mostrar mensaje de error
        echo "<p>Producto no encontrado.</p>";
        exit;
    }
} else {
    // Si no se pasa el id_producto en la URL, mostrar mensaje de error
    echo "<p>ID de producto no especificado.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <!-- Cargar Bootstrap 5 desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        body{
            background-color: #ffedee;
            height: 100vh; 
        }
        header{
            background-color: #FFFFFF;
        }
    
        .product-image img {
            width: 300px;  /* Ajusta el ancho a 300px */
            height: auto;  /* La altura se ajusta proporcionalmente */
            object-fit: contain;  /* Asegura que la imagen no se deforme */
        }

        .product-detail {
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #ffccd5; 
            color: #333; 
        }

        .btn-custom:hover {
            background-color: #ffedee; 
            
            color: #000; 
        }

    </style>
</head>
<body>
<header>
      <div class="container-fluid">
        <div class="row py-3 border-bottom">
          
          <div class="col-sm-4 col-lg-2 text-center text-sm-start d-flex gap-3 justify-content-center justify-content-md-start">
            <div class="d-flex align-items-center my-3 my-sm-0">
              <a href="index.html">
                <img src="images/logo.jpg" alt="logo" width="70">
              </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
              aria-controls="offcanvasNavbar">
              <svg width="24" height="24" viewBox="0 0 24 24"><use xlink:href="#menu"></use></svg>
            </button>
          </div>
          
          <div class="col-lg-6">
            <ul class="navbar-nav list-unstyled d-flex flex-row gap-3 gap-lg-5 justify-content-center flex-wrap align-items-center mb-0 fw-bold text-uppercase text-dark">
              <li class="nav-item active">
                <a href="index.html" class="nav-link">Inicio</a>
              </li>
              <li class="nav-item active">
                <a href="tienda.php" class="nav-link">Tienda</a>
              </li>
              <li class="nav-item active">
                <a href="about.html" class="nav-link">Acerca de Nosotros</a>
              </li>
            </ul>
          </div>
          
          
          
        </div>
      </div>
    </header>
    <div class="container product-detail">
        <h1 class="text-center mb-4">Detalles del Producto</h1>

        <div class="row">
            <div class="col-md-6">
                <!-- Mostrar imagen del producto -->
                <div class="product-image">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['foto']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="img-fluid">
                </div>
            </div>

            <div class="col-md-6">
                <!-- Detalles del producto en tarjeta -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                        <p class="card-text"><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                        <p class="card-text"><strong>Precio:</strong> $<?php echo htmlspecialchars($producto['precio']); ?></p>
                        <p class="card-text"><strong>Cantidad disponible:</strong> <?php echo htmlspecialchars($producto['cantidad']); ?></p>
                        <p class="card-text"><strong>Fabricante:</strong> <?php echo htmlspecialchars($producto['nombre_fabricante']); ?></p>
                        <p class="card-text"><strong>Origen:</strong> <?php echo htmlspecialchars($producto['pais_origen']); ?></p>

                        <!-- Botón para agregar al carrito -->
                        <form method="POST" action="carrito.php">
                            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                            <input type="hidden" name="cantidad" value="1">
                            <button type="submit" class="btn btn-custom mt-3">Añadir al carrito</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cargar Bootstrap 5 y Popper.js desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
