		<div>
			<label for="inicia" class="rotulo">Fechas: Inicio: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="date" id="inicia" class="cambio" name="inicia_txt" placeholder="dd/mm/aaaa" title="Fecha de inicio de la competencia"  value="<?php echo $inicia; ?>" />
			<label for="termina" class="rotulo">Terminación: </label>
			<input type="date" id="termina" class="cambio" name="termina_txt" placeholder="dd/mm/aaaa" title="Fecha de terminación de la competencia"  value="<?php echo $termina; ?>" />
		</div>
		<div>
			<label for="fecha-limite" class="rotulo">Límite de Inscripción</label>
			<input type="date" id="fecha-limite" class="cambio" name="fecha_limite_txt" placeholder="dd/mm/aaaa" title="Fecha límite para inscripciones de la competencia"  value="<?php echo $fecha_limite; ?>"/>
			<label for="hora-limite" class="rotulo">Hora: </label>
			<input type="time" id="hora-limite" class="cambio" name="hora_limite_txt" title="hora límite para inscripciones (dd/mm/aaaa hh:mm am/pm" placeholder="hh:mm am/pm"  value="<?php echo $hora_limite; ?>" />
		</div>
