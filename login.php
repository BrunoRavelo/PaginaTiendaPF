<?php
session_start();
include("php/conexionBD.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //primero checa que se envie el formulario 
    $email = $_POST['email'] ?? ''; //se hace un ?? para que si el valor es nulo se sustituya con ''
    $password = $_POST['password'] ?? '';
    // Consulta para verificar el usuario y contraseña
    $stmt = $con->prepare("SELECT id_usuario, correo, password, rol FROM usuarios WHERE correo = ?"); //se prepara la consulta, ? significa que aqui se va a poner el blind param
    $stmt->bind_param("s", $email); //manda el correo con una s de string como parametro
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc(); //contiene id usuario y correo 

    if ($user && $password === $user['password']) { //si user existe y la contraseña es igual a la contraseña dentr de user entonces sigue
        //Caso de que ingrese todo bien se guarda 
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['correo'] = $user['correo'];
        $_SESSION['rol'] = $user['rol'];

        echo "Rol del usuario: " . $_SESSION['rol'];

        if ($_SESSION['rol'] == 1) {
            header("Location: administrador.php"); 
        } else {
            header("Location: tienda.php"); 
        }

        exit();
    } else {
        $error = "Usuario o contraseña incorrectos. Por favor, inténtelo de nuevo.";
    }

    $stmt->close(); //cierra la consulta preparada
    $con->close(); //cierra la conexion
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background-color: #FAE3DE;
            height: 100vh; /* Esto asegura que el div ocupe toda la altura de la pantalla */
        }
    </style>
</head>
<body>
    <!--Se utilizo un formato preestablecido de incio de sesion-->
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 400px;">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?> <!-- esto evita ataques con entradas de usuario-->
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background-color: #F08C9C; color: white;border: none;">Iniciar Sesión</button>
            </form>
            <div class="text-center mt-3">
                <p>¿Aún no tienes cuenta? 
                    <a href="registro.php"style="color: #F08C9C;">Regístrate</a>
                </p>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
