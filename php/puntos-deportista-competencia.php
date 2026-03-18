<?php 
// Pruebas individuales categorias
$datos=array();
$conexion=conectarse();
$consulta="SELECT p.clavadista, u.nombre as nom_cla, u.sexo, q.equipo, sum(t.puntos) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=1
group by p.clavadista
order BY u.sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'puntos' => 10000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	$fila["puntos"] +=number_format($row["tot_puntos"],1);
	$datos[$cla]=$fila;
}

// Pruebas individuales participantes en abierta
$consulta="SELECT p.clavadista, u.nombre as nom_cla, u.sexo, q.equipo, sum(t.puntos) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar_abierta
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and (p.categoria='AB' or p.part_abierta='*') and p.usuario_retiro IS NULL and p.extraof_abierto is NULL and p.momento_termino is not NULL and  m.individual=1
group by p.clavadista
order BY u.sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	$fila["puntos"] +=number_format($row["tot_puntos"],1);
	$datos[$cla]=$fila;
}

// Pruebas Sincronizado Deportista 1
$consulta="SELECT p.clavadista,  u.nombre as nom_cla, u.sexo, q.equipo, sum(t.puntos_sinc) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0
group by p.clavadista
order BY sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	$fila["puntos"] +=number_format($row["tot_puntos"]/2,1);
	$datos[$cla]=$fila;
}
// Pruebas Sincronizado Deportista 2
$consulta="SELECT p.clavadista2,  u.nombre as nom_cla, u.sexo, q.equipo, sum(t.puntos_sinc) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista2
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0
group by p.clavadista2
order BY u.sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);
while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista2"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	$fila["puntos"] +=number_format($row["tot_puntos"]/2,1);
	$datos[$cla]=$fila;
}

arsort($datos);

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

$conexion->close();
include("impr-puntos-deportista-competencia.php");
$transfer="Location: $llamo";
header($transfer);
exit();
 ?>