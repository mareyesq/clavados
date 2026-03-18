<?php 	
$btn_nuevo=(isset($_POST["nuevo_sbm"])?$_POST["nuevo_sbm"]:null);
$btn_regresar=(isset($_POST["regresar_sbm"])?$_POST["regresar_sbm"]:null);

$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);

$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);

$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null);

if (isset($btn_regresar)) {
	header("Location: ?op=php/muestra-competencia.php&cco=$cod_competencia&com=$competencia&lg=$logo");
	exit();
}

if (isset($btn_nuevo)) {
	header("Location: ?op=php/alta-preserie-competencia.php&cco=$cod_competencia&com=$competencia&lg=$logo");
	exit();
}

header("Location: ?op=php/preseries-competencia.php");
exit();

?>