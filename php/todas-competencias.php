<?php 

include("funciones.php");

$conexion=conectarse();
$consulta="SELECT DISTINCT 
	c.cod_competencia, c.competencia, d.City, c.fecha_inicia, c.fecha_termina, c.limite_inscripcion, p.Country, c.organizador, c.logo_organizador 
	FROM competencias as c 
	LEFT JOIN cities as d on d.CityId=c.ciudad 
	LEFT JOIN countries as p on p.CountryId=d.CountryID 
	ORDER BY c.fecha_inicia";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

if ($ejecutar_consulta)
	$num_prox=$ejecutar_consulta->num_rows;

?>
<form id="administradores-competencia" name="admin-competencia-frm" action="?op=php/alta-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend align="center">Competencias &nbsp;&nbsp;
		<input type="submit" id="nuevo" class="cambio" name="nuevo_btn" value="Nueva Competencia" />
	</legend>
	<div id='div1'>
	<table width='100%' class="tablas">
		<thead>
			<tr align='center' >
				<th>Competencia</th>
				<th>Cierre Inscripciones</th>
			</tr>  
		</thead>
		<tbody>
	<?php 
		if (!$num_prox==0){
			echo "<tr align='center'><td class='subtitulo' colspan='4' ><b>Próximas Competencias<b></td></tr>";
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_competencia=$row["cod_competencia"];		
				$competencia=$row["competencia"];		
				$ciudad=utf8_encode($row['City']);
				if (inscripciones($competencia)){
					$logo=$row["logo_organizador"];
					$pais=$row['Country'];
					$inicia=$row['fecha_inicia'];
					$termina=$row['fecha_termina'];
					$limite=$row['limite_inscripcion'];
					$limite=hora_coloquial($limite);
					$organizador=utf8_encode($row['organizador']);
					$fec=fecha_desde_hasta($inicia,$termina);
					echo "<tr align='center'>
    					<td><a class='enlaces' href='?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia'>";
    				if ($logo) 
    					echo "<img class='textwrap' src='img/fotos/$logo' width='6%' height='60' />&nbsp;&nbsp;";
    				echo $competencia.'<br>'.$fec.' - '.$ciudad.'-'.$pais.'<br>'.$organizador. '</a></td>';
	    			echo "<td align='left'>$limite</td></tr>"; 
				}
			}	
		}
//			WHERE (c.fecha_termina<'$hoy')
		$consulta="SELECT DISTINCT 
			c.cod_competencia, c.competencia, d.City, c.fecha_inicia, c.fecha_termina, p.Country, c.organizador, c.logo_organizador 
			FROM competencias as c 
			LEFT JOIN cities as d on d.CityId=c.ciudad 
			LEFT JOIN countries as p on p.CountryId=d.CountryID 
			ORDER BY c.fecha_inicia DESC";

		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		if ($ejecutar_consulta)
			$num_ant=$ejecutar_consulta->num_rows;
		if (!$num_ant==0){
			echo "<tr align='center'><td class='subtitulo' colspan='4' ><b>Competencias Anteriores<b></td></tr>";

			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_competencia=$row["cod_competencia"];		
				$competencia=$row["competencia"];		
				$inicia=$row['fecha_inicia'];
				$termina=$row['fecha_termina'];
				if (!inscripciones($competencia)){
					$ciudad=utf8_encode($row['City']);
					$pais=$row['Country'];
					$fec=fecha_desde_hasta($inicia,$termina);
					$logo=$row["logo_organizador"];
					$organizador=utf8_encode($row['organizador']);
					echo "<tr align='center'>
    					<td><a class='enlaces' href='?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia'>";
    				if ($logo)
    					echo "<img class='textwrap' src='img/fotos/$logo' width='6%' height='60' />&nbsp;&nbsp;";

    				echo $competencia.'  '.$fec.' - '.$ciudad.'-'.$pais.'<br>'.$organizador. '</a></td>';
    				echo "<td></td>";
    				echo "</tr>"; 
    			}	
			}	
		}

		if ($num_prox+$num_ant==0) 		
			$mensaje="No hay registros para esta consulta :(";
		$conexion->close();
	?>
		</tbody>
	</table> 
	</div>
	</fieldset>
</form>
