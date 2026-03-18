		<div>
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="logo_hdn" value="<?php echo $logo; ?>">
		</div>
		<div>
			<label for="equipo" class="rotulo">Equipo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
			<input type="text" size="50" id="equipo" class="cambio" name="equipo_txt" title="Nombre de tu Equipo" value="<?php echo "$equipo"; ?>"/>
		</div>
		<div>
			<label for="corto" class="rotulo">Nombre Corto: </label>
			<input type="text" id="corto" class="cambio" name="corto_txt" title="Define un nombre corto para tu Equipo" value="<?php echo "$corto"; ?>"/>
		</div>
		<div>
			<label for="representante" class="rotulo">Representante: </label>
			<input type="text" size="50" id="representante" class="cambio" name="representante_txt" placeholder="Escribe el nombre del representante" title="nombre del representante o delegado de tu equipo" value="<?php echo "$representante"; ?>"/>
		</div>
		<div>
			<label for="email" class="rotulo">Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
			<input type="email" size="50" id="email" class="cambio" name="email_txt" placeholder="Escribe tu email" title="Tu email" value="<?php echo $email; ?>" />
		</div>
		<div>
			<label for="telefono" class="rotulo">Tel&eacute;fono:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
			<input type="tel" id="telefono" class="cambio" name="telefono_txt" placeholder="Escribe tu telefono" title="Teléfono para contactarlos" value="<?php echo $telefono; ?>" />
		</div>
		<div>
			<label for="password" class="rotulo">Clave Inscripciones: </label>
			<input type="password" id="password" class="cambio" name="password_txt" placeholder="Define la Clave" title="Define la clave para inscripciones de tu equipo" value="<?php echo $password; ?>" />
		</div>
		<div>
			<label for="password_conf" class="rotulo">Confirmar Clave:&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input type="password" id="password_conf" class="cambio" name="password_conf_txt" placeholder="Confirma la Clave" title="Confirma la clave para inscripciones" value="<?php echo $password_conf; ?>" />
		</div>

		