<?php 
include("funciones.php");
$conexion=conectarse();
session_start();
if (isset($_GET["pla"])){
	$cod_planilla=$_GET["pla"];
	$consulta="SELECT c.nombre as nom_cla, c.imagen as img1, c2.nombre as nom_cla2, c2.imagen as img2, c3.nombre as nom_cla3, c2.imagen as img3, c4.nombre as nom_cla4, c4.imagen as img4, p.cod_planilla, p.competencia, p.evento, p.modalidad, p.categoria
		FROM planillas as p 
		LEFT JOIN usuarios  as c  on c.cod_usuario=p.clavadista
		LEFT JOIN usuarios  as c2 on c2.cod_usuario=p.clavadista2
		LEFT JOIN usuarios  as c3 on c3.cod_usuario=p.clavadista3
		LEFT JOIN usuarios  as c4 on c4.cod_usuario=p.clavadista4
		WHERE p.cod_planilla=$cod_planilla";
	$ejecutar_consulta=$conexion->query($consulta);
	$reg=$ejecutar_consulta->fetch_assoc();
	$nom_cla=utf8_decode($reg["nom_cla"]);
	$nom_cla2=utf8_decode($reg["nom_cla2"]);
	$nom_cla3=utf8_decode($reg["nom_cla3"]);
	$nom_cla4=utf8_decode($reg["nom_cla4"]);
	$modalidad=isset($reg["modalidad"])?$reg["modalidad"]:NULL;
	$cat=isset($reg["categoria"])?$reg["categoria"]:NULL;
	if (strlen($nom_cla2)>0){
		if ($modalidad=="E" AND $cat=='Q1'){
			$sincronizado=FALSE;
			if ($nom_cla2)
				$nom_cla .= $nom_cla2;
			if ($nom_cla3)
				$nom_cla .= $nom_cla3;
			if ($nom_cla4)
				$nom_cla .= $nom_cla4;
		}
		else{
			$sincronizado=TRUE;
			$nom_cla .= " / ".$nom_cla2;
		}
	}
	else
		$sincronizado=FALSE;
	$nom_cla=strtolower($nom_cla);
	$nom_cla=ucwords($nom_cla);
	$imagen1=$reg["img1"];
	$imagen2=isset($reg["img2"])?$reg["img2"]:NULL;
	$imagen3=isset($reg["img3"])?$reg["img3"]:NULL;
	$imagen4=isset($reg["img4"])?$reg["img4"]:NULL;
	$cod_competencia=$reg["competencia"];
	$evento=$reg["evento"];
	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
	$dec=decimales($cod_competencia);
	$es_administrador=FALSE;
	if (!is_null($cod_usuario)) {
		$es_administrador=administrador_competencia($cod_usuario,$cod_competencia,$conexion);
		if (substr($es_administrador, 0, 5)=="Error")
			$es_administrador=FALSE;
	}

	$consulta="SELECT DISTINCT z.panel, z.ubicacion, z.juez
		FROM competenciasz as z
		WHERE z.competencia=$cod_competencia and z.evento=$evento and z.panel=1
		ORDER BY ubicacion";
	$ejecutar_consulta = $conexion->query($consulta);
	if ($ejecutar_consulta){
		$num_jueces=0;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$ub=$row["ubicacion"];
			if ($ub<25)
				$num_jueces++;
		}
	}
	
	$consulta="SELECT ronda, turno, salto, posicion, altura, grado_dif, suma, total_salto, puntaje_salto, acumulado, penalizado, cal1, cal2, cal3, cal4, cal5, cal6, cal7, cal8, cal9, cal10, cal11, abierto, ejecutor, ejecutor2
		FROM planillad 
		WHERE planilla=$cod_planilla
		ORDER BY ronda";
	$ejecutar_consulta=$conexion->query($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8"/>
    <?php 
        $nav=$_SERVER['HTTP_USER_AGENT'];
        $cual="chrome";
        //Demilitador 'i' para no diferenciar mayus y minus
        if (preg_match("/".$cual."/i", $nav)) 
    ?>
     <link rel='stylesheet' type='text/css' href='../css/estilos.css'/>
</head>
<body>
<span class="rotulo"><?php echo $nom_cla ?></span>	
<?php 
	if (isset($imagen1))
		echo "<img class='textwrap'src='../img/fotos/$imagen1' width='8%'/>&nbsp;&nbsp;";
	if (isset($imagen2))
		echo "<img class='textwrap'src='../img/fotos/$imagen2' width='8%'/>";
	if (isset($imagen3))
		echo "<img class='textwrap'src='../img/fotos/$imagen3' width='8%'/>";
	if (isset($imagen4))
		echo "<img class='textwrap'src='../img/fotos/$imagen4' width='8%'/>";
?>
<br>
<section id="principal">
<table border='1' bordercolor='#0000FF' cellspacing='1.5em' cellpadding='1.5em' class="tablas" width="100%">
	<tr align="center">
		<th>Ronda</th>
		<th>Salto</th>
		<th>Altura</th>
		<th>Gr.Df.</th>
		<?php 
		if ($sincronizado) {
			switch ($num_jueces) {
				case '5':
					echo "<th>&nbsp;E-1&nbsp;</th>";
					echo "<th>&nbsp;E-2&nbsp;</th>";
					echo "<th>&nbsp;S-1&nbsp;</th>";
					echo "<th>&nbsp;S-2&nbsp;</th>";
					echo "<th>&nbsp;S-3&nbsp;</th>";
					break;
				case '7':
					echo "<th>&nbsp;E-1&nbsp;</th>";
					echo "<th>&nbsp;E-2&nbsp;</th>";
					echo "<th>&nbsp;S-1&nbsp;</th>";
					echo "<th>&nbsp;S-2&nbsp;</th>";
					echo "<th>&nbsp;S-3&nbsp;</th>";
					echo "<th>&nbsp;S-4&nbsp;</th>";
					echo "<th>&nbsp;S-5&nbsp;</th>";
					break;
				case '8':
					echo "<th>&nbsp;J-1&nbsp;</th>";
					echo "<th>&nbsp;J-2&nbsp;</th>";
					echo "<th>&nbsp;J-3&nbsp;</th>";
					echo "<th>&nbsp;J-4&nbsp;</th>";
					echo "<th>&nbsp;J-5&nbsp;</th>";
					echo "<th>&nbsp;J-6&nbsp;</th>";
					echo "<th>&nbsp;J-7&nbsp;</th>";
					echo "<th>&nbsp;J-8&nbsp;</th>";
					break;
				case '9':
					echo "<th>&nbsp;E-1&nbsp;</th>";
					echo "<th>&nbsp;E-2&nbsp;</th>";
					echo "<th>&nbsp;E-3&nbsp;</th>";
					echo "<th>&nbsp;E-4&nbsp;</th>";
					echo "<th>&nbsp;S-1&nbsp;</th>";
					echo "<th>&nbsp;S-2&nbsp;</th>";
					echo "<th>&nbsp;S-3&nbsp;</th>";
					echo "<th>&nbsp;S-4&nbsp;</th>";
					echo "<th>&nbsp;S-5&nbsp;</th>";
					break;
				case '10':
					echo "<th>&nbsp;J-1&nbsp;</th>";
					echo "<th>&nbsp;J-2&nbsp;</th>";
					echo "<th>&nbsp;J-3&nbsp;</th>";
					echo "<th>&nbsp;J-4&nbsp;</th>";
					echo "<th>&nbsp;J-5&nbsp;</th>";
					echo "<th>&nbsp;J-6&nbsp;</th>";
					echo "<th>&nbsp;J-7&nbsp;</th>";
					echo "<th>&nbsp;J-8&nbsp;</th>";
					echo "<th>&nbsp;J-9&nbsp;</th>";
					echo "<th>&nbsp;J-10&nbsp;</th>";
					break;
				case '11':
					echo "<th>&nbsp;E-1&nbsp;</th>";
					echo "<th>&nbsp;E-2&nbsp;</th>";
					echo "<th>&nbsp;E-3&nbsp;</th>";
					echo "<th>&nbsp;E-4&nbsp;</th>";
					echo "<th>&nbsp;E-5&nbsp;</th>";
					echo "<th>&nbsp;E-6&nbsp;</th>";
					echo "<th>&nbsp;S-1&nbsp;</th>";
					echo "<th>&nbsp;S-2&nbsp;</th>";
					echo "<th>&nbsp;S-3&nbsp;</th>";
					echo "<th>&nbsp;S-4&nbsp;</th>";
					echo "<th>&nbsp;S-5&nbsp;</th>";
					break;
			}
		}
		else{
			if ($num_jueces>2){
				echo "<th>&nbsp;J-1&nbsp;</th>";
				echo "<th>&nbsp;J-2&nbsp;</th>";
				echo "<th>&nbsp;J-3&nbsp;</th>";
			} 
			if ($num_jueces>3){
				echo "<th>&nbsp;J-4&nbsp;</th>";
				echo "<th>&nbsp;J-5&nbsp;</th>";
			} 
			if ($num_jueces>5)
				echo "<th>&nbsp;J-6&nbsp;</th>";
			 
			if ($num_jueces>6)
				echo "<th>&nbsp;J-7&nbsp;</th>";
		
			if ($num_jueces>7)
				echo "<th>&nbsp;J-8&nbsp;</th>";
			
			if ($num_jueces>8)
				echo "<th>&nbsp;J-9&nbsp;</th>";
			
			if ($num_jueces>9)
				echo "<th>&nbsp;J-10&nbsp;</th>";
		}	 
		?>
		<th>Suma</th>
		<th>Total</th>
		<th>Punt.</th>
		<th>Acum.</th>
	</tr>
<?php 
	$linea=FALSE;
	while ($reg=$ejecutar_consulta->fetch_assoc()){
		$turno=$reg["turno"];
		$ronda=$reg["ronda"];
		$salto=$reg["salto"]."-".$reg["posicion"];
		$altura=number_format($reg["altura"],1);
		$grado_dif=number_format($reg["grado_dif"],1);
		$suma=isset($reg["suma"])?number_format($reg["suma"],$dec):NULL;
		$total_salto=isset($reg["total_salto"])?number_format($reg["total_salto"],$dec):NULL;
		$puntaje_salto=number_format($reg["puntaje_salto"],$dec);
		$acumulado=number_format($reg["acumulado"],$dec);
		$penalidad=$reg["penalizado"];
		$abierto=$reg["abierto"];
		$ejecutor=$reg["ejecutor"];
		$ejecutor2=$reg["ejecutor2"];
		$cal= array();
		$cal_cop= array();
		$cal[1]=!is_null($reg["cal1"])?$reg["cal1"]:NULL;
		$cal[2]=!is_null($reg["cal2"])?$reg["cal2"]:NULL;
		$cal[3]=!is_null($reg["cal3"])?$reg["cal3"]:NULL;
		$cal[4]=!is_null($reg["cal4"])?$reg["cal4"]:NULL;
		$cal[5]=!is_null($reg["cal5"])?$reg["cal5"]:NULL;
		$cal[6]=!is_null($reg["cal6"])?$reg["cal6"]:NULL;
		$cal[7]=!is_null($reg["cal7"])?$reg["cal7"]:NULL;
		$cal[8]=!is_null($reg["cal8"])?$reg["cal8"]:NULL;
		$cal[9]=!is_null($reg["cal9"])?$reg["cal9"]:NULL;
		$cal[10]=!is_null($reg["cal10"])?$reg["cal10"]:NULL;
		$cal[11]=!is_null($reg["cal11"])?$reg["cal11"]:NULL;

		if (!is_null($penalidad))
			for ($i=1; $i <$num_jueces+1 ; $i++) 
				$cal[$i] -=$penalidad;
		
		if ($modalidad=='E' and $cat=='Q1' and $ejecutor2)
			$sincro=1;
		else
			$sincro=$sincronizado;

		$cal_cop=elimina_calificaciones($cal,$sincro,$num_jueces);
		echo "<tr align='center'";
		if ($linea) {
				echo " class='linea1'";
		}
		echo ">";
		$linea=!$linea;
		echo "<td>$ronda</td>";
		echo "<td>";
		if ($es_administrador) 
			echo "<a class='enlaces_tab' href='../index.php?op=php/corregir-salto.php&pla=$cod_planilla&rd=$ronda'>".$abierto." $salto</a></td>";
		else
			echo $abierto." $salto</td>";

		echo "<td>$altura</td>";
		echo "<td>$grado_dif</td>";
		for ($i=1; $i < 12; $i++) { 
			if (!is_null($cal[$i])){
				echo "<td";
				if ($cal[$i]!=$cal_cop[$i])
					echo " class='tachado'";
				echo ">$cal[$i]</td>";
			}
		}
		if ($acumulado>0) {
			echo "<td>$suma</td>";	
			echo "<td>$total_salto</td>";	
			echo "<td>$puntaje_salto</td>";	
			echo "<td>$acumulado</td>";	
		}
		echo "</tr>";
	}
	echo "</table>";
}
$conexion->close();
 ?>
 </section>
</body>
</html>