<?php
// Incluir conexión a la base de datos
include('conexionBD.php');

// Verificar si se ha recibido el parámetro 'id'
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_categoria = intval($_GET['id']); // Sanitizar el parámetro para evitar inyecciones SQL

    // Consulta para obtener los datos de la categoría
    $query = "SELECT * FROM categorias WHERE id_categoria = $id_categoria";
    $result = mysqli_query($con, $query);

    // Verificar si la categoría existe
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<h1>Categoría: " . htmlspecialchars($row['categoria']) . "</h1>";
        echo "<p>Información adicional sobre la categoría puede ir aquí.</p>";
        // Aquí podrías agregar consultas adicionales, como productos de esta categoría
    } else {
        echo "<p>Categoría no encontrada.</p>";
    }
} else {
    echo "<p>Parámetro inválido o no proporcionado.</p>";
}

// Cerrar la conexión
mysqli_close($con);
?>
