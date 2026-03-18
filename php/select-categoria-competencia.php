<?php 
$conexion=conectarse();
$consulta="SELECT * 
	FROM competenciapr 
	left join categorias on categorias.cod_categoria=competenciapr.categoria 
	where competenciapr.competencia=$cod_competencia";

if ($individual==1)
	$consulta.=" AND individual=1";

if ($pareja==1)
	$consulta.=" AND individual=0";

if ($mixto==1)
	$consulta.=" AND mixto=1";

if (strlen($edad)>0)
	$consulta.=" AND (categorias.edad_hasta>=$edad)";

$consulta.=" ORDER BY categorias.categoria";
$ejecutar_consulta=$conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==0)
	echo "<option value='no hay categorias definidas'>no hay categorias definidas</option>";
else{
	while($registro=$ejecutar_consulta->fetch_assoc()){
		$de=$registro["edad_desde"];
		$ha=$registro["edad_hasta"];
		$nombre_categoria=utf8_encode($registro["cod_categoria"])."-".utf8_encode($registro["categoria"])." (de ".$de;
		if ($ha==99)
			$nombre_categoria=$nombre_categoria." años en adelante)";
		else
			if ($de==$ha)
				$nombre_categoria=$nombre_categoria." años)";
			else
				$nombre_categoria=$nombre_categoria." a ".$ha." años)";
		echo "<option value='$nombre_categoria'";
		if(isset($categoria)){
			if ($nombre_categoria==$categoria)
				echo " selected";
		}
		echo ">$nombre_categoria</option>";
	}
}
$conexion->close();
?>