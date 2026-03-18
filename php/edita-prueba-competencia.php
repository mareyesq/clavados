<?php 
if (isset($_GET["com"])) {
	$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
	$cod_competencia=isset($_GET["codco"])?$_GET["codco"]:NULL;
	$modalidades=isset($_GET["codmo"])?$_GET["codmo"]:NULL;
	$categoria=isset($_GET["cat"])?$_GET["cat"]:NULL;
	$individual=isset($_GET["ind"])?$_GET["ind"]:NULL;
}

session_start();
if (!isset($competencia)) 
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:null;
if (!isset($cod_competencia)) 
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null;
if (!$logo)
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
if (!$logo2)
	$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
if (!isset($modalidades)) 
	$modalidades=isset($_SESSION["modalidades"])?$_SESSION["modalidades"]:null;
if (!isset($categoria)) 
	$categoria=(isset($_SESSION["categoria"])?$_SESSION["categoria"]:null);
if (!isset($individual)) 
	$individual=(isset($_SESSION["individual"])?$_SESSION["individual"]:null);

$pareja=!$individual;
include("funciones.php");
$conexion=conectarse();
?>
<form id="edita-prueba" name="edita-frm" action="php/actualiza-prueba-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>

		<h2>Edita Pruebas de Competencia</h2>
		<div>
			<label for="categoria" class="rotulo">Categoría: </label>
			<input id="categoria" name="categoria_txt" class="cambio" value="<?php echo $categoria; ?>" readonly>

			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
		</div>
		<div>
			<label for="s" class="rotulo">Modalidades: </label>
			<?php 
				$todas=0; 
				include("php/radio-modalidades.php"); 
				$conexion->close();
				?>
		</div>

		<div>
			<br>
			<input type="submit" id="enviar-edit" title="Actualiza esta Prueba de la Competencia" class="cambio" name="actualizar_sbm" value="Actualiza Prueba" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a Pruebas de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>