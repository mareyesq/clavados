<?php 

include("funciones.php");
$conexion=conectarse();
if (isset($_GET["fh"])) {
	$fechahora=trim($_GET["fh"]);
	$cod_competencia=trim($_GET["codco"]);
	include("lee-evento-competencia.php");
/*	$competencia=trim($_GET["com"]);
	$modalidad=trim($_GET["mod"]);
	$cat=trim($_GET["cat"]) ;
	$sx=trim($_GET["sx"]);
	$tipo=trim($_GET["tp"]);
	$numero_evento=trim($_GET["nev"]);
	$evento=trim($_GET["ev"]);
	$descripcion=trim($_GET["des"]);
	$fecha=trim($_GET["ho"]);
	$hora=trim($_GET["hor"]);
	$primero_libres=trim($_GET["pl"]) ;
*/	$origen="?op=php/califica-evento-competencia.php*com=$competencia*mod=$modalidad*cat=$cat*sx=$sx*tp=$tipo*codco=$cod_competencia*nev=$numero_evento*des=$descripcion*ho=$fecha*ev=$evento*hor=$hora*pl=$primero_libres";
	$categorias=explode("-", $cat) ;
	$sexos=explode("-", $sx);
	if (in_array("GB", $categorias)
		and in_array("GA", $categorias)
		and (in_array("AB", $categorias) or in_array("MY", $categorias))) {
		$mezcla=TRUE;
		$ronda_mayores=0;
		if (!$primero_libres) {
			if (in_array("F", $sexos) 
				or $modalidad=="P")
				$ronda_mayores=4;
			else
				$ronda_mayores=5;
		}
	}
	else{
		$mezcla=FALSE;
		$ronda_mayores=0;
	}

	if ($modalidad=="E" AND $cat=='EQ')
		include("asigna-turno-eq.php");
	else{
		$actualiza="UPDATE planillad as d 
			LEFT JOIN planillas as p on p.cod_planilla=d.planilla
			SET d.turno=d.ronda 
			WHERE p.competencia=$cod_competencia 
				and p.evento=$numero_evento
				AND p.usuario_retiro IS NULL";
		$ejecutar_actualiza = $conexion->query($actualiza);
	}

	if ($ronda_mayores>0) {
		$actualiza="UPDATE planillad as d 
			LEFT JOIN planillas as p on p.cod_planilla=d.planilla
 			SET d.turno=d.ronda+$ronda_mayores 
			WHERE p.competencia=$cod_competencia 
				AND p.evento=$numero_evento 
				AND p.usuario_retiro IS NULL
				AND (categoria='AB' or categoria='MY') ";
		$ejecutar_actualiza = $conexion->query($actualiza);
	}
	if ($primero_libres) {
/* 		AND p.part_abierta='*' 
		AND (p.categoria='GA' OR p.categoria='GB')
 */
		$consulta="SELECT d.planilla, d.ronda, d.turno, d.abierto
			FROM planillad as d 
			LEFT JOIN planillas as p on p.cod_planilla=d.planilla
		WHERE p.competencia=$cod_competencia 
			AND p.evento=$numero_evento 
			AND p.usuario_retiro IS NULL
		ORDER BY d.planilla, d.abierto DESC, d.ronda";
		$ejecutar_consulta = $conexion->query($consulta);
		$plan=0;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$planilla=$row["planilla"];
			$ronda=$row["ronda"];
			if ($planilla!=$plan){
				$turno=0;
				$plan=$planilla;
			}
			$turno++;
			$actualiza="UPDATE planillad
				SET turno=$turno
				WHERE planilla=$planilla and ronda=$ronda";
			$ejecutar_actualiza = $conexion->query($actualiza);
		}
	}
}

session_start();
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
if (is_null($competencia)) $competencia=trim($_SESSION["competencia"]);
if (is_null($cod_competencia)) $cod_competencia=trim($_SESSION["cod_competencia"]);
if (is_null($numero_evento)) $numero_evento=trim($_SESSION["numero_evento"]);
if (is_null($evento)) $evento=trim($_SESSION["evento"]);
$consulta="SELECT inicio_comp, usa_dispositivos FROM competenciaev
	WHERE competencia=$cod_competencia and numero_evento=$numero_evento";
$ejecutar_consulta = $conexion->query($consulta);
$row=$ejecutar_consulta->fetch_assoc();
$inicio=isset($row["inicio_comp"])?$row["inicio_comp"]:NULL;
$usa_dispositivos=isset($row["usa_dispositivos"])?$row["usa_dispositivos"]:NULL;

if (is_null($fechahora)) $fechahora=trim($_SESSION["fechahora"]);
if (is_null($modalidad)) $modalidad=trim($_SESSION["modalidad"]);
if (is_null($cat)) $cat=trim($_SESSION["cat"]);
if (is_null($sx)) $sx=trim($_SESSION["sx"]);
if (is_null($tipo)) $tipo=trim($_SESSION["tipo"]);
if (is_null($descripcion)) $descripcion=trim($_SESSION["descripcion"]);
if (is_null($fecha)) $fecha=trim($_SESSION["fecha"]);
if (is_null($hora)) $hora=trim($_SESSION["hora"]);
if (is_null($primero_libres)) $primero_libres=trim($_SESSION["primero_libres"]);
if (is_null($origen)) $origen=trim($_SESSION["origen"]);
if (is_null($edita))
	$edita=strlen($_SESSION["edita"])>0?trim($_SESSION["edita"]):1;

switch ($tipo) {
	case 'P':
		$tipo_competencia="Preliminar";
		break;
	case 'S':
		$tipo_competencia="Semifinal";
		break;
	case 'F':
		$tipo_competencia="Final";
		break;
}

$categorias=explode("-", $cat) ;
$sexos=explode("-", $sx);

$criterio=" WHERE (p.competencia=$cod_competencia AND p.evento=$numero_evento AND p.usuario_retiro IS NULL ";

$n=count($sexos);
$criterio_sexos="";
for ($i=0; $i < $n ; $i++) { 
	if ($criterio_sexos=="") 
		$criterio_sexos=" AND (p.sexo='$sexos[$i]'";
	else
		$criterio_sexos .= " OR p.sexo='$sexos[$i]'";
}
if (!$criterio_sexos=="") 
	$criterio_sexos .= ")"; 

$n=count($categorias);
$criterio_categorias="";
for ($i=0; $i < $n ; $i++) { 
	if ($criterio_categorias=="") 
		$criterio_categorias=" AND (p.categoria='$categorias[$i]'";
	else
		$criterio_categorias .= " OR p.categoria='$categorias[$i]'";
}
if (!$criterio_categorias=="") 
	$criterio_categorias .= ")";

// Marca extraof y extraof los que participan como extraoficial
$consulta="UPDATE planillas SET extraof='*', extraof_abierto='*'";
$consulta .= $criterio." AND participa_extraof='*' )";
$ejecutar_consulta = $conexion->query($consulta);

$consulta="SELECT DISTINCT p.cod_planilla 
	FROM planillas as p";
//$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")";
$consulta .= $criterio.")";
$ejecutar_consulta = $conexion->query($consulta);
$competidores=$ejecutar_consulta->num_rows;

$consulta="SELECT DISTINCT MAX(d.ronda) as rondas
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";

$consulta .= $criterio.")";
$ejecutar_consulta = $conexion->query($consulta);
$row=$ejecutar_consulta->fetch_assoc();

$rondas=$row["rondas"];
if (in_array("GB", $categorias)
	and in_array("GA", $categorias)
	and (in_array("AB", $categorias) or in_array("MY", $categorias))) {
	if (in_array("F", $sexos) 
		or $modalidad=="P")
		$ronda_mayores=$rondas-5;
	else
		$ronda_mayores=$rondas-6;
}
else
	$ronda_mayores=0;

$turno=isset($_SESSION["turno"])?trim($_SESSION["turno"]):NULL;
$ejecutor=isset($_SESSION["ejecutor"])?trim($_SESSION["ejecutor"]):NULL;
$ejecutor2=isset($_SESSION["ejecutor2"])?trim($_SESSION["ejecutor2"]):NULL;
$orden_salida=isset($_SESSION["orden_salida"])?trim($_SESSION["orden_salida"]):NULL;

if (strlen($turno)>0) {
 	$criterio_turno=" AND d.turno=".$turno." AND p.orden_salida=".$orden_salida;
 	if ($modalidad=='E' AND $cat=='EQ' AND $ejecutor)
 		$criterio_turno .= " AND ejecutor=$ejecutor";
} 
else
 	$criterio_turno=" AND calificado IS NULL ";


$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.orden_salida, d.ronda, d.salto, d.posicion, d.altura, d.calificado
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";
$consulta .= $criterio.")";
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;

$tiempo_estimado=number_format($num_regs*(40/60)+2);
$panel_1=array();
$panel_2=array();

$consulta="SELECT DISTINCT z.panel, z.ubicacion, z.juez, u.nombre, u.imagen
	FROM competenciasz as z
	LEFT JOIN usuarios as u on u.cod_usuario=z.juez 
	WHERE z.competencia=$cod_competencia and z.evento=$numero_evento and z.panel=1
	ORDER BY ubicacion";
$ejecutar_consulta = $conexion->query($consulta);

if ($ejecutar_consulta){
	$num_regs=$ejecutar_consulta->num_rows;

	if (!$num_regs)
		$mensaje="No hay jueces en Panel 1 registrados para este evento :(";
	else{
		$num_jueces=0;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$ub=$row["ubicacion"];
			if ($ub!=25){
				$num_jueces++;
				$panel_1[$ub]=$row;
			} 
		}
	}
}

$consulta="SELECT DISTINCT z.panel, z.ubicacion, z.juez, u.nombre, u.imagen
	FROM competenciasz as z
	LEFT JOIN usuarios as u on u.cod_usuario=z.juez 
	WHERE z.competencia=$cod_competencia and z.evento=$numero_evento and z.panel=2
	ORDER BY ubicacion";
$ejecutar_consulta = $conexion->query($consulta);
if ($ejecutar_consulta){
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$ub=$row["ubicacion"];
		$panel_2[$ub]=$row;
	}
}

$dec=decimales($cod_competencia);

$jueces = array();
$nombres= array();
if ($panel_2) {
	# colocar opción para seleccionar panel
}
else
	$panel=1;

for ($i=1; $i < 12; $i++) { 
	if ($panel==1)
		$row=$panel_1[$i];
	if ($panel==2) 
		$row=$panel_2[$i];
	$ubi=$row["ubicacion"];
	$jueces[$ubi]=$row["juez"];
	$nombre=primera_mayuscula_palabra($row["nombre"]);
	$nombres[$ubi]=$nombre;
}

$_SESSION["jueces"]=$jueces;
$sincronizado=0;

$consulta_base="SELECT DISTINCT p.cod_planilla, p.clavadista, p.entrenador, p.competencia, p.modalidad, p.categoria, p.sexo, p.equipo, p.extraof, p.clavadista2, p.clavadista3, p.clavadista4, p.orden_salida, p.total, p.lugar, d.ronda, d.salto, d.turno, s.salto as nom_salto, d.posicion, d.altura, d.grado_dif, d.abierto, d.suma, d.total_salto, d.puntaje_salto, d.acumulado, d.penalizado, d.calificado, d.juez1, d.juez2, d.juez3, d.juez4, d.juez5, d.juez6, d.juez7, d.juez8, d.juez9, d.juez10, d.juez11, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, d.ejecutor, d.ejecutor2, c.nombre as nom_cla, c2.nombre as nom_cla2, c3.nombre as nom_cla3, c4.nombre as nom_cla4, m.modalidad as nom_mod,  q.equipo as nom_equipo, q.bandera, t.categoria as nom_cat, c.imagen as img_1, c2.imagen as img_2, c3.imagen as img_3, c4.imagen as img_4, j1.nombre as nom_juez1, j2.nombre as nom_juez2, j3.nombre as nom_juez3, j4.nombre as nom_juez4, j5.nombre as nom_juez5, j6.nombre as nom_juez6, j7.nombre as nom_juez7, j8.nombre as nom_juez8, j9.nombre as nom_juez9, j10.nombre as nom_juez10, j11.nombre as nom_juez11
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
	LEFT JOIN saltos as s on s.cod_salto=d.salto
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN usuarios as c3 on c3.cod_usuario=p.clavadista3
	LEFT JOIN usuarios as c4 on c4.cod_usuario=p.clavadista4
	LEFT JOIN categorias as t on t.cod_categoria=p.categoria
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
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
	LEFT JOIN usuarios as j11 on j11.cod_usuario=d.juez11";
$consulta = $consulta_base.$criterio.$criterio_turno.")
 ORDER BY d.turno, p.orden_salida, d.ronda
 LIMIT 1";
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==0) {
	$finalizo=TRUE;
	$consulta="SELECT finalizo_comp
		FROM competenciaev as p 
		WHERE competencia=$cod_competencia and numero_evento=$numero_evento";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$row=$ejecutar_consulta->fetch_assoc();
	if (is_null($row["finalizo_comp"])){
		$consulta="SELECT ciudad FROM competencias WHERE cod_competencia=$cod_competencia";
		$ejecutar_consulta = $conexion->query($consulta);
		if ($ejecutar_consulta){
			$row=$ejecutar_consulta->fetch_assoc();
			$cod_ciudad=$row["ciudad"];
		}
		$ahora=ahora($cod_ciudad);
		$actualiza="UPDATE competenciaev
			SET finalizo_comp='$ahora'
			WHERE competencia=$cod_competencia and numero_evento=$numero_evento";
		$ejecutar_actualiza = $conexion->query($actualiza);
		$actualiza="UPDATE planillas
			SET momento_termino='$ahora'
			WHERE competencia=$cod_competencia and evento=$numero_evento";
		$ejecutar_actualiza = $conexion->query($actualiza);

	}
}
else{
	$finalizo=FALSE;
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$cod_planilla=$row["cod_planilla"];
		$cod_cla=$row["clavadista"];
		$cod_ent=$row["entrenador"];
		$cod_equ=$row["equipo"];
		$bandera=$row["bandera"];
		$orden_salida=$row["orden_salida"];
		$nom_cla=primera_mayuscula_palabra($row["nom_cla"]);
		$nom_cla2=isset($row["nom_cla2"])?primera_mayuscula_palabra($row["nom_cla2"]):NULL;
		$nom_cla3=isset($row["nom_cla3"])?primera_mayuscula_palabra($row["nom_cla3"]):NULL;
		$nom_cla4=isset($row["nom_cla4"])?primera_mayuscula_palabra($row["nom_cla4"]):NULL;
		$modalidad=$row["modalidad"];
		$ejecutor=isset($row["ejecutor"])?$row["ejecutor"]:NULL;
		$ejecutor2=isset($row["ejecutor2"])?$row["ejecutor2"]:NULL;
		if ($nom_cla2){
			if ($modalidad=="E" AND $cat=='EQ'){
				if ($ejecutor==2)
					$nom_cla=$nom_cla2;
			}
			else{
				if ($modalidad=="E" AND $cat=='Q1') {
					switch ($ejecutor) {
						case '1':
							$nom=$nom_cla;
							break;
						case '2':
							$nom=$nom_cla2;
							break;
						case '3':
							$nom=$nom_cla3;
							break;
						case '4':
							$nom=$nom_cla4;
							break;
					}
					if ($ejecutor2){
						$sincronizado=1;
						switch ($ejecutor2) {
							case '1':
								$nom.=' / '.$nom_cla;
								break;
							case '2':
								$nom.=' / '.$nom_cla2;
								break;
							case '3':
								$nom.=' / '.$nom_cla3;
								break;
							case '4':
								$nom.=' / '.$nom_cla4;
								break;
						}
					}
					$nom_cla=$nom;
				}
				else{
					$sincronizado=1;		
					$nom_cla .= " / ".$nom_cla2;
				}
			}
		} 
//		$nom_cla=strtolower($nom_cla);
//		$nom_cla=ucwords($nom_cla);
		$nom_equipo=strtoupper($row["nom_equipo"]);
		$categoria=$row["categoria"];
		$sexo=$row["sexo"];
		if ($sexo=="F") 
			$nom_sexo="Damas";
		elseif ($sexo=="F") 
				$nom_sexo="Varones";
		elseif ($sexo=="X") 
			$nom_sexo="Mixto";
		
		$nom_cat=primera_mayuscula_frase($row["nom_cat"]);
		$nom_mod=primera_mayuscula_frase($row["nom_mod"]);
		$total=$row["total"];
		$lugar=$row["lugar"];
		if ($row["part_abierta"]=="*")
			$nom_cat.=" Y ABIERTA";
		
		$ronda=$row["ronda"];
		$turno=$row["turno"];
		if ($modalidad=="E" AND $cat=='EQ'){
			if ($ejecutor==1){
				$imagen1=$row["img_1"];
				$imagen2=NULL;
			}
			else{
				$imagen1=NULL;
				$imagen2=$row["img_2"];
			}
		}
		else
			if ($modalidad=='E' AND $cat=='Q1') {
				switch ($ejecutor) {
					case '1':
						$imagen1=$row["img_1"];
						break;
					case '2':
						$imagen1=$row["img_2"];
						break;
					case '3':
						$imagen1=$row["img_3"];
						break;
					case '4':
						$imagen1=$row["img_4"];
						break;
				}
				switch ($ejecutor2) {
					case '1':
						$imagen2=$row["img_1"];
						break;
					case '2':
						$imagen2=$row["img_2"];
						break;
					case '3':
						$imagen2=$row["img_3"];
						break;
					case '4':
						$imagen2=$row["img_4"];
						break;
				}
				
			}
			else{
				$imagen1=$row["img_1"];
				$imagen2=$row["img_2"];
			}
		$salto=$row["salto"];
		$nom_salto=utf8_encode($row["nom_salto"]);
		$posicion=$row["posicion"];
		$altura=$row["altura"];
		$grado_dif=$row["grado_dif"];	
		$abierto=$row["abierto"];
		$suma=isset($row["suma"])?$row["suma"]:NULL;
		$total_salto=$row["total_salto"];
		$puntaje_salto=$row["puntaje_salto"];
		$acumulado=$row["acumulado"];
		$penalidad=number_format($row["penalizado"],1);
		$cal1=isset($row["cal1"])?$row["cal1"]:NULL;
		$cal2=isset($row["cal2"])?$row["cal2"]:NULL;
		$cal3=isset($row["cal3"])?$row["cal3"]:NULL;
		$cal4=isset($row["cal4"])?$row["cal4"]:NULL;
		$cal5=isset($row["cal5"])?$row["cal5"]:NULL;
		$cal6=isset($row["cal6"])?$row["cal6"]:NULL;
		$cal7=isset($row["cal7"])?$row["cal7"]:NULL;
		$cal8=isset($row["cal8"])?$row["cal8"]:NULL;
		$cal9=isset($row["cal9"])?$row["cal9"]:NULL;
		$cal10=isset($row["cal10"])?$row["cal10"]:NULL;
		$cal11=isset($row["cal11"])?$row["cal11"]:NULL;
		$calificado=$row["calificado"];
		if ($calificado==1){
			$juez1=$row["juez1"];
			$juez2=$row["juez2"];
			$juez3=$row["juez3"];
			$juez4=$row["juez4"];
			$juez5=$row["juez5"];
			$juez6=$row["juez6"];
			$juez7=$row["juez7"];
			$juez8=$row["juez8"];
			$juez9=$row["juez9"];
			$juez10=$row["juez10"];
			$juez11=$row["juez11"];
			$nom_juez1=primera_mayuscula_palabra($row["nom_juez1"]);
			$nom_juez2=primera_mayuscula_palabra($row["nom_juez2"]);
			$nom_juez3=primera_mayuscula_palabra($row["nom_juez3"]);
			$nom_juez4=primera_mayuscula_palabra($row["nom_juez4"]);
			$nom_juez5=primera_mayuscula_palabra($row["nom_juez5"]);
			$nom_juez6=primera_mayuscula_palabra($row["nom_juez6"]);
			$nom_juez7=primera_mayuscula_palabra($row["nom_juez7"]);
			$nom_juez8=primera_mayuscula_palabra($row["nom_juez8"]);
			$nom_juez9=primera_mayuscula_palabra($row["nom_juez9"]);
			$nom_juez10=primera_mayuscula_palabra($row["nom_juez10"]);
			$nom_juez11=primera_mayuscula_palabra($row["nom_juez11"]);
		}
		else{
			$juez1=$jueces[1];
			$juez2=$jueces[2];
			$juez3=$jueces[3];
			$juez4=$jueces[4];
			$juez5=$jueces[5];
			$juez6=$jueces[6];
			$juez7=$jueces[7];
			$juez8=$jueces[8];
			$juez9=$jueces[9];
			$juez10=$jueces[10];
			$juez11=$jueces[11];
			$nom_juez1=$nombres[1];
			$nom_juez2=$nombres[2];
			$nom_juez3=$nombres[3];
			$nom_juez4=$nombres[4];
			$nom_juez5=$nombres[5];
			$nom_juez6=$nombres[6];
			$nom_juez7=$nombres[7];
			$nom_juez8=$nombres[8];
			$nom_juez9=$nombres[9];
			$nom_juez10=$nombres[10];
			$nom_juez11=$nombres[11];
		}
	}

	if ($_SESSION["err"]==1) {
		$cal=$_SESSION["cal"];
		unset($_SESSION["err"]);
		if ($_SESSION["penalidad_ss"]>0) {
			$penalidad=number_format($_SESSION["penalidad_ss"],1);
			$_SESSION["penalidad_ss"]="";
		}
		else
			$penalidad="";
		$cal1=$cal[1];
		$cal2=$cal[2];
		$cal3=$cal[3];
		$cal4=$cal[4];
		$cal5=$cal[5];
		$cal6=$cal[6];
		$cal7=$cal[7];
		$cal8=$cal[8];
		$cal9=$cal[9];
		$cal10=$cal[10];
		$cal11=$cal[11];
	}

	$actualiza_calificando="UPDATE planillad SET calificando=1 WHERE planilla=$cod_planilla and turno=$turno";
	if ($ejecutor) 
		$actualiza_calificando .= " AND ejecutor=$ejecutor";
	
	$ejecutar_actualiza_calificando = $conexion->query($actualiza_calificando);
	
}
/* Cambié por función Puntaje_primero abril 4/2019
$consulta = "SELECT lugar, lugar_abierta, extraof, total, total_abierta 
	FROM planillas 
	WHERE (competencia=$cod_competencia 
		AND evento=$numero_evento
		AND extraof IS NULL ";

if ($sexo) 
	$consulta.=" AND sexo='$sexo'";

if ($categoria) 
	$consulta.=" AND categoria='$categoria'";

if ($categoria=="AB")
	$consulta.=" AND lugar_abierta=1)";
else
	$consulta.=" AND lugar=1)";

if ($categoria=="AB")
	$consulta.=" ORDER BY lugar_abierta";
else
	$consulta.=" ORDER BY lugar";

$ejecutar_consulta = $conexion->query($consulta);

if ($ejecutar_consulta) {
	$reg=$ejecutar_consulta->fetch_assoc();
	if ($categoria=="AB")
		$total1=$reg["total_abierta"];
	else
		$total1=$reg["total"];
}
else
	$total1=0;
*/
$total1=puntaje_primero($cod_competencia,$numero_evento,$sexo,$categoria);

$consulta="SELECT DISTINCT p.categoria as cat, p.cod_planilla, p.equipo, p.part_abierta, p.orden_salida, p.total, p.lugar, p.total_abierta, p.lugar_abierta, p.extraof, p.extraof_abierto";
if ($categoria or $sexo or $ronda) 
	$consulta .= ", d.ronda, d.calificado";

$consulta .= ", c.nombre as nom_cla, c2.nombre as nom_cla2, c3.nombre as nom_cla3, c4.nombre as nom_cla4
	FROM planillas as p";

if ($categoria or $sexo or $ronda) 
	$consulta .= " LEFT JOIN planillad as d on d.planilla=p.cod_planilla";

$consulta .= " LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
 	LEFT JOIN usuarios as c3 on c3.cod_usuario=p.clavadista3
	LEFT JOIN usuarios as c4 on c4.cod_usuario=p.clavadista4
	WHERE competencia=$cod_competencia
		AND p.evento=$numero_evento
		AND p.usuario_retiro IS NULL";

if ($categoria) {
	if ($categoria=="AB") 
		$criterio_categoria = " AND (p.categoria='AB' or (p.categoria<>'AB' and p.part_abierta='*'))";
	else
		$criterio_categoria = " AND p.categoria='$categoria'";
}
else
	$criterio_categoria = NULL;

if ($sexo) {
	$criterio_sexo = " AND p.sexo='$sexo'";
}
else
	$criterio_sexo = NULL;

if (!$turno) 
	$turno=1;

if ($turno) {
	$criterio_turno = " AND d.turno=$turno";
}
else
	$criterio_turno = NULL;

if ($ejecutor AND $cat!='Q1')
	$criterio_turno .= " AND ejecutor=$ejecutor";	

$consulta_posiciones=$consulta;
if ($criterio_categoria) {
	$consulta_posiciones.=$criterio_categoria;
}
if ($criterio_sexo) {
	$consulta_posiciones.=$criterio_sexo;
}

if ($criterio_turno) {
	$consulta_posiciones.=$criterio_turno;
}

if ($categoria=="AB") 
	$consulta_posiciones .= " ORDER BY p.sexo, p.extraof_abierto, p.lugar_abierta";
else
	$consulta_posiciones .= " ORDER BY p.categoria, p.sexo, p.extraof, p.lugar";

if ($modalidad=="E") 
	$consulta_posiciones .= ", p.cod_planilla";

$ejecutar_consulta_posiciones = $conexion->query($consulta_posiciones);
$num_comp=$ejecutar_consulta_posiciones->num_rows;

$llamador="?op=php/eventos-competencia.php*com=$competencia*cco=$cod_competencia";

$encabezado="Evento No. ".$evento." ".$fecha." hora: ".$hora." - ".$descripcion." Tiempo Estimado: ".$tiempo_estimado." minutos";
?>

<form id="califica-evento" name="califica-evento-frm" action="?op=php/menu-califica-evento.php" method="post" enctype="multipart/form-data">
<fieldset>
	<?php include("titulo-competencia.php") ?>
	<h2><?php echo $encabezado; ?></h2>
	<div>
		<?php
			if (!$num_jueces)
				echo "<span>Debe definir los jueces de esta competencia :(<br> </span>";
			else
				if ($finalizo) 
					echo "<span>Finaizó esta competencia !!! :)<br> </span>";
				else{
					include("califica-evento-competencia-1.php");
					if ($inicio) 
						include("califica-evento-competencia-2.php");
				}
			
		 ?>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
		<input type="hidden" id="evento" name="evento_hdn" value="<?php echo $evento; ?>" />
		<input type="hidden" id="numero-evento" name="numero_evento_hdn" value="<?php echo $numero_evento; ?>" />
		<input type="hidden" id="descripcion" name="descripcion_hdn" value="<?php echo $descripcion; ?>" />
		<input type="hidden" id="usa_dispositivos" name="usa_dispositivos_hdn" value="<?php echo $usa_dispositivos; ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamador; ?>" />
		<input type="hidden" id="origen" name="origen_hdn" value="<?php echo $origen; ?>" />
		<input type="hidden" id="grado-dif" name="grado_dif_hdn" value="<?php echo $grado_dif; ?>" />
		<input type="hidden" id="num-jueces" name="num_jueces_hdn" value="<?php echo $num_jueces; ?>" />
		<input type="hidden" id="sincronizado" name="sincronizado_hdn" value="<?php echo $sincronizado; ?>" />
		<input type="hidden" id="fecha" name="fecha_hdn" value="<?php echo $fecha; ?>" />
		<input type="hidden" id="criterio" name="criterio_hdn" value="<?php echo $criterio; ?>" />
		<input type="hidden" id="criterio-sexo" name="criterio_sexo_hdn" value="<?php echo $criterio_sexo; ?>" />
		<input type="hidden" id="criterio-categoria" name="criterio_categoria_hdn" value="<?php echo $criterio_categoria; ?>" />
		<input type="hidden" id="sx" name="sx_hdn" value="<?php echo $sx; ?>" />
		<input type="hidden" id="cat" name="cat_hdn" value="<?php echo $cat; ?>" />
		<input type="hidden" id="cod-planilla" name="cod_planilla_hdn" value="<?php echo $cod_planilla; ?>" />
		<input type="hidden" id="ronda" name="ronda_hdn" value="<?php echo $ronda; ?>" />
		<input type="hidden" id="turno" name="turno_hdn" value="<?php echo $turno; ?>" />
		<input type="hidden" id="ejecutor" name="ejecutor_hdn" value="<?php echo $ejecutor; ?>" />
		<input type="hidden" id="orden-salida" name="orden_salida_hdn" value="<?php echo $orden_salida; ?>" />
		<input type="hidden" id="modalidad" name="modalidad_hdn" value="<?php echo $modalidad; ?>" />

		<input type="hidden" id="categoria" name="categoria_hdn" value="<?php echo $categoria; ?>" />
		<input type="hidden" id="sexo" name="sexo_hdn" value="<?php echo $sexo; ?>" />
		<input type="hidden" id="sexo" name="completo_hdn" value="<?php echo $completo; ?>" />

		<input type="hidden" id="edita" name="edita_hdn" value="<?php echo $edita; ?>" />

		<?php 
		if (!$inicio)
			echo "<input type='submit' tabindex = '1' id='corregir' acces_key='i' title='Iniciar Competencia' class='cambio' name='iniciar_sbm' value='Iniciar' />";
		else
			if ($num_jueces)
				if (!$finalizo) {
					echo "<label for='penalidad'>Penalizado: </label>";
					echo "<input type='number' min='0' max='2' id='penalidad' acceskey='p'  name='penalidad_hdn' value='$penalidad' readonly />&nbsp;";
				if ($edita==1) 
					echo "<input tabindex = '12' type='submit' acceskey='t' id='totaliza'  autofocus title='calcula total del salto' class='cambio' name='totaliza_sbm' value='Totaliza'>&nbsp;";
				else
					echo "<input tabindex = '13' type='submit' acceskey='s' id='siguiente' title='Siguiente Salto' class='cambio' name='siguiente_sbm' value='Siguiente' >&nbsp;";
				echo "<input type='submit' tabindex = '14' id='anterior' acceskey='a' title='Salto anterior' class='cambio' name='anterior_sbm' value='Anterior' >&nbsp;";
				echo "<input type='submit' id='limpia' acceskey='l' title='Limpia calificaciones' class='cambio' name='limpia_sbm' value='Limpia' >&nbsp;";
				echo "<input type='submit' tabindex = '15' id='fallado' acceskey='f' title='Salto Fallado' class='cambio' name='fallado_sbm' value='Fallado' >&nbsp;";
				if ($usa_dispositivos and $edita) {
					echo "<input type='submit' tabindex = '20' id='recibe-cal' acceskey='r' title='Recibe calificaciones de los dispositivos' class='cambio' name='recibe_cal_sbm' value='Recibe Calificaciones'>&nbsp;";
				}
				if ($penalidad>0) 
					echo "<input type='submit' tabindex = '16' id='despenalizar' acces_key='d' title='Despenalizar Salto' class='cambio' name='despenalizar_sbm' value='Despenalizar' >&nbsp;";
			
				else
					echo "<input type='submit' tabindex = '17' id='penalizar' acces_key='p' title='Salto Penalizado' class='cambio' name='penalizar_sbm' value='Penalizar' >&nbsp;";	
		
				if ($edita==1) {
					echo "<input type='submit' tabindex = '18' id='iguales' acces_key='i' title='asigna calificaciones iguales' class='cambio' name='iguales_sbm' value='Iguales' >&nbsp;";
					if ($usa_dispositivos)
						echo "<input type='submit' tabindex = '19' id='corregir' acces_key='c' title='Corregir Calificaciones' class='cambio' name='corregir_sbm' value='Corregir' >&nbsp;";

				}
				else
					echo "<input type='submit' tabindex = '19' id='corregir' acces_key='c' title='Corregir Calificaciones' class='cambio' name='corregir_sbm' value='Corregir' >&nbsp;";


		}
		?>
		
		<input type="submit" id="regresar" tabindex = '20'  title="Regresa a eventos de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" >
	</div>
</fieldset>
</form>
