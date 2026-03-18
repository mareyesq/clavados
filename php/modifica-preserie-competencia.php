<?php 
session_start();
include("funciones.php");
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null);
if (isset($_POST["regresar_sbm"])){
	include("preserie-limpia-session.php");
	header("Location: ..?op=php/preseries-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

$actualizar=(isset($_POST["actualizar_sbm"])?$_POST["actualizar_sbm"]:null);

$alta=FALSE;
// Asigno a variables php los valores que vienen en el formulario
include("valida-preserie-competencia.php");

if (is_null($mensaje) and $actualizar){
	$conexion=conectarse();
	$ver_mysql=substr($conexion->server_info,0,3);
	if ($ver_mysql>="5.6")
		$conexion->begin_transaction();
	$op_result=array();
	$consulta="DELETE FROM preseries WHERE competencia=$cod_competencia and modalidad='$modalidad' and categoria='$categoria'";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

	$op_result["borra preserie"]=($ejecutar_consulta)?1:0;
	$consulta="INSERT INTO preseries (competencia, modalidad, categoria, orden, salto, posicion, altura, grado, observacion, libre, plataforma) VALUES ";

	$valores=NULL;
	foreach ($items_ser as $row) {
		$orden=$row["orden"];
		$salto=isset($row["salto"])?$row["salto"]:NULL;
		$posicion=isset($row["posicion"])?$row["posicion"]:NULL;
		$altura=isset($row["altura"])?$row["altura"]:NULL;
		$grado=isset($row["grado"])?$row["grado"]:NULL;
		$observacion=isset($row["observacion"])?$row["observacion"]:NULL;
		$libre=isset($row["libre"])?$row["libre"]:NULL;
		if ($valores)
			$valores .= ", ";
		$valores .= "($cod_competencia, '$modalidad', '$categoria', $orden";
		if ($salto) 
			$valores .= ", '$salto'";
		else
			$valores .= ", NULL";
		if ($posicion) 
			$valores .= ", '$posicion'";
		else
			$valores .= ", NULL";
		if (isset($altura))
			$valores .= ", $altura";
		else
			$valores .= ", NULL";
		if ($grado) 
			$valores .= ", $grado";
		else
			$valores .= ", NULL";
		if ($observacion) 
			$valores .= ", '$observacion'";
		else
			$valores .= ", NULL";
		if ($libre) 
			$valores .= ", 1";
		else
			$valores .= ", NULL";
		if ($plataforma) 
			$valores .= ", 1";
		else
			$valores .= ", NULL";
		$valores .= ")";
	}
	if ($valores)
		$consulta .= $valores.";";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$op_result["graba preserie"]=($ejecutar_consulta)?1:0;
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
		$mensaje="Se actualizó la serie  <b>$modalidad - $categoria</b> :)";
		include ("preserie-limpia-session.php");
		header("Location: ..?op=php/preseries-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo&mensaje=$mensaje");
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
header("Location: ..?op=php/edita-preserie-competencia.php&mensaje=$mensaje");
?>