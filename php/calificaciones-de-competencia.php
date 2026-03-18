<?php 
$conexion=conectarse();
$consulta_posiciones="SELECT DISTINCT  e.competencia, e.fechahora, e.numero_evento, p.sexo, p.categoria, p.extraof, p.part_abierta, p.clavadista2, p.orden_salida, p.total, p.lugar, p.total_abierta, p.lugar_abierta, d.ronda, d.salto, d.posicion, d.altura, d.grado_dif, d.abierto, d.total_salto, d.puntaje_salto, d.acumulado, d.penalizado, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, s.salto as nom_salto, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod, q.equipo AS nom_equipo,  q.nombre_corto as nom_equipo_corto, t.categoria as nom_cat, c.imagen as img_1, c2.imagen as img_2
	FROM competenciaev as e
	LEFT JOIN planillas 	as p    on p.competencia=e.competencia and p.evento=e.numero_evento
	LEFT JOIN planillad 	as d    on d.planilla=p.cod_planilla
	LEFT JOIN saltos 		as s    on s.cod_salto=d.salto
	LEFT JOIN usuarios 		as c 	on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios 		as c2 	on c2.cod_usuario=p.clavadista2
	LEFT JOIN categorias 	as t 	on t.cod_categoria=p.categoria
	LEFT JOIN modalidades 	as m 	on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta_posiciones .= " WHERE e.competencia = $cod_competencia and p.momento_termino is NOT NULL
 ORDER BY e.competencia, e.fechahora, m.modalidad, t.categoria, p.sexo, p.extraof, p.lugar, p.orden_salida, d.planilla, d.ronda";

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

$dec=decimales($cod_competencia);
include("impr-calificaciones-competencia.php");
$conexion->close();

$transfer="Location: $llamo";
header($transfer);
exit();

?>