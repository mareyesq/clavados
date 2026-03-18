<?php 
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();
include_once("funciones.php");

if (isset($_GET["fh"])) {
	$fechahora=trim($_GET["fh"]);
	$cod_competencia=trim($_GET["codco"]);
	$competencia=trim($_GET["com"]);
	$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;
	$logo2=isset($_GET["lg2"])?$_GET["lg2"]:NULL;
	$_SESSION["logo"]=$logo;
	$_SESSION["logo2"]=$logo2;
	$modalidad=trim($_GET["mod"]);
	$cat=trim($_GET["cat"]) ;
	$sx=trim($_GET["sx"]);
	$tipo=trim($_GET["tp"]);
	$numero_evento=trim($_GET["nev"]);
	$descripcion=trim($_GET["des"]);
	$fecha=trim($_GET["ho"]);
	$hora=trim($_GET["hor"]);
	$evento=trim($_GET["ev"]);
	$anunciador=isset($_GET["anu"])?$_GET["anu"]:NULL;
	$mostrar_resultados=isset($_SESSION['mostrar_resultados'])?$_SESSION['mostrar_resultados']:NULL;
	$origen="?op=php/siguiente-salto.php*com=$competencia*fh=$fechahora*mod=$modalidad*cat=$cat*sx=$sx*tp=$tipo*codco=$cod_competencia*nev=$evento*des=$descripcion*ho=$fecha*ev=$evento*hor=$hora&lg=$logo";
	if ($anunciador)
		$origen .= "&anu=$anunciador";
	$_SESSION["cod_competencia"]=$cod_competencia;
	$_SESSION["modalidad"]=$modalidad;
	$_SESSION["evento"]=$evento;
	$_SESSION["logo"]=$logo;
	$_SESSION["logo2"]=$logo2;
	$_SESSION["numero_evento"]=$numero_evento;
}
if (!$logo)
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
if (!$logo2)
	$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
$ronda_anterior=isset($_SESSION["ronda_anterior"])?$_SESSION["ronda_anterior"]:NULL;
$turno=isset($_SESSION["anterior_turno"])?$_SESSION["anterior_turno"]:NULL;

$orden_salida=isset($_SESSION["orden_salida"])?$_SESSION["orden_salida"]:NULL;
$ejecutor=isset($_SESSION["ejecutor"])?$_SESSION["ejecutor"]:NULL;

if ($turno) {
 	$criterio_turno=" AND turno=".$turno." AND orden_salida=".$_SESSION["anterior_orden_salida"];
 	$ejecutor=isset($_SESSION["anterior_ejecutor"])?$_SESSION["anterior_ejecutor"]:NULL;
 	if ($ejecutor)
 		$criterio_turno .= " AND ejecutor=$ejecutor";
 } 
 else
 	$criterio_turno=" AND calificado IS NULL ";

if (is_null($competencia)) $competencia=trim($_SESSION["competencia"]);
if (is_null($cod_competencia)) $cod_competencia=trim($_SESSION["cod_competencia"]);
if (is_null($fechahora)) $fechahora=trim($_SESSION["fechahora"]);
if (is_null($modalidad)) $modalidad=trim($_SESSION["modalidad"]);
if (is_null($cat)) $cat=trim($_SESSION["cat"]);
if (is_null($sexos)) $sexos=trim($_SESSION["sexoss"]);
if (is_null($tipo)) $tipo=trim($_SESSION["tipo"]);
if (is_null($evento)) $evento=trim($_SESSION["evento"]);
if (is_null($numero_evento)) $numero_evento=trim($_SESSION["numero_evento"]);
if (is_null($descripcion)) $descripcion=trim($_SESSION["descripcion"]);
if (is_null($fecha)) $fecha=trim($_SESSION["fecha"]);
if (is_null($hora)) $hora=trim($_SESSION["hora"]);
if (is_null($origen)) $origen=trim($_SESSION["origen"]);
if (is_null($anunciador)) $anunciador=$_SESSION["anunciador"];

$dec=decimales($cod_competencia);
$conexion=conectarse();
$consulta="SELECT inicio_comp FROM competenciaev WHERE competencia=$cod_competencia AND numero_evento=$numero_evento and inicio_comp IS NULL";
$ejecutar_consulta = $conexion->query($consulta);

$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==0) 
	$inicio=TRUE;
else
	$inicio=FALSE;

$consulta="SELECT finalizo_comp FROM competenciaev WHERE competencia=$cod_competencia AND numero_evento=$numero_evento and finalizo_comp IS NULL";
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==0) 
	$finalizo=1;
else
	$finalizo=0;

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

$criterio=" WHERE (p.competencia=$cod_competencia AND p.modalidad='$modalidad' AND p.usuario_retiro IS NULL AND p.evento=$numero_evento) ";

$consulta="SELECT DISTINCT p.cod_planilla 
	FROM planillas as p";
$consulta .= $criterio;
$ejecutar_consulta = $conexion->query($consulta);

$competidores=$ejecutar_consulta->num_rows;

$consulta="SELECT DISTINCT MAX(d.ronda) as rondas
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";

$consulta .= $criterio;
$ejecutar_consulta = $conexion->query($consulta);
$row=$ejecutar_consulta->fetch_assoc();

$rondas=$row["rondas"];

$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.orden_salida, d.ronda, d.salto, d.posicion, d.altura, d.calificado
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";
$consulta .= $criterio;
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;

$tiempo_estimado=tiempo_estimado($num_regs);
$consulta="SELECT DISTINCT ubicacion, juez	FROM competenciasz
	WHERE competencia=$cod_competencia and evento=$numero_evento and panel=1
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
			if (!($ub==25)){
				$num_jueces++;
			} 
		}

	}
}

$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.entrenador, p.competencia, p.modalidad, p.categoria, p.sexo, p.equipo, p.extraof, p.part_abierta, p.clavadista2, p.orden_salida, p.total, p.lugar, d.ronda, d.turno, d.ejecutor, d.salto, s.salto as nom_salto, d.posicion, d.altura, d.grado_dif, d.abierto, d.total_salto, d.puntaje_salto, d.acumulado, d.penalizado, d.calificado, d.juez1, d.juez2, d.juez3, d.juez4, d.juez5, d.juez6, d.juez7, d.juez8, d.juez9, d.juez10, d.juez11, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod,  q.equipo as nom_equipo, q.bandera, t.categoria as nom_cat, c.imagen as img_1, c2.imagen as img_2, r.marca_damas, r.grado_damas, r.promedio_damas, r.marca_varones, r.grado_varones, r.promedio_varones
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
	LEFT JOIN competenciasm as r on r.competencia=p.competencia and r.categoria=p.categoria and r.modalidad=p.modalidad
	LEFT JOIN saltos as s on s.cod_salto=d.salto
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN categorias as t on t.cod_categoria=p.categoria
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta .= $criterio.$criterio_turno." ORDER BY d.turno, p.orden_salida";
 if ($modalidad=="E") 
 	$consulta .= ", d.ejecutor";
 $consulta .=  " LIMIT 1";
if (!$finalizo){
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$cod_planilla=$row["cod_planilla"];
		$cod_cla=$row["clavadista"];
		$cod_ent=$row["entrenador"];
		$cod_equ=$row["equipo"];
		$bandera=$row["bandera"];
		$orden_salida=$row["orden_salida"];
		$nom_cla=utf8_decode($row["nom_cla"]);
		$nom_cla2=utf8_decode($row["nom_cla2"]);
		$ejecutor=isset($row["ejecutor"])?$row["ejecutor"]:NULL;
		if ($nom_cla2){
			if ($modalidad=="E"){
				if ($ejecutor==2)
					$nom_cla=$nom_cla2;
			}
			else{
				$sincronizado=1;		
				$nom_cla .= " / ".$nom_cla2;
			}
		} 
//		$nom_cla=strtolower($nom_cla);
//		$nom_cla=ucwords($nom_cla);
		$nom_equipo=utf8_decode($row["nom_equipo"]);
		$modalidad=$row["modalidad"];
		$categoria=$row["categoria"];
		$sexo=$row["sexo"];
		if ($sexo=="F") 
			$nom_sexo="Damas";
		elseif ($sexo=="M") 
			$nom_sexo="Varones";
		else
			$nom_sexo="Mixto";
		
		$nom_cat=utf8_encode($row["nom_cat"]);
		$nom_mod=utf8_encode($row["nom_mod"]);
		$total=$row["total"];
		$part_abierta=$row["part_abierta"];
		$lugar=$row["lugar"];
		$ronda=$row["ronda"];
		$turno=$row["turno"];
		if ($modalidad=="E"){
			if ($ejecutor==1){
				$imagen1=$row["img_1"];
				$imagen2=NULL;
			}
			else{
				$imagen1=NULL;
				$imagen2=$row["img_2"];
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
		$total_salto=$row["total_salto"];
		$puntaje_salto=$row["puntaje_salto"];
		$acumulado=$row["acumulado"];
		$penalidad=number_format($row["penalizado"],1);
		$cal= array();
		$cal_cop= array();
		$cal[1]=$row["cal1"];
		$cal[2]=$row["cal2"];
		$cal[3]=$row["cal3"];
		$cal[4]=$row["cal4"];
		$cal[5]=$row["cal5"];
		$cal[6]=$row["cal6"];
		$cal[7]=$row["cal7"];
		$cal[8]=$row["cal8"];
		$cal[9]=$row["cal9"];
		$cal[10]=$row["cal10"];
		$cal[11]=$row["cal11"];
		if (!is_null($penalidad))
			for ($i=1; $i <$num_jueces+1 ; $i++) 
				$cal[$i] -=$penalidad;
		$cal_cop=elimina_calificaciones($cal,$sincronizado,$num_jueces);
		$calificado=$row["calificado"];
	}
}

$encabezado="Evento No. ".$evento." ".$fecha." Hora: ".$hora." - ".$descripcion." Tiempo Estimado: ".$tiempo_estimado." minutos";
$encabezado1="Evento No. ".$evento." ".$fecha." Hora: ".$hora;

if ($calificado) {
	unset($_SESSION["anterior_turno"]);
	unset($_SESSION["anterior_orden_salida"]);
	unset($_SESSION["anterior_ejecutor"]);
	$espera=5;
}
else{
	$_SESSION["anterior_ronda"]=$ronda;
	$_SESSION["anterior_turno"]=$turno;
	$_SESSION["anterior_orden_salida"]=$orden_salida;
	$_SESSION["anterior_ejecutor"]=$ejecutor;
	$espera=2;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Competencias de Clavados</title>
	<meta charset="utf-8"/>
	<meta http-equiv="refresh" content="<?php echo $espera; ?>" > 
	<meta name="description" content="Software para administrar competencias de clavados"/>
    <?php 
        $nav=$_SERVER['HTTP_USER_AGENT'];
        $cual="chrome";
        //Demilitador 'i' para no diferenciar mayus y minus
        if (preg_match("/".$cual."/i", $nav)) 
//            echo "<link rel='stylesheet' type='text/css' href='css/estilos.css'/>";
    ?>
     <link rel='stylesheet' type='text/css' href='../css/estilos.css'/>
     <script type="text/javascript">
     	function abrir_ventana(pagina,planilla){
            var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, titlebar=no, scrollbars=no, resizable=yes, width=700, height=300, top=200, left=640";
            var url=pagina+"?pla="+planilla;
     		window.open(url,"ventana",opciones);
     	}
     </script>
</head>
<body>
    <section id="contenedor">
		<section id="principal" >
 		<form id="siguiente-salto" name="siguiente-salto-frm" action="..?op=php/menu-siguiente-salto.php" method="post" enctype="multipart/form-data">
 		<fieldset>
		<?php  
			$nueva_pagina=TRUE;
			include("titulo-competencia.php") ?>
		<h2><?php echo $encabezado; ?></h2>
		<?php 
			if (!$inicio) 
				echo "<span aling='center'><br>Esta competencia no ha iniciado :(<br></span>";
			elseif (!$finalizo){
//				if ($turno == 1 AND $mostrar_resultados){
				if ($orden_salida == 1 AND $mostrar_resultados){
					include("resultados-evento.php");
					$_SESSION['mostrar_resultados']=FALSE;
				}
				include("siguiente-salto-1.php");
			}
		?>
		<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>"> 	
		<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>"> 	
		<input type="hidden" name="evento_hdn" value="<?php echo $evento; ?>"> 	
		<input type="hidden" name="numero_evento_hdn" value="<?php echo $numero_evento; ?>"> 	
		<input type="hidden" name="encabezado_hdn" value="<?php echo $encabezado; ?>"> 	
		<input type="hidden" name="encabezado1_hdn" value="<?php echo $encabezado1; ?>"> 	
		<input type="hidden" name="fechahora_hdn" value="<?php echo $fechahora; ?>"> 	
		<input type="hidden" name="modalidad_hdn" value="<?php echo $modalidad; ?>"> 	
		<input type="hidden" name="cat_hdn" value="<?php echo $cat; ?>"> 	
		<input type="hidden" name="sexos_hdn" value="<?php echo $sexos; ?>">
		<input type="hidden" name="tipo_hdn" value="<?php echo $tipo; ?>">
		<input type="hidden" name="descripcion_hdn" value="<?php echo $descripcion; ?>">
		<input type="hidden" name="fecha_hdn" value="<?php echo $fecha; ?>">
		<input type="hidden" name="hora_hdn" value="<?php echo $hora; ?>">
		<input type="hidden" name="origen_hdn" value="<?php echo $origen; ?>">
		<input type="hidden" name="calificado_hdn" value="<?php echo $calificado; ?>">
		
	</fieldset>
	</form>		
	</section>
<!-- 		<aside>
			&lt;aside&gt
		</aside>
 --></section>
	<footer>
      <p>Información de Contacto: <a href="mailto:soporte@softneosas.com">
  soporte@softneosas.com</a></p>
	</footer>
</body>
</html>
