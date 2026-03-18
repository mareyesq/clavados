<?php 
$cnx=conectarse();
$qry="SELECT * FROM saltos ORDER BY cod_salto";
$ejecutar_qry=$cnx->query($qry);

while($registro=$ejecutar_qry->fetch_assoc()){
	$cod_salto=$registro["cod_salto"];
	$nombre_salto=$cod_salto."-".utf8_encode($registro["salto"]);
	echo "<option value='$cod_salto'";
	if(isset($salto)){
		if ($cod_salto==$salto)
			echo " selected";
	}
	echo ">$nombre_salto</option>";
}
$cnx->close();
?>