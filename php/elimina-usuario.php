<?php 
	$nombre=isset($_POST["nombre_txt"])?trim($_POST["nombre_txt"]):null;
	$email=isset($_POST["email_txt"])?trim($_POST["email_txt"]):null;
	$password=isset($_POST["password_txt"])?trim($_POST["password_txt"]):null;
	$cod_usuario=isset($_POST["cod_usuario_hdn"])?trim($_POST["cod_usuario_hdn"]):NULL;
	$llamo=isset($_POST["llamo"])?trim($_POST["llamo"]):NULL;
		
	session_start();
	if (isset($_POST["regresar_sbm"])){
		$_SESSION["nombre"]="";
		$_SESSION["email"]="";
		$_SESSION["password"]="";
		$_SESSION["llamo"]="";
		$transfer="Location: ..?op=php/$llamo";
		header($transfer);
		exit();
	}
	
	include("conexion.php");
	include("funciones.php");
	
	$_SESSION["nombre"]=isset($nombre)?$nombre:NULL;
	$_SESSION["email"]=isset($email)?$email:NULL;
	$_SESSION["password"]=isset($password)?$password:NULL;
	$_SESSION["cod_usuario"]=isset($cod_usuario)?$cod_usuario:NULL;
	$_SESSION["llamo"]=isset($llamo)?$llamo:NULL;

	$mensaje=NULL;

	$usuario_id=trim($_SESSION["usuario_id"]);
	if (is_null($usuario_id) or strlen($usuario_id)==0)
		$mensaje= "Error: Debes iniciar sesión para poder eliminar tu usuario :(";


	if (is_null($mensaje))
		if (!($usuario_id==$cod_usuario)){
			$mensaje= "Error: No estás autorizado para eliminar el usuario <b>$nombre<b> :(";
		}

	if (is_null($mensaje)){
		$p=equivalencia($password);
		$usok=valida_usuario($email,$p,$conexion);
		if(!$usok)
			$mensaje = "Error: contraseña errada :(";
	}

	if (is_null($mensaje)) {
		$consulta="DELETE FROM usuarios
			WHERE (cod_usuario=$cod_usuario)";
		$ejecutar_consulta=$conexion->query($consulta);
		echo "cons: $consulta<br>";
		print_r($conexion->error_list);
		exit();

		if ($ejecutar_consulta){
			$mensaje="Se eliminó al usuario: <b>$nombre</b> del sistema :(";
			$_SESSION["nombre"]="";
			$_SESSION["email"]="";
			$_SESSION["password"]="";
			$_SESSION["llamo"]="";
			$transfer="Location: ?op=php/$llamo&mensaje=$mensaje";
			header($transfer);
			exit();
		}
		else{
			$mensaje="No se pudo eliminar al usuario <b>$nombre</b>";
			header("Location: ..?op=php/baja-usuario.php&mensaje=$mensaje");
			exit();
		}	
	}
	else
		header("Location: ..?op=php/baja-usuario.php&mensaje=$mensaje");
 ?>