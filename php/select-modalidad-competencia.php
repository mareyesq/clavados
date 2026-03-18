<?php 
$conex=conectarse();
$qry="SELECT DISTINCT e.modalidad, m.modalidad as nom_modalidad
	FROM competenciaev as e 
	LEFT JOIN modalidades as m on m.cod_modalidad=e.modalidad
	WHERE competencia = $cod_competencia ORDER BY modalidad";

$ejecutar_qry=$conex->query($qry);

while($reg=$ejecutar_qry->fetch_assoc()){
	$modalidad=$reg["modalidad"];
	$nom_modalidad=utf8_decode($reg["nom_modalidad"]);
	echo "<option value='$modalidad'";
	if ($modalidad_sel)
		if($modalidad==$modalidad_sel)
			echo " selected";
	echo ">$nom_modalidad</option>";
}
$conex->close();
?>