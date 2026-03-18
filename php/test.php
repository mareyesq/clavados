<?php 
	include("funciones.php");
 	$calif_estimada=ajuste_calificacion(10.67);
 	if ($calif_estimada>10)
		$calif_estimada="Más de 10 :(";

	echo $calif_estimada;
 ?>