<?php 
if (isset($_GET["ccom"])) {
	$cod_competencia=isset($_GET["ccom"])?$_GET["ccom"]:NULL;
	$evento=isset($_GET["nev"])?$_GET["nev"]:NULL;
	$juez=isset($_GET["ju"])?$_GET["ju"]:NULL;

	$llamo=isset($_GET["ori"])?trim($_GET["ori"]):NULL;
	$llamo=str_replace("*", "&", $llamo);
}
include("funciones.php");
$conexion=conectarse();
$consulta="DELETE FROM competenciass
			WHERE competencia=$cod_competencia 
				AND evento=$evento 
				AND juez=$juez";
$ejecutar_consulta = $conexion->query($consulta);
$conexion->close();
header("Location: ?op=$llamo");
exit();
 ?>