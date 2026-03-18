<?php 
$llamo=isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:NULL;
session_start();
if (is_null($llamo)) {
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	if (!is_null($llamados)) 
		$llamo=$llamados["alta-usuario.php"];
}
$llamo=str_replace("%", "&", $llamo);
$competencia=isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL;

if (isset($_POST["regresar_sbm"])){
	include("limpia-usuario.php");
	if (!isset($llamo) or $llamo=="home" or $llamo==" ") {
		header("Location: ..?op=php/todas-competencias.php");
		exit();
	}
	else{
		if (isset($llamados)) {
			unset($llamados["alta-usuario.php"]);
			$_SESSION["llamados"]=$llamados;
		}
		header("Location: ..?op=php/$llamo");
		exit();
	}
}

include("funciones.php");
$alta=TRUE;
include("valida-usuario.php");

if (is_null($mensaje)){
	// Dependiendo de el sexo, colocamos una imagen predeterminada
	$imagen_generica=($sexo=="M")?"amigo.png":"amiga.png";
	// ejecuto función para subir la imagen
	$se_subio_imagen=subir_imagen($tipo,$archivo,$email);
	// Si la foto en el formulario viene vacía, entonces le asigno el valor de la imagen genérica, sino entonces el nombre de la foto que se subio (operador ternario)
	$imagen=empty($archivo)?$imagen_generica:$se_subio_imagen;

	$cod_pais=determina_pais($pais);
	$password=equivalencia($password);

	$cod_ciudad=3615;
	$ahora=ahora($cod_ciudad).".000";
	
	// inserto el registro
	$conexion=conectarse();
	$consulta="INSERT INTO usuarios (nombre, sexo, nacimiento, email, pais, telefono, imagen, administrador, entrenador, clavadista, juez, password, fecha_alta) VALUES ('$nombre', '$sexo', '$nacimiento', '$email', $cod_pais, '$telefono', '$imagen', '$administrador', '$entrenador', '$clavadista', '$juez', '$password', '$ahora')";
	
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	
	if ($ejecutar_consulta){
		$cod_usuario=$conexion->insert_id;
		$conexion->close();
		$mensaje="Se ha dado de alta al usuario con el email <b>$email</b> :)";
		include("limpia-usuario.php");
		if (!isset($llamo) or $llamo=="home" or $llamo==" ") {
			header("Location: ..?op=php/todas-competencias.php&mensaje=$mensaje");
			exit();
		}
		else{
			if (isset($llamados)) {
				unset($llamados["alta-usuario.php"]);
				$_SESSION["llamados"]=$llamados;
			}
			header("Location: ..?op=$llamo&mensaje=$mensaje");
			exit();
		}
	}
	else
		$mensaje="No se pudo dar de alta al usuario con el email <b>$email</b> :(";
}
header("Location: ..?op=php/alta-usuario.php&mensaje=$mensaje");
?>