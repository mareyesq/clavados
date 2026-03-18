<?php 
$cod_planilla=$row["cod_planilla"];
$cod_cla=$row["clavadista"];
$cod_ent=$row["entrenador"];
$cod_equ=$row["equipo"];
$bandera=$row["bandera"];
$orden_salida=$row["orden_salida"];
$nom_cla=utf8_decode($row["nom_cla"]);
$nom_cla2=utf8_decode($row["nom_cla2"]);
$nom_cla3=utf8_decode($row["nom_cla3"]);
$nom_cla4=utf8_decode($row["nom_cla4"]);
$ejecutor=isset($row["ejecutor"])?$row["ejecutor"]:NULL;
$ejecutor2=isset($row["ejecutor2"])?$row["ejecutor2"]:NULL;

$sincronizado=0;
if ($nom_cla2){
	if ($modalidad=="E" and $cat=='EQ'){
		if ($ejecutor==2)
			$nom_cla=$nom_cla2;
	}
	else
		if ($modalidad=="E" AND $cat=='Q1'){
			switch ($ejecutor) {
				case '1':
					$nom=$nom_cla;
					break;
				case '2':
					$nom=$nom_cla2;
					break;
				case '3':
					$nom=$nom_cla3;
					break;
				case '4':
					$nom=$nom_cla4;
					break;
			}
			if ($ejecutor2){
				$sincronizado=1;
				switch ($ejecutor2) {
					case '1':
						$nom.=' / '.$nom_cla;
						break;
					case '2':
						$nom.=' / '.$nom_cla2;
						break;
					case '3':
						$nom.=' / '.$nom_cla3;
						break;
					case '4':
						$nom.=' / '.$nom_cla4;
						break;
				}
			}
			$nom_cla=$nom;
		}
		else{
			$sincronizado=1;		
			$nom_cla .= " / ".$nom_cla2;
		}
} 
//$nom_cla=strtolower($nom_cla);
//$nom_cla=ucwords($nom_cla);
$nom_equipo=(utf8_decode($row["nom_equipo"]));
$modalidad=$row["modalidad"];
$categoria=$row["categoria"];
$sexo=$row["sexo"];
if ($sexo=="F") 
	$nom_sexo="Damas";
elseif ($sexo=="M") 
	$nom_sexo="Varones";
else
	$nom_sexo="Mixto";
$nom_cat=utf8_encode($row["nom_cat"]);
$nom_mod=utf8_encode($row["nom_mod"]);
$total=$row["total"];
$part_abierta=$row["part_abierta"];
$lugar=$row["lugar"];
$ronda=$row["ronda"];
$turno=$row["turno"];
if ($modalidad=="E" AND $cat=='EQ'){
	if ($ejecutor==1){
		$imagen1=$row["img_1"];
		$imagen2=NULL;
	}
	else{
		$imagen1=NULL;
		$imagen2=$row["img_2"];
	}
}
else
	if ($modalidad=="E" AND $cat=='Q1'){
		switch ($ejecutor) {
			case '1':
				$imagen1=$row["img_1"];
				break;
			case '2':
				$imagen1=$row["img_2"];
				break;
			case '3':
				$imagen1=$row["img_3"];
				break;
			case '4':
				$imagen1=$row["img_4"];
				break;
		}
		switch ($ejecutor2) {
			case '1':
				$imagen2=$row["img_1"];
				break;
			case '2':
				$imagen2=$row["img_2"];
				break;
			case '3':
				$imagen2=$row["img_3"];
				break;
			case '4':
				$imagen2=$row["img_4"];
				break;
		}
	}
	else{
		$imagen1=$row["img_1"];
		$imagen2=$row["img_2"];
	}

$salto=$row["salto"];
$nom_salto=utf8_encode($row["nom_salto"]);
$posicion=$row["posicion"];
$altura=$row["altura"];
$grado_dif=$row["grado_dif"];	
$abierto=$row["abierto"];
$total_salto=$row["total_salto"];
$puntaje_salto=$row["puntaje_salto"];
$acumulado=$row["acumulado"];
$cal= array();
$cal_cop= array();
$cal[1]=isset($row["cal1"])?$row["cal1"]:NULL;
$cal[2]=isset($row["cal2"])?$row["cal2"]:NULL;
$cal[3]=isset($row["cal3"])?$row["cal3"]:NULL;
$cal[4]=isset($row["cal4"])?$row["cal4"]:NULL;
$cal[5]=isset($row["cal5"])?$row["cal5"]:NULL;
$cal[6]=isset($row["cal6"])?$row["cal6"]:NULL;
$cal[7]=isset($row["cal7"])?$row["cal7"]:NULL;
$cal[8]=isset($row["cal8"])?$row["cal8"]:NULL;
$cal[9]=isset($row["cal9"])?$row["cal9"]:NULL;
$cal[10]=isset($row["cal10"])?$row["cal10"]:NULL;
$cal[11]=isset($row["cal11"])?$row["cal11"]:NULL;

if ($penalidad){
	$penalidad=number_format($row["penalizado"],1);
	for ($i=1; $i <$num_jueces+1 ; $i++) 
		$cal[$i] -=$penalidad;
}
$cal_cop=elimina_calificaciones($cal,$sincronizado,$num_jueces);
$calificado=$row["calificado"];

?>