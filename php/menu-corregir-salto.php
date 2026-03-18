<?php 
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
include("funciones.php");

$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);

$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$id_planilla=(isset($_POST["id_planilla_hdn"])?$_POST["id_planilla_hdn"]:null);
$evento=(isset($_POST["evento_hdn"])?$_POST["evento_hdn"]:null);
$origen="?op=php/corregir-salto.php&pla=$id_planilla&rd=$ronda";
session_start();
if (isset($_POST["regresar_sbm"])){
	unset($_SESSION["cod_competencia_ss"]);
	unset($_SESSION["competencia_ss"]);
	unset($_SESSION["evento_ss"]);
	unset($_SESSION["ronda_ss"]);
	$transfer="php/calificaciones.php?pla=$id_planilla";
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header("Location: $transfer");
	exit();
}

$nom_cla=isset($_POST["nom_cla_hdn"])?trim($_POST["nom_cla_hdn"]):NULL;
$_SESSION["nom_cla_ss"]=$nom_cla;

$sincronizado=isset($_POST["sincronizado_hdn"])?trim($_POST["sincronizado_hdn"]):NULL;
$_SESSION["sincronizado_ss"]=$sincronizado;

$modalidad=isset($_POST["modalidad_hdn"])?trim($_POST["modalidad_hdn"]):NULL;
$_SESSION["modalidad_ss"]=$modalidad;
$categoria=isset($_POST["categoria_hdn"])?trim($_POST["categoria_hdn"]):NULL;
$_SESSION["categoria_ss"]=$categoria;
$sexo=isset($_POST["sexo_hdn"])?trim($_POST["sexo_hdn"]):NULL;
$_SESSION["sexo_ss"]=$sexo;

$ronda=isset($_POST["ronda_hdn"])?trim($_POST["ronda_hdn"]):NULL;
$_SESSION["ronda_ss"]=$ronda;

$salto=isset($_POST["salto_hdn"])?trim($_POST["salto_hdn"]):NULL;
$_SESSION["salto_ss"]=$salto;

$posicion=isset($_POST["posicion_hdn"])?trim($_POST["posicion_hdn"]):NULL;
$_SESSION["posicion_ss"]=$posicion;

$altura=isset($_POST["altura_hdn"])?trim($_POST["altura_hdn"]):NULL;
$_SESSION["altura_ss"]=$altura;

$grado_dif=isset($_POST["grado_dif_nbr"])?trim($_POST["grado_dif_nbr"]):NULL;
$_SESSION["grado_dif_ss"]=$grado_dif;

$abierto=isset($_POST["abierto_hdn"])?trim($_POST["abierto_hdn"]):NULL;
$_SESSION["abierto_ss"]=$abierto;

$penalidad=isset($_POST["penalidad_hdn"])?trim($_POST["penalidad_hdn"]):NULL;
$_SESSION["penalidad_ss"]=$penalidad;

$total_salto=isset($_POST["total_salto_hdn"])?trim($_POST["total_salto_hdn"]):NULL;
$_SESSION["total_salto_ss"]=$total_salto;

$puntaje_salto=isset($_POST["puntaje_salto_hdn"])?trim($_POST["puntaje_salto_hdn"]):NULL;
$_SESSION["puntaje_salto_ss"]=$puntaje_salto;

$cal1=isset($_POST["cal1"])?trim($_POST["cal1"]):NULL;
$cal2=isset($_POST["cal2"])?trim($_POST["cal2"]):NULL;
$cal3=isset($_POST["cal3"])?trim($_POST["cal3"]):NULL;
$cal4=isset($_POST["cal4"])?trim($_POST["cal4"]):NULL;
$cal5=isset($_POST["cal5"])?trim($_POST["cal5"]):NULL;
$cal6=isset($_POST["cal6"])?trim($_POST["cal6"]):NULL;
$cal7=isset($_POST["cal7"])?trim($_POST["cal7"]):NULL;
$cal8=isset($_POST["cal8"])?trim($_POST["cal8"]):NULL;
$cal9=isset($_POST["cal9"])?trim($_POST["cal9"]):NULL;
$cal10=isset($_POST["cal10"])?trim($_POST["cal10"]):NULL;
$cal11=isset($_POST["cal11"])?trim($_POST["cal11"]):NULL;

$_SESSION["cal1_ss"]=$cal1;
$_SESSION["cal2_ss"]=$cal2;
$_SESSION["cal3_ss"]=$cal3;
$_SESSION["cal4_ss"]=$cal4;
$_SESSION["cal5_ss"]=$cal5;
$_SESSION["cal6_ss"]=$cal6;
$_SESSION["cal7_ss"]=$cal7;
$_SESSION["cal8_ss"]=$cal8;
$_SESSION["cal9_ss"]=$cal9;
$_SESSION["cal10_ss"]=$cal10;
$_SESSION["cal11_ss"]=$cal11;

$cod_usuario=$_SESSION["usuario_id"];

if (isset($_POST["totaliza_sbm"])){
	include("totaliza-corregir-salto.php");
	$transfer="Location: php/calificaciones.php?pla=$id_planilla";
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["penalizar_sbm"])){
	$_SESSION["penalidad_ss"]=2;
	$_SESSION["edita_ss"]=1;
	$_SESSION["ronda_ss"]=$_POST["ronda_hdn"];
	$transfer="Location: $origen";
	$_SESSION["err_ss"]=1;
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["despenalizar_sbm"])){
	$_SESSION["penalidad_ss"]="";
	$_SESSION["edita_ss"]=1;
	$_SESSION["ronda_ss"]=$_POST["ronda_hdn"];
	$transfer="Location: $origen";
	$_SESSION["err"]=1;
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}
?>