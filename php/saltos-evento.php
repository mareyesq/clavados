<?php 
if (isset($_GET["com"])) {
	$primera_vez=1;
	$fechahora=trim($_GET["fh"]);
	$cod_competencia=trim($_GET["codco"]);
	$competencia=trim($_GET["com"]);
	$modalidad=trim($_GET["mod"]);
	$cat=trim($_GET["cat"]) ;
	$sx=trim($_GET["sx"]);
	$tipo=trim($_GET["tp"]);
	$numero_evento=trim($_GET["nev"]);
	$evento=trim($_GET["ev"]);
	$hora=trim($_GET["hor"]);
	$descripcion=trim($_GET["des"]);
	$fecha=trim($_GET["ho"]);
	$origen="?op=php/califica-evento-juez.php*com=$competencia*fh=$fechahora*mod=$modalidad*cat=$cat*sx=$sx*tp=$tipo*codco=$cod_competencia*nev=$numero_evento*des=$descripcion*ho=$fecha*ev=$evento*hor=$hora";
}
session_start();
include("funciones.php");

if (!isset($evento))
	$evento=isset($_SESSION["evento"])?trim($_SESSION["evento"]):NULL;

if (!isset($numero_evento))
 	$numero_evento=isset($_SESSION["numero_evento"])?trim($_SESSION["numero_evento"]):NULL;

$conexion=conectarse();
$consulta="SELECT inicio_comp FROM competenciaev WHERE competencia=$cod_competencia AND numero_evento=$numero_evento";
$ejecutar_consulta=$conexion->query($consulta);
$row=$ejecutar_consulta->fetch_assoc();
if (is_null($row["inicio_comp"])) 
	$inicio=FALSE;
else
	$inicio=TRUE;

$consulta="SELECT finalizo_comp FROM competenciaev WHERE competencia=$cod_competencia AND numero_evento=$numero_evento";
$ejecutar_consulta=$conexion->query($consulta);
$row=$ejecutar_consulta->fetch_assoc();
if (is_null($row["finalizo_comp"])) 
	$finalizo=FALSE;
else
	$finalizo=TRUE;

$calificando=isset($_SESSION["calificando"])?$_SESSION["calificando"]:NULL;
$turno=isset($_SESSION["turno"])?trim($_SESSION["turno"]):NULL;
$orden_salida=isset($_SESSION["orden_salida"])?trim($_SESSION["orden_salida"]):NULL;
if ($turno) 
 	$criterio_turno=" AND turno=".$turno." AND orden_salida=".$orden_salida;
else{
// 	$criterio_turno=" AND calificado IS NULL";
 	$criterio_turno="  AND calificado IS NULL AND calificando=1";
}

$protege=isset($_SESSION["protege"])?$_SESSION["protege"]:FALSE;
if (strlen($protege)==0) 
	$protege=FALSE;

if (!isset($calificacion)) 
	$calificacion=isset($_SESSION["calificacion"])?number_format(trim($_SESSION["calificacion"]),1):NULL;
if (is_null($competencia)) $competencia=trim($_SESSION["competencia"]);
if (is_null($cod_competencia)) $cod_competencia=trim($_SESSION["cod_competencia"]);
if (is_null($fechahora)) $fechahora=trim($_SESSION["fechahora"]);
if (is_null($modalidad)) $modalidad=trim($_SESSION["modalidad"]);
if (is_null($cat)) $cat=trim($_SESSION["cat"]);
if (!isset($sexos)) 
	$sexos=isset($_SESSION["sexos"])?trim($_SESSION["sexos"]):NULL;
if (is_null($tipo)) $tipo=trim($_SESSION["tipo"]);
if (is_null($descripcion)) $tipo=trim($_SESSION["descripcion"]);
if (is_null($fecha)) $tipo=trim($_SESSION["fecha"]);
if (is_null($hora)) $hora=trim($_SESSION["hora"]);
if (is_null($origen)) $origen=trim($_SESSION["origen"]);

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

$criterio=" WHERE (p.competencia=$cod_competencia AND p.modalidad='$modalidad' AND p.usuario_retiro IS NULL AND p.evento=$numero_evento ";

$consulta="SELECT DISTINCT p.cod_planilla 
	FROM planillas as p";
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

$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.orden_salida, d.ronda, d.salto, d.posicion, d.altura, d.calificado
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";
$consulta .= $criterio.")";
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;

$tiempo_estimado=number_format(($num_regs*40/60)+2);
$sincronizado=0;

$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.entrenador, p.competencia, p.modalidad, p.categoria, p.sexo, p.equipo, p.extraof, p.clavadista2, p.orden_salida, p.total, p.lugar, d.ronda, d.turno, d.salto, s.salto as nom_salto, d.posicion, d.altura, d.grado_dif, d.calificado, d.juez1, d.juez2, d.juez3, d.juez4, d.juez5, d.juez6, d.juez7, d.juez8, d.juez9, d.juez10, d.juez11, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod,  q.equipo as nom_equipo, t.categoria as nom_cat, c.imagen as img_1, c2.imagen as img_2
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
	LEFT JOIN competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
	LEFT JOIN saltos as s on s.cod_salto=d.salto
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN categorias as t on t.cod_categoria=p.categoria
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad";
$consulta .= $criterio.$criterio_turno.")
 ORDER BY d.turno, p.orden_salida
 LIMIT 1";
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==0) {
	$calificar=FALSE;
}
else{
	$calificar=TRUE;
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$cod_planilla=$row["cod_planilla"];
		$cod_cla=$row["clavadista"];
		$cod_ent=$row["entrenador"];
		$orden_salida=$row["orden_salida"];
		$nom_cla=utf8_decode($row["nom_cla"]);
		$nom_cla2=utf8_decode($row["nom_cla2"]);
		if (strlen($nom_cla2)>0){
			$nom_cla .= " / ".$nom_cla2;
			$sincronizado=1;		
		} 
		$nom_cla=strtolower($nom_cla);
		$nom_cla=ucwords($nom_cla);
		$modalidad=$row["modalidad"];
		$categoria=$row["categoria"];
		$sexo=$row["sexo"];
		if ($sexo=="F") 
			$nom_sexo="Damas";
		else
			$nom_sexo="Varones";
		$nom_cat=utf8_encode($row["nom_cat"]);
		$nom_mod=utf8_encode($row["nom_mod"]);
		$ronda=$row["ronda"];
		$turno=$row["turno"];
		$imagen1=$row["img_1"];
		$imagen2=$row["img_2"];
		$salto=$row["salto"];
		$nom_salto=utf8_encode($row["nom_salto"]);
		$posicion=$row["posicion"];
		$altura=$row["altura"];
		$grado_dif=$row["grado_dif"];	
		$calificado=$row["calificado"];
	}
}
$conexion->close();

$llamador="?op=php/eventos-competencia.php*com=$competencia*cco=$cod_competencia";

$encabezado="Evento No. ".$evento." ".$fecha." Hora: ".$hora." - ".$descripcion." Tiempo Estimado: ".$tiempo_estimado." minutos";

?>

<form id="saltos-evento" name="saltos-evento-frm" action="?op=php/menu-saltos-evento.php" method="post" enctype="multipart/form-data">
	<h2>Competencia: <?php echo $competencia ?></h2>
	<h3><?php echo $encabezado; ?></h3>
	<div>
		<?php 
			if (!$inicio) {
				echo "<span>Aún no ha iniciado esta competencia !!! :(<br> </span>";
			}
			elseif ($finalizo) 
				echo "<span>Finalizó esta competencia !!! :)<br> </span>";
			else
				if (!$calificar) 
				 	echo "<span>No hay saltos a ejecutar, dale Regresar y espera instrucciones de Mesa de Control<br></span>";
				 else
					include("salto-evento.php");
		 ?>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
		<input type="hidden" id="evento" name="evento_hdn" value="<?php echo $evento; ?>" />
		<input type="hidden" id="numero-evento" name="numero_evento_hdn" value="<?php echo $numero_evento; ?>" />
		<input type="hidden" id="descripcion" name="descripcion_hdn" value="<?php echo $descripcion; ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamador; ?>" />
		<input type="hidden" id="origen" name="origen_hdn" value="<?php echo $origen; ?>" />
		<input type="hidden" id="sincronizado" name="sincronizado_hdn" value="<?php echo $sincronizado; ?>" />
		<input type="hidden" id="criterio" name="criterio_hdn" value="<?php echo $criterio; ?>" />
		<input type="hidden" id="criterio-sexos" name="criterio_sexos_hdn" value="<?php echo $criterio_sexos; ?>" />
		<input type="hidden" id="criterio-categorias" name="criterio_categorias_hdn" value="<?php echo $criterio_categorias; ?>" />
		<input type="hidden" id="cod-planilla" name="cod_planilla_hdn" value="<?php echo $cod_planilla; ?>" />
		<input type="hidden" id="ronda" name="ronda_hdn" value="<?php echo $ronda; ?>" />
		<input type="hidden" id="turno" name="turno_hdn" value="<?php echo $turno; ?>" />
		<input type="hidden" id="ubicacion" name="ubicacion_hdn" value="<?php echo $mi_ubicacion; ?>" />
		<input type="hidden" id="orden-salida" name="orden_salida_hdn" value="<?php echo $orden_salida; ?>" />
		<input type="hidden" id="modalidad" name="modalidad_hdn" value="<?php echo $modalidad; ?>" />

		<input type="hidden" id="categoria" name="categoria_hdn" value="<?php echo $categoria; ?>" />
		<input type="hidden" id="sexo" name="sexo_hdn" value="<?php echo $sexo; ?>" />

		<input type="submit" id="regresar" title="Regresa a eventos de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
</form>