<?php
session_start();
if (!$_SESSION["autentificado"]){
	$mensaje="usuario no autorizado, debe iniciar una sesión";
	header("Location: ..?op=php/inicia-sesion.php&mensaje=$mensaje");
}

if (!isset($_SESSION["nombre_usuario"]))
	echo "usuario no definido";
$usuario=$_SESSION["usuario"];
$nombre_usuario=utf8_decode($_SESSION["nombre_usuario"]);

include("conexion.php");
include("funciones.php");
$rol=defina_rol($usuario,$conexion);
$administrador=((in_array("A", $rol)?"A":null));
$clavadista=((in_array("C", $rol)?"C":null));
$entrenador=((in_array("E", $rol)?"E":null));
$juez=((in_array("J", $rol)?"J":null));
?>

<legend>Bienvenido <?php echo $nombre_usuario ?></legend>
<ul id="menu-sesion">
   	<li><a href="index.php?op=php/cambio-usuario.php">Actualiza tu Información</a></li>
   	<li><a href="index.php?op=php/cambio-password.php">Cambia tu Contraseña</a></li>
	<?php 
		if ($administrador=="A")
			echo "<li><a href='index.php?op=php/consulta-competencias.php'>Competencias</a></li>";
		if ($clavadista=="C" or $entrenador=="E")
			echo "<li><a href='index.php?op=php/consulta-planillas.php'>Planillas</a></li>";
	?>
   	<li><a href="index.php?op=php/cierra-sesion.php">Cierra tu Sesión</a></li>
</ul>
            
