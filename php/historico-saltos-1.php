<?php 
session_start();

$btn_sin_filtro=(isset($_POST["sin_filtros_sbm"])?$_POST["sin_filtros_sbm"]:null);
if (isset($btn_sin_filtro)) {
	unset($_SESSION["salto_sel"]);
	unset($_SESSION["posicion_sel"]);
	unset($_SESSION["altura_sel"]);
	unset($_SESSION["sexo_sel"]);
	unset($_SESSION["clavadista_sel"]);
	header("Location: ?op=php/historico-saltos.php");
	exit();
}

$btn_buscar=(isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:null);
$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
$salto_sel=isset($_POST["salto_sel_slc"]) ? $_POST["salto_sel_slc"] : null;
$posicion_sel=isset($_POST["posicion_sel_slc"]) ? $_POST["posicion_sel_slc"] : null;
$altura_sel=isset($_POST["altura_sel_slc"]) ? $_POST["altura_sel_slc"] : null;
$sexo_sel=isset($_POST["sexo_sel_slc"]) ? $_POST["sexo_sel_slc"] : null;
$clavadista_sel=isset($_POST["clavadista_hdn"]) ? $_POST["clavadista_hdn"] : null;


$_SESSION["salto_sel"]=isset($salto_sel)?$salto_sel:NULL;
$_SESSION["posicion_sel"]=isset($posicion_sel)?$posicion_sel:NULL;
$_SESSION["altura_sel"]=isset($altura_sel)?$altura_sel:NULL;
$_SESSION["sexo_sel"]=isset($sexo_sel)?$sexo_sel:NULL;
$_SESSION["buscar"]=isset($buscar)?$buscar:NULL;
$_SESSION["clavadista_sel"]=isset($clavadista_sel)?$clavadista_sel:NULL;

if (isset($btn_buscar)) {
	$llamo="php/historico-saltos.php";
	header("Location: ?op=php/busca-usuario.php&bus=$buscar&ori=$llamo&tipo='C'");
	exit();
}

header("Location: ?op=php/historico-saltos.php");
exit();
 ?>
