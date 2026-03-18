<?php 
	$btn_buscar=(isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:null);
	$llamo=(isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):null);
	$ronda=(isset($_POST["ronda_hdn"])?$_POST["ronda_hdn"]:null);
	$nom_salto=(isset($_POST["salto_hdn"])?$_POST["salto_hdn"]:null);
	if (isset($btn_buscar)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/busca-salto.php&bus=$buscar&ron=$ronda&ori=$llamo");
		exit();
	}

	if (isset($_POST["regresar_sbm"])){
		if (strlen($llamo)==0)
			$llamo="todas-competencias.php";
		header("Location: ?op=php/$llamo&com=$competencia");
		exit();
	}

?>