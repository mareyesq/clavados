<?php 
session_start();
if (!$equipo=$_SESSION["equipo"])
	$consulta="SELECT DISTINCT equipos.equipo, countries.Country
	FROM equipos left join countries on countries.CountryId=equipos.pais
	";
else
	$consulta="SELECT DISTINCT equipos.equipo, countries.Country
	FROM equipos left join countries on countries.CountryId=equipos.pais 
	WHERE (equipos.equipo like '%$equipo%')";
include("conexion.php");
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
?>

<form id="buscar-equipos" name="buscar-equipos-frm" action="?op=php/busca-equipos-1.php" method="post" enctype="multipart/form-data">
	<legend>Buscar equipos </legend>
	<input type="search" class="cambio" name="buscar_src" value="<?php echo $buscar; ?>">
	<input type="submit" class="cambio" name="buscar_sbm" value="Buscar" title="Busca Equipo">
	<input type="submit" id="nuevo" class="cambio" name="nuevo_sbm" value="Nuevo" title="Regístra tu Equipo en el Sistema">
	<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" />
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align='center' >
		<th>Equipo</th>
		<th>Pais</th>	  
		<th colspan="4">Opciones</th>	  
	</tr> 

	<?php 
		if (!$ejecutar_consulta)
			$mensaje="No hay registros para esta consulta :(";
		else
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0)
			$mensaje="No hay registros para esta consulta :(";
		else
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$equipo=utf8_decode($row["equipo"]);		
				$pais=utf8_decode($row["Country"]);
    			echo "<tr align='center'>
    				<td><a href='?op=php/inscribe-equipo.php&eq=$equipo'>".$equipo."</a></td>";
    			echo "<td>".$pais."</td>\n"; 
				echo "<td><a href='?op=php/cambio-equipo.php&eq=$cod_eq&ori=busca-equipo.php'>Modificar</td>";  
				echo "<td><a href='?op=php/eliminar-equipo.php&eq=$cod_eq&ori=busca-equipo.php'>Eliminar</td>";  
				echo "</tr>"; 
			}	
		?>
	</table> 
</form>
