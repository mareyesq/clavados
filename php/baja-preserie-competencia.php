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
	$consulta="SELECT p.*, c.categoria as nom_categoria, m.modalidad as nom_modalidad, s.salto as nom_salto
		FROM preseries as p
		LEFT JOIN categorias as c on c.cod_categoria=p.categoria
		LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
		LEFT JOIN saltos as s on s.cod_salto=p.salto
		WHERE (p.competencia=$cod_competencia 
			AND p.modalidad='$modalidad' 
			AND p.categoria='$categoria' ) 
		ORDER BY p.orden";
	$ejecutar_consulta = $conexion->query($consulta);
	$items_ser=array();
	while ($row=$ejecutar_consulta->fetch_assoc()) {
		$orden=isset($row["orden"])?$row["orden"]:NULL;
		$salto=isset($row["salto"])?$row["salto"]:NULL;
		$nom_salto=isset($row["nom_salto"])?$row["nom_salto"]:NULL;
		$posicion=isset($row["posicion"])?$row["posicion"]:NULL;
		$grado=isset($row["grado"])?$row["grado"]:NULL;
		$observacion=isset($row["observacion"])?$row["observacion"]:NULL;
		$libre=isset($row["libre"])?$row["libre"]:NULL;
		$nom_modalidad=isset($row["nom_modalidad"])?utf8_decode($row["nom_modalidad"]):NULL;
		$nom_categoria=isset($row["nom_categoria"])?utf8_decode($row["nom_categoria"]):NULL;
		$items_ser[]=array('orden' => $orden, 'salto' => $salto, 'posicion' => $posicion, 'grado' => $grado, 'observacion' => $observacion, 'libre' => $libre, 'nom_salto' => $nom_salto);
	}
	$conexion->close();
}

$editando=0;
$alta=0;
$baja=1;
?>
<form id="baja-preserie" name="baja-preserie-frm" action="php/elimina-preserie-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">Eliminar Serie Predefinida</legend>
		<?php include("formulario-preserie-ver.php"); ?>
		<div>
			<?php 
				if (is_null($mensaje)) {
					echo "<input type='submit' id='eliminar' class='cambio' name='eliminar_sbm' value='Eliminar' title='Elimina la serie predefinida del Sistema' />";
				 } ?>
			<input type="submit" id="regresar" title="Regresa a Consulta de Preseries" class="cambio" name="regresar_sbm" value="Regresar" />
		</div> 
	</fieldset>
</form>
