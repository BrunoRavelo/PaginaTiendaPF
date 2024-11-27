<?php
session_start();

// Comprobar si el usuario no está logueado
if (!isset($_SESSION['id_usuario'])) {
    // Si no está logueado, redirigir al formulario de login
    header("Location: login.php");
    exit(); // Termina la ejecución del script
}

if ($_SESSION['rol'] != 0) {
    // Redirigir a administrador.php si no es un usuario regular
    header("Location: administrador.php");
    exit();
}

// Obtener datos del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Incluir el archivo de conexión
include("php/conexionBD.php");

// Validar si se realizó una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);

    // Verificar si el producto ya está en el carrito
    $consulta = "SELECT cantidad FROM carrito WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
    $resultado = $con->query($consulta);

    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($con));
    }

    if ($resultado->num_rows > 0) {
        // Si el producto ya está en el carrito, actualizar la cantidad
        $fila = $resultado->fetch_assoc();
        $nuevaCantidad = $fila['cantidad'] + $cantidad;

        if ($nuevaCantidad <= 0) {
            // Si la cantidad final es 0 o menor, redirigir a eliminarProductoCarrito.php
            header("Location: eliminarProductoCarrito.php?id_producto=$id_producto");
            exit();
        } else {
            // Si la cantidad es válida, actualizar el registro
            $update = "UPDATE carrito SET cantidad = $nuevaCantidad WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
            if (!$con->query($update)) {
                die("Error al actualizar el carrito: " . mysqli_error($con));
            }
        }
    } else {
        // Si no está en el carrito, insertar un nuevo registro
        $insert = "INSERT INTO carrito (id_producto, id_usuario, cantidad) VALUES ($id_producto, $id_usuario, $cantidad)";
        if (!$con->query($insert)) {
            die("Error al insertar en el carrito: " . mysqli_error($con));
        }
    }

    // Redirigir de vuelta a la página de productos
    header("Location: tienda.php");
    exit();
}

// Cerrar la conexión
mysqli_close($con);
?>
