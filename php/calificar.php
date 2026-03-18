<?php 
if (isset($_GET["com"])) {
	$cod_competencia=trim($_GET["cco"]);
	$competencia=trim($_GET["com"]);
	$origen="?op=php/muestra-competencia.php*com=$competencia*cco=$cod_competencia";
}

session_start();
include("funciones.php");
if (!isset($competencia))
	$competencia=isset($_SESSION["competencia"])?trim($_SESSION["competencia"]):NULL;
if (!isset($cod_competencia))
	$cod_competencia=isset($_SESSION["cod_competencia"])?trim($_SESSION["cod_competencia"]):NULL;

$ubicacion=isset($_SESSION["ubicacion"])?$_SESSION["ubicacion"]:NULL;
$password=isset($_SESSION["password"])?$_SESSION["password"]:NULL;
$calificacion=isset($_SESSION["calificacion"])?$_SESSION["calificacion"]:NULL;
$calificar=isset($_SESSION["calificar"])?$_SESSION["calificar"]:NULL;
$proteger=isset($_SESSION["proteger"])?$_SESSION["proteger"]:NULL;
$protegido=isset($_SESSION["protegido"])?$_SESSION["protegido"]:NULL;
?>

<form id="calificar" name="calificar-frm" action="?op=php/calificar-1.php" method="post" enctype="multipart/form-data">
	<h2>PANEL DE CALIFICACION</h2>
	<?php include("calificar-formulario.php") ?>
	<input type="hidden" name="ubicacion_hdn" value="<?php echo $ubicacion ?>" >
</form>