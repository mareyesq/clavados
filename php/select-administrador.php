<?php 
$conexion=conectarse();
$consulta="SELECT cod_administrador, administrador FROM administradores ORDER BY administrador";

$ejecutar_consulta=$conexion->query($consulta);

while($registro=$ejecutar_consulta->fetch_assoc()){
	$nombre_administrador=utf8_encode($registro["administrador"]);
	$cod_administrador=$registro["cod_administrador"];
	echo "<option value='$nombre_administrador'";
	if(isset($registro_principal["administrador"])){
		if($cod_administrador==utf8_decode($registro_principal["administrador"]))
			echo " selected";
	}
	echo ">$nombre_administrador</option>";
}
$conexion->close();
?>