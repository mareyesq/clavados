<?php 
	$k=0;
	$ka=0;
	$kc=0;
	$ns=0;

	$consulta="SELECT ronda, turno, total_salto, puntaje_salto, abierto
			FROM planillad
			WHERE planilla=$cod_planilla 
				and calificado=1
			ORDER BY ronda";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	$participa_abierto="";
	
	if ($ejecutar_consulta)
		while ($row=$ejecutar_consulta->fetch_assoc()) {
			$k+=$row["puntaje_salto"];
			$rd=$row["ronda"];
			$turno=$row["turno"];
			if ($rd<=$saltos_categoria) 
				$kc+=$row["puntaje_salto"];
			$abierto=$row["abierto"];
			if ($abierto=="*" or $categoria=="AB")
				$ka+=$row["puntaje_salto"];
			if ($abierto=="*") 
				$participa_abierto="*";
			$actualiza="UPDATE planillad 
				SET acumulado=$k 
				WHERE planilla=$cod_planilla and turno=$turno";
			$ejecutar_actualiza = $conexion->query($actualiza);
		}

	$_SESSION["total_acumulado"]=$kc;

	if ($categoria=="AB" or $participa_abierto=="*"){
		$_SESSION["acumulado_abierto"]=$ka;
	}
	$actualiza="UPDATE planillas 
		SET total=$kc";
	if ($ka>0)
		$actualiza .= ", total_abierta=$ka ";
	if ($participa_abierto=="*")
		$actualiza .= ", part_abierta='*' ";
	$actualiza .= " WHERE cod_planilla=$cod_planilla";
	$ejecutar_actualiza = $conexion->query($actualiza);

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

	if ($separa_extraoficiales) {
		$actualiza="UPDATE planillas as p
			SET p.extraof=NULL ";
//		$actualiza .= $criterio.$criterio_sexo.$criterio_categoria.")";
		$actualiza .= $criterio.")";
		$ejecutar_actualiza = $conexion->query(utf8_decode($actualiza));
		$actualiza="UPDATE planillas as p
			SET p.extraof='*' ";
//		$actualiza .= $criterio.$criterio_sexo.$criterio_categoria." AND p.participa_extraof='*') ";
		$actualiza .= $criterio." AND p.participa_extraof='*') ";
		$ejecutar_actualiza = $conexion->query(utf8_decode($actualiza));

		$consulta="SELECT p.cod_planilla, p.competencia, p.categoria, p.sexo, p.equipo, p.extraof, p.total, p.lugar
			FROM planillas as p"; 
//		$consulta .= $criterio.$criterio_sexo.$criterio_categoria.") ".
		$consulta .= $criterio." AND p.extraof IS NULL) ".
  			"ORDER BY p.categoria, p.sexo, p.equipo, p.total DESC";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

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
			}
		}
	}

	if ($categoria!="AB"){
		$consulta="SELECT p.cod_planilla, p.competencia, p.categoria, p.sexo, p.equipo, p.extraof, p.total, p.lugar
		FROM planillas as p"; 
//		$consulta .= $criterio.$criterio_sexo.$criterio_categoria.") ".
		$consulta .= $criterio." AND p.extraof IS NULL) ".
  		"ORDER BY p.categoria, p.sexo, p.extraof, p.total DESC";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$cat_sex="";
		$total_ant=NULL;
		$empate=FALSE;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$planilla=$row["cod_planilla"];
			$extraof=$row["extraof"];
			$total=$row["total"];
			$leido=$row["categoria"].$row["sexo"].$extraof;
			if ($cat_sex!=$leido) {
				$pos=0;
				$ext=0;
				$n=0;
				$cat_sex=$leido;
			}
			if ($extraof=="*") 
				$ext++;
			else{
				$n++;
				if ($total!=$total_ant) {
					if ($empate) {
						$pos=$n;
						$empate=FALSE;
					}
					else
						$pos++;
				}
				else					
					$empate=TRUE;
				
				$total_ant=$total;
			}
		
			$actualiza="UPDATE planillas ";
			if ($extraof=="*") 
				$actualiza .= "SET 	lugar=$ext";
			else
				$actualiza .= "SET lugar=$pos ";
			$actualiza .= " WHERE cod_planilla=$planilla";
			$ejecutar_actualiza = $conexion->query($actualiza);
		}
	}

	if ($categoria=="AB" or $participa_abierto=="*"){
		$consulta="SELECT p.cod_planilla, p.competencia, p.categoria, p.sexo, p.extraof_abierto, p.total_abierta, p.lugar_abierta
			FROM planillas as p"; 
//		$consulta .= $criterio.$criterio_sexo." AND (p.part_abierta='*' or p.categoria='AB'))
		$consulta .= $criterio." AND (p.part_abierta='*' or p.categoria='AB'))
  			ORDER BY p.sexo, p.extraof_abierto, p.total_abierta DESC";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		
		$cat_sex_ext="";
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$planilla=$row["cod_planilla"];
			$extraof=$row["extraof_abierto"];
			$leido=$row["sexo"].$extraof;		
			if ($cat_sex_ext!=$leido) {
				$pos=0;
				$ext=0;
				$cat_sex_ext=$leido;
			}
			if (is_null($extraof)) 
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
		}
	}
	$consulta="SELECT inicio_comp
		FROM competenciaev  
		WHERE competencia=$cod_competencia and numero_evento=$numero_evento";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$row=$ejecutar_consulta->fetch_assoc();
	if (is_null($row["inicio_comp"])){
		$actualiza="UPDATE competenciaev
			SET inicio_comp='$ahora'
			WHERE competencia=$cod_competencia and numero_evento=$numero_evento";
		$ejecutar_actualiza = $conexion->query($actualiza);
	}

 ?>