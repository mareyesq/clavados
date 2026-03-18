<?php 
$conex=conectarse();
$qry="SELECT DISTINCT DATE(fechahora) as fecha FROM competenciaev WHERE competencia = $cod_competencia ORDER BY fechahora";

$ejecutar_qry=$conex->query($qry);

while($reg=$ejecutar_qry->fetch_assoc()){
	$fecha_comp=$reg["fecha"];
	$corta=hora_coloquial($fecha_comp,TRUE);
	echo "<option value='$fecha_comp'";
	if ($dia_sel)
		if($fecha_comp==$dia_sel)
			echo " selected";
	echo ">$corta</option>";
}
$conex->close();
?>