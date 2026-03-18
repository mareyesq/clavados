<?php
if (isset($_GET["comp"])) {
	$competencia=(isset($_GET["comp"])?$_GET["comp"]:null);
	$cod_competencia=(isset($_GET["cco"])?$_GET["cco"]:null);
}

	if (!isset($competencia)) 
		$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);

	session_start();
	$logo=(isset($_SESSION["logo"])?$_SESSION["logo"]:null);
	$logo2=(isset($_SESSION["logo2"])?$_SESSION["logo2"]:null);

	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);
	
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null);

	if (isset($_POST["regresar_sbm"])){
		header("Location: ?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}

	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
	if (is_null($cod_usuario)) {
		$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
		$llamo="?op=php/pruebas-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	include("funciones.php");
	
	$es_administrador_competencia=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($es_administrador_competencia,0,5)=="Error"){
		$mensaje=$es_administrador_competencia;
		$llamo="?op=php/pruebas-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	$modalidades=(isset($_SESSION["modalidades"])?$_SESSION["modalidades"]:null);
	if (!isset($categoria)) 
		$categoria=(isset($_SESSION["categoria"])?$_SESSION["categoria"]:null);
?>
<form id="alta-prueba" name="alta-frm" action="php/agrega-prueba-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<h2>Registro de Pruebas de Competencia</h2>
		<div>
			<label for="categoria" class="rotulo">Categoría: </label>
			<select id="categoria" name="categoria_slc" class="cambio" onChange='this.form.submit()'>
				<option value="">- - -</option>	
				<?php include("php/select-categoria.php") ?>
			</select>

			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
		</div>
		<div>
			<label for="s"  class="rotulo">Modalidades: </label>
			<?php $todas=1; include("php/radio-modalidades.php"); ?>
		</div>

		<div>
			<br>
			<input type="submit" id="enviar-alta" title="Registra esta Prueba de la Competencia" class="cambio" name="registrar_sbm" value="Registrar Prueba" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>