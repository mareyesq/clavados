<?php 
$conexion=conectarse();
$consulta="SELECT * 
	FROM competenciapr 
	where competencia=$cod_competencia 
		and categoria='$cod_categoria'";
$ejecutar_consulta=$conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;

if ($num_regs==1){
	$registro=$ejecutar_consulta->fetch_assoc();
	$modalidades_competencia=$registro["modalidades"];	
}
$consulta="SELECT * 
	FROM competenciapr 
	where competencia=$cod_competencia and 
		categoria like 'S%'";
$ejecutar_consulta=$conexion->query($consulta);
while($registro=$ejecutar_consulta->fetch_assoc()){
	$cod_cat=$registro["categoria"];
	$edad_ok=valida_edad_categoria($cod_cat,$edad,$conexion);
	if ($edad_ok=="todo ok"){
		$modalidades_competencia.="-".$registro["modalidades"];
	}
}
echo "tmodalidades_competencia: $modalidades_competencia<br>";
exit();

$modalidades_comp=explode("-", $modalidades_competencia);	
$modalidades_comp = array_unique($modalidades_comp);
$cod_modalidades=explode("-", $modalidades);
$n=count($modalidades_comp);
for ($i=0; $i < $n; $i++) { 
	$cod_mod=$modalidades_comp[$i];
	$consulta="SELECT * FROM modalidades where cod_modalidad='$cod_mod' ";
	$ejecutar_consulta=$conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		$cod_modalidad=utf8_decode($registro["cod_modalidad"]);
		$modalidad=utf8_decode($registro["modalidad"]);
		$name=$cod_modalidad."_rdo";
		$fijo=utf8_decode($registro["fijo"]);
		if ($fijo==1) 
			$title="todos los saltos de ".$modalidad;
		else
			$title="Puede ejecutar saltos de diferentes alturas";

		echo "<br><input type='checkbox' id='$cod_modalidad' name='$name' value='$modalidad' title='$title'";
		if (in_array($cod_modalidad, $cod_modalidades)) 
			echo " checked";
		echo "/> <label for='$cod_modalidad'>$modalidad</label>";
	}
}
$conexion->close();
?>