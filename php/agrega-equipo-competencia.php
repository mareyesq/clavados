<?php 
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL;
if (isset($_POST["regresar_sbm"])){
	header("Location: ..?op=php/equipos-competencia.php&com=$competencia&cco=$cod_competencia");
	return;
}
$equipo=(isset($_POST["equipo_txt"])?$_POST["equipo_txt"]:null);
$corto=(isset($_POST["corto_txt"])?$_POST["corto_txt"]:null);
$representante=isset($_POST["representante_txt"])?$_POST["representante_txt"]:NULL;
$email=isset($_POST["email_txt"])?$_POST["email_txt"]:NULL;
$telefono=isset($_POST["telefono_txt"])?$_POST["telefono_txt"]:NULL;
$password=isset($_POST["password_txt"])?$_POST["password_txt"]:NULL;
$password_conf=isset($_POST["password_conf_txt"])?$_POST["password_conf_txt"]:NULL;
$bandera=isset($_POST["bandera_slc"])?$_POST["bandera_slc"]:NULL;
$alta=isset($_POST["alta_hdn"])?$_POST["alta_hdn"]:NULL;

$logo=isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:NULL;

session_start();
include("funciones.php");
include("valida-equipo-competencia.php");

if (is_null($mensaje)){
	$conexion=conectarse();
	$consulta="SELECT ciudad FROM competencias WHERE (cod_competencia=$cod_competencia)";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$registro=$ejecutar_consulta->fetch_assoc();
	$cod_ciudad=$registro["ciudad"];
	$ahora=ahora($cod_ciudad,$conexion);
	$password=equivalencia($password);
	$consulta="INSERT INTO competenciasq (competencia, nombre_corto, equipo, representante, email, telefono, clave_inscripciones, fecha_alta, usuario_alta";
	if ($bandera)
		$consulta.= ", bandera";
	$consulta.=") VALUES ($cod_competencia, '$corto', '$equipo', '$representante', '$email', '$telefono', '$password', '$ahora', $usuario";
	if ($bandera)
		$consulta.= ", $bandera";
	$consulta.=")";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	
	if ($ejecutar_consulta){
		$_SESSION["equipo"]=NULL;
		$_SESSION["corto"]=NULL;
		$_SESSION["representante"]=NULL;
		$_SESSION["email"]=NULL;
		$_SESSION["telefono"]=NULL;
		$_SESSION["password"]=NULL;
		$_SESSION["password_conf"]=NULL;
		$mensaje="Quedó inscrito el equipo <b>$equipo</b> en la competencia :)";
		$conexion->close();
		header("Location: ..?op=php/equipos-competencia.php&cco=$cod_competencia&mensaje=$mensaje");
		exit();
	}
	else
		$mensaje="No se pudo inscribir al equipo <b>$equipo</b> en la competencia :(";
}	
header("Location: ..?op=php/alta-equipo-competencia.php&mensaje=$mensaje");

?>