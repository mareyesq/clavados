<?php 
$hoy=date("Y-m-d");
include("conexion.php");
$consulta="SELECT distinct competencias.competencia, cities.City, competencias.fecha_inicia,competencias.fecha_termina, countries.Country
FROM competencias left join cities on cities.CityId=competencias.ciudad left join countries on countries.CountryId=cities.CountryID , competenciasa
WHERE (competencias.fecha_termina<'$hoy')";
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
if (!$ejecutar_consulta){
}
else{
	$num_regs=$ejecutar_consulta->num_rows;
}
?>
<legend>Competencias Anteriores</legend>
<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em'>
<tr bgcolor='#F60' align='center' ><td>Competencia</td><td>Ciudad</td><td>Inició</td><td>Terminó</td><td>Opciones</td></tr>

<?php 
if ($num_regs==0){
	$mensaje="No hay registros para esta consulta :(";
}
else
while ($row=$ejecutar_consulta->fetch_assoc()){
	$competencia=$row["competencia"];		
    echo "<tr><td align='center'><a href='?op=php/muestra-competencia.php&com=$competencia'>".$competencia."</a></td><td align='center'>".$row["City"]."-".$row["Country"]."</td><td align='center'>".$row["fecha_inicia"]."</td><td align='center'>".$row["fecha_termina"]."</td><td align='center'><a href='?op=php/resultados-competencia.php&com=$competencia'>Resultados<a></td></tr>"; 
}	
?>

</table>
<?php 
if (!$mensaje=="") 	echo "<br><span class='mensajes' >$mensaje</span><br />";
?>
