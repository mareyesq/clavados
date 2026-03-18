<?php 
	$btn_buscar=(isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:null);
	$btn_nuevo=(isset($_POST["nuevo_sbm"])?$_POST["nuevo_sbm"]:null);
	$llamo=(isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:null);
	$tipo=(isset($_POST["tipo_hdn"])?$_POST["tipo_hdn"]:null);
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	if (isset($btn_buscar)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/busca-equipo.php&bus=$buscar&ori=$llamo&comp=$competencia");
		return;
	}

	if (isset($btn_nuevo)) {
		header("Location: ?op=php/alta-equipo.php&ori=$origen");
		return;
	}
	if (isset($_POST["regresar_sbm"])){
		header("Location: ?op=$llamo&com=$competencia");
		return;
	}

?>