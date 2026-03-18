<?php 
$consulta="DELETE FROM calificaciones where ubicacion <> 0";
$ejecutar_consulta=$conexion->query($consulta);
 ?>