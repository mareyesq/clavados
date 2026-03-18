<?php 
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$encabezado=isset($_POST["encabezado_hdn"])?trim($_POST["encabezado_hdn"]):NULL;
$encabezado1=isset($_POST["encabezado1_hdn"])?trim($_POST["encabezado1_hdn"]):NULL;
session_start();

if (isset($_POST["regresar_sbm"])){
	$_SESSION["competencia"]=NULL;
	header("Location: ?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia");
	exit();
}

include("funciones.php");
$dec=decimales($cod_competencia);

if (isset($_POST["resultados_sbm"])){
	include("resultados-de-competencia.php");
	exit();
}
if (isset($_POST["puntos_cat_sexo_sbm"])){
	include("puntos-categoria-sexo-competencia.php");
	exit();
}

if (isset($_POST["puntos_sexo_sbm"])){
	include("puntos-sexo-competencia.php");
	exit();
}
if (isset($_POST["puntos_cat_sbm"])){
	include("puntos-categoria-competencia.php");
	exit();
}

if (isset($_POST["puntos_gen_sbm"])){
	include("puntos-general-competencia.php");
	exit();
}

if (isset($_POST["calificaciones_sbm"])){
	include("calificaciones-de-competencia.php");
	exit();
}

if (isset($_POST["puntos_deportista_sbm"])){
	include("puntos-deportista-competencia.php");
	exit();
}

if (isset($_POST["medallas_sbm"])){
	include("medallistas-competencia.php");
	exit();
}

if (isset($_POST["evaluacion_jueces_sbm"])){
	$general=TRUE;
	include("evaluacion-jueces-de-competencia.php");
	exit();
}

if (isset($_POST["medallas_cat_sexo_sbm"])){
	include("medallas-categoria-sexo-competencia.php");
	exit();
}

if (isset($_POST["medallas_cat_sbm"])){
	include("medallas-categoria-competencia.php");
	exit();
}

if (isset($_POST["medallas_gen_sbm"])){
	include("medallas-competencia-resumen.php");
	exit();
}

if (isset($_POST["medallas_deportista_sbm"])){
	include("medallas-deportista-competencia.php");
	exit();
}

?>