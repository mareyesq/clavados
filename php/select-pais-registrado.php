<?php 
$conex=conectarse();
$qry="SELECT DISTINCT u.pais, p.Country 
	FROM usuarios as u
	LEFT JOIN countries as p ON p.CountryId=u.pais";
switch ($tipo) {
	case 'A':
		$qry .= " WHERE u.administrador='A' ";
		break;
	
	case 'E':
		$qry .= " WHERE u.entrenador='E' ";
		break;
	case 'C':
		$qry .= " WHERE u.clavadista='C' ";
		break;
	case 'J':
		$qry .= " WHERE u.juez='J' ";
		break;
}
$qry .= " ORDER BY Country";
$ejecutar_qry=$conex->query($qry);

while($reg=$ejecutar_qry->fetch_assoc()){
	$cod_pais=$reg["pais"];
	$nombre_pais=utf8_encode($reg["Country"]);
	echo "<option value='$cod_pais'";
	if(isset($pais_sel)){
		if ($cod_pais==utf8_decode($pais_sel))
			echo " selected";
	}
	echo ">$nombre_pais</option>";
}
$conex->close();
?>