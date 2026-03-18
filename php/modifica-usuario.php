<?php 
// Asigno a variables php los valores que vienen en el formulario
$llamo=isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:NULL;
session_start();
if (isset($_POST["regresar_sbm"])){
	unset($_SESSION["llamo"]);
	unset($_SESSION["nombre"]);
	unset($_SESSION["sexo"]);
	unset($_SESSION["nacimiento"]);
	unset($_SESSION["administrador"]);
	unset($_SESSION["clavadista"]);
	unset($_SESSION["entrenador"]);
	unset($_SESSION["juez"]);
	unset($_SESSION["pais"]);
	unset($_SESSION["email"]);
	unset($_SESSION["telefono"]);
	unset($_SESSION["foto"]);
	unset($_SESSION["dia"]);
	unset($_SESSION["mes"]);
	unset($_SESSION["ano"]);
	unset($_SESSION["password"]);
	unset($_SESSION["password_conf"]);
	unset($_SESSION["imagen"]);
	if (!isset($llamo) or $llamo=="home" or $llamo==" ") {
		header("Location: ..?op=php/todas-competencias.php");
	}
	else{
		$llamo=trim($llamo);
		header("Location: ..?op=php/$llamo");
	}
	exit();
}

$alta=FALSE;
include("funciones.php");
include("valida-usuario.php");

if (is_null($mensaje)){
	if(empty($archivo)){
		$imagen=$_POST["foto_hdn"];
	}
	else{
	// Dependiendo de el sexo, colocamos una imagen predeterminada
		$imagen_generica=($sexo=="M")?"amigo.png":"amiga.png";
		// ejecuto función para subir la imagen
		$se_subio_imagen=subir_imagen($tipo,$archivo,$email);
		// Si la foto en el formulario viene vacía, entonces le asigno el valor de la imagen genérica, sino entonces el nombre de la foto que se subio (operador ternario)
		$imagen=empty($archivo)?$imagen_generica:$se_subio_imagen;
	}
	$cod_pais=determina_pais($pais);
	$password=equivalencia($password);
	$conexion=conectarse();
	$consulta="UPDATE usuarios SET nombre='$nombre', sexo='$sexo', nacimiento='$nacimiento', email='$email' , pais=$cod_pais, administrador='$administrador', clavadista='$clavadista', entrenador='$entrenador', juez='$juez', password='$password', imagen='$imagen', telefono='$telefono' WHERE cod_usuario=$cod_usuario";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

	if ($ejecutar_consulta)
		$mensaje="Se han hecho los cambios en los datos del usuario con el email <b>$email</b> :)";
	else
		$mensaje="No se hicieron  los cambios en los datos del usuario con el email <b>$email</b> :(";
	unset($_SESSION["llamo"]);
	unset($_SESSION["nombre"]);
	unset($_SESSION["sexo"]);
	unset($_SESSION["nacimiento"]);
	unset($_SESSION["administrador"]);
	unset($_SESSION["clavadista"]);
	unset($_SESSION["entrenador"]);
	unset($_SESSION["juez"]);
	unset($_SESSION["pais"]);
	unset($_SESSION["email"]);	
	unset($_SESSION["telefono"]);
	unset($_SESSION["foto"]);
	unset($_SESSION["dia"]);
	unset($_SESSION["mes"]);
	unset($_SESSION["ano"]);
	unset($_SESSION["password"]);
	unset($_SESSION["password_conf"]);
	unset($_SESSION["imagen"]);
	$conexion->close();
	if (!isset($llamo) or strlen(trim($llamo))==0)
		$llamo="todas-competencias.php";
	else
		$llamo=trim($llamo);

	$transfiere="Location: ..?op=php/".$llamo."&mensaje=$mensaje";
	header($transfiere);
	exit();
} 
else
	header("Location: ..?op=php/cambio-usuario.php&mensaje=$mensaje");
 ?>