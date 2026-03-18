	<?php 

$cod_usuario=isset($_POST["cod_usuario_hdn"])?$_POST["cod_usuario_hdn"]:NULL;
//$nombre=isset($_POST["nombre_txt"]) ? quitar_tildes($_POST["nombre_txt"]) :null;
$nombre=isset($_POST["nombre_txt"]) ?$_POST["nombre_txt"] :null;
$sexo=isset($_POST["sexo_rdo"]) ? $_POST["sexo_rdo"] : null;
if (!navegador_compatible("chrome")){
	$dia=isset($_POST["dia_nac_slc"]) ? $_POST["dia_nac_slc"] : null;
	$dia=(int) $dia+100;
	if (isset($dia)) $dia=substr($dia, 1, 2);
	$nom_mes=isset($_POST["mes_nac_slc"]) ? $_POST["mes_nac_slc"] : null;
	$ano=isset($_POST["ano_nac_slc"]) ? $_POST["ano_nac_slc"] : null;
	$mes=mes_num($nom_mes);
	$mes=(int) $mes+100;
	if (isset($mes)) $mes=substr($mes, 1, 2);
	$nacimiento="$ano-$mes-$dia";
}
else{
	$nacimiento=isset($_POST["nacimiento_txt"]) ? $_POST["nacimiento_txt"] : null;
		$cadenas = explode("-", $nacimiento);
	$ano=isset($cadenas[0])?$cadenas[0]:NULL;
	$mes=isset($cadenas[1])?$cadenas[1]:NULL;
	$dia=isset($cadenas[2])?$cadenas[2]:NULL;
}

$administrador=isset($_POST["administrador_chk"]) ? $_POST["administrador_chk"] : null;
$clavadista=isset($_POST["clavadista_chk"]) ? $_POST["clavadista_chk"] : null;
$entrenador=isset($_POST["entrenador_chk"]) ? $_POST["entrenador_chk"] : null;
$juez=isset($_POST["juez_chk"]) ? $_POST["juez_chk"] : null;
;
$pais=isset($_POST["pais_slc"]) ? $_POST["pais_slc"] : null;
$email = isset($_POST["email_txt"]) ? $_POST["email_txt"] : null;
$telefono=isset($_POST["telefono_txt"]) ? $_POST["telefono_txt"] : null;
$password=isset($_POST["passw_txt"]) ? $_POST["passw_txt"] : null;
$password_conf=isset($_POST["passw_conf_txt"]) ? $_POST["passw_conf_txt"] : null;
$imagen=isset($_POST["foto_hdn"]) ? $_POST["foto_hdn"] : null;

$tipo=isset($_FILES["foto_fls"]["type"]) ? $_FILES["foto_fls"]["type"] : null;
$archivo=isset($_FILES["foto_fls"]["tmp_name"]) ? $_FILES["foto_fls"]["tmp_name"] : null;
// validando

$_SESSION["cod_usuario"]=isset($cod_usuario)?$cod_usuario:NULL;
$_SESSION["llamo"]=isset($llamo)?$llamo:NULL;
$_SESSION["nombre"]=isset($nombre)?$nombre:NULL;
$_SESSION["sexo"]=isset($sexo)?$sexo:NULL;
$_SESSION["ano"]=isset($ano)?$ano:NULL;
$_SESSION["mes"]=isset($mes)?$mes:NULL;
$_SESSION["dia"]=isset($dia)?$dia:NULL;
$_SESSION["nacimiento"]=isset($nacimiento)?$nacimiento:NULL;
$_SESSION["administrador"]=isset($administrador)?$administrador:NULL;
$_SESSION["clavadista"]=isset($clavadista)?$clavadista:NULL;
$_SESSION["entrenador"]=isset($entrenador)?$entrenador:NULL;
$_SESSION["juez"]=isset($juez)?$juez:NULL;
$_SESSION["pais"]=isset($pais)?$pais:NULL;
$_SESSION["email"]=isset($email)?$email:NULL;	
$_SESSION["telefono"]=isset($telefono)?$telefono:NULL;
$_SESSION["password"]=isset($password)?$password:NULL;
$_SESSION["password_conf"]=isset($password_conf)?$password_conf:NULL;
$_SESSION["imagen"]=isset($imagen)?$imagen:NULL;

$mensaje=NULL;
$usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;

if (is_null($usuario) or strlen($usuario)==0){
	if (!$alta)
		$mensaje= "Error: Debes iniciar sesión para poder actualizar información :(";
}

if (is_null($mensaje)){
    $admin_sist=isset($_SESSION["admin_sist"])?$_SESSION["admin_sist"]:NULL;
	if ($admin_sist==FALSE){
		if (!$alta)
			if (!($usuario==$cod_usuario))
				$mensaje= "Error: No estás autorizado para actualizar la información de <b>$nombre<b> :(";
	}
}			
if (is_null($nombre) or strlen($nombre)==0)
	$mensaje= "Error: No definió el nombre";

if (is_null($mensaje))
	if (is_null($sexo) or strlen($sexo)==0)
		$mensaje="Error: No registró el sexo";

if (is_null($mensaje))
	if(!is_null($clavadista))
		if($clavadista=="C")
			if (is_null($nacimiento) or $nacimiento=="")
				$mensaje="Error: Clavadista, se requiere que ingrese la fecha de nacimiento";
			else
				if (!checkdate ($mes, $dia, $ano ))
					$mensaje="Error: La fecha de nacimiento no es válida";

if (is_null($mensaje))
	if ((is_null($administrador) or $administrador=="") and 
		(is_null($clavadista) or $clavadista=="") and
		(is_null($entrenador) or $entrenador=="") and 
		(is_null($juez) or $juez==""))
		$mensaje = "Error: No definió el tipo de usuario";

if (is_null($mensaje))
	if (is_null($pais) or $pais=="")
		$mensaje="Error: No registró el país";

if (is_null($mensaje))
	if (is_null($email) or $email=="")
		$mensaje= "Error: No definió el email";

if (is_null($mensaje) and $alta){
	$conexion=conectarse();
	$consulta="SELECT email FROM usuarios WHERE email='$email'";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if (!$num_regs==0)
		$mensaje="Ya está registrado un usuario con el email <b>$email</b> :(";
	$conexion->close();
}

if (is_null($mensaje))
	if (strlen($password)==0)
		$mensaje = "Error: Debe ingresar la contraseña";

if (is_null($mensaje))
	if (strlen($password_conf)==0)
		$mensaje="Error: No confirmó la contraseña";

if (is_null($mensaje))
	if (!($password===$password_conf))
		$mensaje="Error: Contraseña no confirmada";
?>