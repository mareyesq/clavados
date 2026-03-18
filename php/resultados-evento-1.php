		<div>
			<span class="rotulo">Ronda: </span>
			<span ><?php echo $ronda; ?></span>
			<span  class="rotulo">/</span>
			<span><?php echo $rondas; ?></span>
			<span class="rotulo"> Competidor: </span>
			<span ><?php echo $orden_salida; ?></span>
			<span class="rotulo">/</span>
			<span><?php echo $competidores; ?></span>
			<br>
			<span>
				<?php 
					echo $nom_cla.' - '.$nom_equipo; 
					if ($bandera) 
						echo "<img class='textwrap' src='../img/banderas/".$bandera.".png' width='6%'/>";
					
			?>
		
					</span>
			<?php 
				if ($imagen1) 
    				echo "<img class='textwrap'src='../img/fotos/$imagen1' width='5%'/>&nbsp;&nbsp;";
    			if ($imagen2) 
	    			echo "<img class='textwrap'src='../img/fotos/$imagen2' width='5%'/>";
    			if ($imagen3) 
	    			echo "<img class='textwrap'src='../img/fotos/$imagen3' width='5%'/>";
    			if ($imagen4) 
	    			echo "<img class='textwrap'src='../img/fotos/$imagen4' width='5%'/>";
			?>
		 	</span>
			<span class="rotulo">Categor&iacute;a: </span><span><?php echo $nom_cat.'  '.$nom_sexo; ?></span>
			<br>
			<span class="rotulo">Salto: </span>
			<span><?php echo $salto.'-'.$posicion.' '.$nom_salto.', altura: '.number_format($altura,1).' grado dif. '.number_format($grado_dif,1) ?>
			</span>
			<br>
			<span class="rotulo">Puntaje Salto: </span>
			<span >
				<?php 
					echo number_format($puntaje_salto,$dec)."&nbsp;&nbsp;";
				?>
			</span>
			<span class="rotulo">Acumulado: </span>
			<span><?php echo number_format($total,$dec);?>&nbsp;&nbsp;</span>
			<span class="rotulo">Lugar:</span>
			<span> <?php echo number_format($lugar,0);?></span>
			<?php 
				$total1=puntaje_primero($cod_competencia,$numero_evento,$sexo,$categoria);
				if ($total1>$total) {
				 	$faltan=$total1-$total;
				 	$acum1=$faltan/$grado_dif;
				 	$calif_estimada=$acum1/3;
				 	$calif_estimada=ajuste_calificacion($calif_estimada);
				 	if ($calif_estimada>10)
						$calif_estimada="+10... :(";
					else
						$calif_estimada=number_format($calif_estimada,1);
				} 
				else
				 	$calif_estimada=0;
			?>
			<span class="rotulo">Para ser primero:</span> 
			<span><?php echo $calif_estimada; ?></span>
			<?php 
				$promedio=calificacion_promedio($cod_cla,$salto,$posicion,$altura);
				if ($promedio) {
					echo '<span class="rotulo">Promedio Histórico: </span><span>'.number_format($promedio,1).'</span>';
				}
			 ?>
			
		</div>
		<div id='calificaciones'>
			<table>
				<tr>
					<td class="rotulo">Calificaciones:</td>
					<?php 
						if ($calificado) {
							$n=cantidad_jueces($cod_competencia,$numero_evento);
							for ($i=1; $i <=$n ; $i++) 
								if (!is_null($cal[$i]))
									if ($cal[$i]!==$cal_cop[$i])
										echo "<td class='tachado' class='rotulo'>&nbsp;&nbsp;".number_format($cal[$i],1)."&nbsp;</td>";
									else
										echo "<td>&nbsp;&nbsp;".number_format($cal[$i],1)."&nbsp;</td>";
								else
									echo "<td>&nbsp;NC&nbsp;</td>";
							
						}
					 ?>
				</tr>
			</table>
		</div>
