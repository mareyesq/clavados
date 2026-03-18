<?php 
$separa_extraoficiales=0;
$conexion=conectarse();
$consulta="SELECT max_2_competidores 
	FROM competencias 
	WHERE cod_competencia=$cod_competencia";

$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==1) {
	$row=$ejecutar_consulta->fetch_assoc();
	$separa_extraoficiales=$row["max_2_competidores"];
}

$consulta_posiciones="SELECT p.categoria, p.lugar, p.equipo, q.equipo as nom_equipo, k.categoria as nom_cat
	FROM planillas 	as p
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN modalidades 	as m 	on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta_posiciones .= " WHERE p.competencia = $cod_competencia AND p.momento_termino is NOT NULL AND p.extraof IS NULL AND p.categoria<>'AB' AND m.individual=1
		AND p.usuario_retiro IS NULL and p.lugar<4
 	ORDER BY k.categoria, p.sexo, q.equipo, p.lugar";
$ejecutar_consulta_posiciones = $conexion->query($consulta_posiciones);

$consulta_abierta="SELECT p.lugar_abierta as lugar, p.equipo, q.equipo as nom_equipo
	FROM planillas 	as p
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN modalidades 	as m 	on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar_abierta
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta_abierta .= " WHERE p.competencia = $cod_competencia AND (p.categoria='AB' OR p.part_abierta='*') and p.momento_termino is NOT NULL AND p.extraof_abierto IS NULL and p.lugar_abierta<4 and m.individual=1
 	ORDER BY p.sexo, q.equipo, p.lugar";
$ejecutar_consulta_abierta = $conexion->query($consulta_abierta);

$consulta_sinc="SELECT p.categoria, p.lugar, p.equipo, q.equipo as nom_equipo, k.categoria as nom_cat
	FROM planillas 	as p
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN modalidades 	as m 	on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta_sinc .= " WHERE p.competencia = $cod_competencia AND p.momento_termino is NOT NULL AND p.extraof IS NULL AND m.individual=0
		AND p.usuario_retiro IS NULL and p.lugar<4
 	ORDER BY k.categoria, p.sexo, q.equipo, p.lugar";
$ejecutar_consulta_sinc = $conexion->query($consulta_sinc);

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

include("impr-medallas-categoria-competencia.php");
$conexion->close();
$transfer="Location: $llamo";
header($transfer);
exit();

?>