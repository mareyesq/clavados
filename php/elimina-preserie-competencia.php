<?php 
$cod_competencia=isset($_POST["cod_competencia_hdn"])?trim($_POST["cod_competencia_hdn"]):NULL;
$competencia=isset($_POST["competencia_hdn"])?trim($_POST["competencia_hdn"]):NULL;
$logo=isset($_POST["logo_hdn"])?trim($_POST["logo_hdn"]):NULL;
$categoria=isset($_POST["categoria_hdn"])?trim($_POST["categoria_hdn"]):NULL;
$modalidad=isset($_POST["modalidad_hdn"])?trim($_POST["modalidad_hdn"]):NULL;

session_start();
if (isset($_POST["regresar_sbm"])){
	include("preserie-limpia-session.php");
	header("Location: ..?op=php/preseries-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}
	
include("funciones.php");

$eliminar=isset($_POST["eliminar_sbm"])?$_POST["eliminar_sbm"]:NULL;
if ($eliminar) {
	$conexion=conectarse();
	$ver_mysql=substr($conexion->server_info,0,3);
	if ($ver_mysql>="5.6")
		$conexion->begin_transaction();

	$op_result=array();
	$consulta="DELETE FROM preseries
		WHERE (competencia=$cod_competencia 
			AND categoria='$categoria' 
			and modalidad='$modalidad')";
	$ejecutar_consulta=$conexion->query($consulta);
	$op_result["elimina preserie"]=($ejecutar_consulta)?1:0;

	if ($ver_mysql>="5.6"){
		$all_ok=1;
		foreach ($op_result as $value) {
			if (!$value) {
				$all_ok = 0;
				break;
			}
		}
		if ($all_ok){
			if (!$conexion->commit())
				$mensaje="Error: No se pudo terminar la Transacción :(";
		}
		else{
			if ($conexion->rollback()) 
				$mensaje="Error: Transacción reversada :( ";
			else
				$mensaje="Error: Transacción NO reversada :( ";
		}
	}
	$conexion->close();
	if (is_null($mensaje)){
		$mensaje="Se ha eliminado la serie <b>$categoria - $modalidad</b> :)";
		include("preserie-limpia-session.php");
		header("Location: ..?op=php/preseries-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
		exit();
	}
	else{
		if ($ver_mysql>="5.6"){
			echo "all_ok: $all_ok<br>";
			echo "$mensaje<br>";
			print_r($op_result);
			exit();
		}
	}
}
else
	header("Location: ..?op=php/baja-preserie.php&mensaje=$mensaje");
?>