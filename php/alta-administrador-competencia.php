<?php 
	if ($_GET["us"]){
		$administrador=isset($_GET["us"])?$_GET["us"]:null;
		$cod_administrador=isset($_GET["cod"])?$_GET["cod"]:null;

	}
	include("funciones.php");	

	session_start();
	$competencia=isset($_GET["comp"])?$_GET["comp"]:null;

	if (!isset($competencia)) 
		$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL);

	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL);

	$logo=(isset($_SESSION["logo"])?$_SESSION["logo"]:NULL);
	$logo2=(isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL);

	$cod_competencia=isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL;
	
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:NULL);

	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;

	if ($llamados){
		$llamo=isset($llamados["administradores-competencia.php"])?$llamados["administradores-competencia.php"]:NULL;
	}
	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;

	if (isset($_POST["regresar_sbm"])){
		if (!isset($llamo) or $llamo=="home" or $llamo==" ") {
			header("Location: ..?op=php/todas-competencias.php");
			exit();
		}
		else{
			if (array_key_exists("administradores-competencia.php", $llamados)){
				unset($llamados["administradores-competencia.php"]);
				$_SESSION["llamados"]=$llamados;
			}
			$transfer="?op=php/$llamo";
			header("Location: $transfer");
			exit();
		}
	}
	if (is_null($cod_usuario)) {
		$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
		$llamo="?op=php/administradores-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	 $es_admin_ppal=administrador_ppal_competencia($cod_usuario,$cod_competencia);
 	if (substr($es_admin_ppal,0,5)=="Error") {
 		$mensaje=$es_admin_ppal;
		$llamo="?op=php/administradores-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	$principal=(isset($_SESSION["principal"])?$_SESSION["principal"]:null);

	if (!isset($administrador)) 
		$administrador=(isset($_SESSION["administrador"])?$_SESSION["administrador"]:null);
	if (!isset($cod_administrador)) 
		$cod_administrador=isset($_SESSION["cod_administrador"])?$_SESSION["cod_administrador"]:null;
	$_SESSION["llamo"]=isset($llamo)?$llamo:NULL;
	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	$_SESSION["logo"]=isset($logo)?$logo:NULL;

?>
<form id="alta-administrador" name="alta-frm" action="php/agrega-administrador-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<div>
			<label for="administrador" class="rotulo">Administrador: </label>
			<input type="text" id="administrador" class="cambio" name="administrador_txt" size="50" placeholder="Escribe parte del nombre y luego dale Buscar" title="nombre del administrador" value="<?php echo "$administrador"; ?>"/>
			<input type="submit" id="buscar-admin" name="busca_administrador_sbm" class="cambio" value="Buscar" title="Busca el Administrador">
			<input type="submit" id="nuevo-admin" name="nuevo_administrador_sbm" class="cambio" value="Nuevo" title="Registrar un Administrador Nuevo">
			<input type="hidden" name="origen_hdn" value="php/alta-administrador-competencia.php">
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="cod_administrador_hdn" value="<?php echo $cod_administrador; ?>">
		</div>
		<div>
			<label for="s"  class="rotulo">Administrador Principal: </label>
			<input type="radio" id="s" name="principal_rdo" title="Es administrador principal" value="S" />&nbsp<label for="n">Sí</label>
			&nbsp;&nbsp;
			<input type="radio" id="n" name="principal_rdo" title="NO es administrador principal" value="N" />&nbsp<label for="n">No</label>
		</div>

		<div>
			<br>
			<input type="submit" id="enviar-alta" title="Registra este Administrador de la Competencia" class="cambio" name="registrar_sbm" value="Registrar" />
			<input type="submit" id="regresar" title="Regresa a administradores de la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	</fieldset>
</form>