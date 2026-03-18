<?php 
$baja=isset($_POST["baja_btn"])?$_POST["baja_btn"]:NULL;
$mensaje=NULL;
$mensaje=trim($mensaje);
if (isset($baja)) {
	$competencia=isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL;
	$juez=isset($_POST["juez_hdn"])?$_POST["juez_hdn"]:NULL;
	$cod_competencia=isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL;
	$cod_usuario=isset($_POST["cod_usuario_hdn"])?$_POST["cod_usuario_hdn"]:NULL;
	include("funciones.php");
	$conexion=conectarse();
	$consulta="DELETE FROM competenciasjz WHERE competencia=$cod_competencia AND juez=$cod_usuario";
	$ejecutar_consulta=$conexion->query($consulta);
	if ($ejecutar_consulta)
		$mensaje="Juez <b>".$juez."</b> eliminado de competencia <b>".$competencia."<b> :(";
	else
		$mensaje="No se pudo eliminar el juez <b>".$juez."</b> :/";
	$conexion->close();
}
header("Location: ..?op=php/jueces-competencia.php&com=$competencia&mensaje=$mensaje");
?>