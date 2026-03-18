		<div>
			<?php 
				if ($imagen4) 
	    			echo "<img src='img/fotos/$imagen4' width='5%' height='60'/>&nbsp;&nbsp;";
	 		?>
			<label for="clavadista4" class="rotulo">Clavadista 4: </label>
			<input type="text" id="clavadista4" class="cambio" name="clavadista4_txt" placeholder="Escribe el nombre completo" title="nombre del cuarto clavadista del equipo" value="<?php echo $clavadista4; ?>" <?php if ($ok or !$alta) echo " readonly"; ?>>
			<?php 
				if ($alta) {
					echo '<input type="submit" id="busca_clavadista" name="busca_clavadista_sbm" value="Buscar" title="Busca el clavadista">';						}
			 ?>
			
			<input type="hidden" name="cod_clavadista4_hdn" value="<?php echo $cod_clavadista4; ?>">
			<input type="hidden" name="imagen4_hdn" value="<?php echo $imagen4; ?>">
			<input type="hidden" name="buscar_hdn" value="<?php echo $buscar; ?>">
			<?php 
				$fn4=isset($nacimiento4)?substr($nacimiento4,0,4):NULL;
				$ed4=isset($nacimiento4)?edad_deportiva($nacimiento4):NULL;
				$ct4=isset($nacimiento4)?grupo_categoria($nacimiento4):NULL;
			?>
			<span class="rotulo">Nació: <?php echo "$fn4&nbsp&nbsp Edad: $ed4&nbsp;&nbsp;Categoría: $ct4 &nbsp;Sexo: $sexo4" ; ?></span>
			<input type="hidden" name="edad4_hdn" value="<?php echo $ed4; ?>" >
			<input type="hidden" name="cod_sexo4_hdn" value="<?php echo $cod_sexo4; ?>" >
		</div>