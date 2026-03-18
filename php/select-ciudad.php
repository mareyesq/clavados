<?php 
$conexion=conectarse();
if (isset($pais)) {
	$consulta="SELECT CountryID FROM countries WHERE Country='$pais'";

	$ejecutar_consulta=$conexion->query($consulta);
	if ($registro=$ejecutar_consulta->fetch_assoc()){
		$cod_pais=$registro["CountryID"];
	$consulta="SELECT CityId, City FROM cities WHERE CountryID='$cod_pais' ORDER BY City";
	}
}
else
	$consulta="SELECT CityId, City FROM cities ORDER BY City";

$ejecutar_consulta=$conexion->query($consulta);

while($registro=$ejecutar_consulta->fetch_assoc()){
	$nombre_ciudad=utf8_encode($registro["City"]);
	$cod_ciudad=$registro["CityId"];
	echo "<option value='$nombre_ciudad'";
	if(isset($ciudad)){
		if ($nombre_ciudad==utf8_decode($ciudad))
			echo " selected";

	}
	echo ">$nombre_ciudad</option>";
}
$conexion->close();
?>