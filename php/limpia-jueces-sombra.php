<?php 

$consulta="DELETE FROM competenciass 
	WHERE competencia=$cod_competencia AND evento=$numero_evento";
$conexion=conectarse();
$ejecutar_consulta = $conexion->query($consulta);
$conexion->close();
 ?>