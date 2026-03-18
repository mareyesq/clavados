<form id="baja-competidor" name="baja_frm" action="php/eliminar-competidor.php" method="post" cnctype="application/x-www-form-urlencoded">
	<fieldset>
		<legend>Baja de Competidor</legend>
		<div>
			<label for="email">Email:</label>
			<select id="email" class="cambio" name="email_slc" required>
				<option value="">- - -</option>
				<?php include("select-email.php"); ?>
			</select>
		</div>
		<div>
			<input type="submit" id="enviar-baja" class="cambio" name="enviar_btn" value="eliminar" />
		</div>
	</fieldset>
</form>