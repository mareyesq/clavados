<?php 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);
	if (isset($_POST["regresar_sbm"])){
		header("Location: ..?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}
	$fechahora_original=isset($_POST["fechahora_original_hdn"])?$_POST["fechahora_original_hdn"]:NULL;
	$descripcion=(isset($_POST["descripcion_hdn"])?$_POST["descripcion_hdn"]:NULL);
	
	include("funciones.php");
	$conexion=conectarse();
	$consulta="DELETE FROM competenciaev WHERE (competencia=$cod_competencia AND fechahora='$fechahora_original')";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	if ($ejecutar_consulta){
		$mensaje="Se eliminó el evento: <b>$descripcion</b>";
		$conexion->close();
		header("Location: ..?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
		exit();
	}
	else{
		$mensaje="No se pudo eliminar el evento <b>$descripcion</b>";
	}	
	$conexion->close();
	header("Location: ..?op=php/baja-evento-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
	exit();
 ?>