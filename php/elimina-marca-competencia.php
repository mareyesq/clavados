<?php 
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);
if (isset($_POST["regresar_sbm"])){
	header("Location: ..?op=php/marcas-competencia.php&com=$competencia&cco=$cod_competencia");
	exit();
}

include("funciones.php");
$categoria=isset($_POST["categoria_slc"]) ? $_POST["categoria_slc"] : null;
$cod_categoria=substr($categoria, 0, 2);
$modalidad=isset($_POST["modalidad_slc"]) ? $_POST["modalidad_slc"] : null;
$cod_modalidad=determina_modalidad($modalidad,$conexion);

$conexion=conectarse();	
$consulta="DELETE FROM competenciasm 
	WHERE (competencia=$cod_competencia 
			AND categoria='$cod_categoria' 
			AND modalidad='$cod_modalidad' )";
$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
$conexion->close();
if ($ejecutar_consulta){
	$mensaje="Se eliminó la marca: <b>$categoria</b> modalidad <b>$modalidad<b>";
	header("Location: ..?op=php/marcas-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
	exit();
}
else{
	$mensaje="No se pudo eliminar la marca:  <b>$categoria</b> modalidad <b>$modalidad<b>";
}	

header("Location: ..?op=php/baja-marca-competencia.php&mensaje=$mensaje");
exit();
 ?>