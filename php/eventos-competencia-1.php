<?php 
if (!isset($competencia)) 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
if (!isset($cod_competencia)) 
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);

include("funciones.php");
session_start();
if (isset($_POST["regresar_sbm"])){
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	if ($llamados){
		$llamo=isset($llamados["eventos-competencia.php"])?$llamados["eventos-competencia.php"]:NULL;
	}
	unset($llamados["eventos-competencia.php"]);
	$_SESSION["llamados"]=$llamados;
	$transfer="?op=php/$llamo";
	header("Location: $transfer");
	exit();
}

$dia_sel=isset($_POST["dia_sel_slc"])?$_POST["dia_sel_slc"]:NULL;
$modalidad_sel=isset($_POST["modalidad_sel_slc"])?$_POST["modalidad_sel_slc"]:NULL;
$categoria_sel=isset($_POST["categoria_sel_slc"])?$_POST["categoria_sel_slc"]:NULL;


if (!$dia_sel)
	unset($_SESSION["dia_sel"]);
else
	$_SESSION["dia_sel"]=$dia_sel;

if (!$modalidad_sel)
	unset($_SESSION["modalidad_sel"]);
else
	$_SESSION["modalidad_sel"]=$modalidad_sel;

if (!$categoria_sel)
	unset($_SESSION["categoria_sel"]);
else
	$_SESSION["categoria_sel"]=$categoria_sel;

if (isset($_POST["registrar_evento_btn"])){
	header("Location: ?op=php/alta-evento-competencia.php&com=$competencia&cco=$cod_competencia");
	exit();
}

if (isset($_POST["clavadistas_equipo_btn"])){
	$conexion=conectarse();
	$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, City, Country, logo2
		FROM competencias 
		LEFT JOIN cities  on cities.CityId=competencias.ciudad
		LEFT JOIN countries  on countries.CountryId=cities.CountryId
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$row=$ejecutar_consulta->fetch_assoc();
	$organizador=quitar_tildes($row["organizador"]);
	$desde=$row["fecha_inicia"];
	$fec=explode("-", $desde);
	$dia_des=$fec[2];
	$mes_des=$fec[1];
	$ano_des=$fec[0];
	$hasta=$row["fecha_termina"];
	$fec=explode("-", $hasta);
	$dia_has=$fec[2];
	$mes_has=$fec[1];
	$ano_has=$fec[0];
	$subtitulo="Del ".$dia_des."-".$mes_des."-".$ano_des." al ".$dia_has."-".$mes_has."-".$ano_has;
	$subtitulo1=$row["City"]." - ".$row["Country"] ;
	$logo=$row["logo_organizador"];
	$logo2=$row["logo2"];

	include("impr-lista-clavadistas.php");
	$conexion->close();
	$transfer="Location: $llamo";
	header($transfer);
	exit();
}

if (isset($_POST["imprime_programa_btn"])){
	$conexion=conectarse();
	$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, logo2, City, Country
		FROM competencias 
		LEFT JOIN cities  on cities.CityId=competencias.ciudad
		LEFT JOIN countries  on countries.CountryId=cities.CountryId
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$row=$ejecutar_consulta->fetch_assoc();
	$organizador=quitar_tildes($row["organizador"]);
	$desde=$row["fecha_inicia"];
	$fec=explode("-", $desde);
	$dia_des=$fec[2];
	$mes_des=$fec[1];
	$ano_des=$fec[0];
	$hasta=$row["fecha_termina"];
	$fec=explode("-", $hasta);
	$dia_has=$fec[2];
	$mes_has=$fec[1];
	$ano_has=$fec[0];
	$subtitulo="Del ".$dia_des."-".$mes_des."-".$ano_des." al ".$dia_has."-".$mes_has."-".$ano_has;
	$subtitulo1=$row["City"]." - ".$row["Country"] ;
	$logo=$row["logo_organizador"];
	$logo2=$row["logo2"];

	include("impr-programa.php");
	$conexion->close();
	$transfer="Location: $llamo";
	header($transfer);
	exit();
}

if (isset($_POST["exporta_planillas_btn"])){
	$transfer="?op=php/$llamo";
	include("exporta-planillas.php");
	$transfer="Location: ?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["calcular_horarios_btn"])){
	tiempo_estimado_inicial($cod_competencia);
	asigna_hora_estimada($cod_competencia);
}

header("Location: ?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia");
exit();

?>