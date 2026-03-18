<?php 
session_start();
include("funciones.php");
if (isset($_GET["codco"])) {
	$competencia=isset($_GET["com"])?$_GET["com"]:null;
	$logo=isset($_GET["lg"])?$_GET["lg"]:null;
	$cod_competencia=$_GET["codco"];
	$modalidad=$_GET["mod"];
	$categoria=$_GET["cat"];
	$conexion=conectarse();
	$consulta="SELECT *
		FROM preseries
		WHERE (competencia=$cod_competencia 
			AND modalidad='$modalidad' 
			AND categoria='$categoria' ) 
		ORDER BY orden";
	$ejecutar_consulta = $conexion->query($consulta);
	$items_ser=array();
	while ($row=$ejecutar_consulta->fetch_assoc()) {
		$orden=isset($row["orden"])?$row["orden"]:NULL;
		$salto=isset($row["salto"])?$row["salto"]:NULL;
		$posicion=isset($row["posicion"])?$row["posicion"]:NULL;
		$altura=isset($row["altura"])?$row["altura"]:NULL;
		$grado=isset($row["grado"])?$row["grado"]:NULL;
		$observacion=isset($row["observacion"])?$row["observacion"]:NULL;
		$libre=isset($row["libre"])?$row["libre"]:NULL;
		$items_ser[]=array('orden' => $orden, 'salto' => $salto, 'posicion' => $posicion, 'altura' => $altura, 'grado' => $grado, 'observacion' => $observacion, 'libre' => $libre);
	}
	$conexion->close();
	unset($orden);
	unset($salto);
	unset($posicion);
	unset($altura);
	unset($grado);
	unset($observacion);
	unset($libre);
	$_SESSION["cod_competencia"]=$cod_competencia;
	$_SESSION["modalidad"]=$modalidad;
	$_SESSION["categoria"]=$categoria;
	$_SESSION["items_ser"]=$items_ser;
}
else
	include("preserie-toma-session.php");

$alta=FALSE; 
$baja=0; 
?>
<form id="edita-preserie" name="edita-preserie
-frm" action="php/modifica-preserie-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo"><?php echo "$competencia"; ?></legend>
		<h1>Modifica Serie Predefinida</h1>
		<?php include("formulario-preserie.php"); ?>
		<div>
			<input type="submit" id="actualizar" class="cambio" name="actualizar_sbm" value="Actualizar" />
			<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" />
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
		</div>
	</fieldset>
</form>
