<?php 
// Pruebas individuales categorias
$cla=array();
$sex=array();
$nom=array();
$equ=array();
$pun=array();
$oro=array();
$pla=array();
$bro=array();
$sal=array();

$consulta="SELECT p.clavadista, u.nombre as nom_cla, p.sexo, q.equipo, p.lugar, t.puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.part_abierta is null and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=1";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$clavadista=$row["clavadista"];
	$puntos=number_format($row["puntos"],1);
	if (in_array($clavadista, $cla)) {
		$n=array_search($clavadista, $cla);
		if ($row["puntos"]>0)
			$pun[$n] += $puntos;
		if ($row["lugar"]==1)
			$oro[$n] ++;
		if ($row["lugar"]==2)
			$pla[$n] ++;
		if ($row["lugar"]==3)
			$bro[$n] ++;
	}
	else{
		$cla[]=$clavadista;
		$sex[]=$row["sexo"];
		$nom[]=$row["nom_cla"];
		$equ[]=$row["equipo"];
		if ($puntos>0)
			$pun[] = $puntos;
		else
			$ppun[]=0;
		if ($row["lugar"]==1)
			$oro[]=1;
		else
			$oro[]=0;
		if ($row["lugar"]==2)
			$pla[]=1;
		else
			$pla[]=0;
		if ($row["lugar"]==3)
			$bro[]=1;
		else
			$bro[]=0;

		$sal[]=0;
	}
}

// Pruebas individuales participantes en abierta
$consulta="SELECT p.clavadista, u.nombre as nom_cla, p.sexo, q.equipo, sum(t.puntos) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar_abierta
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.part_abierta='*' and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=1
group by p.clavadista
order BY sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cod_clavadista=$row["clavadista"];
	if (in_array($cod_clavadista, $cla)) {
		$n=array_search($cod_clavadista, $cla);
		$pun[$n] +=	number_format($row["tot_puntos"],1);
	}	
/*	if (array_key_exists($cod_clavadista, $punt)){
		$reg=$punt[$cod_clavadista]; 
		$reg["puntos"]+=number_format($row["tot_puntos"],1);
		$punt[$cod_clavadista]=$reg;
	}
*/	else{
		$cla[]=$row["clavadista"];
		$sex[]=$row["sexo"];
		$nom[]=$row["nom_cla"];
		$equ[]=$row["equipo"];
		$pun[]=number_format($row["tot_puntos"],1);
/*		$reg=array(	'sexo' => $row["sexo"], 
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"], 
					'puntos' => number_format($row["tot_puntos"],1));
		$punt[] = array($row["clavadista"] => $reg);
*/	}
}

// Pruebas Sincronizado Deportista 1 y Deportista2
$consulta="SELECT p.clavadista, p.clavadista2, u.nombre as nom_cla, u2.nombre as nom_cla2, p.sexo, q.equipo, sum(t.puntos_sinc) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join usuarios as u2 on u2.cod_usuario=p.clavadista2
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.part_abierta is null and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0
group by p.clavadista
order BY sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cod_clavadista=$row["clavadista"];
	$puntos=number_format($row["tot_puntos"]/2,1);
	if (in_array($cod_clavadista, $cla)) {
		$n=array_search($cod_clavadista, $cla);
		$pun[$n] +=	$puntos;
	}	

/*	if (array_key_exists($cod_clavadista, $punt)){
		$reg=$punt[$cod_clavadista]; 
		$reg["puntos"]+=$puntos;
		$punt[$cod_clavadista]=$reg;
	}
*/	else{
		$cla[]=$row["clavadista"];
		$sex[]=$row["sexo"];
		$nom[]=$row["nom_cla"];
		$equ[]=$row["equipo"];
		$pun[]=$puntos;
/*		$reg=array(	'sexo' => $row["sexo"], 
					'equipo' => $row["equipo"], 
					'nombre' => $row["nom_cla"], 
					'puntos' => $puntos);
		$punt[] = array($cod_clavadista => $reg);
*/	}
	$cod_clavadista=$row["clavadista2"];
	if (in_array($cod_clavadista, $cla)) {
		$n=array_search($cod_clavadista, $cla);
		$pun[$n] +=	$puntos;
	}	
/*	if (array_key_exists($cod_clavadista, $punt)){
		$reg=$punt[$cod_clavadista]; 
		$reg["puntos"]+=$puntos;
		$punt[$cod_clavadista]=$reg;
	}
*/	else{
		$cla[]=$row["clavadista"];
		$sex[]=$row["sexo"];
		$nom[]=$row["nom_cla"];
		$equ[]=$row["equipo"];
		$pun[]=$puntos;
/*		$reg=array(	'sexo' => $row["sexo"], 
					'nombre' => $row["nom_cla2"], 
					'equipo' => $row["equipo"], 
					'puntos' => $puntos);
		$punt[] = array($cod_clavadista => $reg);
*/	}
}


// ordena descendente
$datos=array();
$n=count($cla);
$row=array();
for ($i=0; $i < $n-1 ; $i++) { 
	$puntos=number_format($pun[$i]+1000,1);
	$datos[]=$sex[$i]."-".$puntos."-".$nom[$i]."-".$equ[$i];
}

/*foreach ($punt as $reg) {
	$clavadista=key($reg);
	$row=$reg[$clavadista];

	$sexo=$row["sexo"];
	$nombre=$row["nombre"];
	$equipo=$row["equipo"];
	$puntos=$row["puntos"]+1000;
	$puntos=substr($puntos,1,5);
}
*/

rsort($datos);

$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, City, Country
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

$conexion->close();

include("impr-puntos-deportista-competencia.php");
$transfer="Location: $llamo";
header($transfer);
exit();
 ?>