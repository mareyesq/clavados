<?php 
if (isset($_GET["ccom"])) {
	$cod_competencia=isset($_GET["ccom"])?$_GET["ccom"]:NULL;
	$evento=isset($_GET["nev"])?$_GET["nev"]:NULL;
	$panel=isset($_GET["panel"])?$_GET["panel"]:NULL;
	$ubi=isset($_GET["ubi"])?$_GET["ubi"]:NULL;

	if ($ubi>11) {
		$ubi=$ubi-11;
		$panel=2;		
	}

	$llamo=isset($_GET["ori"])?trim($_GET["ori"]):NULL;
	$llamo=str_replace("*", "&", $llamo);
}
include("funciones.php");
$conexion=conectarse();
$consulta="DELETE FROM competenciasz
			WHERE competencia=$cod_competencia 
				AND evento=$evento 
				AND panel=$panel
				AND ubicacion=$ubi";
$ejecutar_consulta = $conexion->query($consulta);
$conexion->close();
header("Location: ?op=$llamo");
exit();
 ?>