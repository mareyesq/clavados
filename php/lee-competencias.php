<?php 
session_start();
$cod_usuario=$_SESSION["usuario_id"];
//include("conexion.php");  

$consulta="SELECT distinct competencias.competencia, cities.City, competencias.fecha_inicia, competenciasa.administrador, countries.Country
FROM competencias left join cities on cities.CityId=competencias.ciudad left join countries on countries.CountryId=cities.CountryID , competenciasa
WHERE (competenciasa.administrador =$cod_usuario)";
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
if (!$ejecutar_consulta){
/*	print_r($conexion->error_list);
    exit();  */
}
else{
	$num_regs=$ejecutar_consulta->num_rows;
/*	echo "Regs: $num_regs";
	exit(); */
}
//   echo "<table border = '1'> \n"; 
   echo "<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em'>";
   echo "<tr bgcolor='#F60' align='center' ><td>Competencia</td><td>Ciudad</td><td>País</td><td>Fecha</td></tr> \n"; 

if ($num_regs==0){
	$mensaje="No hay registros para esta consulta :(";
}
else
while ($row=$ejecutar_consulta->fetch_assoc()){
	$competencia=$row["competencia"];		
    echo "<tr><td><a href='?op=php/muestra-competencia.php&com=$competencia'>".$competencia."</a></td><td>".$row["City"]."</td><td>".$row["Country"]."</td><td>".$row["fecha_inicia"]."</td></tr> \n"; 
}	
  	echo "</table> \n"; 
?>
