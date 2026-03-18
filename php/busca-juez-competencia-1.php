<?php 
	$btn_buscar=(isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:null);
	$btn_buscar_todos=(isset($_POST["buscar_todos_sbm"])?$_POST["buscar_todos_sbm"]:null);
	$btn_nuevo=(isset($_POST["nuevo_sbm"])?$_POST["nuevo_sbm"]:null);
	$llamo=(isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):null);
	$tipo=(isset($_POST["tipo_hdn"])?$_POST["tipo_hdn"]:null);
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	session_start();
	$origen=isset($_POST["llamador_hdn"])?$_POST["llamador_hdn"]:NULL;
	
	if (!isset($llamo))
		$llamo=isset($_SESSION["llamo"])?trim($_SESSION["llamo"]):NULL;

	if (isset($btn_buscar)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/busca-usuario.php&bus=$buscar&ori=$llamo&comp=$competencia&tipo=$tipo");
		exit();
	}

	if (isset($btn_buscar_todos)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/busca-usuario.php&bus=$buscar&ori=$llamo&comp=$competencia&tipo=$tipo&t=todos");
		exit();
	}
	
	if (isset($btn_nuevo)) {
		header("Location: ?op=php/alta-usuario.php&bus=$administrador&ori=$origen&tipo=$tipo");
		exit();
	}
	if (isset($_POST["regresar_sbm"])){
		if (strlen($llamo)==0)
			$llamo="php/todas-competencias.php";
		header("Location: ?op=$llamo&com=$competencia");
		exit();
	}

?>