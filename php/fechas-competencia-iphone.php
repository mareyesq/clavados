<div>
	<label for="inicia" class="rotulo">Fechas: Inicio: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
		$fec=explode('-', $inicia);
		echo "<select id='dia-ini' class='cambio' name='dia_ini_slc' >
				<option value='' >- - -</option>";
		include("php/select-dia.php"); 
		echo "</select>";
		echo "&nbsp;<select id='mes-ini' class='cambio' name='mes_ini_slc' >
				<option value='' >- - -</option>";
		include("php/select-mes.php"); 
		echo "</select>";
		echo "&nbsp;<select id='ano-ini' class='cambio' name='ano_ini_slc' >
				<option value='' >- - -</option>";
		include("php/select-anio.php"); 
		echo "</select>";
	 ?>

	<label for="termina" class="rotulo">Terminación: </label>
	<?php 
		$fec=explode('-', $termina);
		echo "<select id='dia-ter' class='cambio' name='dia_ter_slc' >
				<option value='' >- - -</option>";
		include("php/select-dia.php"); 
		echo "</select>";
		echo "&nbsp;<select id='mes-ter' class='cambio' name='mes_ter_slc' >
				<option value='' >- - -</option>";
		include("php/select-mes.php"); 
		echo "</select>";
		echo "&nbsp;<select id='ano-ter' class='cambio' name='ano_ter_slc' >
				<option value='' >- - -</option>";
		include("php/select-anio.php"); 
		echo "</select>";
	 ?>
</div>
<div>
	<label for="fecha-limite" class="rotulo">Límite de Inscripción</label>
	<?php 
		$fec=explode('-', $fecha_limite);
		echo "<select id='dia-lim' class='cambio' name='dia_lim_slc' >
				<option value='' >- - -</option>";
		include("php/select-dia.php"); 
		echo "</select>";
		echo "&nbsp;<select id='mes-lim' class='cambio' name='mes_lim_slc' >
				<option value='' >- - -</option>";
		include("php/select-mes.php"); 
		echo "</select>";
		echo "&nbsp;<select id='ano-lim' class='cambio' name='ano_lim_slc' >
				<option value='' >- - -</option>";
		include("php/select-anio.php"); 
		echo "</select>";
	 ?>
	<label for="hora-limite" class="rotulo">Hora: </label>
	<?php 
		$hh=substr($limite, 11, 2);
		if ($hh>12) {
			$hh -= 12;
			$ampm_lim="PM";
		}
		else
			$ampm_lim="AM";

		echo "</select>";
		echo "&nbsp;<select id='hh-lim' class='cambio' name='hh_lim_slc' >
				<option value='' >- - -</option>";
		include("php/select-hora.php"); 
		echo "</select>";

		$mm=substr($limite, 14, 2);
		echo "&nbsp;<select id='mm-lim' class='cambio' name='mm_lim_slc' >
				<option value='' >- - -</option>";
		include("php/select-minutos.php"); 
		echo "</select>";

		echo "</select>";
		echo "&nbsp;<select id='ampm-lim' class='cambio' name='ampm_lim_slc' >
				<option value='AM' ";
		if ($ampm_lim=="AM") 
			echo " selected ";
		echo ">AM</option>";
		echo "<option value='PM' ";
		if ($ampm_lim=="PM") 
			echo " selected ";
		echo ">PM</option>";
		echo "</select>";

	 ?>
</div>
