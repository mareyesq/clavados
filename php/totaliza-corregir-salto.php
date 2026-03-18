<?php 
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

$conexion=conectarse();
$consulta="SELECT DISTINCT panel, ubicacion, juez
	FROM competenciasz
	WHERE competencia=$cod_competencia and evento=$evento and panel=1
	ORDER BY ubicacion";
$ejecutar_consulta = $conexion->query($consulta);
$mensaje=NULL;
if ($ejecutar_consulta){
	$num_regs=$ejecutar_consulta->num_rows;
	if (!$num_regs)
		$mensaje="No hay jueces en Panel 1 registrados para este evento :(";
	else{
		$num_jueces=0;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$ub=$row["ubicacion"];
			if ($ub<25){
				$num_jueces++;
			} 
		}
	}
}
$conexion->close();

if (is_null($mensaje)) {
	$cal=array();
	$cal[1]=isset($cal1)?$cal1:NULL;
	$cal[2]=isset($cal2)?$cal2:NULL;
	$cal[3]=isset($cal3)?$cal3:NULL;
	$cal[4]=isset($cal4)?$cal4:NULL;
	$cal[5]=isset($cal5)?$cal5:NULL;
	$cal[6]=isset($cal6)?$cal6:NULL;
	$cal[7]=isset($cal7)?$cal7:NULL;
	$cal[8]=isset($cal8)?$cal8:NULL;
	$cal[9]=isset($cal9)?$cal9:NULL;
	$cal[10]=isset($cal10)?$cal10:NULL;
	$cal[11]=isset($cal11)?$cal11:NULL;

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

	$_SESSION["cal_ss"] =$cal;

	for ($i=1; $i <= $num_jueces; $i++) { 
		if (!is_numeric($cal[$i])){
			$mensaje="Faltan calificaciones de jueces :(";
			$_SESSION["err_ss"]=1;
			return;
		}
		if ($cal[$i]>10) {
			$mensaje="Error: verifique la calificación del juez número $i :(";
			$_SESSION["err_ss"]=1;
			return;
		}
	}
}

if (is_null($mensaje)) {
	$penalidad=isset($_POST["penalidad_hdn"])?$_POST["penalidad_hdn"]:NULL;
	$sincronizado=isset($_POST["sincronizado_hdn"])?trim($_POST["sincronizado_hdn"]):NULL;

	$cal_cop=$cal;
	if (!is_null($penalidad))
		for ($i=1; $i <$num_jueces+1 ; $i++) { 
			$cal_cop[$i] -=$penalidad;
		}

	$res=suma($cal_cop,$sincronizado,$num_jueces);
	$suma=$res["suma"];
	$t_salto=$res["puntaje"];
	$grado_dificultad=isset($_POST["grado_dif_nbr"])?$_POST["grado_dif_nbr"]:NULL;
	if (is_null($grado_dificultad)){
		$grado_dificultad=grado_dificultad($salto,$posicion,$altura,$conexion);
		if (substr($grado_dificultad,0,5)=="Error")
			$mensaje=$grado_dificultad;
		else
			$_SESSION["grado_dificultad_ss"]=$grado_dificultad;
	}
}
if (is_null($mensaje)) {
	$conexion=conectarse();
	$separa_extraoficiales=0;
	$consulta="SELECT max_2_competidores 
		FROM competencias 
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);

	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1) {
		$row=$ejecutar_consulta->fetch_assoc();
		$separa_extraoficiales=$row["max_2_competidores"];
	}

	$consulta="SELECT competencia, categoria, sexo, evento, part_abierta
	FROM planillas
	WHERE cod_planilla=$id_planilla";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	if ($ejecutar_consulta) {
		$row=$ejecutar_consulta->fetch_assoc();
		$categoria=$row["categoria"];
		$sexo=$row["sexo"];
		$evento=$row["evento"];
		$participa_abierto=$row["part_abierta"];
	}
	
	$res=suma($cal_cop,$sincronizado,$num_jueces);
	$suma=$res["suma"];
	$t_salto=$res["puntaje"];
	$p_salto=$t_salto*$grado_dificultad;
	$jueces=$_SESSION["jueces_ss"];

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

	$op_result=array();

	$consulta="UPDATE planillad
		SET grado_dif=$grado_dif,
		 	suma=$suma,
			total_salto=$t_salto,
			puntaje_salto=$p_salto,
			calificado=1";
	if ($penalidad>0) 
		$consulta .= ", penalizado=$penalidad";
	else
		$consulta .= ", penalizado=NULL";
	if ($cal[1]>0) 
		$consulta .= ", cal1=$cal[1]";
	if ($cal[2]>0) 
		$consulta .= ", cal2=$cal[2]";
	if ($cal[3]>0) 
		$consulta .= ", cal3=$cal[3]";
	if ($cal[4]>0) 
		$consulta .= ", cal4=$cal[4]";
	if ($cal[5]>0) 
		$consulta .= ", cal5=$cal[5]";
	if ($cal[6]>0) 
		$consulta .= ", cal6=$cal[6]";
	if ($cal[7]>0) 
		$consulta .= ", cal7=$cal[7]";
	if ($cal[8]>0) 
		$consulta .= ", cal8=$cal[8]";
	if ($cal[9]>0) 
		$consulta .= ", cal9=$cal[9]";
	if ($cal[10]>0) 
		$consulta .= ", cal1=$cal[10]";
	if ($cal[11]>0) 
		$consulta .= ", cal11=$cal[11]";
	$consulta .=" WHERE planilla=$id_planilla and ronda=$ronda";
	$ejecutar_consulta = $conexion->query($consulta);

	$op_result["Actualiza Total Salto"]=$ejecutar_consulta?TRUE:FALSE;

	$k=0;
	$ka=0;
	$kc=0;
	$ns=0;
	$consulta="SELECT ronda, total_salto, puntaje_salto, abierto
			FROM planillad
			WHERE planilla=$id_planilla and calificado=1";
	$ejecutar_consulta = $conexion->query($consulta);
	$op_result["Saltos calificados"]=$ejecutar_consulta?TRUE:FALSE;

	while ($row=$ejecutar_consulta->fetch_assoc()) {
		$k += $row["puntaje_salto"];
		$ronda=$row["ronda"];
		if ($ronda<=$saltos_categoria){
			$kc += $row["puntaje_salto"];
		} 
		$abierto=$row["abierto"];
		if ($abierto=="*" 
			or $categoria=="AB")
			$ka += $row["puntaje_salto"];

		$actualiza="UPDATE planillad 
			SET acumulado=$k 
			WHERE planilla=$id_planilla and ronda=$ronda";
		$ejecutar_actualiza = $conexion->query($actualiza);
		$op_result["Actualiza Ronda".$ronda]=$ejecutar_actualiza?TRUE:FALSE;
	}
	$_SESSION["total_acumulado_ss"]=$kc;
	if ($categoria=="AB" 
		or $abierto=="*"){
		$_SESSION["acumulado_abierto_ss"]=$ka;
	}

	$actualiza="UPDATE planillas 
		SET total=$kc";
	if ($ka>0)
		$actualiza .= ", total_abierta=$ka ";
	
	$actualiza .= " WHERE cod_planilla=$id_planilla";
	$ejecutar_actualiza = $conexion->query($actualiza);
	$op_result["Actualiza Total en Planilla"]=$ejecutar_actualiza?TRUE:FALSE;

	if ($separa_extraoficiales) {
		$actualiza="UPDATE planillas as p
			SET p.extraof=NULL 
			WHERE competencia=$cod_competencia and evento=$evento and categoria='$categoria' and sexo='$sexo'";
		$ejecutar_actualiza = $conexion->query($actualiza);
		$op_result["Desmarca Extraoficiales"]=$ejecutar_actualiza?TRUE:FALSE;

		$actualiza="UPDATE planillas 
			SET extraof='*' 
			WHERE competencia=$cod_competencia and evento=$evento and categoria='$categoria' and sexo='$sexo' AND participa_extraof='*'";
		$ejecutar_actualiza = $conexion->query($actualiza);
		$op_result["Marca los que participan extraoficiales"]=$ejecutar_actualiza?TRUE:FALSE;

		$consulta="SELECT cod_planilla, competencia, categoria, sexo, equipo, extraof, total, lugar
			FROM planillas
			WHERE competencia=$cod_competencia and evento=$evento and categoria='$categoria' and sexo='$sexo' AND extraof IS NULL
			ORDER BY categoria, sexo, equipo, total DESC";
		$ejecutar_consulta = $conexion->query($consulta);
		$op_result["selecciona competidores del evento"]=$ejecutar_consulta?TRUE:FALSE;
		$cat_sex_equ="";
		$n=0;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$cod_planilla=$row["cod_planilla"];
			$leido=$row["categoria"].$row["sexo"].$row["equipo"];
			if ($cat_sex_equ!=$leido) {
				$n=0;
				$cat_sex_equ=$leido;
			}
			$n++;
			if ($n>2){
				$actualiza="UPDATE planillas 
						SET extraof='*'
						WHERE cod_planilla=$cod_planilla";
				$ejecutar_actualiza = $conexion->query($actualiza);
				$op_result["Marca Extraoficial"]=$ejecutar_actualiza?TRUE:FALSE;
			}
		}
	}

	$consulta="SELECT cod_planilla, competencia, categoria, sexo, extraof, total, lugar
		FROM planillas
		WHERE competencia=$cod_competencia and evento=$evento and categoria='$categoria' and sexo='$sexo'
		ORDER BY categoria, sexo, extraof, total DESC";
	$ejecutar_consulta = $conexion->query($consulta);
	$op_result["Selecciona Planillas Evento, categoria y sexo"]=$ejecutar_consulta?TRUE:FALSE;

	$pos=0;
	$ext=0;
	$cat_sex="";
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$num_planilla=$row["cod_planilla"];
		$extraof=isset($row["extraof"])?$row["extraof"]:NULL;
		$leido=$row["categoria"].$row["sexo"];		
		if ($cat_sex!==$leido) {
			$pos=0;
			$ext=0;
			$cat_sex=$leido;
		}

		if (is_null($extraof) or 
			$extraof=="") 
			$pos++;
		else
			$ext++;
			
		$actualiza="UPDATE planillas SET lugar=";
		if ($extraof=="*") 
			$actualiza .= "$ext";
		else
			$actualiza .= "$pos";
		$actualiza .= " WHERE cod_planilla=$num_planilla";
		$ejecutar_actualiza = $conexion->query($actualiza);
		$op_result["Actualiza Puesto Planilla".$num_planilla]=$ejecutar_actualiza?TRUE:FALSE;
	}

	if ($categoria=="AB" or $participa_abierto=="*"){
		$consulta="SELECT cod_planilla, competencia, sexo, extraof_abierto, total_abierta, lugar_abierta
			FROM planillas
			WHERE competencia=$cod_competencia and evento=$evento and sexo='$sexo' AND (part_abierta='*' or categoria='AB' )
 			ORDER BY sexo, extraof_abierto, total_abierta DESC";
		$ejecutar_consulta = $conexion->query($consulta);
		$op_result["Selecciona Planillas Abierto"]=$ejecutar_consulta?TRUE:FALSE;
		
		$cat_sex="";
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$planilla=$row["cod_planilla"];
			$extraof=isset($row["extraof_abierto"])?$row["extraof_abierto"]:NULL;
				$leido=$row["sexo"];
			if ($cat_sex!==$leido) {
				$pos=0;
				$ext=0;
				$cat_sex=$leido;
			}
			if (is_null($extraof) or 
				$extraof=="") 
				$pos++;
			else
				$ext++;
			$actualiza="UPDATE planillas ";
			if (is_null($extraof))
				$actualiza .= "SET lugar_abierta=$pos ";
			else
				$actualiza .= "SET lugar_abierta=$ext ";
			$actualiza .= "WHERE cod_planilla=$planilla";
			$ejecutar_actualiza = $conexion->query($actualiza);
			$op_result["Actualiza lugar del Abierto en Planilla".$num_planilla]=$ejecutar_actualiza?TRUE:FALSE;
		}
	}

	$all_ok=TRUE;
	foreach ($op_result as $value) {
		if ($value==FALSE) {
			$all_ok = FALSE;
			break;
		}
	}
	if ($all_ok){
		if (!$conexion->commit())
			$mensaje="Error: No se pudo terminar de registrar la corrección :(";
	}
//	else{
//		if ($ver_mysql>="5.6"){
//			if ($conexion->rollback()) 
//				$mensaje="Error: Transacción reversada :( ";
//			else
//				$mensaje="Error: Transacción NO reversada :( ";
//		}
//		else
//			$mensaje="Error: Transacción NO reversada :( ";
//	}

	$conexion->close();

	if (!is_null($mensaje)){
		echo "$mensaje<br>";
		print_r($op_result);
		exit();
	}
}
?>