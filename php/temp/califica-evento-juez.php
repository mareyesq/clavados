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

$cod_usuario=$_SESSION["usuario_id"];
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

$jueces = array();
$nombres= array();
$estoy=FALSE;
$mi_ubicacion=NULL;
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
	$jz=$row["juez"];
	if ($jz==$cod_usuario){
		$estoy=TRUE;
		$mi_ubicacion=$ubi;		
	}
	$jueces[$ubi]=$row["juez"];
	$nombre=strtolower($row["nombre"]);
	$nombres[$ubi]=ucwords($nombre);
}
if (!$estoy){
	$mensaje="Lo siento, no estás en el panel de jueces de este evento :(";
	$_SESSION["llamo"]="";
	$_SESSION["competencia"]="";
	header("Location: ?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
	exit();

}
$conexion->close();
$encabezado="Evento No. ".$evento." ".$fecha." Hora: ".$hora." - ".$descripcion;
if ($mi_ubicacion)
	$nombre_juez=$nombres[$mi_ubicacion];
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
		<select class="calif" name="calificacion_slc" title="Escoja la calificación y luego pulse el botón Enviar" required <?php if ($protege) echo " disabled"; ?> > 
			<option value="">---</option>
			<option value="10.0" <?php 	if ($calificacion=="10.0") echo " selected";?>>10.0</option>
			<option value="9.5" <?php 	if ($calificacion=="9.5") echo " selected";?>>9.5</option>
			<option value="9.0" <?php 	if ($calificacion=="9.0") echo " selected";?>>9.0</option>
			<option value="8.5" <?php 	if ($calificacion=="8.5") echo " selected";?>>8.5</option>
			<option value="8.0" <?php 	if ($calificacion=="8.0") echo " selected";?>>8.0</option>
			<option value="7.5" <?php 	if ($calificacion=="7.5") echo " selected";?>>7.5</option>
			<option value="7.0" <?php 	if ($calificacion=="7.0") echo " selected";?>>7.0</option>
			<option value="6.5" <?php 	if ($calificacion=="6.5") echo " selected";?>>6.5</option>
			<option value="6.0" <?php 	if ($calificacion=="6.0") echo " selected";?>>6.0</option>
			<option value="5.5" <?php 	if ($calificacion=="5.5") echo " selected";?>>5.5</option>
			<option value="5.0" <?php 	if ($calificacion=="5.0") echo " selected";?>>5.0</option>
			<option value="4.5" <?php 	if ($calificacion=="4.5") echo " selected";?>>4.5</option>
			<option value="4.0" <?php 	if ($calificacion=="4.0") echo " selected";?>>4.0</option>
			<option value="3.5" <?php 	if ($calificacion=="3.5") echo " selected";?>>3.5</option>
			<option value="3.0" <?php 	if ($calificacion=="3.0") echo " selected";?>>3.0</option>
			<option value="2.5" <?php 	if ($calificacion=="2.5") echo " selected";?>>2.5</option>
			<option value="2.0" <?php 	if ($calificacion=="2.0") echo " selected";?>>2.0</option>
			<option value="1.5" <?php 	if ($calificacion=="1.5") echo " selected";?>>1.5</option>
			<option value="1.0" <?php 	if ($calificacion=="1.0") echo " selected";?>>1.0</option>
			<option value="0.5" <?php 	if ($calificacion=="0.5") echo " selected";?>>0.5</option>
			<option value="0.0" <?php 	if ($calificacion=="0.0") echo " selected";?>>0.0</option>
		</select>&nbsp;
		<?php 
			if (!$protege) 
				echo "<input type='submit' id='enviar' title='Envía tu calificación' class='cambio' name='enviar_sbm' value='Enviar'>";
			else{
				echo "<input type='submit' id='siguiente' title='Siguiente Clavadista' class='cambio' name='siguiente_sbm' value='Siguiente'>&nbsp;";
				echo "<input type='submit' id='corregir' title='Corrige calificación' class='cambio' name='corregir_sbm' value='Corregir'>";
			}
		 ?>
		&nbsp;
		<input type="submit" id="regresar" title="Regresa a eventos de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" formnovalidate>
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
		<input type="hidden" id="sexo" name="sexo_hdn" value="<?php echo $sexo; ?>">
		<input type="hidden" id="mi_ubicacion" name="mi_ubicacion_hdn" value="<?php echo $mi_ubicacion; ?>" />
		<input type="hidden" id="calificacion_hdn" name="calificacion_hdn" value="<?php echo $calificacion; ?>" />

	</div>
</form>