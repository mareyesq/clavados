<?php 
	if ($_GET["us"]){
		$juez=isset($_GET["us"])?$_GET["us"]:null;
		$cod_juez=isset($_GET["cod"])?$_GET["cod"]:null;
	}

	include("funciones.php");	

	$competencia=isset($_GET["comp"])?$_GET["comp"]:null;
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:null;

	if (!isset($competencia)) 
		$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL);

	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);

	if (!isset($numero_evento)) 
		$numero_evento=(isset($_POST["numero_evento_hdn"])?$_POST["numero_evento_hdn"]:NULL);

	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL);

	if (!isset($cod_competencia)) 
		$cod_competencia=determina_competencia($competencia);

	if (!isset($numero_evento)) 
		$numero_evento=(isset($_SESSION["numero_evento_hdn"])?$_SESSION["numero_evento_hdn"]:NULL);

	if (!isset($llamo)) 
		$llamo=(isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL);

	if ($llamo)
		$llamo=str_replace("*", "&", $llamo);

	session_start();

	if (isset($_POST["regresar_sbm"])){
		if (!isset($llamo_r)) {
			$llamor=isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):NULL;
			$llamo_r=str_replace("*", "&", $llamor);
		}
		header("Location: $llamo_r");
		exit();
	}

	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;

	if (is_null($cod_usuario)) {
		$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
		header("Location: $llamo_r&mensaje=$mensaje");
		exit();
	}

	$es_admin=administrador_competencia($cod_usuario,$cod_competencia);
 	if (substr($es_admin,0,5)=="Error") {
		header("Location: $llamo_r&mensaje=$es_admin");
		exit();
	}

	$encabezado=isset($_POST["encabezado_hdn"])?trim($_POST["encabezado"]):NULL;
	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);
	if (!isset($juez)) 
		$juez=(isset($_SESSION["juez"])?$_SESSION["juez"]:null);
	if (!isset($cod_juez)) 
		$cod_juez=isset($_SESSION["cod_juez"])?$_SESSION["cod_juez"]:null;
	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	$_SESSION["llamo"]=isset($llamo)?$llamo:NULL;

	if (isset($_POST["buscar_arbitro_sbm"])){
		header("Location: ?op=php/busca-juez-competencia.php&tipo=J&ori=$llamo&ubi=25&com=$competencia&cco=$cod_competencia");
		exit();
	}
	if (isset($_POST["agrega_juez_sombra_sbm"])){
		header("Location: ?op=php/busca-juez-competencia.php&tipo=J&ori=$llamo&ubi=57&com=$competencia&cco=$cod_competencia");
		exit();
	}
	if (isset($_POST["jueces_sombra_sbm"])){
		include("toma-jueces-sombra.php");
		header("Location: ?op=$llamo");
		exit();
	}

	if (isset($_POST["limpia_sombra_sbm"])){
		include("limpia-jueces-sombra.php");
		header("Location: ?op=$llamo");
		exit();
	}

?>
<form id="alta-juez" name="alta-frm" action="php/agrega-juez-evento.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center">Competencia: <?php echo $competencia ?></legend>
		<h3><?php echo 'Jueces de '.$encabezado ?></h3>
		<div>
<!-- 			<label for="panel">Panel: </label>
			<input type="number" size="1" maxlength="1" min="1" max="2" id="panel" class="cambio" name="panel_nbr" title="Número de Panel de Jueces" value="<?php echo "$panel"; ?>" readonly/>
			<label for="ubicacion">Ubicaci&oacute;n: </label>
			<input type="number" min="1" max="11" id="ubicacion" class="cambio" name="ubicacion_nbr" placeholder="Escribe el número de ubicación del Juez" title="Número de ubicación del juez dentro del panel" value="<?php echo "$ubicacion"; ?>">
 -->			<label for="juez">Juez: </label>
			<input type="text" id="juez" class="cambio" name="juez_txt" title="nombre del juez" value="<?php echo $juez; ?>" readonly >
			<input type="submit" id="buscar-juez" name="busca_juez_sbm" class="cambio" value="Buscar" title="Busca el Juez">
			<input type="submit" id="nuevo-juez" name="nuevo_juez_sbm" class="cambio" value="Nuevo" title="Registrar un Juez Nuevo">
			<input type="hidden" name="llamo_hdn" value="<?php echo $llamo; ?>">
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="cod_juez_hdn" value="<?php echo $cod_juez; ?>">
		</div>

		<div>
			<br><br>
			<input type="submit" id="enviar-alta" title="Registra este Administrador de la Competencia" class="cambio" name="registrar_sbm" value="Registrar" />
			<input type="submit" id="regresar" title="Regresa a administradores de la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	</fieldset>
</form>