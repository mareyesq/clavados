		<div>
			<?php 
				if ($imagen3) 
	    			echo "<img src='img/fotos/$imagen3' width='5%' height='60'/>&nbsp;&nbsp;";
	 		?>
			<label for="clavadista3" class="rotulo">Clavadista 3: </label>
			<input type="text" id="clavadista3" class="cambio" name="clavadista3_txt" placeholder="Escribe el nombre completo" title="nombre del tercer clavadista del equipo" value="<?php echo $clavadista3; ?>" <?php if ($ok or !$alta) echo " readonly"; ?>>
			<?php 
				if ($alta) {
					echo '<input type="submit" id="busca_clavadista" name="busca_clavadista_sbm" value="Buscar" title="Busca el clavadista">';						}
			 ?>
			
			<input type="hidden" name="cod_clavadista3_hdn" value="<?php echo $cod_clavadista3; ?>">
			<input type="hidden" name="imagen3_hdn" value="<?php echo $imagen3; ?>">
			<input type="hidden" name="buscar_hdn" value="<?php echo $buscar; ?>">
			<?php 
				$fn3=isset($nacimiento3)?substr($nacimiento3,0,4):NULL;
				$ed3=isset($nacimiento3)?edad_deportiva($nacimiento3):NULL;
				$ct3=isset($nacimiento3)?grupo_categoria($nacimiento3):NULL;
			?>
			<span class="rotulo">Nació: <?php echo "$fn3&nbsp;&nbsp; Edad: $ed3&nbsp;&nbsp;Categoría: $ct3 &nbsp;Sexo: $sexo3" ; ?></span>
			<input type="hidden" name="edad3_hdn" value="<?php echo $ed3; ?>" >
			<input type="hidden" name="cod_sexo3_hdn" value="<?php echo $cod_sexo3; ?>" >
		</div>