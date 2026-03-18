<div>
	<label for="inicia" class="rotulo">Fecha edad deportiva:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
		$fec=explode('-', $fecha_edad_deportiva);
		echo "<select id='dia-edd' class='cambio' name='dia_edd_slc' >
				<option value='' >- - -</option>";
		include("php/select-dia.php"); 
		echo "</select>";
		echo "&nbsp;<select id='mes-edd' class='cambio' name='mes_edd_slc' >
				<option value='' >- - -</option>";
		include("php/select-mes.php"); 
		echo "</select>";
		echo "&nbsp;<select id='ano-edd' class='cambio' name='ano_edd_slc' >
				<option value='' >- - -</option>";
		include("php/select-anio.php"); 
		echo "</select>";
	 ?>
</div>
