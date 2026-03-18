<?php 
if (!isset($competencia)) 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
if (!isset($cod_competencia)) 
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
if (!isset($logo)) 
	$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null);
session_start();
if (!isset($competencia)) 
	$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);
if (!isset($cod_competencia)) 
	$cod_competencia=(isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null);

$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["logo"]=$logo;

if (isset($_POST["regresar_sbm"])){
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	if ($llamados){
			$llamo=isset($llamados["marcas-competencia.php"])?$llamados["marcas-competencia.php"]:NULL;
	}
	unset($_SESSION["categoria"]);
	unset($_SESSION["cod_modalidad"]);
	unset($llamados["marcas-competencia.php"]);
	$_SESSION["llamados"]=$llamados;
	$transfer="?op=php/$llamo";
	header("Location: $transfer");
	exit();
}

$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
if (is_null($cod_usuario)) {
	$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
	$llamo="?op=php/marcas-competencia.php&com=$competencia&cco=$cod_competencia";
	header("Location: $llamo&mensaje=$mensaje");
	exit();
}

include("funciones.php");
$es_administrador_competencia=administrador_competencia($cod_usuario,$cod_competencia);
if (substr($es_administrador_competencia,0,5)=="Error"){
	$mensaje=$es_administrador_competencia;
	$llamo="?op=php/marcas-competencia.php&com=$competencia&cco=$cod_competencia";
	header("Location: $llamo&mensaje=$mensaje");
	exit();
}

if (!isset($categoria)) 
	$categoria=(isset($_SESSION["categoria"])?$_SESSION["categoria"]:null);
if (!isset($modalidad)) 
	$modalidad=(isset($_SESSION["modalidad"])?$_SESSION["modalidad"]:null);
if (!isset($cod_modalidad)) 
	$cod_modalidad=(isset($_SESSION["cod_modalidad"])?$_SESSION["cod_modalidad"]:null);
if (!isset($marca_f)) 
	$marca_f=(isset($_SESSION["marca_f"])?$_SESSION["marca_f"]:null);
if (!isset($grado_f)) 
	$grado_f=(isset($_SESSION["grado_f"])?$_SESSION["grado_f"]:null);
if (!isset($prom_f)) 
	$prom_f=(isset($_SESSION["prom_f"])?$_SESSION["prom_f"]:null);

if (!isset($marca_m)) 
	$marca_m=(isset($_SESSION["marca_m"])?$_SESSION["marca_m"]:null);
if (!isset($grado_m)) 
	$grado_m=(isset($_SESSION["grado_m"])?$_SESSION["grado_m"]:null);
if (!isset($prom_m)) 
	$prom_m=(isset($_SESSION["prom_m"])?$_SESSION["prom_m"]:null);

$alta=TRUE;
$baja=FALSE;
?>
<form id="alta-prueba" name="alta-frm" action="php/agrega-marca-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
			<?php 
				if ($logo) 
					echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
					echo $competencia; 
			?>
		</legend>
		<h3>Registro de Marcas de Competencia</h3>
		<?php include("formulario-marcas.php"); ?>
		<div>
			<br>
			<input type="submit" id="enviar-alta" title="Registra esta Marca de la Competencia" class="cambio" name="registrar_sbm" value="Registrar Marca" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>