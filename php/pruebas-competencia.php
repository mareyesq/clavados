<?php 
$competencia=isset($_GET["com"])?trim($_GET["com"]):NULL;
$cod_competencia=isset($_GET["cco"])?trim($_GET["cco"]):NULL;
$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;

/*if (!isset($competencia)) 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
*/
session_start();
if (!$competencia)
	$competencia=$_SESSION["competencia"];

if (!$cod_competencia)
	$cod_competencia=$_SESSION["cod_competencia"];

if (!$logo)
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
if (!$logo2)
	$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;

include("funciones.php");
$conexion=conectarse();
$consulta="SELECT DISTINCT cat.categoria as categoria, cat.edad_desde as desde, cat.edad_hasta as hasta, cat.individual as individual, competenciapr.modalidades as modalidades, competenciapr.categoria as cod_categoria FROM competenciapr LEFT JOIN categorias as cat on cat.cod_categoria=competenciapr.categoria
WHERE (competenciapr.competencia=$cod_competencia) 
ORDER BY categoria";

$ejecutar_consulta = $conexion->query($consulta);

?>

<form id="pruebas-competencia" name="pruebas-competencia-frm" action="?op=php/alta-prueba-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
	<h2>Categorías y Modalidades en Competencia</h2>
	<div id="div1">
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align="center">
			<th>Categoría</th>	  
			<th>Modalidades</th>
			<th colspan="3" >Opciones</th>	  
		</tr> 

	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs)
			$mensaje="No hay Pruebas registrados en la competencia $competencia :(";
		else{
			$i=0;
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$i++;
				$modalidades=utf8_decode($row["modalidades"]);
				$cod_categoria=$row["cod_categoria"];
				$categoria=$cod_categoria."-".utf8_encode($row["categoria"])." (de ".$row["desde"]." a ".$row["hasta"]." años)" ;
				$individual=$row["individual"];
				$nom_modalidades=describe_modalidades($modalidades);
				echo "<tr ";
	    		echo "align='center'><td>".$categoria."</td>";  
    			echo "<td>".$nom_modalidades."</td>";
    			echo "<td><a href='?op=php/edita-prueba-competencia.php&com=$competencia&mod=$nom_modalidades&cat=$categoria&codmo=$modalidades&codco=$cod_competencia&ind=$individual'>Editar<td>";
	    		echo "<td><a href='?op=php/baja-prueba-competencia.php&com=$competencia&mod=$nom_modalidades&cat=$categoria&cca=$cod_categoria&codmo=$modalidades&codco=$cod_competencia'>Eliminar<td>";
    			echo "</tr>"; 
			}	
		}
		$conexion->close();
	?>
	</table> 
	</div>
	<div>
		<br>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
		<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
		<input type="submit" id="nuevo" title="Registra una nueva categoría y sus modalidades" class="cambio" name="registrar_prueba_btn" value="Nueva Prueba" />
		&nbsp;
		<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	</fieldset>
</form>