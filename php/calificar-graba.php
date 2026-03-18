<?php 
$consulta="SELECT calificacion 
FROM calificaciones 
WHERE ubicacion=$ubicacion";
$conexion=conectarse();
$ejecutar_consulta=$conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs){
	$consulta="UPDATE calificaciones 
	SET calificacion=$calificacion";
	$consulta.=" WHERE ubicacion=$ubicacion";
}
else{
	$consulta="INSERT INTO calificaciones 
	(ubicacion, calificacion) VALUES ($ubicacion, $calificacion)";
}
$ejecutar_consulta = $conexion->query($consulta);
if (!$ejecutar_consulta){
	$mensaje="Error: No se pudo registrar tu calificación, Avisa a Mesa de Control :(";
	$conexion->close();
}
else{
	$_SESSION["proteger"]=TRUE;
	$_SESSION["calificacion"]=isset($calificacion)?$calificacion:NULL;

	$mensaje="Cuando estén anunciando el próximo salto, pulsa en Calificar ;)";
	$conexion->close();
	header("Location: ?op=php/calificar.php&com=$competencia&cco=$cod_competencia");
	exit();
}		

?>
