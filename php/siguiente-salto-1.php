		<div>
			<span class="rotulo">Ronda: </span>
			<span><?php echo $ronda; ?></span>
			<span class="rotulo">/</span>
			<span><?php echo $rondas; ?></span>
			<span class="rotulo">Competidor:</span>
			<span><?php echo $orden_salida; ?></span>
			<span class="rotulo">/</span>
			<span ><?php echo $competidores; ?></span>
			<?php 
				if ($bandera) 
    				echo "<img class='textwrap'src='../img/banderas/".$bandera.".png' width='8%'/>&nbsp;&nbsp;";
				if ($imagen1) 
    				echo "<img class='textwrap'src='../img/fotos/$imagen1' width='20%'/>&nbsp;&nbsp;";
    			if ($imagen2) 
	    			echo "<img class='textwrap'src='../img/fotos/$imagen2' width='20%'/>";
			?>
		 	</span>
			<span class="rotulo">Categor&iacute;a:</span>
			<span><?php echo $nom_cat.'  '.$nom_sexo; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="rotulo">Acumulado: </span>
			<span><?php echo number_format($total,$dec)."&nbsp;&nbsp;";?></span>
			<span class="rotulo">Lugar: </span>
			<span><?php echo number_format($lugar,0); ?></span><br>
			<span><?php echo $nom_cla.' - '.$nom_equipo; ?></span>
			<span class="rotulo">Puntaje Salto: </span>
			<span><?php	echo number_format($puntaje_salto,$dec)."&nbsp;&nbsp;";
				?></span>
			<span><?php 
				if ($anunciador) {
					echo "$nom_salto</span>";
					echo '<div><input type="submit" name="actualizar_sbm" value="Actualizar"></div>';
				}
		 		?>
		 	</span>
			<?php 
				if ($calificado) {
					echo "<span class='rotulo'>Salto:</span>
						<span><?php echo $salto.'-'.$posicion?></span>
						<span><?php echo number_format($altura,1)?></span>
						<span class='rotulo'>gd.</span>
						<span><?php echo number_format($grado_dif,1) ?></span>";		
					echo "<br><br><div><table><tr>";
//						<td class'rotulo'>Calificaciones:</td>";
					$n=cantidad_jueces($cod_competencia,$numero_evento);
					if ($n>5)
						$clase="medio3";
					else
						$clase="medio";
//					for ($i=1; $i <=$n; $i++) {
					for ($i=1; $i <$n; $i++) {
						if (!is_null($cal[$i]))
							if ($cal[$i]!=$cal_cop[$i])
								echo "<td class='tachado ".$clase."'>&nbsp;&nbsp;".number_format($cal[$i],1)."&nbsp;</td>";
							else  
								echo "<td class='".$clase."'>".number_format($cal[$i],1)."&nbsp;</td>";
						else
							echo "<td>&nbsp;NC&nbsp;</td>";
					}
					echo "</tr></table></div>";
				}
				else{
					echo "<div><table><tr><td><span class='medio'>".$salto."-".$posicion."</span></td></tr>";	
					echo "<tr><td><span class='peque'>Altura: ". number_format($altura,1)."</span></td></tr><tr><td><span class='peque'>Grado Dif.: ".number_format($grado_dif,1)."</span></td></tr></table></div>";
				}
			 ?>
