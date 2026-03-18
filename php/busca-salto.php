<?php 
$buscar=isset($_GET["bus"])?$_GET["bus"]:NULL;
$llamo=isset($_GET["ori"])?$_GET["ori"]:NULL;
$ronda=isset($_GET["ron"])?$_GET["ron"]:NULL;

session_start();

if (!isset($buscar)) 
	$buscar=(isset($_SESSION["buscar"])?$_SESSION["buscar"]:NULL);

if (!isset($llamo))
	$llamo=(isset($_SESSION["llamo"])?$_SESSION["llamo"]:NULL);

$llamados=$_SESSION["llamados"];

$llamo=isset($llamados["php/busca-salto.php"])?$llamados["php/busca-salto.php"]:NULL;

if (!isset($ronda))
	$ronda=(isset($_SESSION["ronda"])?$_SESSION["ronda"]:NULL);

include("funciones.php");
$conexion=conectarse();
$consulta="SELECT 
		saltos.cod_salto, saltos.salto, dificult.altura, dificult.posicion_a, dificult.posicion_b, dificult.posicion_c, dificult.posicion_d
	FROM saltos left join dificult on dificult.cod_salto=saltos.cod_salto";
if (!is_null($buscar))
	$consulta .= " WHERE saltos.salto LIKE '%$buscar%' OR saltos.cod_salto LIKE '%$buscar%'";

$consulta .= " ORDER BY saltos.cod_salto";

$ejecutar_consulta = $conexion->query($consulta);
?>

<form id="buscar-saltos" name="buscar-saltos-frm" action="?op=php/busca-salto-1.php" method="post" enctype="multipart/form-data">
<legend>Buscar Saltos</legend>
	<label for="buscar" >Buscar: </label>
	<input type="search" class="cambio" id="buscar" name="buscar_src" value="<?php echo $buscar; ?>">
	<input type="submit" class="cambio" name="buscar_sbm" value="Buscar" title="Busca el salto">
	<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" title="regresa sin seleccionar salto" />
	<div class='scrollbars'>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align='center' >
			<th>Salto</th>
			<th>Altura</th>
			<th>A</th>
			<th>B</th>
			<th>C</th>
			<th>D</th>
		</tr> 
	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs){
			$mensaje="No hay saltos ";
			if ($buscar) $mensaje=$mensaje." con nombre $buscar";
			$mensaje=$mensaje." :(";
		}
		else
			$i=1;
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_salto=$row["cod_salto"];
				$salto=utf8_encode($row["salto"]);
				$altura=$row["altura"];
				$posiciona=number_format($row["posicion_a"],1);
				$posicionb=number_format($row["posicion_b"],1);
				$posicionc=number_format($row["posicion_c"],1);
				$posiciond=number_format($row["posicion_d"],1);
/*				if ($i%2==0)
					echo "<tr class='linea1'";
				else
*/					echo "<tr";
				$i++;
    			echo " align='center' ><td><a class='enlaces' href='?op=$llamo&co=$cod_salto&ron=$ronda&sal=$salto'>$cod_salto - $salto</td>";
    			echo "<td>".$altura."</td>";  
    			echo "<td>".$posiciona."</td>";  
    			echo "<td>".$posicionb."</td>";  
    			echo "<td>".$posicionc."</td>";  
    			echo "<td>".$posiciond."</td>";  
    			echo "</tr>"; 
		}	
		$conexion->close();
	?>
	</table> 
	</div>
	<div>
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo; ?>" />
		<input type="hidden" id="ronda" name="ronda_hdn" value="<?php echo $ronda; ?>" />
	</div>
</form>