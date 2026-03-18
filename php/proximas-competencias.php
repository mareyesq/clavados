<?php 

$hoy=date("Y-m-d");
include("conexion.php");
$consulta="SELECT distinct competencias.competencia, cities.City, competencias.fecha_inicia, competencias.fecha_termina, countries.Country
FROM competencias left join cities on cities.CityId=competencias.ciudad left join countries on countries.CountryId=cities.CountryID
WHERE (competencias.fecha_termina>='$hoy')";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
if ($ejecutar_consulta){
	$num_regs=$ejecutar_consulta->num_rows;
}
?>

<legend>Próximas Competencias</legend>
<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
<tr align='center' ><th>Competencia</th><th>Ciudad</th><th>Inicia</th><th>Termina</th></tr>  

<?php 
if ($num_regs==0){
	$mensaje="No hay registros para esta consulta :(";
}
else
while ($row=$ejecutar_consulta->fetch_assoc()){
	$competencia=$row["competencia"];		
    echo "<tr><td align='center'><a href='?op=php/muestra-competencia.php&com=$competencia'>".$competencia."</a></td><td align='center'>".$row["City"]."-".$row["Country"]."</td><td align='center'>".$row["fecha_inicia"]."</td><td align='center'>".$row["fecha_termina"]."</td></tr>"; 
}	
?>
</table> 
<?php 
if (!$mensaje=="") 	echo "<br><span class='mensajes' >$mensaje</span><br />";
?>
