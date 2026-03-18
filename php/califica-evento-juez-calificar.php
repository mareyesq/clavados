		<select class="calif" name="calificacion_slc" title="Escoja la calificación y luego pulse el botón Enviar" required > 
			<option value="">---</option>
			<option value="10.0" <?php 	if ($calificacion=="10.0") echo " selected";?>>10.0</option>
			<option value="9.5" <?php 	if ($calificacion=="9.5") echo " selected";?>>9.5</option>
			<option value="9.0" <?php 	if ($calificacion=="9.0") echo " selected";?>>9.0</option>
			<option value="8.5" <?php 	if ($calificacion=="8.5") echo " selected";?>>8.5</option>
			<option value="8.0" <?php 	if ($calificacion=="8.0") echo " selected";?>>8.0</option>
			<option value="7.5" <?php 	if ($calificacion=="7.5") echo " selected";?>>7.5</option>
			<option value="7.0" <?php 	if ($calificacion=="7.0") echo " selected";?>>7.0</option>
			<option value="6.5" <?php 	if ($calificacion=="6.5") echo " selected";?>>6.5</option>
			<option value="6.0" <?php 	if ($calificacion=="6.0") echo " selected";?>>6.0</option>
			<option value="5.5" <?php 	if ($calificacion=="5.5") echo " selected";?>>5.5</option>
			<option value="5.0" <?php 	if ($calificacion=="5.0") echo " selected";?>>5.0</option>
			<option value="4.5" <?php 	if ($calificacion=="4.5") echo " selected";?>>4.5</option>
			<option value="4.0" <?php 	if ($calificacion=="4.0") echo " selected";?>>4.0</option>
			<option value="3.5" <?php 	if ($calificacion=="3.5") echo " selected";?>>3.5</option>
			<option value="3.0" <?php 	if ($calificacion=="3.0") echo " selected";?>>3.0</option>
			<option value="2.5" <?php 	if ($calificacion=="2.5") echo " selected";?>>2.5</option>
			<option value="2.0" <?php 	if ($calificacion=="2.0") echo " selected";?>>2.0</option>
			<option value="1.5" <?php 	if ($calificacion=="1.5") echo " selected";?>>1.5</option>
			<option value="1.0" <?php 	if ($calificacion=="1.0") echo " selected";?>>1.0</option>
			<option value="0.5" <?php 	if ($calificacion=="0.5") echo " selected";?>>0.5</option>
			<option value="0.0" <?php 	if ($calificacion=="0.0") echo " selected";?>>0.0</option>
		</select>&nbsp;
		<input type='submit' id='enviar' title='Envía tu calificación' class='cambio' name='enviar_sbm' value='Enviar'>
