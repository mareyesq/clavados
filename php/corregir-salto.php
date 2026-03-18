<?php 
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

include("funciones.php");

if (isset($_GET["pla"])) {
	$id_planilla=trim($_GET["pla"]);
	$ronda=trim($_GET["rd"]);
	$conexion=conectarse();
	$consulta="SELECT d.salto, d.posicion, d.altura, d.grado_dif, d.abierto, d.penalizado, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, d.total_salto, d.puntaje_salto, p.competencia, p.evento, p.modalidad, p.categoria, p.sexo, c.nombre as nom_cla, c2.nombre as nom_cla2, com.competencia as nom_competencia
	FROM planillad as d
	LEFT JOIN planillas AS p ON p.cod_planilla=d.planilla
	LEFT JOIN usuarios as c ON c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 ON c2.cod_usuario=p.clavadista2
	LEFT JOIN competencias as com ON com.cod_competencia=p.competencia
	WHERE d.planilla=$id_planilla and d.ronda=$ronda";
	$ejecutar_consulta = $conexion->query($consulta);
	$row=$ejecutar_consulta->fetch_assoc();
	$cod_competencia=$row["competencia"];
	$competencia=$row["nom_competencia"];
	$evento=$row["evento"];
	$nom_cla=utf8_decode($row["nom_cla"]);
	$nom_cla2=utf8_decode($row["nom_cla2"]);
	if (strlen($nom_cla2)>0){
		$nom_cla .= " / ".$nom_cla2;
		$sincronizado=1;		
	} 
	$nom_cla=strtolower($nom_cla);
	$nom_cla=ucwords($nom_cla);
	$modalidad=$row["modalidad"];
	$categoria=$row["categoria"];
	$sexo=$row["sexo"];
	$salto=$row["salto"];
	$posicion=$row["posicion"];
	$altura=$row["altura"];
	$grado_dif=$row["grado_dif"];
	$abierto=$row["abierto"];
	$penalizado=$row["penalizado"];
	$total_salto=$row["total_salto"];
	$puntaje_salto=$row["puntaje_salto"];
	$cal1=$row["cal1"];
	$cal2=$row["cal2"];
	$cal3=$row["cal3"];
	$cal4=$row["cal4"];
	$cal5=$row["cal5"];
	$cal6=$row["cal6"];
	$cal7=$row["cal7"];
	$cal8=$row["cal8"];
	$cal9=$row["cal9"];
	$cal10=$row["cal10"];
	$cal11=$row["cal11"];
	$conexion->close();
}
session_start();
if (is_null($competencia)) 
	$competencia=isset($_SESSION["competencia_ss"])?trim($_SESSION["competencia_ss"]):NULL;
if (is_null($cod_competencia)) 
	$cod_competencia=isset($_SESSION["cod_competencia_ss"])?trim($_SESSION["cod_competencia_ss"]):NULL;
if (is_null($evento)) 
	$evento=isset($_SESSION["evento_ss"])?trim($_SESSION["evento_ss"]):NULL;
if (is_null($id_planilla)) 
	$id_planilla=isset($_SESSION["id_planilla_ss"])?trim($_SESSION["id_planilla_ss"]):NULL;
if (is_null($nom_cla)) 
	$nom_cla=isset($_SESSION["nom_cla_ss"])?trim($_SESSION["nom_cla_ss"]):NULL;
if (is_null($sincronizado)) 
	$sincronizado=isset($_SESSION["sincronizado_ss"])?trim($_SESSION["sincronizado_ss"]):NULL;
if (is_null($modalidad)) 
	$modalidad=isset($_SESSION["modalidad_ss"])?trim($_SESSION["modalidad_ss"]):NULL;
if (is_null($categoria)) 
	$categoria=isset($_SESSION["categoria_ss"])?trim($_SESSION["categoria_ss"]):NULL;
if (is_null($sexo)) 
	$sexo=isset($_SESSION["sexo_ss"])?trim($_SESSION["sexo_ss"]):NULL;
if (is_null($ronda)) 
	$ronda=isset($_SESSION["ronda_ss"])?trim($_SESSION["ronda_ss"]):NULL;
if (is_null($salto))	
	$salto=isset($_SESSION["salto_ss"])?trim($_SESSION["salto_ss"]):NULL;
if (is_null($posicion))	
	$posicion=isset($_SESSION["posicion_ss"])?trim($_SESSION["posicion_ss"]):NULL;
if (is_null($altura))	
	$altura=isset($_SESSION["altura_ss"])?trim($_SESSION["altura_ss"]):NULL;
if (is_null($grado_dif))	
	$grado_dif=isset($_SESSION["grado_dificultad_ss"])?trim($_SESSION["grado_dificultad_ss"]):NULL;
if (is_null($abierto))	
	$abierto=isset($_SESSION["abierto_ss"])?trim($_SESSION["abierto_ss"]):NULL;
if (is_null($penalizado))	
	$penalizado=isset($_SESSION["penalizado_ss"])?trim($_SESSION["penalizado_ss"]):NULL;
if (is_null($total_salto))	
	$total_salto=isset($_SESSION["total_salto_ss"])?trim($_SESSION["total_salto_ss"]):NULL;
if (is_null($puntaje_salto))	
	$puntaje_salto=isset($_SESSION["puntaje_salto_ss"])?trim($_SESSION["puntaje_salto_ss"]):NULL;
if (is_null($cal1))	
	$cal1=isset($_SESSION["cal1_ss"])?trim($_SESSION["cal1_ss"]):NULL;
if (is_null($cal2))	
	$cal2=isset($_SESSION["cal2_ss"])?trim($_SESSION["cal2_ss"]):NULL;
if (is_null($cal3))	
	$cal3=isset($_SESSION["cal3_ss"])?trim($_SESSION["cal3_ss"]):NULL;
if (is_null($cal4))	
	$cal4=isset($_SESSION["cal4_ss"])?trim($_SESSION["cal4_ss"]):NULL;
if (is_null($cal5))	
	$cal5=isset($_SESSION["cal5_ss"])?trim($_SESSION["cal5_ss"]):NULL;
if (is_null($cal6))	
	$cal6=isset($_SESSION["cal6_ss"])?trim($_SESSION["cal6_ss"]):NULL;
if (is_null($cal7))	
	$cal7=isset($_SESSION["cal7_ss"])?trim($_SESSION["cal7_ss"]):NULL;
if (is_null($cal8))	
	$cal8=isset($_SESSION["cal8_ss"])?trim($_SESSION["cal8_ss"]):NULL;
if (is_null($cal9))	
	$cal9=isset($_SESSION["cal9_ss"])?trim($_SESSION["cal9_ss"]):NULL;
if (is_null($cal10))	
	$cal10=isset($_SESSION["cal10_ss"])?trim($_SESSION["cal10_ss"]):NULL;
if (is_null($cal11))	
	$cal11=isset($_SESSION["cal11_ss"])?trim($_SESSION["cal11_ss"]):NULL;

$dec=decimales($cod_competencia);
$encabezado="Evento No. ".$evento;

?>

<form id="corregir-salto" name="corregir-salto-frm" action="?op=php/menu-corregir-salto.php" method="post" enctype="multipart/form-data">
	<h2>Competencia: <?php echo $competencia ?></h2>
	<h3><?php echo $encabezado; ?></h3>
	<div>
		<?php
			include("formulario-salto.php");
		 ?>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
		<input type="hidden" id="evento" name="evento_hdn" value="<?php echo $evento; ?>" />
		<input type="hidden" id="nom_cla" name="nom_cla_hdn" value="<?php echo $nom_cla; ?>" />
		<input type="hidden" id="sincronizado" name="sincronizado_hdn" value="<?php echo $sincronizado; ?>" />
		<input type="hidden" id="modalidad" name="modalidad_hdn" value="<?php echo $modalidad; ?>" />
		<input type="hidden" id="categoria" name="categoria_hdn" value="<?php echo $categoria; ?>" />
		<input type="hidden" id="sexo" name="sexo_hdn" value="<?php echo $sexo; ?>" />
		<input type="hidden" id="salto" name="salto_hdn" value="<?php echo $salto; ?>" />
		<input type="hidden" id="posicion" name="posicion_hdn" value="<?php echo $posicion; ?>" />
		<input type="hidden" id="altura" name="altura_hdn" value="<?php echo $altura; ?>" />
		<input type="hidden" id="grado-dif" name="grado_dif_hdn" value="<?php echo $grado_dif; ?>" />
		<input type="hidden" id="id-planilla" name="id_planilla_hdn" value="<?php echo $id_planilla; ?>" />
		<input type="hidden" id="ronda" name="ronda_hdn" value="<?php echo $ronda; ?>" />
		<label for="penalidad">Penalizado: </label>
		<input type="number" min="0" max="2" id="penalidad" name="penalidad_hdn" value="<?php echo $penalidad; ?> " readonly/>
		<input tabindex = '12' type='submit' acceskey='t' id='totaliza'  autofocus title='calcula total del salto' class='cambio' name='totaliza_sbm' value='Totaliza'/>

		<?php 
			if ($penalidad>0) 
				echo "<input type='submit' tabindex = '16' id='despenalizar' acces_key='d' title='Despenalizar Salto' class='cambio' name='despenalizar_sbm' value='Despenalizar' />";
			
			else
				echo "<input type='submit' tabindex = '17' id='penalizar' acces_key='p' title='Salto Penalizado' class='cambio' name='penalizar_sbm' value='Penalizar' />";	
		?>
		<input type="submit" id="regresar" tabindex = '20'  title="Regresa a eventos de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
</form>