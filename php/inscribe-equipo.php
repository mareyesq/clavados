<?php 
if (isset($_GET["com"])) $competencia=$_GET["com"];

if (!isset($competencia)) 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);

if (isset($_POST["regresar_sbm"])){
	header("Location: ?op=php/muestra-competencia.php&com=$competencia");
	return;
}

session_start();
if (!isset($competencia))
	$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);

if (isset($competencia)) 
	$_SESSION["competencia"]=$competencia;
else
	$competencia=null;

if (!$_SESSION["autentificado"]){
	$mensaje="Para hacer inscripciones, debes iniciar sesión";
	header("Location: ?op=php/equipos-competencia.php&mensaje=$mensaje&com=$competencia");
	return;
}

$equipo=(isset($_GET["eq"])?$_GET["eq"]:null);
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