<?php 

$consulta="SELECT DISTINCT j.juez, j.sombra, u.nombre as nombre
	FROM competenciasjz as j
	LEFT JOIN usuarios as u on u.cod_usuario=j.juez 
	WHERE competencia=$cod_competencia AND sombra=1
	 ORDER BY u.nombre";
$conexion=conectarse();
$ejecutar_consulta = $conexion->query($consulta);
if ($ejecutar_consulta){
	$val=NULL;
	while ($row=$ejecutar_consulta->fetch_assoc()) {
		$juez=isset($row['juez'])?$row['juez']:NULL;
		$esta=esta_en_evento($juez);
		if (!$esta){
			if ($val) 
				$val.=",";
			$val.=" ($cod_competencia, $numero_evento, $juez)";
		}
	}
	if ($val) {
		$consulta="INSERT INTO competenciass (competencia, evento, juez) VALUES ".$val;
		$ejecutar_consulta = $conexion->query($consulta);
	}
}
$conexion->close();
 ?>