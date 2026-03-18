<?php 
include("funciones.php");
$email=(isset($_POST["email_txt"])?$_POST["email_txt"]:null);
$passw=(isset($_POST["passw_txt"])?$_POST["passw_txt"]:null);
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);

session_start();
$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
if (isset($llamados)) 
	$llamado=isset($llamados["inicia-sesion.php"])?$llamados["inicia-sesion.php"]:NULL;

if (isset($_POST["registrar_sbm"])){
	$llamados["alta-usuario.php"]="inicia-sesion.php";
	$_SESSION["llamados"]=$llamados;
	$transfer="..?op=php/alta-usuario.php";
	header("Location: $transfer");
	exit();
}

$_SESSION["email"]=$email;
$_SESSION["passw"]=$passw;
$mensaje=NULL;
if (is_null($email) or $email=="")
	$mensaje= "Verifica tu email";

if (is_null($mensaje)) {
	$nombre_usuario=nombre_usuario($email);
	if (substr($nombre_usuario, 0, 5)=="Error") 
		$mensaje= $nombre_usuario;
}

if (is_null($mensaje)) {
	$imagen_usuario=imagen_usuario($email);
	if (substr($imagen_usuario, 0, 5)=="Error") 
		$mensaje= $imagen_usuario;
}

if (is_null($mensaje)) 
	if (isset($_POST["recordar_sbm"]))
		$mensaje=recordar_passw($email);

if (is_null($mensaje)) 
	if (is_null($passw) or $passw=="")
		$mensaje = "Error: Debes ingresar la contraseña";

if (is_null($mensaje)) {
	$p=equivalencia($passw);
	$usok=valida_usuario($email,$p);

	if(!$usok)
		$mensaje = "Error: escribiste la contraseña errada :(";
	else
		$cod_usuario=determina_usuario($email);
}

if (is_null($mensaje)) {
	if (isset($_POST["actualizar_sbm"])){
		$llamados["edita-usuario.php"]="inicia-sesion.php";
		$_SESSION["llamados"]=$llamados;
		$transfer="..?op=php/edita-usuario.php&email=$email";
		header("Location: $transfer");
		exit();
	}
}

if (is_null($mensaje)){
	unset($_SESSION["email"]);
	$_SESSION["autentificado"]=true;
	$_SESSION["usuario"]=utf8_encode($email);
	$_SESSION["nombre_usuario"]=utf8_encode($nombre_usuario);
	$_SESSION["imagen_usuario"]=utf8_encode($imagen_usuario);
	$_SESSION["usuario_id"]=$cod_usuario;
	if (is_null($cod_usuario)) 
        $_SESSION["admin_sist"]=FALSE;
    else{
        $es_administrador_sistema= administrador_sistema($cod_usuario);
        if (substr($es_administrador_sistema,0,5)=="Error") {
	        $_SESSION["admin_sist"]=FALSE;
        }
        else
        	$_SESSION["admin_sist"]=TRUE;	
    }

	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	if (isset($llamados)) {
		$llamado=isset($llamados["inicia-sesion.php"])?$llamados["inicia-sesion.php"]:NULL;
		if (isset($llamado)){
			unset($llamados["inicia-sesion.php"]);
			$_SESSION["llamados"]=$llamados;
		}
	}
	if (!isset($llamado)) {
		header("Location: ..?op=php/todas-competencias.php");
		exit();
	}
	else{
		header("Location: ..?op=php/$llamado");
		exit();
	}
}  
else{
	unset($_SESSION["autentificado"]);
	unset($_SESSION["usuario"]);
	unset($_SESSION["nombre_usuario"]);
	unset($_SESSION["imagen_usuario"]);
	unset($_SESSION["usuario_id"]);
	unset($_SESSION["admin_sist"]);
	header("Location: ..?op=php/inicia-sesion.php&mensaje=$mensaje");
	exit();
}
?>