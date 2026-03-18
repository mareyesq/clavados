<?php 
$conex=conectarse();
$qry="SELECT cod_modalidad AS id, modalidad FROM modalidades ORDER BY modalidad";
$ejecutar_qry=$conex->query($qry);
while($registro=$ejecutar_qry->fetch_assoc()){
	$id=$registro["id"];
	$nombre_modalidad=utf8_encode($registro["modalidad"]);
	echo "<option value='$id'";
	if(isset($cod_modalidad)){
		if ($cod_modalidad==$id)
			echo " selected";
	}
	echo ">$nombre_modalidad</option>";
}
$conex->close();
?>