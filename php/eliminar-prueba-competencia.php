<?php 
$baja=isset($_POST["baja_btn"])?$_POST["baja_btn"]:NULL;
$competencia=isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL;
$cod_competencia=isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL;
if (isset($baja)) {
	$categoria=isset($_POST["categoria_hdn"])?$_POST["categoria_hdn"]:NULL;
	$cod_categoria=isset($_POST["cod_categoria_hdn"])?$_POST["cod_categoria_hdn"]:NULL;
	$cod_usuario=isset($_POST["cod_usuario_hdn"])?$_POST["cod_usuario_hdn"]:NULL;
	include("funciones.php");
	$conexion=conectarse();
	$consulta="DELETE FROM competenciapr WHERE competencia=$cod_competencia AND categoria='$cod_categoria'";
	$ejecutar_consulta=$conexion->query($consulta);
	if ($ejecutar_consulta)
		$mensaje="Categoria <b>".$categoria."</b> eliminada de competencia <b>".$competencia."<b> :(";
	else
		$mensaje="No se pudo eliminar la categoría <b>".$categoria."</b> :/";
	$conexion->close();
}
header("Location: ..?op=php/pruebas-competencia.php&com=$competencia&cco=$cod_competencia");
?>