<?php 
$cnx=conectarse();
$qry="SELECT p.modalidades
	FROM competenciapr as p
	left join categorias as c on c.cod_categoria=p.categoria 
	where p.competencia=$cod_competencia 
		and p.categoria='$categoria'";
$qry.=" ORDER BY p.categoria";
$ejecutar_qry=$cnx->query($qry);
$num_regs=$ejecutar_qry->num_rows;
if ($num_regs==0)
	echo "<option value='no hay categorias definidas'>no hay categorias definidas</option>";
else{
	while($reg=$ejecutar_qry->fetch_assoc()){
		$modalidades=$reg["modalidades"];
		$modali=explode("-", $modalidades);
		$modal=NULL;
		foreach ($modali as $value) {
			if (!is_null($modal))
				$modal .= ",";
			$modal .= "'$value'";
		}
		$qry1="SELECT cod_modalidad, modalidad 
			FROM modalidades 
			WHERE cod_modalidad IN (".$modal.")";
		$ejecutar_qry1=$cnx->query($qry1);
		while($reg1=$ejecutar_qry1->fetch_assoc()){
			$cod_modalidad=$reg1["cod_modalidad"];
			$nombre_modalidad=utf8_decode($reg1["modalidad"]);
			echo "<option value='$cod_modalidad'";
			if (isset($modalidad)){
				if ($cod_modalidad==$modalidad)
					echo " selected";
			}
			echo ">$nombre_modalidad</option>";
		}
	}
}
$cnx->close();
 ?>