<?php 
include("funciones.php");
if (isset($_GET["com"])){
	$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
	$fechahora=isset($_GET["fh"])?$_GET["fh"]:NULL;
	$hora_coloquial=hora_coloquial($fechahora);
	$fechahora_original=$fechahora.".000";
	$fecha=substr($fechahora, 0, 10);
	$hora=substr($fechahora, 11,5);
	$modalidad=isset($_GET["mod"])?$_GET["mod"]:NULL;
	$categorias=isset($_GET["cat"])?$_GET["cat"]:NULL;
	$sexos=isset($_GET["sx"])?$_GET["sx"]:NULL;
	$preliminar=isset($_GET["pr"])?$_GET["pr"]:0;
	$cod_competencia=$_GET["codco"];
	$descripcion=describe_evento($modalidad,$sexos,$categorias,$preliminar);
}
session_start();
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;

if (isset($fecha)){
		$car=explode("-", $fecha);
		$ano=$car[0];
		$mes=$car[1];
		$dia=$car[2];
		$fecha=$ano."-".$mes."-".$dia;
}

?>
<form id="baja-evento" name="baja-frm" action="php/elimina-evento-competencia.php" method="post" enctype="multipart/form-data">
<div>
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<h2>Elimina Evento de la Competencia</h2>
		<span class="consulta">Fecha y hora: </span><?php echo "$hora_coloquial"; ?> <br>
		<span class="consulta">Descripción: </span><?php echo "$descripcion"; ?><br>
	</fieldset>
</div>
		<div>		
			<input type="hidden" name="descripcion_hdn" value="<?php echo $descripcion; ?>">
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="fechahora_original_hdn" value="<?php echo $fechahora_original; ?>">
			<input type="hidden" name="descripcion_hdn" value="<?php echo $descripcion; ?>">
		</div>

		<div>
			<br>
			<input type="submit" id="enviar-eli" title="Elimina este Evento de la Competencia" class="cambio" name="elimina_sbm" value="Eliminar Evento" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a los eventos de la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>