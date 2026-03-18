<?php 
$conexion=conectarse();
$consulta_posiciones="SELECT DISTINCT  e.competencia, e.fechahora,  p.sexo, p.extraof, p.participa_extraof, p.part_abierta, p.clavadista2, p.orden_salida, p.total, p.lugar, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod,  q.equipo as nom_equipo, k.categoria as nom_cat, k.individual, c.imagen as img_1, c2.imagen as img_2, t.puntos, t.puntos_sinc
	FROM competenciaev as e
	LEFT JOIN planillas 	as p    on p.competencia=e.competencia and p.evento=e.numero_evento
	LEFT JOIN usuarios 		as c 	on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios 		as c2 	on c2.cod_usuario=p.clavadista2
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN modalidades 	as m 	on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta_posiciones .= " WHERE e.competencia = $cod_competencia and e.finalizo_comp IS NOT NULL and p.momento_termino is NOT NULL and p.categoria <> 'AB' ORDER BY e.competencia, e.fechahora, m.modalidad, k.categoria, p.sexo, p.extraof, p.lugar";

$ejecutar_consulta_posiciones = $conexion->query(utf8_decode($consulta_posiciones));
$num_regs=$ejecutar_consulta_posiciones->num_rows;

$consulta_abierta="SELECT DISTINCT  e.competencia, e.fechahora,  p.part_abierta, p.sexo, p.clavadista2, p.extraof_abierto, p.participa_extraof, p.orden_salida, p.total_abierta, p.lugar_abierta, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod,  q.equipo as nom_equipo, k.categoria as nom_cat, k.individual, c.imagen as img_1, c2.imagen as img_2, t.puntos, t.puntos_sinc
	FROM competenciaev as e
	LEFT JOIN planillas 	as p    on p.competencia=e.competencia and p.evento=e.numero_evento
	LEFT JOIN usuarios 		as c 	on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios 		as c2 	on c2.cod_usuario=p.clavadista2
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN modalidades 	as m 	on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar_abierta
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta_abierta .= " WHERE e.competencia = $cod_competencia and (p.categoria='AB' OR p.part_abierta='*') and e.finalizo_comp IS NOT NULL and p.momento_termino is NOT NULL
 ORDER BY e.competencia, e.fechahora, m.modalidad, p.sexo, p.lugar_abierta";

$ejecutar_consulta_abierta = $conexion->query(utf8_decode($consulta_abierta));

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
include("impr-resultados-competencia.php");
$conexion->close();
$transfer="Location: $llamo";
header($transfer);
exit();

?>