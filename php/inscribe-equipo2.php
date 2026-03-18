		<input type="hidden" name="competencia_hdn" value="<?php echo "$competencia"; ?> ">
		<span class="consulta">Nombre Corto:</span> <?php echo "$nombre_corto"; ?> <br>
		<span class="consulta">País:</span> <?php echo "$pais"; ?> <br>
		<span class="consulta">Representante:</span> <?php echo "$representante"; ?> <br>
		<input type="hidden" name="representante_hdn" value="<?php echo "$representante"; ?> ">
		<span class="consulta">Teléfono:</span> <?php echo "$telefono"; ?> <br>
		<span class="consulta">Email:</span> <?php echo "$email"; ?> <br>
		<input type="hidden" name="email_hdn" value="<?php echo "$email"; ?> ">
		<div>
			<input type="submit" id="inscribir-equipo" class="cambio" name="inscribir_equipo_btn" value="Inscribir" title="Inscribe tu  equipo en la Competencia <?php echo $competencia; ?> "/>
		</div>