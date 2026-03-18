<?php 
	$competencia=$_GET["com"];
	$administrador=$_GET["ad"];
	$cod_competencia=$_GET["codco"];
	$cod_usuario=$_GET["codus"];
?>
<form id="baja-administrador-competencia" name="baja_frm" action="php/eliminar-administrador-competencia.php" method="post" cnctype="application/x-www-form-urlencoded">
	<fieldset>
		<legend>Baja de Administrador de Competencia</legend>
		<div>
			<span class="consulta">Desea Eliminar el Administrador: <?php echo $administrador; ?> <br>de la Competencia: <?php echo $competencia; ?>?</span>
			<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" id="cod-usuario" name="cod_usuario_hdn" value="<?php echo $cod_usuario; ?>">
			<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" id="administrador" name="administrador_hdn" value="<?php echo $administrador; ?>">
		</div>
		<div>
			<input type="submit" id="enviar-baja" class="cambio" name="baja_btn" value="Eliminar" />
			&nbsp&nbsp
			<input type="submit" id="cancelar-baja" class="cambio" name="cancelar_btn" value="Cancelar" />
		</div>

	</fieldset>
</form>