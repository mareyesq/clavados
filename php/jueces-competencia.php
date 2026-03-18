<?php 
$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
$llamo=isset($_GET["ori"])?$_GET["ori"]:NULL;

session_start();
if (!$competencia)
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL;


include("funciones.php");
$conexion=conectarse();
$cod_competencia=determina_competencia($competencia);
$consulta="SELECT distinct u.nombre as nombre, u.imagen, jz.juez as cod_usuario , jz.sombra as sombra
	FROM competenciasjz as jz 
	left join usuarios as u on u.cod_usuario=jz.juez
WHERE (jz.competencia=$cod_competencia) 
ORDER BY nombre";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
$num_regs=$ejecutar_consulta->num_rows;
if (!$llamo)
	$llamo=$_SESSION["llamo"];
?>

<form id="jueces-competencia" name="jueces-competencia-frm" action="?op=php/alta-juez-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend align="center" >Competencia: <?php echo $competencia; ?></legend>
		<input type="submit" id="nuevo" class="cambio" name="registrar_juez_btn" value="Registra Juez" />
		<input type='submit' id='regresar' title='Regresar' class='cambio' name='regresar_sbm' value='Regresar' />
		&nbsp;&nbsp;&nbsp;
		<span>Jueces Registrados: <?php echo number_format($num_regs,0); ?></span>
	<div id="div1">
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align="center">
			<th>Juez</th>
			<th>Tipo</th>
			<th>Opciones</th>  
		</tr> 	
	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs)
			$mensaje="No hay Jueces registrados en la competencia $competencia :(";
		else
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$juez=utf8_decode($row["nombre"]);
			$imagen=$row["imagen"];
			$cod_usuario=$row["cod_usuario"];
			$sombra=$row["sombra"];
    		echo "<tr align='center'><td align='left'>";
			if ($imagen) 
    			echo "<img class='textwrap'src='img/fotos/$imagen' width='5%'/>&nbsp;&nbsp;";
    		echo $juez."</td>";
    		if ($sombra)
    			echo "<td align = 'center'>Sombra</td>";
    		else 
    			echo "<td align = 'center'>Oficial</td>";
    		
    		echo "<td align='center'><a href='?op=php/baja-juez-competencia.php&com=$competencia&jz=$juez&codco=$cod_competencia&codus=$cod_usuario'>Eliminar<td>";
    		echo "</tr>"; 
		}	
		$conexion->close();
	?>
	</table> 
	</div>
	<div>
		<br>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
		<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo ?>" />
	</div>
	</fieldset>
</form>