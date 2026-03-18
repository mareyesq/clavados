<?php 
include("funciones.php");
if (isset($_GET["com"])) {
	$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
	$categoria=isset($_GET["cat"])? trim($_GET["cat"]):NULL;
	$modalidad=isset($_GET["mod"])?$_GET["mod"]:NULL;
	$cod_competencia=isset($_GET["codco"])?$_GET["codco"]:NULL;
	$cod_categoria=isset($_GET["codca"])?$_GET["codca"]:NULL;
	$cod_modalidad=isset($_GET["codmo"])?$_GET["codmo"]:NULL;
	$conexion=conectarse();
	$consulta="SELECT * FROM competenciasm 
		WHERE competencia=$cod_competencia 
			AND categoria='$cod_categoria'
			AND modalidad='$cod_modalidad'";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1){
		$row=$ejecutar_consulta->fetch_assoc();
		$marca_f=$row["marca_damas"];
		$grado_f=$row["grado_damas"];
		$prom_f=$row["promedio_damas"];
		$marca_m=$row["marca_varones"];
		$grado_m=$row["grado_varones"];
		$prom_m=$row["promedio_varones"];
	}
	$conexion->close();
}
session_start();
if (!isset($competencia)) 
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:null;
if (!isset($cod_competencia)) 
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null;
if (!isset($categoria)) 
	$categoria=(isset($_SESSION["categoria"])?$_SESSION["categoria"]:null);
if (!isset($modalidad)) 
	$modalidad=isset($_SESSION["modalidad"])?$_SESSION["modalidad"]:null;
if (!isset($marca_f)) 
	$marca_f=isset($_SESSION["marca_f"])?$_SESSION["marca_f"]:null;

if (!isset($grado_f)) 
	$grado_f=isset($_SESSION["grado_f"])?$_SESSION["grado_f"]:null;

if (!isset($prom_f)) 
	$prom_f=isset($_SESSION["prom_f"])?$_SESSION["prom_f"]:null;

if (!isset($marca_m)) 
	$marca_m=isset($_SESSION["marca_m"])?$_SESSION["marca_m"]:null;

if (!isset($grado_m)) 
	$grado_m=isset($_SESSION["grado_m"])?$_SESSION["grado_m"]:null;

if (!isset($prom_m)) 
	$prom_m=isset($_SESSION["prom_m"])?$_SESSION["prom_m"]:null;

$alta=FALSE;
$baja=TRUE;
?>
<form id="edita-marca" name="edita-frm" action="php/elimina-marca-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Elimna Marcas de Competencia: <?php echo $competencia; ?></legend>

		<?php include("formulario-marcas.php"); ?>
		<div>
			<br>
			<input type="submit" id="enviar-edit" title="Eliminar esta Marca de la Competencia" class="cambio" name="eliminar_sbm" value="Elimina Marca" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a Marcas de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>