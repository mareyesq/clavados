<?php 
if (isset($_GET["com"])) {
	$competencia=$_GET["com"];
	$cod_competencia=$_GET["cco"];
	$equipo=$_GET["eq"];
	$corto=$_GET["cor"];
	$logo=$_GET["lg"];
}

session_start();
include("funciones.php");
if (!$competencia)
	$competencia=$_SESSION["competencia"];

if (!$cod_competencia)
	$cod_competencia=$_SESSION["cod_competencia"];

if (!$equipo)
	$equipo=$_SESSION["equipo"];

if (!$corto)
	$corto=$_SESSION["corto"];

if (!$logo)
	$logo=$_SESSION["logo"];

if (!$_SESSION["autentificado"]){
	$mensaje="Para retirar tu equipo de la Competencia $competencia, debes iniciar sesión";
	header("Location: index.php?op=php/equipos-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo&mensaje=$mensaje");
	exit();
}
?>
<form id="retirar-equipo" name="retiro-equ-frm" action="php/baja-inscripcion-equipo.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center">Retiro del Equipo <?php echo "$equipo de la competencia $competencia"; ?> </legend>
		<?php if (isset($equipo)) include("lee-equipo.php");?>
		<div>
			<input type="hidden" name="competencia_hdn" value="<?php echo "$competencia"; ?> ">
		</div>
		<div>
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo "$cod_competencia"; ?> ">
			<input type="hidden" name="corto_hdn" value="<?php echo "$corto"; ?> ">
			<input type="hidden" name="equipo_hdn" value="<?php echo "$equipo"; ?> ">
			<input type="hidden" name="logo_hdn" value="<?php echo "$logo"; ?> ">
		</div>
		<div>
			<br>
			<input type="submit" id="retirar-equipo" class="cambio" name="retirar_equipo_btn" value="Retirar" title="Retira el equipo de la Competencia" />
			<input type="submit" id="regresar" title="Regresa a los equipos de la competencia" class="cambio" name="regresar_sbm" value="Regresar" />

		</div> 
	</fieldset>
</form>
