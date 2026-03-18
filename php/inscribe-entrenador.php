<?php 
if (!$_SESSION["autentificado"]){
	$competencia=$_GET['com'];
	$mensaje="Para hacer inscripciones, debes iniciar sesión";
	header("Location: index.php?op=php/muestra-competencia.php&mensaje=$mensaje&com=$competencia");
	return;
}

if (isset($_GET["com"])){
	$competencia=$_GET["com"];
	$_SESSION["competencia"]=$competencia;
}
elseif (isset($_SESSION["competencia"])) {
	$competencia=$_SESSION["competencia"];
}
else
	$competencia=null;

$equipo=(isset($_GET["eq"])?$_GET["eq"]:null);
session_start();
if(isset($_POST["buscar_equipo_btn"]))
	$buscar=$_POST["buscar_equipo_btn"];
if(isset($_POST["alta_equipo_btn"]))
	$alta=$_POST["alta_equipo_btn"];
if(isset($_POST["inscribir_equipo_btn"]))
	$inscribir=$_POST["inscribir_equipo_btn"];

if(isset($buscar)){
	if (!isset($_POST["equipo_txt"])) {
		$mensaje="debe ingresar el nombre del Equipo a buscar";
	}
	else{
		$equipo=$_POST["equipo_txt"];
		$_SESSION["equipo"]=$equipo;
		include("busca-equipo.php");
	}
}
elseif (isset($alta)) {
	if (isset($_POST["equipo_txt"])) {
		$equipo=$_POST["equipo_txt"];
		$_SESSION["equipo"]=$equipo;
		include("php/alta-equipo.php");
	}
}
elseif (isset($inscribir)) {
	if (!isset($_POST["equipo_txt"])) {
		$mensaje="debe ingresar el nombre del Equipo a inscribir";
	}
	else
		if ($equipo) {
			$equipo=$_POST["equipo_txt"];
			$_SESSION["equipo"]=$equipo;
		}
}
?>
<form id="inscripcion-equipo" name="insc-equ-frm" action="php/opciones-inscripcion-equipo.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Inscripción del Equipo en <?php if (isset($competencia)) echo "$competencia"; ?> </legend>
		<?php 
			if (isset($equipo)){
				include("lee-equipo.php");
				include("inscribe-equipo1.php");
				include("inscribe-equipo2.php");
				include("mensajes.php");
			}
			else
				include("inscribe-equipo1.php");
		 ?>
	</fieldset>
</form>