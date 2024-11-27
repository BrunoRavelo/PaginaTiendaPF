<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario
$id_usuario = $_SESSION['id_usuario'];

// Incluir conexión a la base de datos
include("php/conexionBD.php");

// Variables para los mensajes
$checkout_message = '';
$cart_message = '';

if (isset($_SESSION['checkout_message'])) {
    $checkout_message = $_SESSION['checkout_message'];
    unset($_SESSION['checkout_message']);
}

if (isset($_SESSION['cart_message'])) {
    $cart_message = $_SESSION['cart_message'];
    unset($_SESSION['cart_message']);
}

// Iniciar transacción
mysqli_begin_transaction($con);

try {
    // Seleccionar los productos del carrito
    $query = "
        SELECT id_producto, cantidad
        FROM carrito
        WHERE id_usuario = $id_usuario
    ";
    $result = $con->query($query);
    if (!$result) {
        throw new Exception("Error al obtener el carrito: " . mysqli_error($con));
    }

    // Verificar existencia de productos y cantidades suficientes
    while ($row = $result->fetch_assoc()) {
        $id_producto = $row['id_producto'];
        $cantidad = $row['cantidad'];

        // Verificar si hay suficientes productos en stock
        $stockQuery = "
            SELECT cantidad FROM productos WHERE id_producto = $id_producto
        ";
        $stockResult = $con->query($stockQuery);
        if ($stockResult) {
            $stockRow = $stockResult->fetch_assoc();
            $stockCantidad = $stockRow['cantidad'];

            if ($cantidad > $stockCantidad) {
                // No hay suficiente stock
                $_SESSION['checkout_message'] = [
                    'type' => 'danger',
                    'text' => 'No hay suficientes productos en stock para completar la compra.'
                ];
                header("Location: tienda.php");
                exit();
            } else {
                // Actualizar el stock en la tabla productos
                $newStock = $stockCantidad - $cantidad;
                $updateStockQuery = "
                    UPDATE productos SET cantidad = $newStock WHERE id_producto = $id_producto
                ";
                $con->query($updateStockQuery);
            }
        }
    }

    // Insertar productos en la tabla de ventas
    $insertQuery = "
        INSERT INTO ventas (id_usuario, id_producto, fecha, cantidad)
        SELECT $id_usuario, id_producto, NOW(), cantidad FROM carrito WHERE id_usuario = $id_usuario
    ";
    if (!$con->query($insertQuery)) {
        throw new Exception("Error al insertar en ventas: " . mysqli_error($con));
    }

    // Vaciar el carrito
    $deleteQuery = "DELETE FROM carrito WHERE id_usuario = $id_usuario";
    if (!$con->query($deleteQuery)) {
        throw new Exception("Error al vaciar el carrito: " . mysqli_error($con));
    }

    // Confirmar transacción
    mysqli_commit($con);

    // Establecer mensaje de éxito en la sesión
    $_SESSION['checkout_message'] = [
        'type' => 'success',
        'text' => 'Compra realizada con éxito.'
    ];
    header("Location: tienda.php");
    exit();
} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($con);

    // Establecer mensaje de error en la sesión
    $_SESSION['checkout_message'] = [
        'type' => 'danger',
        'text' => 'Error durante el proceso de compra: ' . $e->getMessage()
    ];
    header("Location: tienda.php");
    exit();
} finally {
    // Cerrar conexión
    $con->close();
}
?>
