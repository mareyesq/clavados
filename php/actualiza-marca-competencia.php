<?php 
session_start();
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
if (isset($_POST["regresar_sbm"])){
	$_SESSION["categoria"]="";
	$_SESSION["modalidad"]="";
	$_SESSION["marca_f"]="";
	$_SESSION["grado_f"]="";
	$_SESSION["prom_f"]="";
	$_SESSION["marca_m"]="";
	$_SESSION["grado_m"]="";
	$_SESSION["prom_m"]="";
	header("Location: ..?op=php/marcas-competencia.php&com=$competencia");
	exit();
}
include("funciones.php");
include("valida-marca.php");
				
if (is_null($mensaje)){
	$conexion=conectarse();
	$consulta="UPDATE competenciasm
		set marca_damas=$marca_f,
			marca_varones=$marca_m";
	if ($grado_m)
			$consulta.=", grado_varones=$grado_m";
	if ($prom_m)
		$consulta.=", promedio_varones=$prom_m";
	if ($grado_f)
		$consulta .= ", grado_damas=$grado_f";			
	$consulta .= " 	WHERE competencia=$cod_competencia 
  		AND categoria='$cod_categoria' 
  		AND modalidad='$cod_modalidad'";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$conexion->close();
	if ($ejecutar_consulta){
		unset($_SESSION["categoria"]);
		unset($_SESSION["modalidad"]);
		unset($_SESSION["marca_f"]);
		unset($_SESSION["grado_f"]);
		unset($_SESSION["prom_f"]);
		unset($_SESSION["marca_m"]);
		unset($_SESSION["grado_m"]);
		unset($_SESSION["prom_m"]);
		$mensaje="se actualizó la marca de la categoría <b>$categoria</b> en la competencia";
		header("Location: ..?op=php/marcas-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo&mensaje=$mensaje");
		exit();
	}
	else
		$mensaje="No se pudo dactualizar la marca de la categoría <b>$categoria</b> en la competencia :(";
}
header("Location: ..?op=php/edita-marca-competencia.php&mensaje=$mensaje");
exit();
 ?>