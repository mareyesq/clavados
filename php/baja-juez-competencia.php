<?php 
	$competencia=$_GET["com"];
	$juez=$_GET["jz"];
	$cod_competencia=$_GET["codco"];
	$cod_usuario=$_GET["codus"];
?>
<form id="baja-juez-competencia" name="baja_frm" action="php/eliminar-juez-competencia.php" method="post" cnctype="application/x-www-form-urlencoded">
	<fieldset>
		<legend>Baja de juez de Competencia</legend>
		<div>
			<span class="consulta">Desea Eliminar el juez: <?php echo $juez; ?> <br>de la Competencia: <?php echo $competencia; ?>?</span>
			<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" id="cod-usuario" name="cod_usuario_hdn" value="<?php echo $cod_usuario; ?>">
			<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" id="juez" name="juez_hdn" value="<?php echo $juez; ?>">
		</div>
		<div>
			<input type="submit" id="enviar-baja" class="cambio" name="baja_btn" value="Eliminar" />
			&nbsp;&nbsp;
			<input type="submit" id="cancelar-baja" class="cambio" name="cancelar_btn" value="Cancelar" />
		</div>

	</fieldset>
</form>