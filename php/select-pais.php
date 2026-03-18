<?php 
$conexion=conectarse();
$consulta="SELECT Country FROM countries ORDER BY Country";
$ejecutar_consulta=$conexion->query($consulta);

while($registro=$ejecutar_consulta->fetch_assoc()){
	$nombre_pais=utf8_encode($registro["Country"]);
	echo "<option value='$nombre_pais'";
	if(isset($pais)){
		if ($nombre_pais==utf8_decode($pais))
			echo " selected";
	}
	echo ">$nombre_pais</option>";
}
$conexion->close();
?>