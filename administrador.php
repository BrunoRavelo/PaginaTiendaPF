<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html"); // Redirigir al inicio de sesión si no es admin
    exit();
}
if ($_SESSION['rol'] != 1) {
    // Redirigir a tienda.php si no es un administrador
    header("Location: tienda.php");
    exit();
}

// Incluir conexión a la base de datos
include("php/conexionBD.php");

// Consultar datos para los menús desplegables
$queryFabricantes = "SELECT id_fabricante, nombre_fabricante FROM fabricantes";
$queryOrigenes = "SELECT id_origen, pais_origen FROM origen";
$queryCategorias = "SELECT id_categoria, categoria FROM categorias";

$resultFabricantes = mysqli_query($con, $queryFabricantes);
$resultOrigenes = mysqli_query($con, $queryOrigenes);
$resultCategorias = mysqli_query($con, $queryCategorias);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $id_fabricante = $_POST['fabricante'];
    $id_origen = $_POST['origen'];
    $id_categoria = $_POST['categoria'];

    // Manejar imagen
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = addslashes(file_get_contents($_FILES['foto']['tmp_name']));
    }

    // Insertar en la base de datos
    $queryInsert = "INSERT INTO productos (nombre, descripcion, foto, precio, cantidad, id_fabricante, id_origen, id_categoria)
                    VALUES ('$nombre', '$descripcion', '$foto', $precio, $cantidad, $id_fabricante, $id_origen, $id_categoria)";

    if (mysqli_query($con, $queryInsert)) {
        $mensaje = "Producto agregado exitosamente.";
    } else {
        $mensaje = "Error al agregar el producto: " . mysqli_error($con);
    }
}
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
              <a href="../index.html">
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
        <h1 class="text-center mb-4">Administrador de Productos</h1>

        <?php if (isset($mensaje)): ?>
        <div class="alert alert-info text-center">
            <?= htmlspecialchars($mensaje) ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <!-- Nombre del Producto -->
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" required>
            </div>

            <!-- Foto -->
            <div class="col-md-6">
                <label for="foto" class="form-label">Foto del Producto</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            </div>

            <!-- Precio -->
            <div class="col-md-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio" required>
            </div>

            <!-- Cantidad -->
            <div class="col-md-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad" required>
            </div>

            <!-- Fabricante -->
            <div class="col-md-4">
                <label for="fabricante" class="form-label">Fabricante</label>
                <select id="fabricante" name="fabricante" class="form-select" required>
                    <option selected disabled>Selecciona un fabricante</option>
                    <?php while ($fabricante = mysqli_fetch_assoc($resultFabricantes)): ?>
                        <option value="<?= $fabricante['id_fabricante'] ?>">
                            <?= htmlspecialchars($fabricante['nombre_fabricante']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Origen -->
            <div class="col-md-4">
                <label for="origen" class="form-label">Origen</label>
                <select id="origen" name="origen" class="form-select" required>
                    <option selected disabled>Selecciona un país de origen</option>
                    <?php while ($origen = mysqli_fetch_assoc($resultOrigenes)): ?>
                        <option value="<?= $origen['id_origen'] ?>">
                            <?= htmlspecialchars($origen['pais_origen']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Categoría -->
            <div class="col-md-4">
                <label for="categoria" class="form-label">Categoría</label>
                <select id="categoria" name="categoria" class="form-select" required>
                    <option selected disabled>Selecciona una categoría</option>
                    <?php while ($categoria = mysqli_fetch_assoc($resultCategorias)): ?>
                        <option value="<?= $categoria['id_categoria'] ?>">
                            <?= htmlspecialchars($categoria['categoria']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Botón de Guardar -->
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
