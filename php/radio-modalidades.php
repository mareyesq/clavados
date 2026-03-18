<?php 
$conexion=conectarse();
if (!isset($todas)) {
	$modalidades_competencia=NULL;
	if (!$eq_juv){
		$consulta="SELECT * 
			FROM competenciapr 
			WHERE competencia=$cod_competencia ";
		if ($cod_categoria) {
			$consulta.=" AND categoria='$cod_categoria'";
		}
		$ejecutar_consulta=$conexion->query($consulta);
		while ($registro=$ejecutar_consulta->fetch_assoc()) {
			if (is_null($modalidades_competencia))
				$modalidades_competencia=$registro["modalidades"];
			else
				$modalidades_competencia.="-".$registro["modalidades"];
		}
		$modalidades_comp=explode("-", $modalidades_competencia);	
		$n=count($modalidades_comp);
		$modalidades_comp=array_unique($modalidades_comp);

		$criterio_modalidad=NULL;
		for ($i=0; $i < $n; $i++) { 
			if ($modalidades_comp[$i]) {
				if (is_null($criterio_modalidad))
					$criterio_modalidad=" AND (cod_modalidad='$modalidades_comp[$i]'";
				else
					$criterio_modalidad.=" OR cod_modalidad='$modalidades_comp[$i]'";
			}
		}
	}
}
$modalidades =isset($modalidades)?$modalidades:NULL;
$cod_modalidades=explode("-", $modalidades);
$consulta="SELECT * FROM modalidades ";

if ($individual==1) 
	$consulta.=" WHERE individual=1";
else
	if ($pareja==1) 
		$consulta.=" WHERE individual=0";
	else
		if ($eq_juv ) 
			$consulta.=" WHERE cod_modalidad='E'";

if (!is_null($criterio_modalidad))
	$consulta.=$criterio_modalidad.")";

$consulta.=" ORDER BY modalidad";
$ejecutar_consulta=$conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
while ($registro=$ejecutar_consulta->fetch_assoc()){
	$cod_modalidad=utf8_decode($registro["cod_modalidad"]);
	$modalidad=utf8_decode($registro["modalidad"]);
	$name=$cod_modalidad."_rdo";
	$fijo=utf8_decode($registro["fijo"]);
	if ($fijo==1) {
		$title="todos los saltos de ".$modalidad;
	}
	else
		$title="Puede ejecutar saltos de diferentes alturas";

	echo "<br><input type='checkbox' id='$cod_modalidad' name='$name' value='$modalidad' title='$title'";
	if (in_array($cod_modalidad, $cod_modalidades)) {
		echo " checked";
	}
	echo "/> <label for='$cod_modalidad'>$modalidad</label>";
}
$conexion->close();
?>