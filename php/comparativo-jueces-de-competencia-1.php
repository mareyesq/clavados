<?php 
include("funciones.php");
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);

session_start();
if (isset($_POST["regresar_sbm"])){
	header("Location: ?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia");
	exit();
}

?>