<?php 
	if(!navegador_compatible("iPhone") and !navegador_compatible("iPod")  and  navegador_compatible("chrome")){
		echo "<br><label for='hora' class='rotulo'> Hora </label>";
		echo "<input type='time' id='hora' name='hora_txt' value='";
		echo "$hora'>"; 
	}
 	else{
		$hh=substr($hora, 0, 2);
		if ($hh>12) {
			$hh -= 12;
			$ampm="pm";
		}
		else
			$ampm="am";
 		echo "<label for='hh' class='rotulo'>Hora: hh </label>";
		echo "<select id='hh' class='cambio' name='hh_slc' >";
		echo "<option value='' >- - -</option>";
		include("php/select-hora.php");
		echo "</select>";

		$mm=substr($hora, 3, 2);
		echo "<label for='mm' class='rotulo'>mm </label>
			<select id='mm' class='cambio' name='mm_slc' >
				<option value='' >- - -</option>";
		include("php/select-minutos.php");
		echo "</select>";
		echo "<label for='am' class='rotulo'>am/pm </label>
			<select id='am' class='cambio' name='am_slc' >
				<option value='' >- - -</option>
				<option value='am'";
		if ($ampm=="am") echo " selected";
		echo " >am</option>";
		echo "<option value='pm'";
		if ($ampm=="pm") 
			echo " selected";
		echo " >pm</option>";
		echo  "</select>";
 }
?>