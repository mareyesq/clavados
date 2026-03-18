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
			<span><?php echo $nom_cla.' - '.$nom_equipo; ?></span>
			<?php 
				if ($bandera) 
	    			echo "<img class='textwrap' src='img/banderas/".$bandera.".png' width='3%'/>";
				if ($imagen1) 
	    			echo "<img class='textwrap' src='img/fotos/$imagen1' width='5%'/>&nbsp;&nbsp;";
				if ($imagen2) 
	    			echo "<img class='textwrap' src='img/fotos/$imagen2' width='5%'/>";
			?>
			 </span>
			<span class="rotulo">Categoria:</span>
			<span><?php echo $nom_cat.'  '.$nom_sexo; ?></span>
			<br>
			<span class="rotulo">Salto: </span>
			<span><?php echo $salto.'-'.$posicion.' '.$nom_salto?></span>
			<span class="rotulo">Altura:</span>
			<span><?php echo number_format($altura,1) ?></span>
			<span class="rotulo">Grado dif.</span>
			<span><?php echo number_format($grado_dif,1) ?></span>
			<br>
			<span class="rotulo">Suma</span>
			<span>
				<?php 
					echo number_format($suma,$dec)."&nbsp;&nbsp;";
				?>
			</span>

			<span class="rotulo">Puntaje: </span>
			<span>
				<?php 
					echo number_format($puntaje_salto,$dec)."&nbsp;&nbsp;";
				?>
			</span>

			<span class="rotulo">Acumulado: </span>
			<span><?php echo number_format($total,$dec)."&nbsp;&nbsp;";?></span>
			<span class="rotulo">Lugar:</span>
			<span><?php echo number_format($lugar,0); ?></span>
			<?php 
				if ($total1>$total) {
				 	$faltan=$total1-$total;
				 	$acum1=$faltan/$grado_dif;
				 	$calif_estimada=$acum1/3;
				 	$calif_estimada=ajuste_calificacion($calif_estimada);
				} 
				else
				 	$calif_estimada=0;
			?>
			<span class="rotulo">Para ser primero:</span> 
			<span><?php echo number_format($calif_estimada,1); ?></span>
		</div>
		<?php
			if (!$inicio) 
				echo "<span class='rotulo'>Puedes iniciar la competencia<br></span>";
			else
				if ($num_jueces>0)
					include("calificaciones-jueces.php");
				else
					$mensaje="Debe definir los jueces de la Competencia";
		 ?>