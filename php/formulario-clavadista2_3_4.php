		<div>
			<label for="clavadista2" class="rotulo">Clavadista 2: </label>
			<input type="text" id="clavadista2" class="cambio" name="clavadista2_txt" placeholder="Si ya está registrado, escribe parte del nombre y Click en Buscar" title="nombre completo del clavadista 2 para equipo juvenil" size="50" maxlength="50" value="<?php echo $clavadista2; ?>">
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
			<input type="hidden" name="edad2" value="<?php echo $edad2; ?>">
		</div>
		<div>
			<label for="clavadista3" class="rotulo">Clavadista 3: </label>
			<input type="text" id="clavadista3" class="cambio" name="clavadista3_txt" placeholder="Si ya está registrado, escribe parte del nombre y Click en Buscar" title="nombre completo del clavadista 3 para equipo juvenil" size="50" maxlength="50" value="<?php echo $clavadista3; ?>">
			<?php 
				if ($alta) {
					echo '<input type="submit" id="busca_clavadista3" name="busca_clavadista3_sbm" value="Buscar" title="Busca el clavadista 3">			<input type="submit" id="nuevo_clavadista3" name="nuevo_clavadista3_sbm" value="Nuevo" title="Regístrate como clavadista">';
				}
				if ($imagen3) 
	    			echo "<img src='img/fotos/$imagen3' width='5%' height='70'/>&nbsp;&nbsp;";
				
    			if ($cod_clavadista3) {
					echo '<span class="cambio">'.$desc_sexo3.'</span>
						<span class="cambio">'.$edad3." años".'</span>'; 
    			}
 			?>
 			<input type="hidden" name="cod_clavadista3_hdn" value="<?php echo $cod_clavadista3; ?>">
			<input type="hidden" name="imagen3_hdn" value="<?php echo $imagen3; ?>">
			<input type="hidden" name="sexo3_hdn" value="<?php echo $sexo3; ?>">
			<input type="hidden" name="edad3" value="<?php echo $edad3; ?>">
		</div>
		<div>
			<label for="clavadista4" class="rotulo">Clavadista 4: </label>
			<input type="text" id="clavadista4" class="cambio" name="clavadista4_txt" placeholder="Si ya está registrado, escribe parte del nombre y Click en Buscar" title="nombre completo del clavadista 4 para equipo juvenil" size="50" maxlength="50" value="<?php echo $clavadista4; ?>">
			<?php 
				if ($alta) {
					echo '<input type="submit" id="busca_clavadista4" name="busca_clavadista4_sbm" value="Buscar" title="Busca el clavadista 4">			<input type="submit" id="nuevo_clavadista4" name="nuevo_clavadista4_sbm" value="Nuevo" title="Regístrate como clavadista">';
				}
				if ($imagen4) 
	    			echo "<img src='img/fotos/$imagen4' width='5%' height='70'/>&nbsp;&nbsp;";
				
    			if ($cod_clavadista4) {
					echo '<span class="cambio">'.$desc_sexo4.'</span>
						<span class="cambio">'.$edad4." años".'</span>'; 
    			}
 			?>
 			<input type="hidden" name="cod_clavadista4_hdn" value="<?php echo $cod_clavadista4; ?>">
			<input type="hidden" name="imagen4_hdn" value="<?php echo $imagen4; ?>">
			<input type="hidden" name="sexo4_hdn" value="<?php echo $sexo4; ?>">
			<input type="hidden" name="edad4" value="<?php echo $edad4; ?>">
		</div>
