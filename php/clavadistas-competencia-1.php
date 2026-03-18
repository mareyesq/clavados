<?php 
session_start();
include_once("funciones.php");

$competencia=isset($_POST['competencia_hdn'])?$_POST['competencia_hdn']:NULL;
$cod_competencia=isset($_POST['cod_competencia_hdn'])?$_POST['cod_competencia_hdn']:NULL;
$equipo=isset($_POST['equipo_hdn'])?$_POST['equipo_hdn']:NULL;
$corto=isset($_POST['corto_hdn'])?$_POST['corto_hdn']:NULL;
$llamo=isset($_POST['llamo_hdn'])?$_POST['llamo_hdn']:NULL;

$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
$btn_buscar=(isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:null);
$btn_individual=(isset($_POST["individual_btn"])?$_POST["individual_btn"]:null);
$btn_pareja=(isset($_POST["pareja_btn"])?$_POST["pareja_btn"]:null);
$btn_equipo_juv=(isset($_POST["equipo_juv_btn"])?$_POST["equipo_juv_btn"]:null);
if ($btn_buscar) {
	header("Location: ?op=php/clavadistas-competencia.php&com=$competencia&eq=$equipo&cor=$corto&ori=equipos-competencia.php&bus=$buscar");
	exit();
}

$btn_limpiar_filtros=(isset($_POST["limpiar_filtros_sbm"])?$_POST["limpiar_filtros_sbm"]:null);
$btn_regresar=(isset($_POST["regresar_sbm"])?$_POST["regresar_sbm"]:null);
if ($btn_limpiar_filtros) {
	unset($_SESSION["clavadistas-competencia"]);
	header("Location: ..?op=php/clavadistas-competencia.php");
	exit();
}

$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;

$temp=isset($_SESSION['clavadistas_competencia'])?$_SESSION['clavadistas_competencia']:NULL;

if (!$competencia) {
	$competencia=isset($temp['competencia'])?$temp['competencia']:NULL;
}
if (!$cod_competencia) {
	$cod_competencia=isset($temp['cod_competencia'])?$temp['cod_competencia']:NULL;
}
if (!$equipo) {
	$equipo=isset($temp['equipo'])?$temp['equipo']:NULL;
}
if (!$corto) {
	$corto=isset($temp['corto'])?$temp['corto']:NULL;
}

$entrenador_sel=isset($_POST['entrenador_sel_slc'])?$_POST['entrenador_sel_slc']:NULL;
$categoria_sel=isset($_POST['categoria_sel_slc'])?$_POST['categoria_sel_slc']:NULL;
$modalidad_sel=isset($_POST['modalidad_sel_slc'])?$_POST['modalidad_sel_slc']:NULL;

echo "<br>entrenador_sel: $entrenador_sel";

if ($entrenador_sel) {
	$temp['entrenador_sel']=$entrenador_sel;
} else {
	$temp['entrenador_sel']=NULL;
}
if ($categoria_sel) {
	$temp['categoria_sel']=$categoria_sel;
} else {
	$temp['categoria_sel']=NULL;
}
if ($modalidad_sel) {
	$temp['modalidad_sel']=$modalidad_sel;
} else {
	$temp['modalidad_sel']=NULL;
}

echo "<br>temp:<br>";print_r($temp);

$_SESSION["clavadistas_competencia"]=$temp;

if (isset($btn_nuevo)) {
	include("clavadistas-competencia-limpia-session.php");
	agrega_llamados("php/clavadistas-competencia-alta.php","php/clavadistas-competencia.php");
	header("Location: ..?op=php/clavadistas-competencia-alta.php");
	exit();
}
if ($btn_regresar){
	$llamo=llamo_a('clavadistas-competencia.php','php/inicial.php');
	header("Location: ..?op=$llamo");
	exit();
}
if ($pag) {
	header("Location: ..?op=php/clavadistas-competencia.php&bus=$buscar&pg=$pag");
	exit();
} 

header("Location: ..?op=php/clavadistas-competencia.php");
exit();

?>