<?php 
	$meses= array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


	$mes=$meses[$fec[1]-1];
	
	for ($i=0; $i<12 ; $i++) { 
		$m=$meses[$i];
		echo "<option value='$m'";
		if ($mes==$meses[$i]) echo " selected";
		echo " >$m</option>";	
	}

 ?>