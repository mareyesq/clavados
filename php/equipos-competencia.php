<?php 

$competencia=isset($_GET["com"])?trim($_GET["com"]):NULL;
$cod_competencia=isset($_GET["cco"])?trim($_GET["cco"]):NULL;

session_start();
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
if (!$competencia)
	$competencia=$_SESSION["competencia"];
if (!$cod_competencia)
	$cod_competencia=$_SESSION["cod_competencia"];
if (!$logo)
	$logo=$_SESSION["logo"];
if (!$logo2)
	$logo2=$_SESSION["logo2"];

include("funciones.php");
$conexion=conectarse();
$consulta="SELECT q.competencia as cod_comp, q.nombre_corto as nombre_corto, q.equipo as equipo, q.bandera FROM competenciasq as q left join competencias as c on c.cod_competencia=q.competencia 
WHERE (q.competencia=$cod_competencia) 
ORDER BY equipo";

$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
?>

<form id="equipos-competencia" name="alta-insc-equi-frm" action="?op=php/alta-equipo-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>

	<h2>Equipos en Competencia</h2>
	<div>
	<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
	<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
	<input type="hidden" id="logo" name="logo_hdn" value="<?php echo $logo ?>" />
	<input type="submit" id="nuevo" class="cambio" name="inscribir_equipo_btn" value="Inscribe tu Equipo" />&nbsp;&nbsp;
	<input type="submit" id="lista" title="lista de clavadistas" class="cambio" name="lista_clavadistas_btn" value="Lista Clavadistas" />
		&nbsp;
	<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	<span>Inscritos: <?php echo "$num_regs"; ?></span>
	</div>

	<!-- <div class="scrollbars"> -->
	<div id="div1">
	<table width='100%' class="tablas">
		<tr align="center">
		<th>Equipo</th>
		<th colspan="4">Opciones</th>	  
	</tr> 
	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs)
			echo "<tr align='center'><td align='center'>No hay Equipos inscritos  !!! :(</td></tr>";
		else{
			$i=1;
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_competencia=$row["cod_comp"];
				$equipo=utf8_decode($row["equipo"]);
				$corto=utf8_decode($row["nombre_corto"]);
				$bandera=isset($row["bandera"])?strtolower($row["bandera"]):NULL;
				echo "<tr ";
				$i++;
	    		echo "align='center'><td><a class='enlaces' href='?op=php/edita-equipo-competencia.php&com=$competencia&codco=$cod_competencia&lg=$logo&eq=$equipo&cor=$corto&ori=equipos-competencia.php'>".$equipo."</a>";
	    		if ($bandera)
	    			echo "<img class='textwrap' src='img/banderas/".$bandera.".png' width='6%'/>";
	    		echo "</td>";
    			echo "<td align='center'>
    					<a class='enlaces_tab' href='?op=php/clavadistas-competencia.php&com=$competencia&cco=$cod_competencia&eq=$equipo&cor=$corto&lg=$logo&ori=php/equipos-competencia.php*com=$competencia*cco=$cod_competencia*lg=$logo'>Clavadistas
    					</a>
    				</td>";
    			echo "<td align='center'>
    					<a class='enlaces_tab' href='?op=php/retirar-equipo-competencia.php&com=$competencia&cco=$cod_competencia&eq=$equipo&cor=$corto&lg=$logo&ori=equipos-competencia.php'>Retirar
    					</a>
    				</td>";
    			echo "</tr>"; 
			}	
		}
		$conexion->close();
	?>
	</table> 
	</div>
	</fieldset>
</form>