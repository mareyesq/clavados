<?php 
	$btn_buscar=(isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:null);
	$btn_nuevo=(isset($_POST["nuevo_sbm"])?$_POST["nuevo_sbm"]:null);
	$llamo=(isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):null);
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	session_start();
	$origen=isset($_POST["llamador_hdn"])?$_POST["llamador_hdn"]:NULL;
	$parael1=isset($_SESSION["parael1"])?$_SESSION["parael1"]:NULL;
	$parael2=isset($_SESSION["parael2"])?$_SESSION["parael2"]:NULL;
	$parael3=isset($_SESSION["parael3"])?$_SESSION["parael3"]:NULL;
	$parael4=isset($_SESSION["parael4"])?$_SESSION["parael4"]:NULL;
	$tipo=isset($_SESSION["tipo"])?$_SESSION["tipo"]:NULL;
	if (!isset($llamo))
		$llamo=isset($_SESSION["llamo"])?trim($_SESSION["llamo"]):NULL;

	$pais_sel=isset($_POST["pais_sel_slc"])?$_POST["pais_sel_slc"]:NULL;
	$tipo_sel=isset($_POST["tipo_sel_slc"])?$_POST["tipo_sel_slc"]:NULL;

	if (!$pais_sel)
		unset($_SESSION["pais_sel"]);
	else
		$_SESSION["pais_sel"]=$pais_sel;

	if (!$tipo_sel)
		unset($_SESSION["tipo_sel"]);
	else
		$_SESSION["tipo_sel"]=$tipo_sel;

	if (isset($btn_buscar)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/busca-usuario.php&bus=$buscar&ori=$llamo&comp=$competencia&parael1=$parael1&parael2=$parael2&parael3=$parael3&parael4=$parael4");
		exit();
	}
	
	if (isset($btn_nuevo)) {
		header("Location: ?op=php/alta-usuario.php&bus=$administrador&ori=$origen&tipo=$tipo");
		exit();
	}
	if (isset($_POST["regresar_sbm"])){
		unset($_SESSION["parael1"]);
		unset($_SESSION["parael2"]);
		unset($_SESSION["parael3"]);
		unset($_SESSION["parael4"]);
		unset($_SESSION["tipo"]);
		if (strlen($llamo)==0)
			$llamo="php/todas-competencias.php";
		header("Location: ?op=$llamo&com=$competencia");
		exit();
	}
header("Location: ?op=php/busca-usuario.php&bus=$buscar&ori=$llamo&comp=$competencia");
exit();

?>