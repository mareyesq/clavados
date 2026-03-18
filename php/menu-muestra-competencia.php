<?php 
include("funciones.php");
$competencia=isset($_POST["competencia_hdn"])?trim($_POST["competencia_hdn"]):null;
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$logo=isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null;
$organizador=isset($_POST["organizador_hdn"])?$_POST["organizador_hdn"]:null;
$email=isset($_POST["email_hdn"])?$_POST["email_hdn"]:null;

session_start();
if (isset($_POST["regresar_sbm"])){
	$_SESSION["llamo"]=NULL;
	$_SESSION["competencia"]=NULL;
	header("Location: ..?op=php/todas-competencias.php");
	exit();
}

$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
$llamar="muestra-competencia.php&com=$competencia&cco=$cod_competencia";
$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
$es_administrador_competencia=administrador_competencia($cod_usuario,$cod_competencia);

if (isset($_POST["pagar_sbm"])){
	$llamados["pagar-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/pagar-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["autorizar_sbm"])){
	$conexion=conectarse();
	$consulta="UPDATE competencias set pagado='C' WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta=$conexion->query($consulta);
	$conexion->close();
	notifica_autorizacion_competencia($competencia,$organizador,$email);
	header("Location: ..?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["modificar_sbm"])){
	if (is_null($cod_usuario)) {
		$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
		$llamo="..?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}
	if (substr($es_administrador_competencia,0,5)=="Error"){
		$mensaje=$es_administrador_competencia;
		$llamo="..?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	$llamados["edita-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/edita-competencia.php&cco=$cod_competencia");
	exit();
}

if (isset($_POST["administradores_sbm"])){
	$llamados["administradores-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/administradores-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["pruebas_sbm"])){
	$llamados["pruebas-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/pruebas-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["preseries_sbm"])){
	$llamados["preseries-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/preseries-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["eventos_sbm"])){
	$llamados["eventos-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["jueces_sbm"])){
	$llamados["jueces-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/jueces-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["equipos_sbm"])){
	$llamados["equipos-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/equipos-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["puntos_sbm"])){
	$llamados["puntos-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/puntos-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["marcas_sbm"])){
	$llamados["marcas-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/marcas-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["calificar_sbm"])){
	$llamados["calificar.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/calificar.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}

if (isset($_POST["resultados_sbm"])){
	$llamados["resultados-competencia.php"]=$llamar;
	$_SESSION["llamados"]=$llamados;
	header("Location: ..?op=php/resultados-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo");
	exit();
}
?>