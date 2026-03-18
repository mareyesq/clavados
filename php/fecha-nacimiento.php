<?php 
	include ("funciones.php");
	if (navegador_compatible("chrome")){
		echo 
		"<label for='nacimiento'>Fecha de Nacimiento: </label>
			<input type='date' id='nacimiento' class='cambio' name='nacimiento_txt' title='Tu fecha de nacimiento' value='";
		echo "$nacimiento";
	}
 	else{
 		echo "<label for='dia_nac'>Fecha nacimiento: Dia </label>";
		echo "<select id='dia_nac' class='cambio' name='dia_nac_slc' >";
		echo "<option value='' >- - -</option>";
		include("php/select-dia.php");
		echo "</select>";
		echo "<label for='mes_nac'>Mes </label>
			<select id='mes_nac' class='cambio' name='mes_nac_slc' >
				<option value='' >- - -</option>";
		include("php/select-mes.php");
		echo "</select>";
		echo "<label for='ano_nac'>Año </label>
			<select id='ano_nac' class='cambio' name='ano_nac_slc' >
				<option value='' >- - -</option>";
		include("php/select-anio.php");
		echo  "</select>";
 }
echo "<span class='error'>";
echo "$nacimiento_err";
echo "</span>";
?>