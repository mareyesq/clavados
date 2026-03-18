<?php 
	if ($_GET["us"]){
		$juez=isset($_GET["us"])?$_GET["us"]:null;
		$cod_juez=isset($_GET["cod"])?$_GET["cod"]:null;

	}
	$competencia=isset($_GET["comp"])?$_GET["comp"]:null;
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:null;

	if (!isset($competencia)) 
		$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL);
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);

	session_start();
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;

	if ($llamados){
		$llamo=isset($llamados["jueces-competencia.php"])?$llamados["jueces-competencia.php"]:NULL;
	}

	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;

	if (isset($_POST["regresar_sbm"])){
		if (!isset($llamo) or $llamo=="home" or $llamo==" ") {
			header("Location: ..?op=php/todas-competencias.php");
			exit();
		}
		else{
			if (array_key_exists("jueces-competencia.php", $llamados)){
				unset($llamados["jueces-competencia.php"]);
				$_SESSION["llamados"]=$llamados;
			}
			$transfer="?op=php/$llamo";
			header("Location: $transfer");
			exit();
		}
	}
	if (is_null($cod_usuario)) {
		$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
		$llamo="?op=php/jueces-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null);
	if (!isset($juez)) 
		$juez=(isset($_SESSION["juez"])?$_SESSION["juez"]:null);
	if (!isset($cod_juez)) 
		$cod_juez=isset($_SESSION["cod_juez"])?$_SESSION["cod_juez"]:null;
	$_SESSION["llamo"]=isset($llamo)?$llamo:NULL;
	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;

?>
<form id="alta-juez" name="alta-frm" action="php/agrega-juez-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center">Registro de juez de Competencia: <?php echo $competencia; ?></legend>
		<div>
			<label for="juez">Juez: </label>
			<input type="text" id="juez" class="cambio" name="juez_txt" placeholder="Escribe parte del nombre y luego dale Buscar" title="nombre del juez" value="<?php echo "$juez"; ?>"/>
			<input type="submit" id="buscar-juez" name="busca_juez_sbm" class="cambio" value="Buscar" title="Busca el Juez">
			<input type="submit" id="nuevo-juez" name="nuevo_juez_sbm" class="cambio" value="Nuevo" title="Registrar un Juez Nuevo">
			<br>
			<label>Sombra</label>
			<input type="checkbox" id="sel-sombra" name="sel_sombra_slc" <?php if ($sombra) {
				echo " checked";
			} ?> title="Juez sombra">
			<input type="hidden" name="origen_hdn" value="php/alta-juez-competencia.php&comp=$competencia&cco=$cod_competencia">
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="cod_juez_hdn" value="<?php echo $cod_juez; ?>">
		</div>
		<div>
			<br><br>
			<input type="submit" id="enviar-alta" title="Registra este Juez de la Competencia" class="cambio" name="registrar_sbm" value="Registrar" />
			<input type="submit" id="regresar" title="Regresa a jueces de la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	</fieldset>
</form>