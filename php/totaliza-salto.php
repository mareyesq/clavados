<?php 
	$_SESSION["err"]="";
	$cal=array();
	$cal[1]=isset($_POST["cal1"])?$_POST["cal1"]:NULL;
	$cal[2]=isset($_POST["cal2"])?$_POST["cal2"]:NULL;
	$cal[3]=isset($_POST["cal3"])?$_POST["cal3"]:NULL;
	$cal[4]=isset($_POST["cal4"])?$_POST["cal4"]:NULL;
	$cal[5]=isset($_POST["cal5"])?$_POST["cal5"]:NULL;
	$cal[6]=isset($_POST["cal6"])?$_POST["cal6"]:NULL;
	$cal[7]=isset($_POST["cal7"])?$_POST["cal7"]:NULL;
	$cal[8]=isset($_POST["cal8"])?$_POST["cal8"]:NULL;
	$cal[9]=isset($_POST["cal9"])?$_POST["cal9"]:NULL;
	$cal[10]=isset($_POST["cal10"])?$_POST["cal10"]:NULL;
	$cal[11]=isset($_POST["cal11"])?$_POST["cal11"]:NULL;
	if ($salto_fallado) 
		for ($i=1; $i < $num_jueces+1; $i++) 
			$cal[$i]=0;

	if ($iguales) {
		$j=1;
		while (is_numeric($cal[$j]) and $j<=$num_jueces){
			$ultima=$cal[$j];
			$j++;
		}
		for ($i=$j; $i < $num_jueces+1; $i++)
			$cal[$i]=$ultima;
	}

	for ($i=1; $i < $num_jueces+1; $i++) { 
		if ($cal[$i]==11) $cal[$i]=1.5; 
		if ($cal[$i]==22) $cal[$i]=2.5; 
		if ($cal[$i]==33) $cal[$i]=3.5; 
		if ($cal[$i]==44) $cal[$i]=4.5; 
		if ($cal[$i]==55) $cal[$i]=5.5; 
		if ($cal[$i]==66) $cal[$i]=6.5; 
		if ($cal[$i]==77) $cal[$i]=7.5; 
		if ($cal[$i]==88) $cal[$i]=8.5; 
		if ($cal[$i]==99) $cal[$i]=9.5; 
	}
	$_SESSION["cal"] =$cal;
	
	for ($i=1; $i <= $num_jueces; $i++) { 
		if (!is_numeric($cal[$i])){
			$calif=$cal[$i];
			$mensaje="Faltan calificaciones de jueces :(";
			$_SESSION["err"]=1;
			return;
		}
		if ($cal[$i]>10) {
			$mensaje="Error: verifique la calificación del juez número $i :(";
			$_SESSION["err"]=1;
			return;
		}
	}

	$penalidad=isset($_SESSION["penalidad_ss"])?$_SESSION["penalidad_ss"]:NULL;
	$sincronizado=isset($_POST["sincronizado_hdn"])?trim($_POST["sincronizado_hdn"]):NULL;
		
	$cal_cop=$cal;
	if (!is_null($penalidad))
		for ($i=1; $i <$num_jueces+1 ; $i++) { 
			$cal_cop[$i] -=$penalidad;
		}

	$grado_dificultad=isset($_POST["grado_dif_hdn"])?$_POST["grado_dif_hdn"]:NULL;

	$res=suma($cal_cop,$sincronizado,$num_jueces);
	$suma=$res["suma"];
	$t_salto=$res["puntaje"];
	$p_salto=$t_salto*$grado_dificultad;
	$jueces=$_SESSION["jueces"];

	$categoria=isset($_POST["categoria_hdn"])?$_POST["categoria_hdn"]:NULL;
	$sexo=isset($_POST["sexo_hdn"])?$_POST["sexo_hdn"]:NULL;
	$modalidad=isset($_POST["modalidad_hdn"])?$_POST["modalidad_hdn"]:NULL;

	$conexion=conectarse();
	$consulta="SELECT saltos_obl, saltos_lib 
			FROM series 
			WHERE categoria='$categoria' and sexo='$sexo' and modalidad='$modalidad'";
	$ejecutar_consulta = $conexion->query($consulta);

	if ($ejecutar_consulta){
		$row=$ejecutar_consulta->fetch_assoc();
		$saltos_categoria=$row["saltos_obl"]+$row["saltos_lib"];
	}

	$consulta="SELECT ciudad FROM competencias WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);
	if ($ejecutar_consulta){
		$row=$ejecutar_consulta->fetch_assoc();
		$cod_ciudad=$row["ciudad"];
	}

	$ahora=ahora($cod_ciudad);
	$cod_planilla=isset($_POST["cod_planilla_hdn"])?$_POST["cod_planilla_hdn"]:NULL;
	$ronda=isset($_POST["ronda_hdn"])?$_POST["ronda_hdn"]:NULL;
	$turno=isset($_POST["turno_hdn"])?$_POST["turno_hdn"]:NULL;
	$ejecutor=isset($_POST["ejecutor_hdn"])?$_POST["ejecutor_hdn"]:NULL;
	$orden_salida=isset($_POST["orden_salida_hdn"])?$_POST["orden_salida_hdn"]:NULL;
	$consulta="UPDATE planillad
		SET suma=$suma,
			total_salto=$t_salto,
			puntaje_salto=$p_salto,
			hora_salto='$ahora',
			calificado=1";
	if ($penalidad>0) 
		$consulta .= ", penalizado=$penalidad";
	if ($jueces[1]>0) 
		$consulta .= ", juez1=$jueces[1], cal1=$cal[1]";
	if ($jueces[2]>0) 
		$consulta .= ", juez2=$jueces[2], cal2=$cal[2]";
	if ($jueces[3]>0) 
		$consulta .= ", juez3=$jueces[3], cal3=$cal[3]";
	if ($jueces[4]>0) 
		$consulta .= ", juez4=$jueces[4], cal4=$cal[4]";
	if ($jueces[5]>0) 
		$consulta .= ", juez5=$jueces[5], cal5=$cal[5]";
	if ($jueces[6]>0) 
		$consulta .= ", juez6=$jueces[6], cal6=$cal[6]";
	if ($jueces[7]>0) 
		$consulta .= ", juez7=$jueces[7], cal7=$cal[7]";
	if ($jueces[8]>0) 
		$consulta .= ", juez8=$jueces[8], cal8=$cal[8]";
	if ($jueces[9]>0) 
		$consulta .= ", juez9=$jueces[9], cal9=$cal[9]";
	if ($jueces[10]>0) 
		$consulta .= ", juez10=$jueces[10], cal10=$cal[10]";
	if ($jueces[11]>0) 
		$consulta .= ", juez11=$jueces[11], cal11=$cal[11]";
	$consulta .=" WHERE planilla=$cod_planilla and ronda=$ronda";
	if ($ejecutor)
		$consulta .= " and ejecutor=$ejecutor";
	$ejecutar_consulta = $conexion->query($consulta);

	if (!$ejecutar_consulta){
		$mensaje="Error: no se actualizó el salto con el puntaje :(";
		$_SESSION["err"]=1;
		$conexion->close();
		exit();
	}

//	envia_calificaciones($cod_planilla,$ronda,$correo);

	include("ordena-posiciones.php");
	include('registra-calificaciones-sombra.php');
	include('limpia-calificaciones.php');
	$conexion->close();

$_SESSION["edita"]=0;
$_SESSION["turno"]=$turno;
$_SESSION["ejecutor"]=$ejecutor;
$_SESSION["orden_salida"]=$orden_salida;
?>