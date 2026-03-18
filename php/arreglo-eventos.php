<?php 
include("funciones.php");
$conexion=conectarse();
$consulta="SELECT competencia, fechahora 
	FROM competenciaev 
	ORDER BY competencia, fechahora";
$ejecutar_consulta=$conexion->query($consulta);
$competencia_ant=NULL;
$arr=array();

while ($row=$ejecutar_consulta->fetch_assoc()) {
	$competencia=isset($row['competencia'])?$row['competencia']:NULL;
	$fechahora=isset($row['fechahora'])?$row['fechahora']:NULL;
	if ($competencia!=$competencia_ant){
		$k=0;
		$competencia_ant=$competencia;
	}
	$k++;
	$arr[]=array('competencia' => $competencia, 'fechahora' => $fechahora, 'evento' => $k);
}
foreach ($arr as $key => $row) {
	$competencia=isset($row['competencia'])?$row['competencia']:NULL;
	$fechahora=isset($row['fechahora'])?$row['fechahora']:NULL;
	$evento=isset($row['evento'])?$row['evento']:NULL;
	$consulta="UPDATE competenciaev SET evento=$evento WHERE competencia=$competencia AND fechahora='$fechahora'";
	$ejecutar_consulta=$conexion->query($consulta);
}
$conexion->close();
?>