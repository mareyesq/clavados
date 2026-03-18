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

$consulta_posiciones="SELECT p.cod_planilla, p.equipo, p.orden_salida, p.total, p.lugar, p.extraof, p.categoria, p.sexo, p.extraof, p.evento, CONCAT (p.categoria, p.sexo) as orden_categorias, cat.categoria as nom_cat, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod, e.modalidad, e.sexos, e.categorias, e.tipo, e.fechahora
	FROM planillas as p ";

$consulta_posiciones.=" LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciaev as e on e.competencia=p.competencia and e.numero_evento=p.evento
	WHERE p.competencia=$cod_competencia
		AND p.categoria<>'AB'
		AND p.usuario_retiro IS NULL
		AND e.finalizo_comp IS NOT NULL";
$consulta_posiciones.=" ORDER BY e.fechahora, p.evento, p.categoria, p.sexo, p.extraof, p.lugar";
$ejecutar_consulta_posiciones = $conexion->query(utf8_decode($consulta_posiciones));

$consulta_abierta="SELECT p.cod_planilla, p.equipo, p.orden_salida, p.total_abierta, p.part_abierta, p.lugar_abierta, p.extraof_abierto, p.sexo, p.evento, m.modalidad as nom_mod, e.modalidad, e.sexos, e.categorias, e.tipo, e.fechahora";

$consulta_abierta.=", c.nombre as nom_cla, c2.nombre as nom_cla2
	FROM planillas as p";

$consulta_abierta.=" LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciaev as e on e.competencia=p.competencia and e.numero_evento=p.evento
	WHERE p.competencia=$cod_competencia
		AND (p.categoria='AB' or p.part_abierta='*')
		AND p.usuario_retiro IS NULL
		AND e.finalizo_comp  IS NOT NULL";
$consulta_abierta.=" ORDER BY e.fechahora, p.evento, p.sexo, p.extraof_abierto, p.lugar_abierta";
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

include("impr-medallistas-competencia.php");
$conexion->close();
$transfer="Location: $llamo";
header($transfer);
exit();
?>	
