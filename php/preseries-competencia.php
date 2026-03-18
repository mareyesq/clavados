<?php 
$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
$cod_competencia=isset($_GET["cco"])?trim($_GET["cco"]):NULL;
$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;

if (is_null($competencia))
	$competencia=isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL;
session_start();

if (!$competencia)
	$competencia=$_SESSION["competencia"];

include("funciones.php");
$cod_usuario=$_SESSION["usuario_id"];
if (isset($cod_usuario)) {
	$es_administrador=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($es_administrador,0,5)=="Error")
		$es_administrador=0;
	else
		$es_administrador=1;
}

$roles=defina_rol($cod_usuario);
$es_juez=in_array("J", $roles);

$conexion=conectarse();
$consulta="SELECT DISTINCT p.modalidad as cod_modalidad, p.categoria as cod_categoria, p.modalidad as cod_modalidad, m.modalidad, c.categoria
	FROM preseries as p 
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad 
	LEFT JOIN categorias as c on c.cod_categoria=p.categoria
	WHERE (p.competencia=$cod_competencia) ORDER BY p.categoria, p.modalidad";
$ejecutar_consulta = $conexion->query($consulta);
?>

<form id="preseries-competencia" name="preseries-competencia-frm" action="?op=php/preseries-competencia-1.php" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend align="center" class="rotulo">
		<?php include("titulo-competencia.php") ?>
	</legend>
	<h3>Series Predefinidas de la Competencia</h3>
	<?php 
		if (navegador_compatible("Chrome"))
			echo "<div id= 'div1'>";
	 ?>
	<div>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
		<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
		<input type="hidden" id="logo" name="logo_hdn" value="<?php echo $logo ?>" />
		<?php 
			if ($es_administrador) {
				echo "&nbsp;<input type='submit' id='nuevo' title='Registra una nueva serie' class='cambio' name='nuevo_sbm' value='Nueva' />";
			}
		?>
		<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	<div id="id1">
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align="center">
			<th>Categoría</th>	  
			<th>Modalidad</th>
			<?php 
				if ($es_administrador==1)
					echo "<th colspan='16' >";
				elseif ($es_juez)
						echo "<th colspan='10' >";
					else
						echo "<th colspan='5' >";
			 ?>
			Opciones</th>	  
		</tr> 

	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;
		$linea=FALSE;
		if (!$num_regs)
			$mensaje="No hay series predefinidas registradas en la competencia $competencia :(";
		else{
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$cod_modalidad=$row["cod_modalidad"];
			$cod_categoria=$row["cod_categoria"];
			$modalidad=utf8_decode($row["modalidad"]);
			$categoria=utf8_decode($row["categoria"]);
			echo "<tr align='center'>";  
			echo "<td>".$categoria."</td>";	
    		echo "<td>".$modalidad."</td>";
			if ($es_administrador){
	    		echo "<td><a class='enlaces_tab' href='?op=php/edita-preserie-competencia.php&com=$competencia&cat=$cod_categoria&mod=$cod_modalidad&codco=$cod_competencia&lg=$logo'>Editar</td>";
	    	}
	    	else
	    		echo "<td></td>";
			if ($es_administrador){
    			echo "<td><a class='enlaces_tab' href='?op=php/baja-preserie-competencia.php&com=$competencia&cat=$cod_categoria&mod=$cod_modalidad&codco=$cod_competencia'>Eliminar</td>";
			}
	    	else
	    		echo "<td></td>";

			echo "</tr>"; 
		}				
	}
	$conexion->close();
	?>
	</table> 
	</div>
	<?php 
		if (navegador_compatible("Chrome"))
			echo "</div>";
	 ?>
	</fieldset>
</form>