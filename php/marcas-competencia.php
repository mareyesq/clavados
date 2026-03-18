<?php 
$competencia=isset($_GET["com"])?trim($_GET["com"]):NULL;
$cod_competencia=isset($_GET["cco"])?trim($_GET["cco"]):NULL;
$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;
session_start();

if (!$competencia)
	$competencia=$_SESSION["competencia"];
if (!$cod_competencia)
	$cod_competencia=$_SESSION["cod_competencia"];
if (!$logo)
	$logo=$_SESSION["logo"];

include("funciones.php");

$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
$es_administrador_competencia=administrador_competencia($cod_usuario,$cod_competencia,$conexion);

if (substr($es_administrador_competencia,0,5)=="Error")
	$puede_editar=FALSE;
else
	$puede_editar=TRUE;

$conexion=conectarse();
$consulta="SELECT DISTINCT c.categoria as categoria, c.edad_desde as desde, c.edad_hasta as hasta, m.modalidad as cod_mod, d.modalidad, m.categoria as cod_cat, m.marca_damas, m.grado_damas, m.promedio_damas, m.marca_varones, m.grado_varones, m.promedio_varones
	FROM competenciasm as m 
	LEFT JOIN categorias as c on c.cod_categoria=m.categoria
	LEFT JOIN modalidades as d on d.cod_modalidad=m.modalidad
WHERE (m.competencia=$cod_competencia) 
ORDER BY categoria, modalidad";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
?>

<form id="marcas-competencia" name="marcas-competencia-frm" action="?op=php/alta-marca-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
			<?php 
				if ($logo) 
					echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
					echo $competencia; 
			?>
		</legend>
		<h3>Marcas a Cumplir en la Competencia</h3>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align="center">
			<th colspan="2" ></th>	  
			<th colspan="3" >Damas</th>
			<th colspan="3" >Varones</th>
			<th colspan="3" ></th>	  
		</tr> 
		<tr align="center">
			<th>Categoría</th>	  
			<th>Modalidad</th>
			<th>Marca</th>
			<th>Grado</th>
			<th>Promedio</th>
			<th>Marca</th>
			<th>Grado</th>
			<th>Promedio</th>
			<?php 
				if ($puede_editar) 
					echo '<th colspan="3" >Opciones</th>';
			?>
		</tr> 

	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs)
			$mensaje="No hay Marcas registradas para la competencia $competencia :(";
		else{
			$i=0;
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$i++;
				$cod_mod=utf8_decode($row["cod_mod"]);
				$cod_cat=$row["cod_cat"];
				$categoria=$cod_cat."-".$row["categoria"]." (de ".$row["desde"];
				if ($row["hasta"]==99)
					$categoria .= utf8_encode(" años en adelante)");
				else
					if ($row["desde"]==$row["hasta"])
						$categoria .= utf8_encode(" años)");
					else
						$categoria .=" a ".$row["hasta"].utf8_encode(" años)");
			
				$categoria=utf8_decode($categoria);

				$modalidad=utf8_decode($row["modalidad"]);
				$marca_damas=$row["marca_damas"];
				$grado_damas=$row["grado_damas"];
				$promedio_damas=$row["promedio_damas"];
				$marca_varones=$row["marca_varones"];
				$grado_varones=$row["grado_varones"];
				$promedio_varones=$row["promedio_varones"];
				echo "<tr ";
	    		echo "align='center'><td>".$categoria."</td>";  
    			echo "<td>".$modalidad."</td>";
    			echo "<td>".$marca_damas."</td>";
	    		echo "<td>".$grado_damas."</td>";
    			echo "<td>".$promedio_damas."</td>";
    			echo "<td>".$marca_varones."</td>";
    			echo "<td>".$grado_varones."</td>";
	    		echo "<td>".$promedio_varones."</td>";
				if ($puede_editar) {
	    			echo "<td><a href='?op=php/edita-marca-competencia.php&com=$competencia&cat=$categoria&mod=$modalidad&codco=$cod_competencia&codca=$cod_cat&codmo=$cod_mod'>Editar<td>";
	    			echo "<td><a href='?op=php/baja-marca-competencia.php&com=$competencia&cat=$categoria&mod=$modalidad&codco=$cod_competencia&codca=$cod_cat&codmo=$cod_mod'>Eliminar<td>";
				}
    			echo "</tr>"; 
			}	
		}	
		$conexion->close();
	?>
	</table> 
	<div>
		<br>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
		<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
		<input type="hidden" id="logo" name="logo_hdn" value="<?php echo $logo ?>" />
		<input type="submit" id="nuevo" title="Registra una nueva marca para categoría y sus modalidad" class="cambio" name="nueva_marca_btn" value="Nueva Marca" />
		&nbsp;
		<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	</fieldset>

</form>