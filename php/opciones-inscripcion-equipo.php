<?php 
session_start();
$regresar=isset($_POST["regresar_sbm"])?$_POST["regresar_sbm"]:NULL;

$buscar_equipo=(isset($_POST["buscar_equipo_btn"])?$_POST["buscar_equipo_btn"]:null);

$nuevo_equipo=(isset($_POST["nuevo_equipo_btn"])?$_POST["nuevo_equipo_btn"]:null);

$inscribir=(isset($_POST["inscribir_equipo_btn"])?$_POST["inscribir_equipo_btn"]:null);

$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);

if(isset($regresar)){
	header("Location: ..?op=php/equipos-competencia.php&com=$competencia");
	return;
}

$equipo=(isset($_POST["equipo_txt"])?$_POST["equipo_txt"]:null);

include("conexion.php");
include("funciones.php");
if ($equipo and $competencia) {
	if (equipo_inscrito($competencia,$equipo,$conexion)) {
		$mensaje="Ya está inscrito el equipo $equipo en la competencia $competencia";
		header("Location: ../index.php?op=php/muestra-competencia.php&mensaje=$mensaje&com=$competencia");
		return;
	}
}
if(isset($buscar_equipo)){
	if (!isset($_POST["equipo_txt"])) {
		$mensaje="debe ingresar el nombre del Equipo a buscar";
	}
	else{
		$_SESSION["equipo"]=$equipo;
		header("Location: ../index.php?op=php/busca-equipo.php");
		return;
	}
}
if(isset($nuevo_equipo)){
	$_SESSION["equipo"]=$equipo;
	header("Location: ../index.php?op=php/alta-equipo.php");
	return;
}
if(isset($inscribir)){
	$_SESSION["equipo"]=$equipo;
	include("agrega-inscripcion-equipo.php");
	header("Location: ../index.php?op=php/muestra-competencia.php&mensaje=$mensaje&com=$competencia");
		return;
}
?>