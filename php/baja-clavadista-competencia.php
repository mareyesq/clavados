<?php 

if (isset($_GET["com"])) {
	$competencia=$_GET["com"];
	$cod_competencia=$_GET["cco"];
	$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;
	$clavadista=$_GET["cl"];
	$cod_clavadista=$_GET["ccl"];
	$cod_clavadista2=$_GET["ccl2"];
	$imagen=$_GET["im"];
	$imagen2=$_GET["im2"];
	$equipo=$_GET["eq"];
	$corto=$_GET["ceq"];
}

include("funciones.php");
session_start();
if (!$competencia)
	$competencia=$_SESSION["competencia"];

if (!$cod_competencia)
	$cod_competencia=$_SESSION["cod_competencia"];

if (!$clavadista)
	$clavadista=$_SESSION["clavadista"];

if (!$cod_clavadista)
	$cod_clavadista=$_SESSION["cod_clavadista"];

if (!$cod_clavadista2)
	$cod_clavadista2=$_SESSION["cod_clavadista2"];

if (!$equipo)
	$equipo=$_SESSION["equipo"];

if (!$corto)
	$corto=$_SESSION["corto"];

if (!isset($clave)) 
	$clave=(isset($_SESSION["clave"])?$_SESSION["clave"]:null);

if (!$_SESSION["autentificado"]){
	$mensaje="Para retirar a este clavadista de la Competencia, debes iniciar sesión";
	header("Location: ..?op=php/inicia-sesion.php&mensaje=$mensaje&com=$competencia");
	exit();
}
?>
<form id="retirar-clavadista" name="retiro-cla-frm" action="php/elimina-clavadista-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
			<?php 
				if ($logo) 
					echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
					echo $competencia;
					echo "<br><br>"; 
				if ($imagen) 
					echo "<img  class='textwrap' src='img/fotos/$imagen' width='6%'/>&nbsp;&nbsp;";
				if ($imagen2) 
					echo "<img  class='textwrap' src='img/fotos/$imagen2' width='6%'/>&nbsp;&nbsp;";
			?>
		</legend>
		<br><br><br>
		<span>Retiro del Clavadista <?php echo "$clavadista de la competencia"; ?> </span>
 		<?php 
			if (isset($equipo)) include("lee-equipo.php");
 		 	echo "<br>del Equipo: $equipo";
 		?>
		<div>
			<label for="clave">Clave Inscripción: </label>
			<input type="password" id="clave" class="cambio" name="clave_txt" placeholder="Escribe la clave de inscripción" title="Clave de inscripción de tu equipo para esta competencia" value="<?php echo $clave; ?>" />
			<input type="hidden" name="competencia_hdn" value="<?php echo "$competencia"; ?> ">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo "$cod_competencia"; ?> ">
			<input type="hidden" name="clavadista_hdn" value="<?php echo "$clavadista"; ?> ">
			<input type="hidden" name="cod_clavadista_hdn" value="<?php echo "$cod_clavadista"; ?> ">
			<input type="hidden" name="cod_clavadista2_hdn" value="<?php echo "$cod_clavadista2"; ?> ">
			<input type="hidden" name="equipo_hdn" value="<?php echo "$equipo"; ?> ">
			<input type="hidden" name="corto_hdn" value="<?php echo "$corto"; ?> ">
		</div>
		<div>
			<br>
			<input type="submit" id="retirar-clavadista" class="cambio" name="retirar_clavadista_btn" value="Retirar" title="Retira el clavadista de la Competencia" />
			<input type="submit" id="regresar" title="Regresa a la competencia" class="cambio" name="regresar_sbm" value="Regresar" />

		</div> 
	</fieldset>
</form>
