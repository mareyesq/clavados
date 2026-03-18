<div>
	<br><br>
	<?php 
		if ($imagen2) 
	    	echo "<img src='img/fotos/$imagen2' width='5%' height='60'/>";
 	?>
	<label for="clavadista2" class="rotulo">Clavadista 2: </label>
	<input type="text" id="clavadista2" class="cambio" name="clavadista2_txt" placeholder="Escribe el nombre completo" title="nombre del segundo clavadista del sincronizado" value="<?php echo $clavadista2; ?>" <?php if ($ok or !$alta) echo " readonly"; ?>>
	<?php 
		if ($alta) {
			echo '<input type="submit" id="busca_clavadista" name="busca_clavadista_sbm" value="Buscar" title="Busca el clavadista">';
		}
	 ?>
	<input type="hidden" name="cod_clavadista2_hdn" value="<?php echo $cod_clavadista2; ?>">
	<input type="hidden" name="imagen2_hdn" value="<?php echo $imagen2; ?>">
	<input type="hidden" name="buscar_hdn" value="<?php echo $buscar; ?>">
	<?php 
		$fn2=isset($nacimiento2)?substr($nacimiento2,0,4):NULL;
		$ed2=isset($nacimiento2)?edad_deportiva($nacimiento2):NULL;
	?>
	<span class="rotulo">Nació: <?php echo "$fn2&nbsp&nbsp Edad: $ed2&nbsp&nbspSexo: $sexo2" ; ?></span>
	<input type="hidden" name="edad2_hdn" value="<?php echo $ed2; ?>" >
	<input type="hidden" name="sexo2_hdn" value="<?php echo $sexo2; ?>" >
</div>
