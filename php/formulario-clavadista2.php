		<div>
			<label for="clavadista" class="rotulo">Clavadista 2: </label>
			<input type="text" id="clavadista2" class="cambio" name="clavadista2_txt" placeholder="Si ya está registrado, escribe parte del nombre y Click en Buscar" title="nombre completo del clavadista 2 para pareja" size="50" maxlength="50" value="<?php echo $clavadista2; ?>">
			<?php 
				if ($alta) {
					echo '<input type="submit" id="busca_clavadista2" name="busca_clavadista2_sbm" value="Buscar" title="Busca el clavadista 2">			<input type="submit" id="nuevo_clavadista2" name="nuevo_clavadista2_sbm" value="Nuevo" title="Regístrate como clavadista">';
				}
				if ($imagen2) 
	    			echo "<img src='img/fotos/$imagen2' width='5%' height='70'/>&nbsp;&nbsp;";
				
    			if ($cod_clavadista2) {
					echo '<span class="cambio">'.$desc_sexo2.'</span>
						<span class="cambio">'.$edad2." años".'</span>'; 
    			}
 			?>
 			<input type="hidden" name="cod_clavadista2_hdn" value="<?php echo $cod_clavadista2; ?>">
			<input type="hidden" name="imagen2_hdn" value="<?php echo $imagen2; ?>">
			<input type="hidden" name="sexo2_hdn" value="<?php echo $sexo2; ?>">
		</div>
