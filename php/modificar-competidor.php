<?php 
// Asigno a variables php los valores que vienen en el formulario
$codcompetidor=$_POST["codcompetidor_hdn"];
$email=$_POST["email_hdn"];
$nombre=$_POST["nombre_txt"];
$sexo=$_POST["sexo_rdo"];
$nacimiento=$_POST["nacimiento_txt"];
$passw=$_POST["passw_txt"];
$passw_conf=$_POST["passw_conf_txt"];
$nom_equipo=$_POST["equipo_slc"];
$nom_entrenador=$_POST["entrenador_slc"];

if ($passw==$passw_conf){
	include("conexion.php");
	$consulta="SELECT * FROM competidores WHERE codcompetidor=$codcompetidor";
	$ejecutar_consulta=$conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1) {
		include("funciones.php");

	// ejecuto función para tomar el código del equipo
		$equipo=determina_equipo($nom_equipo,$conexion);
	// ejecuto función para tomar el código del Entrenador
		$entrenador=determina_entrenador($nom_entrenador,$conexion);

	// ejecuto función para subir la imagen
		$tipo=$_FILES["foto_fls"]["type"];
		$archivo=$_FILES["foto_fls"]["tmp_name"];
		$se_subio_imagen=subir_imagen($tipo,$archivo,$email);
		// Si la foto en el formulario viene vacía, entonces le asigno el valor de la imagen genérica, sino entonces el nombre de la foto que se subio (operador ternario)
		$imagen=empty($archivo)?$imagen_generica:$se_subio_imagen;
		$consulta="UPDATE competidores SET nombre='$nombre', sexo='$sexo', nacimiento='$nacimiento', email='$email' , equipo=$equipo, entrenador=$entrenador, password='$passw', imagen='$imagen' WHERE codcompetidor=$codcompetidor  ";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		if ($ejecutar_consulta)
			$mensaje="Se han hecho los cambios en los datos del competidor con el email <b>$email</b> :)";
		else
			$mensaje="No se hicieron  los cambios en los datos del competidor con el email <b>$email</b> :(";


	} else 
		$mensaje="No puedo hacer los cambios en los datos del competidor con el email <b>$email</b> porque no existe o está duplicado :/";
}
else {
	$mensaje="No se hicieron los cambios en los datos del competidor con el email <b>$email</b> porque la contraseña no se pudo confirmar :(";
}
$conexion->close();
header("Location: ../index.php?op=cambios&mensaje=$mensaje");

 ?>