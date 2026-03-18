<?php 
$conexion=conectarse();
$consulta_posiciones_ind="SELECT DISTINCT  q.equipo as nom_equipo, SUM(t.puntos) as total_puntos
	FROM planillas 	as p
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
	LEFT JOIN modalidades as m on cod_modalidad=p.modalidad ";
$consulta_posiciones_ind .= " WHERE p.competencia = $cod_competencia AND p.momento_termino is NOT NULL AND p.extraof IS NULL AND m.individual=1 AND p.categoria<>'AB'
	GROUP BY nom_equipo
 	ORDER BY total_puntos DESC";

$ejecutar_consulta_posiciones_ind = $conexion->query($consulta_posiciones_ind);

$consulta_abierta="SELECT DISTINCT  q.equipo as nom_equipo, SUM(t.puntos) as total_puntos
	FROM planillas 	as p
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar_abierta
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
	LEFT JOIN modalidades as m on cod_modalidad=p.modalidad ";
$consulta_abierta .= " WHERE p.competencia = $cod_competencia AND p.momento_termino is NOT NULL AND p.extraof_abierto IS NULL AND m.individual=1 AND (p.categoria='AB' or p.part_abierta='*')
	GROUP BY nom_equipo
 	ORDER BY total_puntos DESC";

$ejecutar_consulta_abierta = $conexion->query($consulta_abierta);

$consulta_posiciones_sin="SELECT DISTINCT  q.equipo as nom_equipo, SUM(t.puntos_sinc) as total_puntos
	FROM planillas 	as p
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
	LEFT JOIN modalidades as m on cod_modalidad=p.modalidad ";
$consulta_posiciones_sin .= " WHERE p.competencia = $cod_competencia AND p.momento_termino is NOT NULL AND p.extraof IS NULL AND m.individual=0 
	GROUP BY nom_equipo
 	ORDER BY total_puntos DESC";

$ejecutar_consulta_posiciones_sin = $conexion->query($consulta_posiciones_sin);

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

include("impr-puntos-general-competencia.php");
$conexion->close();
$transfer="Location: $llamo";
header($transfer);
exit();

?>