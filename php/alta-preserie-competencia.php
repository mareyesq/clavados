<?php
session_start();
include("funciones.php");
if (isset($_GET["cco"])){
	$competencia=isset($_GET["com"])?$_GET["com"]:null;
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:null;
	include("preserie-limpia-session.php");
}
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:null;

include("preserie-toma-session.php");
$alta=TRUE;
?>
<form id="alta-preserie" name="alta-frm" action="php/agrega-preserie-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend align="center" class="rotulo">
		<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='25%'/>";
				echo $competencia; 
		?>
	</legend>
	<h3>Serie Predefinida</h3>
		<?php include("formulario-preserie.php"); ?>
		<div>
			<br>
			<input type="submit" id="enviar-alta" title="Registra esta seri predefinida de la Competencia" class="cambio" name="agregar_sbm" value="Registrar Serie" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a Series predefinidas de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>