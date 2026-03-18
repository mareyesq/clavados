<?php 
$consulta="SELECT cod_planilla, orden_salida
	FROM planillas
	WHERE competencia=$cod_competencia 
		AND evento=$numero_evento 
		AND usuario_retiro IS NULL
	ORDER BY orden_salida";

$ejecutar_consulta = $conexion->query($consulta);
$planillas=array();
while ($row=$ejecutar_consulta->fetch_assoc()) {
	$orden=$row["orden_salida"];
	$planilla=$row["cod_planilla"];
	$planillas[$orden]=$planilla;
}
$n=count($planillas);
for ($r=1; $r<=3 ; $r++) { 
	for ($i=1; $i <= $n ; $i++) { 
		$n_planilla=$planillas[$i];
		$k=$r*2;
		$j=$k-1;
		$consulta="UPDATE planillad 
			SET turno=$r
			WHERE planilla=$n_planilla and ronda=$j";
		$ejecutar_rondas = $conexion->query($consulta);
		$consulta="UPDATE planillad 
			SET turno=$r
			WHERE planilla=$n_planilla and ronda=$k";
		$ejecutar_rondas = $conexion->query($consulta);

	}
}
?>