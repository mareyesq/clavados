<?php 
include("funciones.php");
if (isset($_GET["com"])){
	$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
	$fechahora=isset($_GET["fh"])?$_GET["fh"]:NULL;
	$fechahora_original=$fechahora.".000";
	$fecha=substr($fechahora, 0, 10);
	$hora=substr($fechahora, 11,5);
	$modalidad=isset($_GET["mod"])?$_GET["mod"]:NULL;
	$categorias=isset($_GET["cat"])?$_GET["cat"]:NULL;
	$sexos=isset($_GET["sx"])?$_GET["sx"]:NULL;
	$tipo=isset($_GET["tp"])?$_GET["tp"]:NULL;
	$primero_libres=isset($_GET["pl"])?$_GET["pl"]:NULL;
	$cod_competencia=$_GET["codco"];
	$usa_dispositivos=$_GET["disp"];
	$calentamiento=$_GET["cal"];
	$participantes_estimado=$_GET["par"];
}

session_start();
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
if (!isset($competencia)) 
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:null;

if (!isset($cod_competencia)) 
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null;

if (!isset($fechahora_original))
	$fechahora_original=isset($_SESSION["fechahora_original"])?$_SESSION["fechahora_original"]:NULL;

if (!isset($fecha)) 
	$fecha=(isset($_SESSION["fecha"])?$_SESSION["fecha"]:null);

if (isset($fecha)){
		$car=explode("-", $fecha);
		$ano=$car[0];
		$mes=$car[1];
		$dia=$car[2];
		$fecha=$ano."-".$mes."-".$dia;
}

if (!isset($hora)) {
	$hora=(isset($_SESSION["hora"])?$_SESSION["hora"]:null);
}

if (!isset($modalidad)) 
	$modalidad=(isset($_SESSION["modalidad"])?$_SESSION["modalidad"]:null);

if (!isset($categorias)) 
	$categorias=(isset($_SESSION["categorias"])?$_SESSION["categorias"]:null);

if (!isset($sexos)) 
	$sexos=isset($_SESSION["sexos"])?$_SESSION["sexos"]:NULL;

if (!isset($tipo)) 
	$tipo=isset($_SESSION["tipo"])?$_SESSION["tipo"]:NULL;

if (!isset($primero_libres)) 
	$primero_libres=isset($_SESSION["primero_libres"])?$_SESSION["primero_libres"]:NULL;
if (!isset($usa_dispositivos)) 
	$usa_dispositivos=isset($_SESSION["usa_dispositivos"])?$_SESSION["usa_dispositivos"]:NULL;
if (!isset($calentamiento)) 
	$calentamiento=isset($_SESSION["calentamiento"])?$_SESSION["calentamiento"]:NULL;
if (!isset($participantes_estimado)) 
	$participantes_estimado=isset($_SESSION["participantes_estimado"])?$_SESSION["participantes_estimado"]:NULL;

$alta=0;
$desde_anio=1900;
$hasta_anio=date("Y");
$ord="d";

?>
<form id="edita-evento" name="edita-frm" action="php/actualiza-evento-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<h2>Edita Evento de la Competencia</h2>
		<?php include("formulario-evento-competencia.php"); ?>
		<div>
			<br>
			<input type="submit" id="enviar-act" title="Actualiza este Evento de la Competencia" class="cambio" name="actualiza_sbm" value="Actualizar Evento" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a los eventos de la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
			<input type="hidden" name="fechahora_original_hdn" value="<?php echo $fechahora_original; ?>">
		</div>
	</fieldset>
</form>