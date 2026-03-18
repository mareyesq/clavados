		<div>
			<span>Ronda: </span>
			<span class="rotulo"><?php echo $ronda; ?></span>
			<span><?php echo '/'.$rondas; ?> Competidor: </span>
			<span class="rotulo"><?php echo $orden_salida; ?></span>
			<span><?php echo '/'.$competidores; ?></span>
			<br>
			<span class="rotulo"><?php echo $nom_cla; ?></span>
			<?php 
				if ($imagen1) 
	    			echo "<img class='textwrap'src='img/fotos/$imagen1' width='5%'/>&nbsp;&nbsp;";
				if ($imagen2) 
	    			echo "<img class='textwrap'src='img/fotos/$imagen2' width='5%'/>";
			?>
			 </span>
			 <br>
			<span class="grande">Salto: </span>
			<span class="grande"><?php echo $salto.'-'.$posicion.' '.$nom_salto.', altura: '.number_format($altura,1).' grado dif. '.number_format($grado_dif,1) ?>
			</span>
			<br>
		</div>
