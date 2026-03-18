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
if (!isset($competencia))
	$competencia=isset($_SESSION["competencia"])?trim($_SESSION["competencia"]):NULL;
if (!isset($cod_competencia))
	$cod_competencia=isset($_SESSION["cod_competencia"])?trim($_SESSION["cod_competencia"]):NULL;
if (!isset($evento))
	$evento=isset($_SESSION["evento"])?trim($_SESSION["evento"]):NULL;
if (!isset($fecha))
 	$fecha=isset($_SESSION["fecha"])?trim($_SESSION["fecha"]):NULL;
if (!isset($hora))
 	$hora=isset($_SESSION["hora"])?trim($_SESSION["hora"]):NULL;
if (!isset($descripcion))
 	$descripcion=isset($_SESSION["descripcion"])?trim($_SESSION["descripcion"]):NULL;
if (!isset($numero_evento))
 	$numero_evento=isset($_SESSION["numero_evento"])?trim($_SESSION["numero_evento"]):NULL;

$calificacion=isset($_SESSION["calificacion"])?$_SESSION["calificacion"]:NULL;
if (!isset($protege))
 	$protege=isset($_SESSION["protege"])?trim($_SESSION["protege"]):NULL;

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

if (is_null($fechahora)) $fechahora=trim($_SESSION["fechahora"]);
if (is_null($modalidad)) $modalidad=trim($_SESSION["modalidad"]);
if (is_null($cat)) $cat=trim($_SESSION["cat"]);
if (is_null($sexos)) $sexos=trim($_SESSION["sexos"]);
if (is_null($tipo)) $tipo=trim($_SESSION["tipo"]);
if (is_null($descripcion)) $tipo=trim($_SESSION["descripcion"]);
if (is_null($fecha)) $tipo=trim($_SESSION["fecha"]);
if (is_null($hora)) $hora=trim($_SESSION["hora"]);

$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
$panel_activo=panel_activo($cod_competencia,$numero_evento);
$estoy=FALSE;
$mi_ubicacion=NULL;
$sombra=NULL;
$consulta="SELECT z.panel, z.ubicacion, z.juez, u.nombre, u.imagen
	FROM competenciasz as z
	INNER JOIN usuarios as u on u.cod_usuario=z.juez 
	WHERE z.competencia=$cod_competencia and z.evento=$numero_evento and z.panel=$panel_activo AND z.juez=$cod_usuario
	LIMIT 1";
$ejecutar_consulta = $conexion->query($consulta);
if ($ejecutar_consulta){
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs){
		$estoy=TRUE;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$mi_ubicacion=isset($row["ubicacion"])?$row["ubicacion"]:NULL;
			$nombre=isset($row["nombre"])?utf8_decode($row["nombre"]):NULL;
			$imagen=isset($row["imagen"])?$row["imagen"]:NULL;
		}
	}
}
if (!$estoy){
	$consulta="SELECT u.nombre, u.imagen
	FROM competenciass as s
	INNER JOIN usuarios as u on u.cod_usuario=s.juez 
	WHERE s.competencia=$cod_competencia and s.evento=$numero_evento AND s.juez=$cod_usuario
	LIMIT 1";
	$ejecutar_consulta = $conexion->query($consulta);

	if ($ejecutar_consulta){
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs){
			$sombra=TRUE;
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$nombre=isset($row["nombre"])?utf8_decode($row["nombre"]):NULL;
				$imagen=isset($row["imagen"])?$row["imagen"]:NULL;
			}
		}
	}
}

if (!$estoy AND "$sombra"){
	$mensaje="Lo siento, no estás en el panel de jueces ni como juez de sombra para este evento :(";
	$_SESSION["llamo"]="";
	$_SESSION["competencia"]="";
	header("Location: ?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
	exit();

}
$conexion->close();
$encabezado="Evento No. ".$evento." ".$fecha." Hora: ".$hora." - ".$descripcion;
if ($mi_ubicacion)
	$nombre_juez=$nombre;
else
	$nombre_juez=NULL;

?>

<form id="califica-evento-juez" name="califica-evento-juez-frm" action="?op=php/menu-califica-evento-juez.php" method="post" enctype="multipart/form-data">
	<h2>PANEL DE CALIFICACION</h2>
	<h3><?php echo $encabezado; ?></h3>
	<div>
		<span class="rotulo">JUEZ No. <?php echo "$mi_ubicacion - $nombre_juez";?></span>
	</div>
	<div>
		<label class="rotulo">Calificación:&nbsp;</label>
		<?php 
			if ($protege)
				include("califica-evento-juez-enviado.php");
			else
				include("califica-evento-juez-calificar.php");
		 ?>
		<input type="submit" id="regresar" title="Regresa a eventos de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" formnovalidate>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" >
		<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" >
		<input type="hidden" id="evento" name="evento_hdn" value="<?php echo $evento; ?>" >
		<input type="hidden" id="numero-evento" name="numero_evento_hdn" value="<?php echo $numero_evento; ?>" >
		<input type="hidden" id="descripcion" name="descripcion_hdn" value="<?php echo $descripcion; ?>" >
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamador; ?>" >
		<input type="hidden" id="origen" name="origen_hdn" value="<?php echo $origen; ?>" >
		<input type="hidden" id="sincronizado" name="sincronizado_hdn" value="<?php echo $sincronizado; ?>">
		<input type="hidden" id="criterio" name="criterio_hdn" value="<?php echo $criterio; ?>" >
		<input type="hidden" id="criterio-sexos" name="criterio_sexos_hdn" value="<?php echo $criterio_sexos; ?>" >
		<input type="hidden" id="criterio-categorias" name="criterio_categorias_hdn" value="<?php echo $criterio_categorias; ?>" >
		<input type="hidden" id="cod-planilla" name="cod_planilla_hdn" value="<?php echo $cod_planilla; ?>" >
		<input type="hidden" id="ronda" name="ronda_hdn" value="<?php echo $ronda; ?>" >
		<input type="hidden" id="turno" name="turno_hdn" value="<?php echo $turno; ?>" >
		<input type="hidden" id="mi_ubicacion" name="mi_ubicacion_hdn" value="<?php echo $mi_ubicacion; ?>" >
		<input type="hidden" id="sombra" name="sombra_hdn" value="<?php echo $sombra; ?>" >
		<input type="hidden" id="cod_usuario" name="cod_usuario_hdn" value="<?php echo $cod_usuario; ?>" >
		<input type="hidden" id="orden-salida" name="orden_salida_hdn" value="<?php echo $orden_salida; ?>">
		<input type="hidden" id="modalidad" name="modalidad_hdn" value="<?php echo $modalidad; ?>">

		<input type="hidden" id="categoria" name="categoria_hdn" value="<?php echo $categoria; ?>">
		<input type="hidden" id="sexo" name="sexo_hdn" value="<?php echo $sexo; ?>">
		<input type="hidden" id="calificacion_hdn" name="calificacion_hdn" value="<?php echo $calificacion; ?>" >

	</div>
</form>