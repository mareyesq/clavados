<?php 
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
session_start();
include("funciones.php");
$_SESSION["competencia"]=$competencia;
if (isset($_POST["regresar_sbm"])){
	$_SESSION["categoria"]="";
	$_SESSION["modalidad"]="";
	header("Location: ..?op=php/marcas-competencia.php&com=$competencia");
	exit();
}

include("valida-marca.php");

if (is_null($mensaje)){
	$conexion=conectarse();
	$consulta="INSERT INTO competenciasm (competencia, categoria, modalidad, marca_damas, grado_damas, promedio_damas, marca_varones, grado_varones, promedio_varones) VALUES ($cod_competencia, '$cod_categoria', '$cod_modalidad'";
	if ($marca_f) 
		$consulta .= ", $marca_f";
	else
		$consulta .= ", NULL";
	if ($grado_f) 
		$consulta .= ", $grado_f";
	else
		$consulta .= ", NULL";
	if ($prom_f) 
		$consulta .= ", $prom_f";
	else
		$consulta .= ", NULL";
	if ($marca_m) 
		$consulta .= ", $marca_m";
	else
		$consulta .= ", NULL";
	if ($grado_m) 
		$consulta .= ", $grado_m";
	else
		$consulta .= ", NULL";
	if ($prom_m) 
		$consulta .= ", $prom_m";
	else
		$consulta .= ", NULL";
	$consulta .= ")";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

	if ($ejecutar_consulta){
		unset($_SESSION["categoria"]);
		unset($_SESSION["cod_modalidad"]);
		unset($_SESSION["modalidad"]);
		unset($_SESSION["marca_f"]);
		unset($_SESSION["grado_f"]);
		unset($_SESSION["prom_f"]);
		unset($_SESSION["marca_m"]);
		unset($_SESSION["grado_m"]);
		unset($_SESSION["prom_m"]);
		$mensaje="se dió de alta la marca en <b>$categoria</b> en <b>$modalidad</b>";
		$conexion->close();
		header("Location: ..?op=php/marcas-competencia.php&com=$competencia&mensaje=$mensaje");
		exit();
	}
	else
		$mensaje="No se pudo dar de alta la marca en categoría <b>$categoria</b> en la competencia :(";
}
header("Location: ..?op=php/alta-marca-competencia.php&mensaje=$mensaje");
exit();
?>