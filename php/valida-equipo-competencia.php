<?php 
$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["logo"]=$logo;
$_SESSION["logo2"]=isset($logo2)?$logo2:NULL;
$_SESSION["equipo"]=$equipo; 
$_SESSION["corto"]=$corto; 
$_SESSION["corto_original"]=isset($corto_original)?$corto_original:NULL; 
$_SESSION["equipo_original"]=isset($equipo_original)?$equipo_original:NULL; 
$_SESSION["representante"]=$representante;
$_SESSION["email"]=$email;
$_SESSION["telefono"]=$telefono;
$_SESSION["password"]=$password;
$_SESSION["password_conf"]=$password_conf;
$_SESSION["usuario_alta"]=isset($usuario_alta)?$usuario_alta:NULL;
$_SESSION["banderas"]=isset($banderas)?$banderas:NULL;

$mensaje=NULL;

if (is_null($mensaje))
	if (inscripciones($competencia)==FALSE)
		$mensaje="Error: Esta competencia ya no recibe inscripciones :(";

$usuario=isset($_SESSION["usuario_id"])?trim($_SESSION["usuario_id"]):NULL;

if (is_null($usuario) or strlen($usuario)==0){
	$mensaje= "Error: Debes iniciar sesión para poder hacer inscripciones";
}

$admin_sist=isset($_SESSION["admin_sist"])?$_SESSION["admin_sist"]:NULL;

if (is_null($mensaje))
	if ($alta=="edita")
		if (!$admin_sist)
			if (!($usuario==$usuario_alta))
				$mensaje= "Error: No estás autorizado para modificar la inscripción del equipo <b>$equipo<b> :(";

if (is_null($mensaje))
	if (is_null($competencia) or $competencia=="")
		$mensaje= "Error: No definiste la Competencia a participar";

if (is_null($mensaje)){
	$cod_competencia=determina_competencia($competencia);
	if (substr($cod_competencia, 0, 5)=="Error")
		$mensaje=$cod_competencia;
}
if (is_null($mensaje))
	if (inscripciones($competencia)==FALSE)
		$mensaje="Error: Esta competencia ya no recibe inscripciones :(";

if (is_null($mensaje))
	if (!isset($equipo) or $equipo=="")
		$mensaje="Error: Debes definir el nombre de tu equipo";

$conexion=conectarse();
if (is_null($mensaje)){
	if ($alta=="alta" or ($equipo!==$equipo_original)){
		$consulta="SELECT * FROM competenciasq WHERE (competencia=$cod_competencia and equipo='$equipo') ";
		$ejecutar_consulta=$conexion->query($consulta);
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs!==0)
			$mensaje="Error: Ya se inscribió un equipo con este nombre";
	}
}

if (is_null($mensaje))
	if (!isset($corto) or $corto=="")
		$mensaje="Error: Debes definir el nombre corto para tu equipo";

if (is_null($mensaje)){
	if ($alta=="alta" or ($corto!==$corto_original)){
		$consulta="SELECT * FROM competenciasq WHERE (competencia=$cod_competencia and nombre_corto='$corto') ";
		$ejecutar_consulta=$conexion->query($consulta);
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs!==0)
			$mensaje="Error: Ya se inscribió un equipo con este nombre corto";
	}
}
$conexion->close();
if (is_null($mensaje))
	if (!isset($email) or $email=="")
		$mensaje="Error: Debes definir el email de contacto con tu equipo";


if (is_null($mensaje))
	if (!isset($password) or $password=="")
		$mensaje="Error: Debes definir la clave para las inscripciones de tu equipo";

if (is_null($mensaje))
	if (!isset($password_conf) or $password_conf=="")
		$mensaje="Error: Debes confirmar la clave para las inscripciones de tu equipo";

if (is_null($mensaje))
	if (!($password==$password_conf))
		$mensaje="Error: La clave no coincide con la confirmación ";

if (isset($_POST["recordar_sbm"]))
		$mensaje=recordar_clave_inscripcion($cod_competencia,$corto);
?>