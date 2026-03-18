<?php 
$conexion=conectarse();

$consulta_posiciones="SELECT sexo, nom_equipo, SUM(total_puntos) as puntos 
	FROM resu_sexo WHERE competencia=$cod_competencia
	GROUP BY sexo, nom_equipo
	ORDER by sexo, puntos DESC";
$ejecutar_consulta_posiciones = $conexion->query($consulta_posiciones);

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

include("impr-puntos-sexo-competencia.php");
$conexion->close();
$transfer="Location: $llamo";
header($transfer);
exit();

?>