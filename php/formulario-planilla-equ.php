<?php 

	$mixto=NULL;
	if (isset($cod_sexo)) {
		switch ($cod_sexo) {
			case 'F':
				$sexo="Femenino";
				break;
			case 'M':
				$sexo="Masculino";
				break;
			
		}
	}
	if (isset($cod_sexo2)) {
		switch ($cod_sexo2) {
			case 'F':
				$sexo2="Femenino";
				break;
			case 'M':
				$sexo2="Masculino";
				break;
			
		}
		if ($cod_sexo!==$cod_sexo2)
			$mixto=TRUE;
	}
 ?>

		<div>
			<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>"/>
			<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>"/>
			<input type="hidden" id="logo" name="logo_hdn" value="<?php echo $logo; ?>"/>
			<label for="equipo" class="rotulo">Equipo: </label>
			<input type="text" id="equipo" class="cambio" name="equipo_txt" placeholder="Escribe el nombre de tu equipo" title="Nombre de tu equipo" value="<?php echo $equipo; ?>" readonly/>
			<input type="hidden" name="cod_equipo_hdn" value="<?php echo $cod_equipo; ?>">
			<label for="entrenador" class="rotulo">Entrenador: </label>
			<input type="text" id="entrenador" class="cambio" name="entrenador_txt" placeholder="Escribe el nombre de tu entrenador" title="Nombre de tu entrenador" value="<?php echo $entrenador; ?>"   <?php if ($ok) echo " readonly"; ?>>
			<input type="hidden" name="cod_entrenador_hdn" value="<?php echo $cod_entrenador; ?>">
		</div>
		<div>
			<?php 
				if ($imagen) 
	    			echo "<img src='img/fotos/$imagen' width='5%' height='60'/>&nbsp;&nbsp;";
	 		?>
			<label for="clavadista" class="rotulo">Clavadista 1: </label>
			<input type="text" id="clavadista" class="cambio" name="clavadista_txt" placeholder="Escribe tu nombre completo" title="Tu nombre completo" value="<?php echo $clavadista; ?>" <?php if ($ok or !$alta) echo " readonly"; ?>>
			<input type="hidden" name="cod_clavadista_hdn" value="<?php echo $cod_clavadista; ?>">
			<input type="hidden" name="imagen_hdn" value="<?php echo $imagen; ?>">
			<span  class="rotulo">Nació: <?php $fn=substr($nacimiento, 0,4); $ed=edad_deportiva($nacimiento); echo "$fn&nbsp&nbsp Edad: $ed&nbsp&nbspSexo: $sexo" ; ?> 
			</span>
			<input type="hidden" name="edad_hdn" value="<?php echo $ed; ?>" >
			<input type="hidden" name="cod_sexo_hdn" value="<?php echo $cod_sexo; ?>" >
		</div>
		<div>
			<?php 
				if ($imagen2) 
	    			echo "<img src='img/fotos/$imagen2' width='5%' height='60'/>&nbsp;&nbsp;";
	 		?>
			<label for="clavadista2" class="rotulo">Clavadista 2: </label>
			<input type="text" id="clavadista2" class="cambio" name="clavadista2_txt" placeholder="Escribe el nombre completo" title="nombre del segundo clavadista del sincronizado" value="<?php echo $clavadista2; ?>" <?php if ($ok or !$alta) echo " readonly"; ?>>
			<?php 
				if ($alta) {
					echo '<input type="submit" id="busca_clavadista" name="busca_clavadista_sbm" value="Buscar" title="Busca el clavadista">';						}
			 ?>
			
			<input type="hidden" name="cod_clavadista2_hdn" value="<?php echo $cod_clavadista2; ?>">
			<input type="hidden" name="imagen2_hdn" value="<?php echo $imagen2; ?>">
			<input type="hidden" name="buscar_hdn" value="<?php echo $buscar; ?>">
			<?php 
				$fn2=isset($nacimiento2)?substr($nacimiento2,0,4):NULL;
				$ed2=isset($nacimiento2)?edad_deportiva($nacimiento2):NULL;
			?>
			<span class="rotulo">Nació: <?php echo "$fn2&nbsp&nbsp Edad: $ed2&nbsp&nbspSexo: $sexo2" ; ?></span>
			<input type="hidden" name="edad2_hdn" value="<?php echo $ed2; ?>" >
			<input type="hidden" name="cod_sexo2_hdn" value="<?php echo $cod_sexo2; ?>" >
		</div>

		<div>
			<label for="categoria" class="rotulo">Categoría: </label>
			<?php 
				
				if ($ok) echo "<input type='text' name='categoria_slc' value='$categoria' readonly>";
				else {
					echo "<select id='categoria' class='cambio' name='categoria_slc' title='selecciona la categoría para esta planilla' >
						<option value='' >- - -</option>";
					include("php/select-categoria.php"); 
					echo "</select>";
				}
			 ?>

			<label for="modalidad" class="rotulo">Modalidad: </label>
			<?php 
				if ($ok) echo "<input type='text' name='modalidad_slc' value='$modalidad' readonly>";
				else {
					echo "<select id='modalidad' class='cambio' name='modalidad_slc' title='selecciona la modalidad para esta planilla'><option value='' >- - -</option>";
					$cod_modalidad=$modalidad;
					include("php/select-modalidad.php"); 
					echo "</select>";
				}
			 ?>
			<span class="rotulo">Reglamento: <?php echo "$reglamento" ; ?> 
			</span>
			<div>
				<label for="clave" class="rotulo">Clave Inscripción: </label>
				<input type="password" id="clave" class="cambio" name="clave_txt" placeholder="Escribe la clave de inscripción" title="Clave de inscripción de tu equipo para esta competencia" value="<?php echo $clave; ?>" />
				<span  class="rotulo"><?php echo $dificultad; ?></span>
			</div>
			<div>
				<label for="participa-extraoficial" class="rotulo">Participa Extraoficial: </label>
				<input id="participa-extraoficial" type="checkbox" name="participa_extraof_chk" value="*" <?php if ($participa_extraof=='*') echo " checked"; ?>>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php 
					if ($ok==1) {
						echo "<input type='submit' id='modificar-planilla' class='cambio' 	name='modifica_btn' value='Modificar' />
						<input type='submit' id='grabar-planilla' class='cambio' name='guarda_btn' value='Guardar' />";
					}
					else{
						echo "<input type='submit' id='verificar-planilla' class='cambio' name='verifica_btn' value='Verificar' />";
					}
					$n=count($copia_saltos);
					if ($n>0) {
						$linea=$copia_saltos[1];
						$salto1=$linea["cod_salto"];
						if ($salto1){
							echo "&nbsp;<input type='submit' id='pega-planilla' class='cambio' name='pegar_btn' value='Pegar' title='Pega los saltos de la planilla guardada' />";
							echo "&nbsp;<input type='submit' id='cancela-copia' class='cambio' name='cancela_copia_btn' value='Cancela Copia' title='Cancela la copia de los saltos de la planilla guardada' />";
							echo "&nbsp;&nbsp;";
						} 
					}
					else
						echo "&nbsp;<input type='submit' id='copia-planilla' class='cambio' name='copiar_btn' value='Copiar' title='Copia los saltos de esta planilla' />&nbsp;&nbsp";

					echo "&nbsp;<input type='submit' id='regresar' title='Regresar' class='cambio' name='regresar_sbm' value='Regresar' />";
				?>
				<br>
			</div>

		</div>
	<fieldset width="100%">
		<div id="div1">
		<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">

			<caption><b>Saltos a Ejecutar (en Ejecutor, coloca 1 o 2 según el clavadista que hace el salto)<b></caption>
			<thead align="center">
				<tr>
					<th width="1%">No.</th>
					<th width="2%">Código</th>
					<th width="1%">Pos.</th>
					<th width="30%">Salto</th>
					<th width="1%">Alt.</th>
					<th width="1%">Obligatorio</th>
					<th width="5%">Grado Dif.</th>
					<th width="5%">Ejecutor</th>
					<th width="5%">Clavadista</th>
				</tr>
			</thead>
			<tbody>
				<?php 

					for ($i=1; $i <=11 ; $i++) { 
						$salto=$saltos[$i];	

						if ($salto["cod_salto"]){
							$cod=$salto["cod_salto"];
							$pos=$salto["pos"];
							$alt=$salto["alt"];
							$obl=$salto["obl"];
							$sal=$salto["sal"];
							$dif=number_format($salto["gra_dif"],1);
							$eje=$salto["eje"];
							$nej=$salto["nej"];
						}
						else{
							$cod=NULL;
							$pos=NULL;
							$alt=NULL;
							$obl=NULL;
							$sal=NULL;
							$dif=NULL;
							$eje=NULL;
							$nej=NULL;
						}

						echo "<tr><td width='1%' align='right'><a href='?op=php/busca-salto.php&ron=$i&ori=$llamo'>$i</a></td>";
						$name="cod"."$i"."_txt";
						echo "<td width='2%' align='left'>
									<input type='text' name='$name' size='5' value='$cod'"; 
						if ($ok) echo " readonly";
						$name="cod"."$i"."_sbm";
						echo "></td>";

						$name="pos"."$i"."_slc";
						echo "<td width='1%' align='center'>
										<select name='$name' align='center' >
											<option ></option> 
											<option ";
						if ($pos=="A") echo " selected";
						echo ">A</option> 
											<option ";
						if ($pos=="B") echo " selected"; 
						echo ">B</option> 
											<option "; 
						if ($pos=="C") echo " selected"; 
						echo ">C</option> 
											<option ";
						if ($pos=="D") echo " selected";
						echo ">D</option> 
										</select>
									</td>";
						echo "<td width='30%' >$sal</td>";

						$name="alt"."$i"."_slc";
						echo "<td width='1%' align='center'><select name='$name' align='center'><option ></option><option ";

						if ($alt==0) echo " selected";
						echo ">0</option> 
										<option ";
						if ($alt==0.75) echo " selected";
						echo ">0.75</option> 
										<option ";
						if ($alt==1) echo " selected"; 
						echo ">1</option> 
										<option ";
						if ($alt==2) echo " selected"; 
						echo ">2</option> 
										<option ";
						if ($alt==3) echo " selected"; 
						echo ">3</option> 
										<option ";
						if ($alt==5) echo " selected"; 
						echo ">5</option> 
										<option ";
						if ($alt==7.5) echo " selected"; 
						echo ">7.5</option> 
										<option ";

						if ($alt==10) echo " selected"; 
						echo ">10</option> 
									</select>
								</td>";

						$name="obl"."$i"."_rdo";
						echo "<td width='1%' align='center'>
									<input type='checkbox' name='$name'  value='$abi'";
						if ($obl) 
							echo " checked";  
						if ($ok) 
							echo " readonly"; 
						echo "></td>";

						echo "<td width='5%'  align='center'>$dif</td>";

						$name="eje"."$i"."_nbr";
						echo "<td width='2%' align='left'>
									<input type='number' min='1' max='2' step='1' name='$name' value='$eje'"; 
						if ($ok) 
							echo " readonly";
						echo "></td>";

						echo "<td width='30%' >$nej</td></tr>";

						
					}
				 ?>

			</tbody>
		</table>
		</div>
		<div>
			<input type="hidden" id="alta" name="alta_hdn" value="<?php echo "$alta"; ?>" />
		</div>
	</fieldset>