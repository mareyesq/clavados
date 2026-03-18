<?php 
$llamo=(isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:NULL);

session_start();
if (isset($_SESSION["llamados"])) {
	$llamados=$_SESSION["llamados"];
	if (array_key_exists("edita-usuario.php", $llamados)) 
		$llamo=$llamados["edita-usuario.php"];
}

if (isset($_POST["regresar_sbm"])){
	include("limpia-usuario.php");
	if (is_null($llamo)) 
		$transfer="Location: ..?op=php/todas-competencias.php";
	else{
		$transfer="Location: ..?op=php/$llamo";
		unset($llamados["edita-usuario.php"]);
		$_SESSION["llamados"]=$llamados;
	}
	header($transfer);
	exit();
}
$cod_usuario=isset($_POST["cod_usuario_hdn"])?$_POST["cod_usuario_hdn"]:NULL;
$_SESSION["cod_usuario"]=isset($cod_usuario)?$cod_usuario:NULL;

include("conexion.php");
include("funciones.php");
$alta=FALSE;
include("valida-usuario.php");

if (is_null($mensaje)){
	$cod_pais=determina_pais($pais,$conexion);
	$consulta="SELECT CityId
		FROM countries 
		left join cities on cities.City=countries.Capital
		WHERE (countries.CountryId=$cod_pais)";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		$cod_ciudad=$registro["CityId"];
	}
	if (!isset($cod_ciudad)) 
		$cod_ciudad=3615;
	
	$imagen_generica=($sexo=="M")?"amigo.png":"amiga.png";
	// ejecuto función para subir la imagen
	$se_subio_imagen=subir_imagen($tipo,$archivo,$email);
	// Si la foto en el formulario viene vacía, entonces le asigno el valor de la imagen genérica, sino entonces el nombre de la foto que se subio (operador ternario)
	$imagen=empty($archivo)?$imagen_generica:$se_subio_imagen;

	$ahora=ahora($cod_ciudad,$conexion);
	$password=equivalencia($passw);
	$consulta="UPDATE `usuarios` 
		SET `nombre`='$nombre',
			`email`='$email',
			`pais`=$cod_pais,
			`administrador`='$administrador',
			`entrenador`='$entrenador',
			`clavadista`='$clavadista',
			`juez`='$juez',
			`sexo`='$sexo',
			`nacimiento`='$nacimiento',
			`imagen`='$imagen',
			`telefono`='$telefono',
			`password`='$password' 
		WHERE (`cod_usuario`=$cod_usuario)";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	if (!$ejecutar_consulta){
		$mensaje="Error: No se pudo actualizar el usuario con email <b>$email</b>";
		header("Location: ..?op=php/edita-equipo-competencia.php&mensaje=$mensaje");
		exit();
	}
	if (is_null($mensaje)){
		include("limpia-usuario.php");
		$mensaje="se actualizó el usuario con email <b>$email<b>";
		if (isset($llamo)) {
			$transfer="Location: ..?op=php/$llamo&mensaje=$mensaje";
			if (in_array("edita-usuario.php", $llamados)) {
				unset($llamados["edita-usuario.php"]);
				$_SESSION["llamados"]=$llamados;
			}
		}
		else
			$transfer="Location: ..?op=php/todas-competencias.php&mensaje=$mensaje";

		header($transfer);
		exit();
	}
}
else
	header("Location: ..?op=php/edita-equipo-competencia.php&mensaje=$mensaje");
 ?>