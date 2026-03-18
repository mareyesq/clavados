<?php 

$conex=conectarse();
$qry="SELECT fecha_inicia, fecha_termina FROM competencias WHERE cod_competencia = $cod_competencia";
$ejecutar_qry=$conex->query($qry);
$reg=$ejecutar_qry->fetch_assoc();

$fec_w=$reg["fecha_inicia"];
while ($fec_w <= $reg["fecha_termina"]) {
	echo "<option value='$fec_w'";
	if ($dia_sel)
		if($fec_w==$dia_sel)
			echo " selected";
	echo ">$fec_w</option>";	
	
 	$fec_w = date('Y-m-d', strtotime('+1 day', $fec_w));	
}
$conex->close();

?>