<?php 
include("funciones.php");

//if (isset($_POST["actualizar_sbm"])){
	$origen=isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL;
	if ($origen) 
		$origen=str_replace("*", "&", $origen);
	
	$transfer="Location: ".$origen;
	header($transfer);
	exit();
//}

?>