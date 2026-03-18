<div>
	<?php include("php/fecha.php"); ?>
	<?php include("php/hora.php"); ?>
	<label for="modalidad" class="rotulo">Modalidad: </label>

	<select id="modalidad" name="modalidad_slc" class="cambio">
		<option value="">- - -</option>	

		<?php 
			$cod_modalidad=$modalidad;
			include("php/select-modalidad.php")
		 ?>
	</select>
	<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
	<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
</div>
<div>
	<label for="s" class="rotulo">Categorias: </label>
	<?php include("php/radio-categorias.php"); ?>
</div>
<div>
	<label for="s" class="rotulo">Sexos: </label>
	<?php include("php/radio-sexos.php"); ?>
</div>
<div>
	<label for="tipo" class="rotulo">Tipo Evento</label>
	<?php include("php/radio-tipo-evento.php"); ?>
</div>
<div>
	<label for="primero-libres" class="rotulo">Al mezclar Juveniles y Mayores, primero ejecutar saltos libres: </label>
	<input type='checkbox' id='primero-libres' name='primero_libres_chk'  title='Marque si hacen los libres primero' value='1'
	<?php 
		if ($primero_libres) echo " checked";
	 ?>
	 >
</div>
<div>
	<label for="usa_dispositivos" class="rotulo">Usar dispositivos para calificar: </label>
	<input type='checkbox' id='usa_dispositivos' name='usa_dispositivos_chk'  title='Marque si van a usar dispositivos para calificar' value='1'
	<?php 
		if ($usa_dispositivos) 
			echo " checked";
	 ?>
	 >
</div>
<div>
	<label for="calentamiento" class="rotulo">Minutos de calentamiento: </label>
	<input type='number' id='calentamiento' name='calentamiento_nbr' min='0' max="600" step="1" title='Minutos de calentamiento previos al evento ;)' value='<?php echo($calentamiento) ?>'
	 >
</div>
<div>
	<label for="calentamiento" class="rotulo">Participantes Estimados: </label>
	<input type='number' id='participantes_estimado' name='participantes_estimado_nbr' min='0' max="99" step="1" title='Cantidad de participantes estimado ' value='<?php echo($participantes_estimado) ?>'
	 >
</div>

