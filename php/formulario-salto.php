<div>
	<span>Ronda: </span>
	<span class="rotulo"><?php echo $ronda; ?></span>
	<br>
	<span class="rotulo"><?php echo $nom_cla.'-'.$nom_equipo; ?></span>
	<?php 
		if ($imagen1) 
   			echo "<img class='textwrap'src='img/fotos/$imagen1' width='5%'/>&nbsp;&nbsp;";
		if ($imagen2) 
   			echo "<img class='textwrap'src='img/fotos/$imagen2' width='5%'/>";
	?>
	</span>
	<br>
	<label for="salto">Salto:</label>
	<input type="text" id="salto" name="salto_txt" value="<?php echo $salto; ?>">
	<label for="posicion">Posición:</label>
	<select id="posicion" name='posicion_slc' >
	<option ></option> 
	<option <?php if ($posicion=="A") echo " selected"; ?>>A</option> 
	<option <?php if ($posicion=="B") echo " selected"; ?>>B</option> 
	<option <?php if ($posicion=="C") echo " selected"; ?>>C</option> 
	<option <?php if ($posicion=="D") echo " selected"; ?>>D</option> 
	</select>
	<label for="altura">Altura:</label>
	<select id="altura" name='altura_slc'>  
		<option ></option> 
		<option <?php if ($altura==0.75) echo " selected"; ?>>0.75</option> 
		<option <?php if ($altura==1) echo " selected"; ?>>1</option> 
		<option <?php if ($altura==2) echo " selected";?>>2</option> 
		<option <?php if ($altura==3) echo " selected";?>>3</option> 
		<option <?php if ($altura==5) echo " selected";?>>5</option> 
		<option <?php if ($altura==7.5) echo " selected";?>>7.5</option> 
		<option <?php if ($altura==10) echo " selected";?>>10</option> 
	</select>
	<br>
	<label for="grado-dif">Grado Dif.:</label>
	<input type="number" min="0" max="9.9" step="0.1" name="grado_dif_nbr" value="<?php echo $grado_dif; ?>">
	<span>Puntaje Salto: </span>
	<span class="rotulo">
		<?php 
			echo number_format($puntaje_salto,$dec)."&nbsp;&nbsp;";
		?>
	</span>
</div>
<span>Calificaciones</span>
<table border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
	<thead align="center">
		<th>Juez</th>
		<th>Calificaci&oacute;n</th>
	</thead>
	<tbody>
	<?php 
		if (!is_null($cal1)) $cal1=number_format($cal1,1);
		if (!is_null($cal2)) $cal2=number_format($cal2,1);
		if (!is_null($cal3)) $cal3=number_format($cal3,1);
		if (!is_null($cal4)) $cal4=number_format($cal4,1);
		if (!is_null($cal5)) $cal5=number_format($cal5,1);
		if (!is_null($cal6)) $cal6=number_format($cal6,1);
		if (!is_null($cal7)) $cal7=number_format($cal7,1);
		if (!is_null($cal8)) $cal8=number_format($cal8,1);
		if (!is_null($cal9)) $cal9=number_format($cal9,1);
		if (!is_null($cal10)) $cal10=number_format($cal10,1);
		if (!is_null($cal11)) $cal11=number_format($cal11,1);
		if ($cal1) {
			echo "<tr align='center'>
					<td>Juez 1</td>";
			echo "<td><input tabindex = '1' type='number' min='0' max='100' step='0.5' autofocus class='cambio' name='cal1' value='$cal1'";
			echo "></td></tr>";
		} 
		if ($cal2) {
			echo "<tr align='center'>
			<td>Juez 2</td>";
			echo "<td><input tabindex = '2' type='number' min='0' max='100' step='0.5'  class='cambio' name='cal2' value='$cal2'";
			echo "></td></tr>";
		} 
		if ($cal3) {
			echo "<tr align='center'>
				<td>Juez 3</td>";
			echo "<td><input tabindex = '3' type='number' min='0' max='100' step='0.5' class='cambio' name='cal3' value='$cal3'";
			echo "></td></tr>";
		} 
		if ($cal4) {
			echo "<tr align='center'>
			<td>Juez 4</td>";
			echo "<td><input tabindex = '4' type='number' min='0' max='100' step='0.5' class='cambio' name='cal4' value='$cal4'";
			echo "></td></tr>";
		} 
		if ($cal5) {
			echo "<tr align='center'>
			<td>Juez 5</td>";
			echo "<td><input tabindex = '5' type='number'  min='0' max='100' step='0.5' class='cambio' name='cal5' value='$cal5'";
			echo "></td></tr>";
		} 
		if ($cal6) {
			echo "<tr align='center'>
				<td>Juez 6</td>";
			echo "<td><input tabindex = '6' type='number' min='0' max='100' step='0.5' class='cambio' name='cal6' value='$cal6'";
			echo "></td></tr>";
		} 
		if ($cal7) {
			echo "<tr align='center'>
					<td>Juez 7</td>";
			echo "<td><input tabindex = '7' type='number' min='0' max='100' step='0.5' class='cambio' name='cal7' value='$cal7'";
			echo "></td></tr>";
		} 
		if ($cal8) {
			echo "<tr align='center'>
					<td>Juez 8</td>";
			echo "<td><input tabindex = '8' type='number' min='0' max='100' step='0.5'  class='cambio' name='cal8' value='$cal8'";
			echo "></td></tr>";
		} 
		if ($cal9) {
			echo "<tr align='center'>
					<td>Juez 9</td>";
			echo "<td><input tabindex = '9' type='number' min='0' max='100' step='0.5' class='cambio' name='cal9' value='$cal9'";
			echo "></td></tr>";
		} 
		if ($cal10) {
			echo "<tr align='center'>
					<td>Juez 10</td>";
			echo "<td><input tabindex = '10' type='number' min='0' max='100' step='0.5' class='cambio' name='cal10' value='$cal10'";
			echo "></td></tr>";
		} 
		if ($cal11) {
			echo "<tr align='center'>
					<td>Juez 11</td>";
			echo "<td><input tabindex = '11' type='number' min='0' max='100' step='0.5' class='cambio' name='cal11' value='$cal11'";
			echo "></td></tr>";
		} 
	?>
	</tbody>
</table>