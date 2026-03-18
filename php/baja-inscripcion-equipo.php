<?php 
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	
session_start();

if (isset($_POST["regresar_sbm"])){
	header("Location: ..?op=php/equipos-competencia.php&com=$competencia");
	exit();
}

include("funciones.php");

$equipo=isset($_POST["equipo_hdn"]) ? $_POST["equipo_hdn"] : null;
$corto=isset($_POST["corto_hdn"]) ? $_POST["corto_hdn"] : null;
$logo=isset($_POST["logo_hdn"]) ? $_POST["logo_hdn"] : null;

$_SESSION["equipo"]=$equipo;
$_SESSION["corto"]=$corto;
$_SESSION["logo"]=$logo;
$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;

$mensaje=NULL;

if (is_null($mensaje)){
	if (!$_SESSION["admin_sist"]) {
		$cod_usuario=$_SESSION["usuario_id"];
		$conexion=conectarse();
		$consulta="SELECT usuario_alta FROM competenciasq WHERE (competencia=$cod_competencia) and (nombre_corto='$corto')";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		if ($ejecutar_consulta){
			$registro_competencia=$ejecutar_consulta->fetch_assoc();
			$usuario_alta=$registro_competencia["usuario_alta"];
			if ($usuario_alta!==$cod_usuario)
				$mensaje="Error: No estás autorizado para retirar el equipo $equipo";
		}
		$conexion->close();
    }        
}

if (is_null($mensaje)){
	$conexion=conectarse();
	$conexion->begin_transaction();
	$op_result=array();
	$consulta="SELECT cod_planilla FROM planillas WHERE competencia=$cod_competencia and equipo='$corto' ORDER BY cod_planilla";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$op_result["selecciona planillas del equipo"]=$ejecutar_consulta?TRUE:FALSE;
	$ok_detalle=TRUE;
	$planillas="";
	while ($row=$ejecutar_consulta->fetch_assoc()) 
		if ($planillas=="")
			$planillas=isset($row["cod_planilla"])?$row["cod_planilla"]:NULL;
		else
			$planillas.=isset($row["cod_planilla"])?", ".$row["cod_planilla"]:NULL;

	$ok_detalle=TRUE;
	if (strlen($planillas)>1) {
		$borra="DELETE FROM planillad WHERE planilla IN ($planillas)";

		$ejecutar_borra=$conexion->query($borra);
		$op_result["borra detalle de planillas "]=$ejecutar_borra?TRUE:FALSE;
		if (!$ejecutar_borra)
			$ok_detalle=FALSE;
	}	
	if ($ok_detalle) {
		$consulta="DELETE FROM planillas WHERE competencia=$cod_competencia and equipo='$corto'";
		$ejecutar_consulta=$conexion->query($consulta);
		$op_result["borra planillas del equipo"]=$ejecutar_consulta?TRUE:FALSE;
	}
	
	$consulta="DELETE  FROM competenciasq WHERE (competencia=$cod_competencia) and (nombre_corto='$corto')";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$op_result["borra equipo"]=$ejecutar_consulta?TRUE:FALSE;

	$all_ok=TRUE;
	foreach ($op_result as $value) {
		if ($value==FALSE) {
			$all_ok = FALSE;
			break;
		}
	}

	if ($all_ok){
		if (!$conexion->commit())
			$mensaje="Error: No se pudo terminar de elimiar el equipo <b>$corto</b> :(";
	}
	else{
		if ($conexion->rollback()) 
			$mensaje="Error: Transacción reversada :( ";
		else
			$mensaje="Error: Transacción NO reversada :( ";
	}
	$conexion->close();

	if (is_null($mensaje)){
		unset($_SESSION["equipo"]);
		unset($_SESSION["corto"]);
		unset($_SESSION["logo"]);
		unset($_SESSION["competencia"]);
		unset($_SESSION["cod_competencia"]);

		$mensaje="Se retiró el equipo <b>$equipo</b> de la competencia <b>$competencia</b> :(";
		header("Location: ../index.php?op=php/equipos-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo&mensaje=$mensaje");
		exit();
	}
	else{
//		$mensaje="No se pudo retirar el equipo <b>$equipo</b> de la competencia <b>$competencia</b> :(";
		echo "$mensaje<br>";
		print_r($op_result);
		exit();
	}
}
header("Location: ../index.php?op=php/equipos-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo&mensaje=$mensaje");
exit();
?>