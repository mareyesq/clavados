<?php 
$email=$_POST["email_slc"];
include("conexion.php");
$consulta="DELETE FROM competidores WHERE email='$email'";
$ejecutar_consulta=$conexion->query($consulta);
if ($ejecutar_consulta)
	$mensaje="El competidor con el email <b>".$email."</b> ha sido eliminado :(";
else
	$mensaje="No se pudo eliminar el competidor con el email <b>".$email."</b> :/";
$conexion->close();
header("Location: ../index.php?op=baja&mensaje=$mensaje");
 ?>