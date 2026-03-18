<?php 

$conexion=conectarse();
$consulta="SELECT cod_equipo,equipo FROM equipos ORDER BY equipo";

$ejecutar_consulta=$conexion->query($consulta);

while($registro=$ejecutar_consulta->fetch_assoc()){
	$nombre_equipo=utf8_encode($registro["equipo"]);
	$cod_equipo=$registro["cod_equipo"];
	echo "<option value='$nombre_equipo'";
	if(isset($registro_principal["equipo"])){
		if ($cod_equipo==utf8_decode($registro_principal["equipo"]))
			echo " selected";
	}
	echo ">$nombre_equipo</option>";
}
$conexion->close();
?>