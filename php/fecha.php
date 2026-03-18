<?php 
	if(!navegador_compatible("iPhone") and !navegador_compatible("iPod")  and  navegador_compatible("chrome")){
		echo "<label for='fecha' class='rotulo'>Fecha: </label>
			<input type='date' id='fecha' class='cambio' name='fecha_txt' title='Fecha' value='$fecha'";
	}
 	else{
 		$fec=explode("-", $fecha);
 		echo "<label for='dia' class='rotulo'>Fecha: Dia </label>";
		echo "<select id='dia' class='cambio' name='dia_slc' >";
		echo "<option value='' >- - -</option>";
		include("php/select-dia.php");
		echo "</select>";
		echo "<label for='mes' class='rotulo'>Mes </label>
			<select id='mes' class='cambio' name='mes_slc' >
				<option value='' >- - -</option>";
		include("php/select-mes.php");
		echo "</select>";
		echo "<label for='ano' class='rotulo'>Año </label>
			<select id='ano' class='cambio' name='ano_slc' >
				<option value='' >- - -</option>";
		include("php/select-anio.php");
		echo  "</select>";
 }
?>