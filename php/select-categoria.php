<?php 

$conexion=conectarse();
$consulta="SELECT * FROM categorias ORDER BY categoria";
$ejecutar_consulta=$conexion->query($consulta);

while($registro=$ejecutar_consulta->fetch_assoc()){
	$nombre_categoria=utf8_encode($registro["cod_categoria"])."-".utf8_encode($registro["categoria"])." (de ".$registro["edad_desde"];
	if ($registro["edad_hasta"]==99)
		$nombre_categoria=$nombre_categoria." años en adelante)";
	else
		if ($registro["edad_desde"]==$registro["edad_hasta"])
			$nombre_categoria=$nombre_categoria." años)";
		else
			$nombre_categoria=$nombre_categoria." a ".$registro["edad_hasta"]." años)";
	echo "<option value='$nombre_categoria'";
	if(isset($categoria)){
		if ($nombre_categoria==$categoria)
			echo " selected";
	}
	echo ">$nombre_categoria</option>";
}
$conexion->close();
?>