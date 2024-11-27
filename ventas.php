<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html"); 
    exit();
}
if ($_SESSION['rol'] != 1) {
    header("Location: tienda.php"); // Redirigir a tienda.php si no es un administrador
    exit();
}

// Incluir conexión a la base de datos
include("php/conexionBD.php");

// Consultar las ventas de la base de datos
$queryVentas = "
    SELECT 
        ventas.id_venta,
        usuarios.nombre AS nombre_usuario,
        productos.nombre AS nombre_producto,
        ventas.fecha,
        ventas.cantidad
    FROM ventas
    JOIN usuarios ON ventas.id_usuario = usuarios.id_usuario
    JOIN productos ON ventas.id_producto = productos.id_producto
    ORDER BY ventas.fecha DESC";

$resultVentas = mysqli_query($con, $queryVentas);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Productos</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <defs>
            <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="9" r="3"/><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M17.97 20c-.16-2.892-1.045-5-5.97-5s-5.81 2.108-5.97 5"/></g></symbol>
        </defs>
    </svg>


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
                <a href="administrador.php" class="nav-link">Insertar Productos</a>
              </li>
              <li class="nav-item active">
                <a href="modificar.php" class="nav-link">Editar Productos</a>
              </li>
              <li class="nav-item active">
                <a href="ventas.php" class="nav-link">Registro de Ventas</a>
              </li>
            </ul>
          </div>
          
          <div class="col-sm-4 col-lg-4 d-flex gap-5 align-items-center justify-content-center justify-content-sm-end">
            <ul class="d-flex justify-content-end list-unstyled m-0">
              <li class="dropdown">
                <a href="#" class="p-2 mx-1 dropdown-toggle" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <svg width="24" height="24"><use xlink:href="#user"></use></svg>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                  <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                </ul>
              </li>
            </ul>
          </div>
          
        </div>
      </div>
    </header>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Registro de Ventas</h1>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID Venta</th>
                            <th>Usuario</th>
                            <th>Producto</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($resultVentas) > 0): ?>
                            <?php while ($venta = mysqli_fetch_assoc($resultVentas)): ?>
                                <tr>
                                    <td class="text-center"><?= $venta['id_venta'] ?></td>
                                    <td><?= htmlspecialchars($venta['nombre_usuario']) ?></td>
                                    <td><?= htmlspecialchars($venta['nombre_producto']) ?></td>
                                    <td class="text-center"><?= $venta['fecha'] ?></td>
                                    <td class="text-center"><?= $venta['cantidad'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No se encontraron registros de ventas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        </body>

</html>

