<?php 
$agregar=isset($_POST["agregar_sbm"])?$_POST["agregar_sbm"]:NULL;
$actualizar=isset($_POST["actualizar_sbm"])?$_POST["actualizar_sbm"]:NULL;
$agrega_salto=isset($_POST["agrega_salto_sbm"])?$_POST["agrega_salto_sbm"]:NULL;
$modifica_salto=isset($_POST["modifica_salto_sbm"])?$_POST["modifica_salto_sbm"]:NULL;
$item_modificar=isset($_POST["item_modificar_hdn"])?$_POST["item_modificar_hdn"]:NULL;
$indice=isset($_POST["indice_hdn"])?$_POST["indice_hdn"]:NULL;
$reset=isset($_POST["reset_sbm"])?$_POST["reset_sbm"]:NULL;

$categoria=isset($_POST["categoria_slc"]) ? $_POST["categoria_slc"] :null;
$modalidad=isset($_POST["modalidad_slc"])?$_POST["modalidad_slc"] :null;

$orden=isset($_POST["orden_nbr"])?$_POST["orden_nbr"]:NULl;
$salto=isset($_POST["salto_slc"])?$_POST["salto_slc"]:NULl;
$posicion=isset($_POST["posicion_slc"])?$_POST["posicion_slc"]:NULl;
$altura=isset($_POST["altura_nbr"])?$_POST["altura_nbr"]:NULl;
$plataforma=isset($_POST["plataforma_chk"])?$_POST["plataforma_chk"]:NULl;
$grado=isset($_POST["grado_nbr"])?$_POST["grado_nbr"]:NULl;
$observacion=isset($_POST["observacion_txt"])?$_POST["observacion_txt"]:NULl;
$libre=isset($_POST["libre_chk"])?$_POST["libre_chk"]:NULl;
$items_ser=isset($_SESSION["items_ser"])?$_SESSION["items_ser"]:NULL;

// validando

$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
$_SESSION["categoria"]=isset($categoria)?$categoria:NULL;
$_SESSION["modalidad"]=isset($modalidad)?$modalidad:NULL;

$_SESSION["orden"]=$orden;
$_SESSION["salto"]=$salto;
$_SESSION["posicion"]=$posicion;
$_SESSION["altura"]=$altura;
$_SESSION["plataforma"]=$plataforma;
$_SESSION["grado"]=$grado;
$_SESSION["observacion"]=$observacion;
$_SESSION["libre"]=$libre;
$_SESSION["item_modificar"]=$item_modificar;
$_SESSION["indice"]=$indice;

$mensaje=NULL;
$usuario=$_SESSION["usuario_id"];
$nombre_usuario=$_SESSION["nombre_usuario"];

if (!$usuario){
	$mensaje= "Error: Debes iniciar sesión para poder actualizar información :(";
}

$es_administrador_competencia=administrador_competencia($usuario,$cod_competencia);

if (substr($es_administrador_competencia,0,5)=="Error"){
	$mensaje=$es_administrador_competencia;
	$llamo="?op=php/preseries-competencia.php&com=$competencia&cco=$cod_competencia";
	header("Location: $llamo&mensaje=$mensaje");
	exit();
}

if (is_null($mensaje))
	if (!$categoria)
		$mensaje= "Error: No definió la categoría de la serie a registrar :(";

if (is_null($mensaje))
	if (!$modalidad)
		$mensaje= "Error: No definió la modalidad de la serie a registrar :(";

if (is_null($mensaje) AND ($salto or $orden or $posicion or $grado)){

	if (!$orden)
		$mensaje= "Error: No definió el orden del salto :(";
	
	if (is_null($mensaje))
		if ($salto and !$posicion) {
			$mensaje= "Error: No definió la posición del salto :(";
		}
	if (is_null($mensaje))
		if ($salto and !isset($altura)) {
			$mensaje= "Error: No definió la altura del salto :(";
		}

	if (is_null($mensaje) and $agrega_salto){
		$items_ser[]=array('orden' => $orden, 'salto' => $salto, 'posicion' => $posicion, 'altura' => $altura, 'plataforma' => $plataforma, 'grado' => $grado, 'observacion' => $observacion, 'libre' => $libre);
		$_SESSION["items_ser"]=$items_ser;
		unset($_SESSION["orden"]);
		unset($_SESSION["salto"]);
		unset($_SESSION["posicion"]);
		unset($_SESSION["altura"]);
		unset($_SESSION["plataforma"]);
		unset($_SESSION["grado"]);
		unset($_SESSION["observacion"]);
		unset($_SESSION["libre"]);
	}
	if (is_null($mensaje) and $modifica_salto){
		$items_ser[$indice]=array('orden' => $orden, 'salto' => $salto, 'posicion' => $posicion, 'altura' => $altura, 'plataforma' => $plataforma, 'grado' => $grado, 'observacion' => $observacion, 'libre' => $libre);
		$_SESSION["items_ser"]=$items_ser;
		unset($_SESSION["orden"]);
		unset($_SESSION["salto"]);
		unset($_SESSION["posicion"]);
		unset($_SESSION["altura"]);
		unset($_SESSION["plataforma"]);
		unset($_SESSION["grado"]);
		unset($_SESSION["observacion"]);
		unset($_SESSION["libre"]);
		unset($_SESSION["item_modificar"]);
	}
}
if (is_null($mensaje) AND $reset){
	unset($_SESSION["items_ser"]);
	$mensaje= "Lista de saltos limpia :(";	
}

?>