<?php
session_start(); // Iniciar o continuar la sesión
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

$mensaje = "Has cerrado sesión correctamente.";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FAE3DE;
            height: 100vh;
        }
        .card {
            border: none;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 400px;">
            <h2 class="text-center mb-4">Cerrar Sesión</h2>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($mensaje) ?>
            </div>
            <div class="text-center">
                <button class="btn btn-primary w-100" style="background-color: #F08C9C; color: white; border: none;" 
                        onclick="window.location.href='index.html';">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
