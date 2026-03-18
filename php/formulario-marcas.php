		<div>
			<label for="categoria">Categoría: </label>
			<?php 
				if ($alta) {
					echo "<select id='categoria' name='categoria_slc' class='cambio'>
						<option value=''>- - -</option>";
					include("select-categoria.php"); 
					echo "</select>";
				}
				else
					echo "<input type='text' name='categoria_slc' value='$categoria' size='30' readonly>";
			 ?>
			<label for="modalidad">Modalidad: </label>
			<?php 

				if ($alta) {
					echo '<select id="modalidad" name="cod_modalidad_slc" class="cambio">
					<option value="">- - -</option>';
					include("select-modalidad.php");
					echo "</select>";	
				}
				else
					echo "<input type='text' name='modalidad_slc' value='$modalidad' size='20' readonly>";
			 ?>
			
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="logo_hdn" value="<?php echo $logo; ?>">
		</div>
		<br>
		<table>
			<thead>
				<tr align='center'>
					<th>Sexo</th>
					<th>Marca</th>
					<th>Grado Dif.</th>
					<th>Promedio Calif.</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Femenino</td>
					<td><input type='number' step='0.01' name='marca_f_num' value='<?php echo $marca_f; ?>' <?php if ($baja) echo " readonly" ?>></td>
					<td><input type='number' step='0.1'  name='grado_f_num' value='<?php echo $grado_f ?>' <?php if ($baja) echo " readonly" ?>></td>
					<td><input type='number' max='10' step='0.1' name='prom_f_num' value='<?php echo $prom_f ?>' <?php if ($baja) echo " readonly" ?>></td>

				</tr>
				<tr>
					<td>Masculino</td>
					<td><input type='number' step='0.01'  name='marca_m_num'  value='<?php echo $marca_m ?>' <?php if ($baja) echo " readonly" ?>></td>
					<td><input type='number' step='0.1'  name='grado_m_num'  value='<?php echo $grado_m ?>' <?php if ($baja) echo " readonly" ?>></td>
					<td><input type='number'  max='10' step='0.1' name='prom_m_num' value='<?php echo $prom_m ?>' <?php if ($baja) echo " readonly" ?>></td>

				</tr>
			</tbody>
		</table>
