<?php 
if (isset($_GET["pl"])) {
	$planilla=$_GET["pl"];
	$equipo=$_GET["eq"];
	$clavadista=$_GET["cl"];
	$categoria=$_GET["cat"];
	$modalidad=$_GET["md"];
	$competencia=$_GET["comp"];
	$cod_competencia=$_GET["cco"];
	$cod_clavadista=$_GET["ccl"];
	$cod_entrenador=$_GET["cen"];
	$corto=$_GET["cor"];
	$llamo=$_GET["ori"];
	$llamo=str_replace("*", "&", $llamo);
}

session_start();
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
include ("funciones.php");
if (!$planilla)
	$planilla=trim($_SESSION["planilla"]);

if (!$clavadista)
	$clavadista=trim($_SESSION["clavadista"]);

if (!$equipo)
	$equipo=trim($_SESSION["equipo"]);

if (!$cod_clavadista)
	$cod_clavadista=trim($_SESSION["cod_clavadista"]);

if (!$cod_entrenador)
	$cod_entrenador=trim($_SESSION["cod_entrenador"]);

if (!$corto)
	$corto=trim($_SESSION["corto"]);

if (!$llamo)
	$llamo=trim($_SESSION["llamo"]);

if (!$categoria)
	$categoria=trim($_SESSION["categoria"]);

if (!$modalidad)
	$modalidad=trim($_SESSION["modalidad"]);

if (!$competencia)
	$competencia=trim($_SESSION["competencia"]);
if (!$competencia)
	$competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:NULL;

if (!$clave)
	$clave=trim($_SESSION["clave"]);

$mensaje=NULL;

if (!$_SESSION["autentificado"]){
	$mensaje="Para eliminar la planilla, debes iniciar sesión";
	header("Location: ?op=php/inicia-sesion.php&mensaje=$mensaje&com=$competencia");
	exit();
}
if (!isset($cod_usuario))
	$cod_usuario=trim($_SESSION["usuario_id"]);

if (!(($cod_usuario==$cod_clavadista) or
 	 ($cod_usuario==$cod_entrenador))) {
//	$cod_competencia=determina_competencia($competencia);
	$es_administrador=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($es_administrador,0,5)=="Error"){
		$mensaje="Solo el clavadista, el entrenador o un administrador de la competencia pueden eliminar la planilla";
		header("Location: ..?op=".$llamo."&mensaje=$mensaje");
		exit();
	}
}

?>
<form id="eliminar-planilla" name="elimina-planilla-frm" action="php/elimina-planilla.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
	<h2>Eliminar Planilla</h2>
		<div>
			<label for="clavadista" class="rotulo">Clavadista: </label>
			<input type="text" id="clavadista" class="cambio" name="clavadista_txt" value="<?php echo $clavadista; ?>" readonly/>
		</div>	
		<div>
			<label for="categoria" class="rotulo">Categoría: </label>
			<input type="text" id="categoria" class="cambio" name="categoria_txt" value="<?php echo $categoria; ?>" readonly/>
		</div>	
		<div>
			<label for="modalidad" class="rotulo">Modalidad: </label>
			<input type="text" id="modalidad" class="cambio" name="modalidad_txt" value="<?php echo $modalidad; ?>" readonly/>
		</div>	
		<div>
			<label for="clave" class="rotulo">Clave Inscripción: </label>
			<input type="password" id="clave" class="cambio" name="clave_txt" placeholder="Escribe la clave de inscripción" title="Clave de inscripción de tu equipo para esta competencia" value="<?php echo $clave; ?>" />
			<input type="hidden" name="cod_clavadista_hdn" value="<?php echo $cod_clavadista; ?> ">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?> ">
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?> ">
			<input type="hidden" name="cod_entrenador_hdn" value="<?php echo $cod_entrenador; ?> ">
			<input type="hidden" name="planilla_hdn" value="<?php echo $planilla; ?> ">
			<input type="hidden" name="corto_hdn" value="<?php echo $corto; ?> ">
			<input type="hidden" name="llamo_hdn" value="<?php echo $llamo; ?> ">
		</div>
		<div>
			<br>
			<input type="submit" id="eliminar" class="cambio" name="eliminar_sbm" value="Eliminar" title="Elimina la planilla de la Competencia">
			<input type="submit" id="regresar" title="Regresa a las planillas del clavadista" class="cambio" name="regresar_sbm" value="Regresar">

		</div> 
	</fieldset>
</form>
