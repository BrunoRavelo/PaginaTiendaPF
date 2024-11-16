<?php
    $con=mysqli_connect("localhost","root","","tienda");

    // Check connection
    if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
      echo "<script>console.error('Error en la conexion: " . mysqli_error($con) . "');</script>";
    }
?>