<?php 

include("funciones.php");

$conexion=conectarse();
$consulta="SELECT DISTINCT d.planilla, d.salto as cod_salto, d.posicion, d.altura, d.puntaje_salto, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, c.fecha_inicia, c.fecha_termina, s.salto, cl.nombre, m.abreviado, m.fijo, c.competencia, t.City, o.Country, c.logo_organizador 
	FROM planillad as d
	LEFT JOIN planillas as p on p.cod_planilla=d.planilla
	LEFT JOIN categorias as k on k.cod_categoria=p.categoria
	LEFT JOIN competencias as c on c.cod_competencia=p.competencia 
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN cities as t on t.CityId=c.ciudad 
	LEFT JOIN countries as o on o.CountryId=t.CountryID 
	LEFT JOIN usuarios as cl on cl.cod_usuario=p.clavadista
	LEFT JOIN saltos as s on s.cod_salto=d.salto
	WHERE k.individual=1
	ORDER BY d.puntaje_salto DESC, d.salto, d.posicion, d.altura";

$ejecutar_consulta = $conexion->query($consulta);

?>
<form id="historico-saltos" name="historico-saltos-frm" action="?op=php/historico_saltos_1.php" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend align="center">Hist&oacute;rico de Saltos</legend>
	<div class='scrollbars'>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.7em' cellpadding='0.7em' class="tablas">
		<thead>
			<tr align='center' >
				<th>Salto</th>
				<th>Puntaje</th>
				<th>Clavadista</th>
				<th>Evento</th>
			</tr>  
		</thead>
		<tbody>
	<?php 
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$salto=$row["cod_salto"]."-".$row["posicion"]." ".$row["abreviado"];	
			if (!$row["fijo"]) 
				$salto .= " ".number_format($row["altura"],1)." mts";
			$puntaje=$row["puntaje_salto"];
			$cal1=isset($row["cal1"])?$row["cal1"]:NULL;
			$cal2=isset($row["cal2"])?$row["cal2"]:NULL;
			$cal3=isset($row["cal3"])?$row["cal3"]:NULL;
			$cal4=isset($row["cal4"])?$row["cal4"]:NULL;
			$cal5=isset($row["cal5"])?$row["cal5"]:NULL;
			$cal6=isset($row["cal6"])?$row["cal6"]:NULL;
			$cal7=isset($row["cal7"])?$row["cal7"]:NULL;
			$cal8=isset($row["cal8"])?$row["cal8"]:NULL;
			$cal9=isset($row["cal9"])?$row["cal9"]:NULL;
			$cal10=isset($row["cal10"])?$row["cal10"]:NULL;
			$cal11=isset($row["cal11"])?$row["cal11"]:NULL;
			$calificaciones=$cal1."  ".$cal2."  ".$cal3;
			if ($cal4) 
				$calificaciones.="  ".$cal4;
			if ($cal5) 
				$calificaciones.="  ".$cal5;
			if ($cal6) 
				$calificaciones.="  ".$cal6;
			if ($cal7) 
				$calificaciones.="  ".$cal7;
			if ($cal8) 
				$calificaciones.="  ".$cal8;
			if ($cal9) 
				$calificaciones.="  ".$cal9;
			if ($cal10) 
				$calificaciones.="  ".$cal10;
			if ($cal11) 
				$calificaciones.="  ".$cal11;

			$nombre=utf8_decode($row['nombre']);
			$nombre=ucwords(strtolower($nombre));
			$planilla=$row["planilla"];		
			$competencia=$row["competencia"];		
			$ciudad=utf8_decode($row['City']);
			$pais=utf8_decode($row['Country']);
			$inicia=$row["fecha_inicia"];
			$termina=$row["fecha_termina"];
			$fec=fecha_desde_hasta($inicia,$termina);
			$logo=$row["logo_organizador"];
			echo "<tr align='center'>";
			echo "<td>$salto</td>";
			echo "<td title='$calificaciones' >$puntaje</td>";
			echo "<td>$nombre</td>";
			echo "<td>";
   			if ($logo) 
   				echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>&nbsp;&nbsp;";
   			echo $competencia.'<br>'.$fec.' - '.$ciudad.'-'.$pais.'<br>'.'</td>';
    		echo "</tr>"; 
		}
		$conexion->close();

		if ($num_regs==0) 		
			$mensaje="No hay registros para esta consulta :(";
	?>
		</tbody>
	</table> 
	</div>
	</fieldset>
</form>
