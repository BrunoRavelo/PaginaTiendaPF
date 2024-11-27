<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html");
    exit();
}
if ($_SESSION['rol'] != 1) {
    header("Location: tienda.php");
    exit();
}

// Incluir conexión a la base de datos
include("php/conexionBD.php");

// Consultar lista de productos para el menú desplegable
$queryProductos = "SELECT id_producto, nombre FROM productos";
$resultProductos = mysqli_query($con, $queryProductos);
// Consultar datos para los menús desplegables
$queryFabricantes = "SELECT id_fabricante, nombre_fabricante FROM fabricantes";
$queryOrigenes = "SELECT id_origen, pais_origen FROM origen";
$queryCategorias = "SELECT id_categoria, categoria FROM categorias";

$resultFabricantes = mysqli_query($con, $queryFabricantes);
$resultOrigenes = mysqli_query($con, $queryOrigenes);
$resultCategorias = mysqli_query($con, $queryCategorias);

$producto = null;
if (isset($_POST['producto'])) {
    $id_producto = $_POST['producto'];
    $queryProducto = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $resultProducto = mysqli_query($con, $queryProducto);
    $producto = mysqli_fetch_assoc($resultProducto);
}

// Procesar edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $id_fabricante = $_POST['fabricante'];
    $id_origen = $_POST['origen'];
    $id_categoria = $_POST['categoria'];

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = addslashes(file_get_contents($_FILES['foto']['tmp_name']));
        $queryUpdate = "UPDATE productos SET 
            nombre='$nombre', 
            descripcion='$descripcion', 
            foto='$foto', 
            precio=$precio, 
            cantidad=$cantidad, 
            id_fabricante=$id_fabricante, 
            id_origen=$id_origen, 
            id_categoria=$id_categoria 
            WHERE id_producto = $id_producto";
    } else {
        $queryUpdate = "UPDATE productos SET 
            nombre='$nombre', 
            descripcion='$descripcion', 
            precio=$precio, 
            cantidad=$cantidad, 
            id_fabricante=$id_fabricante, 
            id_origen=$id_origen, 
            id_categoria=$id_categoria 
            WHERE id_producto = $id_producto";
    }

    if (mysqli_query($con, $queryUpdate)) {
        $mensaje = "Producto actualizado exitosamente.";
    } else {
        $mensaje = "Error al actualizar el producto: " . mysqli_error($con);
    }
}
// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id_producto = $_POST['id_producto'];
    $queryDelete = "DELETE FROM productos WHERE id_producto = $id_producto";

    if (mysqli_query($con, $queryDelete)) {
        $mensaje = "Producto eliminado exitosamente.";
        $producto = null; // Limpiar la selección después de eliminar
    } else {
        $mensaje = "Error al eliminar el producto: " . mysqli_error($con);
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
            <h1 class="text-center mb-4">Modificar Producto</h1>

            <?php if (isset($mensaje)): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($mensaje) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="row g-3">
                <div class="col-12">
                    <label for="producto" class="form-label">Seleccionar Producto</label>
                    <select id="producto" name="producto" class="form-select" onchange="this.form.submit()" required>
                        <option selected disabled>Selecciona un producto</option>
                        <?php while ($prod = mysqli_fetch_assoc($resultProductos)): ?>
                            <option value="<?= $prod['id_producto'] ?>" 
                                <?= isset($producto['id_producto']) && $producto['id_producto'] == $prod['id_producto'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prod['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </form>

            <?php if ($producto): ?>
            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= htmlspecialchars($producto['descripcion']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="foto" class="form-label">Foto del Producto</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                </div>

                <div class="col-md-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio" value="<?= $producto['precio'] ?>" required>
                </div>

                <div class="col-md-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?= $producto['cantidad'] ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="fabricante" class="form-label">Fabricante</label>
                    <select id="fabricante" name="fabricante" class="form-select" required>
                        <option disabled>Selecciona un fabricante</option>
                        <?php
                        mysqli_data_seek($resultFabricantes, 0); // Reiniciar el puntero
                        while ($fabricante = mysqli_fetch_assoc($resultFabricantes)): ?>
                            <option value="<?= $fabricante['id_fabricante'] ?>" 
                                <?= $producto['id_fabricante'] == $fabricante['id_fabricante'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($fabricante['nombre_fabricante']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="origen" class="form-label">Origen</label>
                    <select id="origen" name="origen" class="form-select" required>
                        <option disabled>Selecciona un país de origen</option>
                        <?php
                        mysqli_data_seek($resultOrigenes, 0);
                        while ($origen = mysqli_fetch_assoc($resultOrigenes)): ?>
                            <option value="<?= $origen['id_origen'] ?>" 
                                <?= $producto['id_origen'] == $origen['id_origen'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($origen['pais_origen']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" name="categoria" class="form-select" required>
                        <option disabled>Selecciona una categoría</option>
                        <?php
                        mysqli_data_seek($resultCategorias, 0);
                        while ($categoria = mysqli_fetch_assoc($resultCategorias)): ?>
                            <option value="<?= $categoria['id_categoria'] ?>" 
                                <?= $producto['id_categoria'] == $categoria['id_categoria'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['categoria']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" name="editar" class="btn btn-primary w-100">Guardar Cambios</button>
                    <button type="submit" name="eliminar" class="btn btn-danger">Eliminar Producto</button>

                </div>
            </form>
            <?php endif; ?>
        </div>
        </body>

</html>

