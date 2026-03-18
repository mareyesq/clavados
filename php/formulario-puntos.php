		<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
			<input type='submit' id='regla-fina' class='cambio' name='regla-fina_btn' value='Reglamento FINA' />
			<thead align="center">
				<tr>
					<th>Puesto</th>
					<th>Puntos Modalidad Individual</th>
					<th>Puntos Modalidad Sincronizado</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					for ($i=1; $i <=12 ; $i++) { 
						$puntaje=explode("/", $puntos[$i]);	
						if (!$puntaje[0]==null){
							$puesto=$puntaje[0];
							$individual=$puntaje[1];
							$sincro=$puntaje[2];
						}
						else{
							$puesto=null;
							$individual=null;
							$sincro=null;
						}

						echo "<tr align='center'>";
						$name="pue"."$i"."_hdn";
						echo "<td width='2%' align='center'><input type='hidden' name='$name' value='$puesto' />$puesto</td>";
						$name="ind"."$i"."_txt";
						echo "<td width='2%' align='center'><input type='number' name='$name' value='$individual'/> 
						 		</td>";
						$name="sin"."$i"."_txt";
						echo "<td width='2%' align='center'><input type='number' name='$name' value='$sincro'/> 
						 		</td></tr>";
					}
				 ?>

			</tbody>
		</table>
		<div>
			<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
			<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
			<input type="hidden" id="logo" name="logo_hdn" value="<?php echo $logo; ?>" />
			<input type="hidden" id="puntos" name="puntos_hdn" value="<?php echo "$puntos"; ?>" />
			<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo; ?>" />
		</div>
		<div>
			<input type='submit' id='grabar-puntos' class='cambio' name='guarda_btn' value='Guardar' />
			<input type='submit' id='regresar' title='Regresar' class='cambio' name='regresar_sbm' value='Regresar' />
		</div>
