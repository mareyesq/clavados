	<table border="0" width="100%" cellpadding="1" cellspacing="1"> 
	<tr align="center"> 
		<td width="45%"> 
			<span class="rotulo">Calificaciones
				<?php 
					if ($usa_dispositivos)
						echo "con <img src='img/tablet.jpg' width='3%'/>";
				 ?>
			</span>
		<table border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
			<thead align="center">
				<th>Nro.</th>
				<th>Juez</th>
				<th>Calificaci&oacute;n</th>
				<?php 
					if ($usa_dispositivos)
						echo '<th>Corregir<th>'
				 ?>
			</thead>
			<tbody>
				<?php 
				if ($juez1) {
					echo "<tr align='center'>
					<td>1</td>
					<td>$nom_juez1</td>";
					echo "<td><input tabindex = '1' type='number' min='0' max='100' step='0.5' autofocus class='cambio' name='cal1' value='$cal1'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor1_chk'";
						if ($cor1) 
							echo " checked";
						echo " title='Habilita al juez 1 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez2) {
					echo "<tr align='center'>
					<td>2</td>
					<td>$nom_juez2</td>";
					echo "<td><input tabindex = '2' type='number' min='0' max='100' step='0.5'  class='cambio' name='cal2' value='$cal2'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor2_chk'";
						if ($cor2) 
							echo " checked";
						echo " title='Habilita al juez 2 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez3) {
					echo "<tr align='center'>
					<td>3</td>
					<td>$nom_juez3</td>";
					echo "<td><input tabindex = '3' type='number' min='0' max='100' step='0.5' class='cambio' name='cal3' value='$cal3'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor3_chk'";
						if ($cor3) 
							echo " checked";
						echo " title='Habilita al juez 3 para corregir'></td>";
					}
					echo "</tr>";

				} 
				if ($juez4) {
					echo "<tr align='center'>
					<td>4</td>
					<td>$nom_juez4</td>";
					echo "<td><input tabindex = '4' type='number' min='0' max='100' step='0.5' class='cambio' name='cal4' value='$cal4'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor4_chk'";
						if ($cor4) 
							echo " checked";
						echo " title='Habilita al juez 4 para corregir'></td>";
					}
					echo "</tr>";

				} 
				if ($juez5) {
					echo "<tr align='center'>
					<td>5</td>
					<td>$nom_juez5</td>";
					echo "<td><input tabindex = '5' type='number'  min='0' max='100' step='0.5' class='cambio' name='cal5' value='$cal5'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor5_chk'";
						if ($cor5) 
							echo " checked";
						echo " title='Habilita al juez 5 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez6) {
					echo "<tr align='center'>
					<td>6</td>
					<td>$nom_juez6</td>";
					echo "<td><input tabindex = '6' type='number' min='0' max='100' step='0.5' class='cambio' name='cal6' value='$cal6'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor6_chk'";
						if ($cor6) 
							echo " checked";
						echo " title='Habilita al juez 6 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez7) {
					echo "<tr align='center'>
					<td>7</td>
					<td>$nom_juez7</td>";
					echo "<td><input tabindex = '7' type='number' min='0' max='100' step='0.5' class='cambio' name='cal7' value='$cal7'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor7_chk'";
						if ($cor7) 
							echo " checked";
						echo " title='Habilita al juez 7 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez8) {
					echo "<tr align='center'>
					<td>8</td>
					<td>$nom_juez8</td>";
					echo "<td><input tabindex = '8' type='number' min='0' max='100' step='0.5'  class='cambio' name='cal8' value='$cal8'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor8_chk'";
						if ($cor8) 
							echo " checked";
						echo " title='Habilita al juez 8 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez9) {
					echo "<tr align='center'>
					<td>9</td>
					<td>$nom_juez9</td>";
					echo "<td><input tabindex = '9' type='number' min='0' max='100' step='0.5' class='cambio' name='cal9' value='$cal9'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor9_chk'";
						if ($cor9) 
							echo " checked";
						echo " title='Habilita al juez 9 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez10) {
					echo "<tr align='center'>
					<td>10</td>
					<td>$nom_juez10</td>";
					echo "<td><input tabindex = '10' type='number' min='0' max='100' step='0.5' class='cambio' name='cal10' value='$cal10'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor10_chk'";
						if ($cor10) 
							echo " checked";
						echo " title='Habilita al juez 10 para corregir'></td>";
					}
					echo "</tr>";
				} 
				if ($juez11) {
					echo "<tr align='center'>
					<td>11</td>
					<td>$nom_juez11</td>";
					echo "<td><input tabindex = '11' type='number' min='0' max='100' step='0.5' class='cambio' name='cal11' value='$cal11'";
					if ($edita==0) 
						echo " readonly";
					echo "></td>";
					if ($usa_dispositivos){
						echo "<td><input type='checkbox' name='cor11_chk'";
						if ($cor11) 
							echo " checked";
						echo " title='Habilita al juez 11 para corregir'></td>";
					}
					echo "</tr>";
				} 
				?>
			</tbody>
		</td>
		</table>
