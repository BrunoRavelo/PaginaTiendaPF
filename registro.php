<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 400px;">
            <h2 class="text-center mb-4">Registro</h2>
            
            <?php
            // Incluir la conexión a la base de datos
            include("php/conexionBD.php");

            // Mensaje dinámico para mostrar resultados
            $mensaje = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
                $correo = mysqli_real_escape_string($con, $_POST['correo']);
                $password = mysqli_real_escape_string($con, $_POST['password']);
                $nacimiento = mysqli_real_escape_string($con, $_POST['nacimiento']);
                $tarjeta = mysqli_real_escape_string($con, $_POST['tarjeta']);
                $cp = mysqli_real_escape_string($con, $_POST['cp']);

                // Verificar si el correo ya existe
                $verificarCorreo = $con->query("SELECT * FROM usuarioss WHERE correo = '$correo'");
                if ($verificarCorreo->num_rows > 0) {
                    $mensaje = '<div class="alert alert-danger">Este correo ya está en uso.</div>';
                } else {
                    // Insertar el nuevo usuario
                    $query = "INSERT INTO usuarioss (nombre, correo, password, nacimiento, tarjeta, cp)
                              VALUES ('$nombre', '$correo', '$password', '$nacimiento', '$tarjeta', '$cp')";
                    if ($con->query($query)) {
                        $mensaje = '<div class="alert alert-success">
                                        Registro exitoso. <a href="index.php" class="btn btn-success btn-sm">Iniciar Sesión</a>
                                    </div>';
                    } else {
                        $mensaje = '<div class="alert alert-danger">Error al registrar usuario: ' . $con->error . '</div>';
                    }
                }
            }
            ?>

            <!-- Mostrar mensaje -->
            <?= $mensaje ?>

            <!-- Formulario de registro -->
            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="nacimiento" name="nacimiento" required>
                </div>
                <div class="mb-3">
                    <label for="tarjeta" class="form-label">Número de Tarjeta</label>
                    <input type="number" class="form-control" id="tarjeta" name="tarjeta" required>
                </div>
                <div class="mb-3">
                    <label for="cp" class="form-label">Código Postal</label>
                    <input type="number" class="form-control" id="cp" name="cp" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Registrar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
