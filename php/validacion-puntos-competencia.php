<?php 
	$competencia=isset($_POST["competencia_hdn"])?trim($_POST["competencia_hdn"]):NULL;
	$cod_competencia=isset($_POST["cod_competencia_hdn"])?trim($_POST["cod_competencia_hdn"]):NULL;

	$logo=isset($_POST["logo_hdn"])?trim($_POST["logo_hdn"]):NULL;

	$pue1=(isset($_POST["pue1_hdn"])?$_POST["pue1_hdn"]:null);
	$ind1=(isset($_POST["ind1_txt"])?$_POST["ind1_txt"]:0);
	$sin1=(isset($_POST["sin1_txt"])?$_POST["sin1_txt"]:0);

	$pue2=(isset($_POST["pue2_hdn"])?$_POST["pue2_hdn"]:null);
	$ind2=(isset($_POST["ind2_txt"])?$_POST["ind2_txt"]:0);
	$sin2=(isset($_POST["sin2_txt"])?$_POST["sin2_txt"]:0);

	$pue3=(isset($_POST["pue3_hdn"])?$_POST["pue3_hdn"]:null);
	$ind3=(isset($_POST["ind3_txt"])?$_POST["ind3_txt"]:0);
	$sin3=(isset($_POST["sin3_txt"])?$_POST["sin3_txt"]:0);

	$pue4=(isset($_POST["pue4_hdn"])?$_POST["pue4_hdn"]:null);
	$ind4=(isset($_POST["ind4_txt"])?$_POST["ind4_txt"]:0);
	$sin4=(isset($_POST["sin4_txt"])?$_POST["sin4_txt"]:0);

	$pue5=(isset($_POST["pue5_hdn"])?$_POST["pue5_hdn"]:null);
	$ind5=(isset($_POST["ind5_txt"])?$_POST["ind5_txt"]:0);
	$sin5=(isset($_POST["sin5_txt"])?$_POST["sin5_txt"]:0);

	$pue6=(isset($_POST["pue6_hdn"])?$_POST["pue6_hdn"]:null);
	$ind6=(isset($_POST["ind6_txt"])?$_POST["ind6_txt"]:0);
	$sin6=(isset($_POST["sin6_txt"])?$_POST["sin6_txt"]:0);

	$pue7=(isset($_POST["pue7_hdn"])?$_POST["pue7_hdn"]:null);
	$ind7=(isset($_POST["ind7_txt"])?$_POST["ind7_txt"]:0);
	$sin7=(isset($_POST["sin7_txt"])?$_POST["sin7_txt"]:0);

	$pue8=(isset($_POST["pue8_hdn"])?$_POST["pue8_hdn"]:null);
	$ind8=(isset($_POST["ind8_txt"])?$_POST["ind8_txt"]:0);
	$sin8=(isset($_POST["sin8_txt"])?$_POST["sin8_txt"]:0);

	$pue9=(isset($_POST["pue9_hdn"])?$_POST["pue9_hdn"]:null);
	$ind9=(isset($_POST["ind9_txt"])?$_POST["ind9_txt"]:0);
	$sin9=(isset($_POST["sin9_txt"])?$_POST["sin9_txt"]:0);

	$pue10=(isset($_POST["pue10_hdn"])?$_POST["pue10_hdn"]:null);
	$ind10=(isset($_POST["ind10_txt"])?$_POST["ind10_txt"]:0);
	$sin10=(isset($_POST["sin10_txt"])?$_POST["sin10_txt"]:0);

	$pue11=(isset($_POST["pue11_hdn"])?$_POST["pue11_hdn"]:null);
	$ind11=(isset($_POST["ind11_txt"])?$_POST["ind11_txt"]:0);
	$sin11=(isset($_POST["sin11_txt"])?$_POST["sin11_txt"]:0);


	$puntos=array();
	if (!is_null($pue1)) $puntos[1] = $pue1."/".$ind1."/".$sin1; 
	if (!is_null($pue2)) $puntos[2] = $pue2."/".$ind2."/".$sin2; 
	if (!is_null($pue3)) $puntos[3] = $pue3."/".$ind3."/".$sin3; 
	if (!is_null($pue4)) $puntos[4] = $pue4."/".$ind4."/".$sin4; 
	if (!is_null($pue5)) $puntos[5] = $pue5."/".$ind5."/".$sin5; 
	if (!is_null($pue6)) $puntos[6] = $pue6."/".$ind6."/".$sin6; 
	if (!is_null($pue7)) $puntos[7] = $pue7."/".$ind7."/".$sin7; 
	if (!is_null($pue8)) $puntos[8] = $pue8."/".$ind8."/".$sin8; 
	if (!is_null($pue9)) $puntos[9] = $pue9."/".$ind9."/".$sin9; 
	if (!is_null($pue10)) $puntos[10] = $pue10."/".$ind10."/".$sin10; 
	if (!is_null($pue11)) $puntos[11] = $pue11."/".$ind11."/".$sin11; 

	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	$_SESSION["logo"]=isset($logo)?$logo:NULL;
	$_SESSION["llamo"]=isset($llamo)?$llamo:NULL;
	$_SESSION["puntos"]=isset($puntos)?$puntos:NULL;

	if (isset($_POST["regla-fina_btn"])) {
		include ("regla-fina-puntos.php");
		$_SESSION["puntos"]=isset($puntos)?$puntos:NULL;
		header("Location: ..?op=php/puntos-competencia.php");
		exit();
	}

	$mensaje=NULL;
 ?>
