<?php 
$conexion=conectarse();
$consulta="SELECT * FROM competenciasq WHERE competencia=$cod_competencia and q.nombre_corto='$corto'";
$ejecutar_consulta = $conexion->query($consulta);
if (!$ejecutar_consulta){
	$mensaje="No hay registros para esta consulta :(";
	$encontro_equipo=false;
}
else{
	$num_regs=$ejecutar_consulta->num_rows;
}
if ($num_regs==0){
	$mensaje="No hay registros para esta consulta :(";
	$encontro_equipo=false;
}
else{
	$row=$ejecutar_consulta->fetch_assoc();
	$nombre_corto=utf8_decode($row["nombre_corto"]);
	$telefono=$row["telefono"];
	$representante=utf8_decode($row["representante"]);
	$email=$row["email"];
	$pais=utf8_decode($row["Country"]);
	$encontro_equipo=true;
}
$conexion->close();
 ?>