<?php 
$conexion=conectarse();
$consulta="SELECT cod_entrenador,entrenador FROM entrenadores ORDER BY entrenador";

$ejecutar_consulta=$conexion->query($consulta);

while($registro=$ejecutar_consulta->fetch_assoc()){
	$nombre_entrenador=utf8_encode($registro["entrenador"]);
	$cod_entrenador=$registro["cod_entrenador"];
	echo "<option value='$nombre_entrenador'";
	if(isset($registro_principal["entrenador"])){
		if($cod_entrenador==utf8_decode($registro_principal["entrenador"]))
			echo " selected";
	}
	echo ">$nombre_entrenador</option>";
}
$conexion->close();
?>