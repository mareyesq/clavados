<?php 
$cnx=conectarse();
$qry="SELECT *
	FROM competenciapr as p
	left join categorias as c on c.cod_categoria=p.categoria 
	where p.competencia=$cod_competencia";

if ($individual==1)
	$qry.=" AND c.individual=1";

if ($pareja==1)
	$qry.=" AND c.individual=0";

if ($mixto==1)
	$qry.=" AND c.mixto=1";

if (strlen($edad)>0)
	$qry.=" AND (c.edad_hasta>=$edad)";

$qry.=" ORDER BY c.categoria";
$ejecutar_qry=$cnx->query($qry);
$num_regs=$ejecutar_qry->num_rows;
if ($num_regs==0)
	echo "<option value='no hay categorias definidas'>no hay categorias definidas</option>";
else{
	while($reg=$ejecutar_qry->fetch_assoc()){
		$de=$reg["edad_desde"];
		$ha=$reg["edad_hasta"];
		$cod_categoria=utf8_decode($reg["cod_categoria"]);
		$nombre_categoria=$cod_categoria."-".utf8_decode($reg["categoria"])." (de ".$de;
		if ($ha==99)
			$nombre_categoria=$nombre_categoria." años en adelante)";
		else
			if ($de==$ha)
				$nombre_categoria=$nombre_categoria." años)";
			else
				$nombre_categoria=$nombre_categoria." a ".$ha." años)";
		echo "<option value='$cod_categoria'";
		if(isset($categoria)){
			if ($cod_categoria==$categoria)
				echo " selected";
		}
		echo ">$nombre_categoria</option>";
	}
}
$cnx->close();
?>
