<?php 
$cod_categorias=explode("-", $categorias);
$conexion=conectarse();
$consulta="SELECT DISTINCT p.categoria as cod_categoria, c.categoria as categoria, c.edad_desde as desde, c.edad_hasta as hasta FROM competenciapr as p LEFT JOIN categorias as c ON c.cod_categoria=p.categoria WHERE p.competencia=$cod_competencia ORDER BY categoria";
$ejecutar_consulta=$conexion->query($consulta);
$n=9;
while($registro=$ejecutar_consulta->fetch_assoc()){
	$cod_categoria=utf8_decode($registro["cod_categoria"]);
	$categoria=utf8_encode($registro["categoria"]);
	$name=$cod_categoria."_rdo";
	$nombre_categoria=$cod_categoria."-".$categoria;
	$nombre_categoria=$cod_categoria."-".$categoria;
	if (is_null($registro["desde"]) and is_null($registro["hasta"]))
		$title="sin restricción de edad";
	else{
	$title="(de ".$registro["desde"];
	if ($registro["hasta"]==99)
		$title=$title." años en adelante)";
	else
		if ($registro["desde"]==$registro["hasta"])
			$title=$title." años)";
		else
			$title=$title." a ".$registro["hasta"]." años)";
	}
	$n++;
	if ($n>4) {
		echo "<br>";
		$n=1;
	}
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' id='$cod_categoria' name='$name' value='$categoria' title='$title'";
	if (in_array($cod_categoria, $cod_categorias)) {
		echo " checked";
	}
	echo "/> <label for='$cod_categoria'>$categoria</label>";
}
$conexion->close();
?>