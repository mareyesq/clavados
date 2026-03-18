<?php 
if (isset($_GET["nev"])) {
	$cod_competencia=isset($_GET["codco"])?$_GET["codco"]:NULL;
	$evento=isset($_GET["nev"])?$_GET["nev"]:NULL;
	$descripcion=isset($_GET["des"])?$_GET["des"]:NULL;
	include("funciones.php");
}

//include("funciones.php");
$conexion=conectarse();
$consulta="SELECT p.clavadista, p.evento, p.modalidad, p.categoria, p.sexo, p.equipo, d.grado_dif, d.total_salto, d.puntaje_salto, d.juez1, d.juez2, d.juez3, d.juez4, d.juez5, d.juez6, d.juez7, d.juez8, d.juez9, d.juez10, d.juez11, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, d.penalizado, j1.nombre as nom_j1, j2.nombre as nom_j2, j3.nombre as nom_j3, j4.nombre as nom_j4, j5.nombre as nom_j5, j6.nombre as nom_j6, j7.nombre as nom_j7, j8.nombre as nom_j8, j9.nombre as nom_j9, j10.nombre as nom_j10, j11.nombre as nom_j11
	FROM planillas as p 
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN categorias as k on k.cod_categoria=p.categoria
	LEFT JOIN usuarios as j1 on j1.cod_usuario=d.juez1
	LEFT JOIN usuarios as j2 on j2.cod_usuario=d.juez2
	LEFT JOIN usuarios as j3 on j3.cod_usuario=d.juez3
	LEFT JOIN usuarios as j4 on j4.cod_usuario=d.juez4
	LEFT JOIN usuarios as j5 on j5.cod_usuario=d.juez5
	LEFT JOIN usuarios as j6 on j6.cod_usuario=d.juez6
	LEFT JOIN usuarios as j7 on j7.cod_usuario=d.juez7
	LEFT JOIN usuarios as j8 on j8.cod_usuario=d.juez8
	LEFT JOIN usuarios as j9 on j9.cod_usuario=d.juez9
	LEFT JOIN usuarios as j10 on j10.cod_usuario=d.juez10
	LEFT JOIN usuarios as j11 on j11.cod_usuario=d.juez11
	WHERE competencia=$cod_competencia AND m.individual=1 AND momento_termino IS NOT NULL";
if ($evento) {
	$consulta .=" AND p.evento=$evento";
}
$consulta .= " ORDER BY p.evento, p.modalidad, p.categoria, p.sexo, p.equipo ";
$ejecutar_consulta_evaluacion = $conexion->query($consulta);

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

$nueva_pagina=TRUE;
include("impr-evaluacion-jueces-competencia.php");
$conexion->close();
$transfer="Location: $llamo";
header($transfer);
exit();

?>
 ?>