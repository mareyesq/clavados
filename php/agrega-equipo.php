<?php 
include("funciones.php");
// Asigno variables de PHP a los valores que vienen del formulario
$regresar=isset($_POST["regresar_sbm"])?$_POST["regresar_sbm"]:NULL;
$equipo=isset($_POST["equipo_txt"]) ? $_POST["equipo_txt"] :null;
$nombre_corto=isset($_POST["nombre_corto_txt"]) ? $_POST["nombre_corto_txt"] :null;
$pais=isset($_POST["pais_slc"]) ? $_POST["pais_slc"] : null;
$telefono=isset($_POST["telefono_txt"]) ? $_POST["telefono_txt"] : null;
$representante=isset($_POST["representante_txt"]) ? $_POST["representante_txt"] : null;
$email = isset($_POST["email_txt"]) ? $_POST["email_txt"] : null;
$passw=isset($_POST["passw_txt"]) ? $_POST["passw_txt"] : null;
$passw_conf=isset($_POST["passw_conf_txt"]) ? $_POST["passw_conf_txt"] : null;

// validando
session_start();
$competencia=$_SESSION["competencia"];
if(isset($regresar)){
	header("Location: ..?op=php/inscribe-equipo.php&com=$competencia");
	return;
}

$_SESSION["equipo"]	= null;
$_SESSION["nombre_corto"]	= null;
$_SESSION["pais"]=null;
$_SESSION["telefono"]=null;
$_SESSION["representante"]=null;
$_SESSION["email"]=null;	

$_SESSION["equipo_err"]=null;
$_SESSION["nombre_corto_err"]=null;
$_SESSION["pais_err"]=null;
$_SESSION["representante_err"]=null;
$_SESSION["email_err"]=null;
$_SESSION["passw_err"]=null;
$_SESSION["passw_conf_err"]=null;


if (is_null($equipo) or $equipo=="")
	$_SESSION["equipo_err"] = "No definió el nombre del equipo";
else 
	$_SESSION["equipo"]=$equipo;

if (is_null($nombre_corto) or $nombre_corto=="")
	$_SESSION["nombre_corto_err"] = "No definió el nombre corto";
else 
	$_SESSION["nombre_corto"]=$nombre_corto;

if (is_null($pais) or $pais=="")
	$_SESSION["pais_err"]="No registró el país";
else 
	$_SESSION["pais"]=$pais;

if (is_null($representante) or $representante=="")
	$_SESSION["representante_err"]= "No definió el Representante";
else 
	$_SESSION["representante"]=$_POST["representante_txt"];

if (is_null($email) or $email=="")
	$_SESSION["email_err"]= "No definió el email";
else 
	$_SESSION["email"]=$_POST["email_txt"];

if (is_null($passw) or $passw=="")
	$_SESSION["passw_err"] = "Debe ingresar la contraseña";

if (is_null($passw_conf) or $passw_conf=="")
	$_SESSION["passw_conf_err"]="no confirmó la contraseña";

if (!($passw==$passw_conf)){
	$_SESSION["passw_conf_err"]="Contraseña no confirmada";
}

$_SESSION["telefono"]=$telefono;

if ($_SESSION["equipo_err"]=="" and
	$_SESSION["nombre_corto_err"]=="" and
	$_SESSION["pais_err"]=="" and 
	$_SESSION["representante_err"]=="" and 
	$_SESSION["email_err"]=="" and 
	$_SESSION["passw_err"]=="" and
	$_SESSION["passw_conf_err"]=="")  
	$ok=true;
else{
	$ok=false;
}

if ($ok){
	
// Verificamos que no exista previamente el nombre del equipo en la BD
	include("conexion.php");
	$consulta="SELECT equipo FROM equipos WHERE equipo='$equipo'";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
// si $num_regs es cero, el equipo no existe y entonces insertamos los datos en la tabla, sino enviamos mensaje de error
	if ($num_regs==0){
	// ejecuto función para tomar el código del país
		$cod_pais=determina_pais($pais,$conexion);
		$password=equivalencia($passw);
		$cod_usuario=$_SESSION["usuario_id"];

		$consulta="INSERT INTO equipos (equipo, pais, telefono, representante, email, nombre_corto, password, usuario_alta) VALUES ('$equipo', $cod_pais, '$telefono', '$representante', '$email', '$nombre_corto', '$password', $cod_usuario)";

		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		if ($ejecutar_consulta)
			$mensaje="Se ha dado de alta al Equipo <b>$equipo</b> :)";
		else
			$mensaje="No se pudo dar de alta al Equipo <b>$equipo</b> :(";
	}
	else{
		$mensaje="Ya está registrado el Equipo <b>$equipo</b> :(";
	}
	$conexion->close();
	if (isset($competencia)) {
		header("Location: ../index.php?op=php/inscribe-equipo.php&comp=$competencia&mensaje=$mensaje");
	}
}
else{
	$mensaje="Verifique los datos errados";
}

header("Location: ../index.php?op=php/alta-equipo.php&mensaje=$mensaje");
?>