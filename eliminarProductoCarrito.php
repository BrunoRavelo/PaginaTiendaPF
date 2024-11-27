<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario y el ID del producto a eliminar
$id_usuario = $_SESSION['id_usuario'];
$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;

if ($id_producto > 0) {
    // Incluir la conexión a la base de datos
    include("php/conexionBD.php");

    // Preparar y ejecutar la consulta para eliminar el producto
    $query = "DELETE FROM carrito WHERE id_usuario = $id_usuario AND id_producto = $id_producto";
    
    if ($con->query($query)) {
        // Si la eliminación fue exitosa, establecer el mensaje de éxito en la sesión
        $_SESSION['cart_message'] = [
            'type' => 'success',
            'text' => 'Producto eliminado del carrito.'
        ];
    } else {
        // Si ocurre un error, establecer el mensaje de error en la sesión
        $_SESSION['cart_message'] = [
            'type' => 'danger',
            'text' => 'Error al eliminar el producto del carrito.'
        ];
    }

    // Redirigir a la página de la tienda
    header("Location: tienda.php");
    exit();
} else {
    // Si no hay un ID de producto válido, redirigir a la tienda
    $_SESSION['cart_message'] = [
        'type' => 'danger',
        'text' => 'Producto no encontrado en el carrito.'
    ];
    header("Location: tienda.php");
    exit();
}
?>
