<?php 

	$btn_buscar=(isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:null);
	$btn_individual=(isset($_POST["individual_btn"])?$_POST["individual_btn"]:null);
	$btn_pareja=(isset($_POST["pareja_btn"])?$_POST["pareja_btn"]:null);
	$btn_equipo_juv=(isset($_POST["equipo_juv_btn"])?$_POST["equipo_juv_btn"]:null);
	$equipo=(isset($_POST["equipo_hdn"])?$_POST["equipo_hdn"]:null);
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null);
	$llamo=(isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:null);
	$imagen=(isset($_POST["imagen_hdn"])?$_POST["imagen_hdn"]:null);
	$imagen2=(isset($_POST["imagen2_hdn"])?$_POST["imagen2_hdn"]:null);
	$imagen3=(isset($_POST["imagen3_hdn"])?$_POST["imagen3_hdn"]:null);
	$imagen4=(isset($_POST["imagen4_hdn"])?$_POST["imagen4_hdn"]:null);
	if (isset($btn_buscar)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/clavadistas-competencia.php&com=$competencia&eq=$equipo&cor=$equipo&ori=equipos-competencia.php&bus=$buscar");
		exit();
	}
	if (isset($_POST["regresar_sbm"])){
		header("Location: ?op=php/equipos-competencia.php&com=$	competencia&cco=$cod_competencia&lg=$logo");
		exit();
	}
	$entrenador_sel=isset($_POST['entrenador_sel_slc'])?$_POST['entrenador_sel_slc']:NULL;

	if ($entrenador_sel)
		$_SESSION['entrenador_sel']=$entrenador_sel;
	else
		unset($_SESSION['entrenador_sel']);
	if (isset($btn_buscar)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/clavadistas-competencia.php&com=$competencia&eq=$equipo&cor=$corto&ori=equipos-competencia.php&bus=$buscar");
		exit();
	}

	if (isset($btn_individual)){
		$individual=1;
		$pareja=0;
		$eq_juv=0;
	}
	if (isset($btn_pareja)){
		$pareja=1;
		$individual=0;
		$eq_juv=0;
	}
	if (isset($btn_equipo_juv)){
		$pareja=0;
		$individual=0;
		$eq_juv=1;
	}

header("Location: ?op=php/clavadistas-competencia.php&com=$competencia&eq=$equipo&cor=$corto&ori=equipos-competencia.php&bus=$buscar");
exit();

 ?>