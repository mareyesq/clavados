<?php 
if (isset($_GET["comp"])){
	$competencia=isset($_GET["comp"])?$_GET["comp"]:NULL;
	$equipo=isset($_GET["eq"])?$_GET["eq"]:NULL;
	$imagen=isset($_GET["img"])?$_GET["img"]:NULL;
	$buscar=isset($_GET["bus"])?$_GET["bus"]:NULL;
	$individual=isset($_GET["individual"])?$_GET["individual"]:NULL;
	$pareja=isset($_GET["pareja"])?$_GET["pareja"]:NULL;
	$eq_juv=isset($_GET["eq_juv"])?$_GET["eq_juv"]:NULL;
}


	$equipo=(isset($_POST["equipo_hdn"])?$_POST["equipo_hdn"]:null);
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null);
	$llamo=(isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:null);
	$imagen=(isset($_POST["imagen_hdn"])?$_POST["imagen_hdn"]:null);
	$imagen2=(isset($_POST["imagen2_hdn"])?$_POST["imagen2_hdn"]:null);
	$imagen3=(isset($_POST["imagen3_hdn"])?$_POST["imagen3_hdn"]:null);
	$imagen4=(isset($_POST["imagen4_hdn"])?$_POST["imagen4_hdn"]:null);

	if (isset($btn_buscar)) {
		$buscar=(isset($_POST["buscar_src"])?$_POST["buscar_src"]:null);
		header("Location: ?op=php/clavadistas-competencia.php&com=$competencia&eq=$equipo&cor=$corto&ori=equipos-competencia.php&bus=$buscar");
		exit();
	}

	session_start();
	if (!isset($llamo)) 
		$llamo=(isset($_SESSION["llamo"])?$_SESSION["llamo"]:null);

	$tipo=isset($_SESSION["tipo"])?$_SESSION["tipo"]:NULL;
	$parael1=isset($_SESSION["parael1"])?$_SESSION["parael1"]:NULL;
	$parael2=isset($_SESSION["parael2"])?$_SESSION["parael2"]:NULL;
	$parael3=isset($_SESSION["parael3"])?$_SESSION["parael3"]:NULL;
	$parael4=isset($_SESSION["parael4"])?$_SESSION["parael4"]:NULL;
	switch ($tipo) {
		case 'C':
			if ($parael4) {
				$clavadista4=$_GET["us"];
				$cod_clavadista4=$_GET["cod"];
				$imagen4=$_GET["img"];
				$sexo4=$_GET["sx"];
				unset($_SESSION["parael4"]);
			}
			if ($parael3) {
				$clavadista3=$_GET["us"];
				$cod_clavadista3=$_GET["cod"];
				$imagen3=$_GET["img"];
				$sexo3=$_GET["sx"];
				unset($_SESSION["parael3"]);
			}
			if ($parael2) {
				$clavadista2=$_GET["us"];
				$cod_clavadista2=$_GET["cod"];
				$imagen2=$_GET["img"];
				$sexo2=$_GET["sx"];
				unset($_SESSION["parael2"]);

			}
			if ($parael1) {
				$clavadista=(isset($_GET["us"])?$_GET["us"]:null);
				$cod_clavadista=(isset($_GET["cod"])?$_GET["cod"]:null);
				$imagen=(isset($_GET["img"])?$_GET["img"]:null);
				$sexo=$_GET["sx"];
				unset($_SESSION["parael1"]);
			}	
			break;
		case 'E':
			$entrenador=(isset($_GET["us"])?$_GET["us"]:null);
			$cod_entrenador=(isset($_GET["cod"])?$_GET["cod"]:null);
			break;
	}
	unset($_SESSION["tipo"]);

	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null);
	if (!isset($logo)) 
		$logo=(isset($_SESSION["logo"])?$_SESSION["logo"]:null);

	if (!isset($individual)) 
		$individual=isset($_SESSION["individual"])?$_SESSION["individual"]:null;

	if (!isset($pareja)) 
		$pareja=isset($_SESSION["pareja"])?$_SESSION["pareja"]:null;

	if (!isset($eq_juv)) 
		$eq_juv=isset($_SESSION["eq_juv"])?$_SESSION["eq_juv"]:null;

	if (!isset($mixto)) 
		$mixto=isset($_SESSION["mixto"])?$_SESSION["mixto"]:null;

	if (!isset($equipo)) 
		$equipo=(isset($_SESSION["equipo"])?$_SESSION["equipo"]:null);

	if (!isset($clave)) 
		$clave=(isset($_SESSION["clave"])?$_SESSION["clave"]:null);

	if (!isset($entrenador)) 
		$entrenador=(isset($_SESSION["entrenador"])?$_SESSION["entrenador"]:null);
	if (!isset($cod_entrenador)) 
		$cod_entrenador=(isset($_SESSION["cod_entrenador"])?$_SESSION["cod_entrenador"]:null);
	if (!isset($clavadista)) 
		$clavadista=(isset($_SESSION["clavadista"])?$_SESSION["clavadista"]:null);
	if (!isset($imagen)) 
		$imagen=(isset($_SESSION["imagen"])?$_SESSION["imagen"]:null);
	
	if (!isset($cod_clavadista)) 
		$cod_clavadista=(isset($_SESSION["cod_clavadista"])?$_SESSION["cod_clavadista"]:null);
	if (!isset($sexo)) 
		$sexo=(isset($_SESSION["sexo"])?$_SESSION["sexo"]:null);
	if (!isset($edad)) 
		$edad=(isset($_SESSION["edad"])?$_SESSION["edad"]:null);

	if (!isset($clavadista2)) 
		$clavadista2=(isset($_SESSION["clavadista2"])?$_SESSION["clavadista2"]:null);
	if (!isset($imagen2)) 
		$imagen2=(isset($_SESSION["imagen2"])?$_SESSION["imagen2"]:null);
	if (!isset($cod_clavadista2)) 
		$cod_clavadista2=(isset($_SESSION["cod_clavadista2"])?$_SESSION["cod_clavadista2"]:null);
	if (!isset($sexo2)) 
		$sexo2=(isset($_SESSION["sexo2"])?$_SESSION["sexo2"]:null);
	if (!isset($edad2)) 
		$edad2=(isset($_SESSION["edad2"])?$_SESSION["edad2"]:null);

	if (!isset($clavadista3)) 
		$clavadista3=(isset($_SESSION["clavadista3"])?$_SESSION["clavadista3"]:null);
	if (!isset($imagen3)) 
		$imagen3=(isset($_SESSION["imagen3"])?$_SESSION["imagen3"]:null);
	if (!isset($cod_clavadista3)) 
		$cod_clavadista3=(isset($_SESSION["cod_clavadista3"])?$_SESSION["cod_clavadista3"]:null);
	if (!isset($sexo3)) 
		$sexo3=(isset($_SESSION["sexo3"])?$_SESSION["sexo3"]:null);
	if (!isset($edad3)) 
		$edad3=(isset($_SESSION["edad3"])?$_SESSION["edad3"]:null);

	if (!isset($clavadista4)) 
		$clavadista4=(isset($_SESSION["clavadista4"])?$_SESSION["clavadista4"]:null);
	if (!isset($imagen4)) 
		$imagen4=(isset($_SESSION["imagen4"])?$_SESSION["imagen4"]:null);
	if (!isset($cod_clavadista4)) 
		$cod_clavadista4=(isset($_SESSION["cod_clavadista4"])?$_SESSION["cod_clavadista4"]:null);
	if (!isset($sexo4)) 
		$sexo4=(isset($_SESSION["sexo4"])?$_SESSION["sexo4"]:null);
	if (!isset($edad4)) 
		$edad4=(isset($_SESSION["edad4"])?$_SESSION["edad4"]:null);

	if (!isset($categoria)) 
		$categoria=(isset($_SESSION["categoria"])?$_SESSION["categoria"]:null);
	
	include("funciones.php");

	$modalidades=(isset($_SESSION["modalidades"])?$_SESSION["modalidades"]:null);
$alta=1;

?>
<form id="alta-clavadista-comp" name="alta-clav-comp-frm" action="php/agrega-clavadista-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
		<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='6%' height='40' />";
				echo $competencia; 
		?>
		</legend>

		<?php include("formulario-clavadista-competencia.php"); ?>
		<div>
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Inscribir" />
			<input type="submit" id="regresar" title="Regresa a equipos en Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
			<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
			<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
		</div>
	</fieldset>
</form>