		<div>
			<span class="rotulo">Ronda: </span>
			<span><?php echo $ronda; ?></span>
			<span class="rotulo">/</span>
			<span><?php echo $rondas; ?></span>
			<span class="rotulo">Competidor: </span>
			<span><?php echo $orden_salida; ?></span>
			<span class="rotulo">/</span>
			<span><?php echo $competidores; ?></span>
			<br>
			<span><?php echo $nom_cla; ?></span>
			<?php 
				if ($imagen1) 
	    			echo "<img class='textwrap'src='img/fotos/$imagen1' width='5%'/>&nbsp;&nbsp;";
				if ($imagen2) 
	    			echo "<img class='textwrap'src='img/fotos/$imagen2' width='5%'/>";
			?>
			 </span>
			 <br>
			<span class="rotulo">Categoria: <?php echo $nom_cat.'  '.$nom_sexo; ?></span>
			<br>
			<span class="rotulo">Salto: </span>
			<span><?php echo $salto.'-'.$posicion.' '.$nom_salto;?></span>
			<span class="rotulo">Altura:</span>
			<span><?php echo number_format($altura,1);?></span>
			<span class="rotulo">Grado dif.</span>
			<span><?php echo number_format($grado_dif,1); ?></span>
			<br>
		</div>
		<span class="rotulo">Juez No: </span>&nbsp;
		<span><?php echo number_format($mi_ubicacion,0); ?></span>

		<label for="calificacion" class="rotulo">Mi Calificación:</label>
		<input type="number" min="0" max="10" step="0.5" id="calificacion" name="calificacion_num" value="<?php echo $calificacion; ?>" 
			<?php if ($protege)  echo " readonly"; ?>>
		<input type="hidden" id="calificacion" name="calificacion_hdn" value="<?php echo $calificacion; ?>">
