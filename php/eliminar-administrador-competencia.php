<?php 
$baja=isset($_POST["baja_btn"])?$_POST["baja_btn"]:NULL;
if (isset($baja)) {
	$competencia=isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL;
	$administrador=isset($_POST["administrador_hdn"])?$_POST["administrador_hdn"]:NULL;
	$cod_competencia=isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL;
	$cod_usuario=isset($_POST["cod_usuario_hdn"])?$_POST["cod_usuario_hdn"]:NULL;
	include("funciones.php");
	$conexion=conectarse();
	$consulta="DELETE FROM competenciasa WHERE competencia=$cod_competencia AND administrador=$cod_usuario";
	$ejecutar_consulta=$conexion->query($consulta);
	if ($ejecutar_consulta)
		$mensaje="Administrador <b>".$administrador."</b> eliminado de competencia <b>".$competencia."<b> :(";
	else
		$mensaje="No se pudo eliminar el administrador <b>".$administrador."</b> :/";
	$conexion->close();
}
header("Location: ..?op=php/administradores-competencia.php&com=$competencia&mensaje=$mensaje");
?>