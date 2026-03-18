<?php 
include("funciones.php");
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$llamo=isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):NULL;
if (!is_null($llamo))
	$llamo=str_replace("*", "&", $llamo);

session_start();
if (isset($_POST["regresar_sbm"])){
	unset($_SESSION["ubicacion"]);
	unset($_SESSION["password"]);
	unset($_SESSION["calificacion"]);
	unset($_SESSION["calificar"]);
	unset($_SESSION["proteger"]);
	header("Location: $llamo");
	exit();
}
$origen=isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL;
$ubicacion=isset($_POST["ubicacion_slc"])?$_POST["ubicacion_slc"]:NULL;
if (!$ubicacion) 
	$ubicacion=isset($_POST["ubicacion_hdn"])?$_POST["ubicacion_hdn"]:NULL;
$password=isset($_POST["password_psw"])?$_POST["password_psw"]:NULL;
$protegido=($password!='32')?TRUE:FALSE;

$_SESSION["protegido"] =isset($protegido)?$protegido:NULL;
$_SESSION["ubicacion"] =isset($ubicacion)?$ubicacion:NULL;
$btn_calificar=isset($_POST["calificar_sbm"])?$_POST["calificar_sbm"]:NULL;
$cero=isset($_POST["cero_sbm"])?$_POST["cero_sbm"]:NULL;
$uno=isset($_POST["uno_sbm"])?$_POST["uno_sbm"]:NULL;
$dos=isset($_POST["dos_sbm"])?$_POST["dos_sbm"]:NULL;
$tres=isset($_POST["tres_sbm"])?$_POST["tres_sbm"]:NULL;
$cuatro=isset($_POST["cuatro_sbm"])?$_POST["cuatro_sbm"]:NULL;
$cinco=isset($_POST["cinco_sbm"])?$_POST["cinco_sbm"]:NULL;
$seis=isset($_POST["seis_sbm"])?$_POST["seis_sbm"]:NULL;
$siete=isset($_POST["siete_sbm"])?$_POST["siete_sbm"]:NULL;
$ocho=isset($_POST["ocho_sbm"])?$_POST["ocho_sbm"]:NULL;
$nueve=isset($_POST["nueve_sbm"])?$_POST["nueve_sbm"]:NULL;
$diez=isset($_POST["diez_sbm"])?$_POST["diez_sbm"]:NULL;
$medio=isset($_POST["medio_sbm"])?$_POST["medio_sbm"]:NULL;
$uno_medio=isset($_POST["uno_medio_sbm"])?$_POST["uno_medio_sbm"]:NULL;
$dos_medio=isset($_POST["dos_medio_sbm"])?$_POST["dos_medio_sbm"]:NULL;
$tres_medio=isset($_POST["tres_medio_sbm"])?$_POST["tres_medio_sbm"]:NULL;
$cuatro_medio=isset($_POST["cuatro_medio_sbm"])?$_POST["cuatro_medio_sbm"]:NULL;
$cinco_medio=isset($_POST["cinco_medio_sbm"])?$_POST["cinco_medio_sbm"]:NULL;
$seis_medio=isset($_POST["seis_medio_sbm"])?$_POST["seis_medio_sbm"]:NULL;
$siete_medio=isset($_POST["siete_medio_sbm"])?$_POST["siete_medio_sbm"]:NULL;
$ocho_medio=isset($_POST["ocho_medio_sbm"])?$_POST["ocho_medio_sbm"]:NULL;
$nueve_medio=isset($_POST["nueve_medio_sbm"])?$_POST["nueve_medio_sbm"]:NULL;

$calificado=($cero or $uno or $dos or $tres	or $cuatro	or $cinco	or $seis	or $siete	or $ocho	or $nueve	or $diez or $medio or $uno_medio or $dos_medio or $tres_medio	or $cuatro_medio	or $cinco_medio	or $seis_medio	or $siete_medio	or $ocho_medio	or $nueve_medio)?TRUE:FALSE;

if ($cero) $calificacion=0;
if ($uno) $calificacion=1;
if ($dos) $calificacion=2;
if ($tres) $calificacion=3;
if ($cuatro) $calificacion=4;
if ($cinco) $calificacion=5;
if ($seis) $calificacion=6;
if ($siete) $calificacion=7;
if ($ocho) $calificacion=8;
if ($nueve) $calificacion=9;
if ($diez) $calificacion=10;
if ($medio) $calificacion=0.5;
if ($uno_medio) $calificacion=1.5;
if ($dos_medio) $calificacion=2.5;
if ($tres_medio) $calificacion=3.5;
if ($cuatro_medio) $calificacion=4.5;
if ($cinco_medio) $calificacion=5.5;
if ($seis_medio) $calificacion=6.5;
if ($siete_medio) $calificacion=7.5;
if ($ocho_medio) $calificacion=8.5;
if ($nueve_medio) $calificacion=9.5;


if ($btn_calificar){
	if (isset($_SESSION['proteger']))
		unset($_SESSION['proteger']);
}
else
	include("calificar-graba.php");


header("Location: ?op=php/calificar.php&com=$competencia&cco=$cod_competencia");
exit();
?>