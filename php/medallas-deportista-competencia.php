<?php 
// Puntos Pruebas individuales categorias
$datos=array();
$conexion=conectarse();
$consulta="SELECT p.clavadista, u.nombre as nom_cla, p.sexo, q.equipo, sum(t.puntos) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.categoria<>'AB' and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=1
group by p.clavadista
order BY p.clavadista, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
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

// Pruebas individuales Medallas en categorías
$consulta="SELECT p.clavadista, u.nombre as nom_cla, p.sexo, q.equipo, p.lugar
FROM `planillas` as p
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.categoria<>'AB' and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL AND p.lugar<4 and  m.individual=1
order BY p.clavadista";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	$lugar=$row["lugar"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	switch ($lugar) {
		case '1':
			$fila["oro"]++;
			break;
		case '2':
			$fila["plata"]++;
			break;
		case '3':
			$fila["bronce"]++;
			break;
	}
	$datos[$cla]=$fila;
}

// Puntos Pruebas individuales participantes en abierta
$consulta="SELECT  p.clavadista, u.nombre as nom_cla, p.evento,  p.sexo, q.equipo, sum(t.puntos) as tot_puntos
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar_abierta
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and (p.categoria='AB' or p.part_abierta='*') and p.usuario_retiro IS NULL and p.extraof is NULL and p.extraof_abierto is NULL and p.momento_termino is not NULL and  m.individual=1
group by p.clavadista
order BY sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
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
// Pruebas individuales Medallas en abierta
$consulta="SELECT p.clavadista, u.nombre as nom_cla, p.sexo, q.equipo, p.lugar_abierta
FROM `planillas` as p
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and (p.categoria='AB' or p.part_abierta='*') and p.usuario_retiro IS NULL and p.extraof_abierto is NULL and p.momento_termino is not NULL AND p.lugar_abierta<4 and  m.individual=1
order BY sexo";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	$lugar=$row["lugar_abierta"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	switch ($lugar) {
		case '1':
			$fila["oro"]++;
			break;
		case '2':
			$fila["plata"]++;
			break;
		case '3':
			$fila["bronce"]++;
			break;
	}
	$datos[$cla]=$fila;
}
$ff=$datos[508];

// Puntos Pruebas Sincronizado Deportista 1 
$consulta="SELECT p.clavadista, u.nombre as nom_cla, q.equipo, sum(t.puntos_sinc) as tot_puntos, u.sexo as sexo
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0
group by p.clavadista
order BY u.sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
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

// Puntos Pruebas Sincronizado Deportista 2
$consulta="SELECT p.clavadista2, u.nombre as nom_cla, q.equipo, sum(t.puntos_sinc) as tot_puntos, u.sexo as sexo
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
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
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

// Puntos Pruebas Equipo Juvenil Deportista 3
$consulta="SELECT p.clavadista3, u.nombre as nom_cla, q.equipo, sum(t.puntos_sinc) as tot_puntos, u.sexo as sexo
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista3
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0
group by p.clavadista3
order BY u.sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=isset($row["clavadista3"])?$row["clavadista3"]:NULL;
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
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

// Puntos Pruebas Equipo Juvenil Deportista 4
$consulta="SELECT p.clavadista4, u.nombre as nom_cla, q.equipo, sum(t.puntos_sinc) as tot_puntos, u.sexo as sexo
FROM `planillas` as p
left join competenciast as t on t.competencia=p.competencia and t.puesto=p.lugar
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista4
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0
group by p.clavadista4
order BY u.sexo, tot_puntos desc";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=isset($row["clavadista4"])?$row["clavadista4"]:NULL;
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
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

// Medallas Sincronizado Deportista 1
$consulta="SELECT p.clavadista, u.nombre as nom_cla, q.equipo, p.lugar, u.sexo as sexo
FROM `planillas` as p
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0 and p.lugar<4
order BY u.sexo";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=$row["clavadista"];
	$lugar=$row["lugar"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	switch ($lugar) {
		case '1':
			$fila["oro"]++;
			break;
		case '2':
			$fila["plata"]++;
			break;
		case '3':
			$fila["bronce"]++;
			break;
	}
	$datos[$cla]=$fila;
}

// Medallas Sincronizado Deportista 2
$consulta="SELECT p.clavadista2, u.nombre as nom_cla, q.equipo, p.lugar, u.sexo as sexo
FROM `planillas` as p
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista2
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.clavadista2 is not NULL and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0 and p.lugar<4
order BY u.sexo";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=isset($row["clavadista2"])?$row["clavadista2"]:NULL;
	$lugar=$row["lugar"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	switch ($lugar) {
		case '1':
			$fila["oro"]++;
			break;
		case '2':
			$fila["plata"]++;
			break;
		case '3':
			$fila["bronce"]++;
			break;
	}
	$datos[$cla]=$fila;
}

// Medallas Sincronizado Deportista 3
$consulta="SELECT p.clavadista3, u.nombre as nom_cla, q.equipo, p.lugar, u.sexo as sexo
FROM `planillas` as p
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista3
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.clavadista3 is not NULL and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0 and p.lugar<4
order BY u.sexo";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=isset($row["clavadista3"])?$row["clavadista3"]:NULL;
	$lugar=$row["lugar"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	switch ($lugar) {
		case '1':
			$fila["oro"]++;
			break;
		case '2':
			$fila["plata"]++;
			break;
		case '3':
			$fila["bronce"]++;
			break;
	}
	$datos[$cla]=$fila;
}

// Medallas Sincronizado Deportista 4
$consulta="SELECT p.clavadista4, u.nombre as nom_cla, q.equipo, p.lugar, u.sexo as sexo
FROM `planillas` as p
left join modalidades as m on m.cod_modalidad=p.modalidad
left join usuarios as u on u.cod_usuario=p.clavadista4
left join competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
WHERE p.competencia=$cod_competencia and p.clavadista4 is not NULL and p.usuario_retiro IS NULL and p.extraof is NULL and p.momento_termino is not NULL and  m.individual=0 and p.lugar<4
order BY u.sexo";
$ejecutar_consulta=$conexion->query($consulta);

while ($row=$ejecutar_consulta->fetch_assoc()){
	$cla=isset($row["clavadista4"])?$row["clavadista4"]:NULL;
	$lugar=$row["lugar"];
	if (!array_key_exists($cla , $datos)) {
		$fila = array('sexo' => $row["sexo"], 
					'oro' 	=> 100,
					'plata' => 100,
					'bronce' => 100,
					'puntos' => 1000,
					'nombre' => $row["nom_cla"], 
					'equipo' => $row["equipo"]);
		$datos[$cla]=$fila;
	}
	else{
		$fila=$datos[$cla];
	}
	switch ($lugar) {
		case '1':
			$fila["oro"]++;
			break;
		case '2':
			$fila["plata"]++;
			break;
		case '3':
			$fila["bronce"]++;
			break;
	}
	$datos[$cla]=$fila;
}

// ordena descendente
/* $datos=array();
$n=count($cla);
$row=array();
for ($i=0; $i < $n-1 ; $i++) { 
	$puntos=number_format($pun[$i]+1000,1);
	$tot=$oro[$i]+$plata[$i]+$bronce[$i];
	$nom[$i]=ucwords(strtolower($nom[$i]));
	if ($tot) {
		$o=number_format($oro[$i]+100,0);
		$p=number_format($plata[$i]+100,0);
		$b=number_format($bronce[$i]+100,0);
		$datos[]=$sex[$i]."-".$o."-".$p."-".$b."-".$puntos."-".$nom[$i]."-".$equ[$i];
	}
}*/

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

include("impr-medallas-deportista-competencia.php");
$transfer="Location: $llamo";
header($transfer);
exit();
 ?>