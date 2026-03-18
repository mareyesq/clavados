		<div>
			<label for="competencia" class="rotulo">Competencia: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text" size="50" id="competencia" name="competencia_txt" placeholder="Nombre de tu competencia" title="Escribe el Nombre de tu Competencia, incluye el año" value="<?php echo $competencia; ?>" />
		</div>
		<div>
			<label for="pais" class="rotulo">Pa&iacute;s: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<select id="pais" class="cambio" name="pais_slc" onChange="this.form.submit()">
				<option value="" >- - -</option>	
				<?php include("php/select-pais.php"); ?>
			</select>
		</div>
		<div>
			<label for="ciudad" class="rotulo">Ciudad: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<select id="ciudad" class="cambio" name="ciudad_slc" title="Selecciona la ciudad sede de la Competencia" >
				<option value="">- - -</option>	
				<?php include("php/select-ciudad.php"); ?>
			</select>
		</div>
		<div>
			<label for="direccion" class="rotulo">Dirección: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text"  size="50" id="direccion" class="cambio" name="direccion_txt" placeholder="direccion de la sede" title="Escribe la direccion de la Sede de la Competencia" value="<?php echo $direccion; ?>" />
		</div>
		<?php 
			if(navegador_compatible("iPhone") || navegador_compatible("iPod")  || !navegador_compatible("chrome")){
				include("fechas-competencia-iphone.php");
			}
			else
				include("fechas-competencia.php");
		 ?>
		<div>
			<label for="convocatoria" class="rotulo">Convocatoria: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<div class="adjuntar-archivo cambio">
				<input type="file" id="convocatoria" name="convocatoria_fls" title="sube la Convocatoria de tu competencia"> 
				<input type="hidden" name="convocatoria_hdn" value="<?php echo $convocatoria; ?>" >

			</div>
		</div>
		<div>
			<label for="instructivo" class="rotulo">Instructivo: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<div class="adjuntar-archivo cambio">
				<input type="file" id="instructivo" name="instructivo_fls" title="sube el instructivo de tu competencia"> 
				<input type="hidden" name="instructivo_hdn" value="<?php echo $instructivo; ?>" >

			</div>
		</div>
		<div>
			<label for="organizador" class="rotulo">Organizador: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text"  size="50" id="organizador" class="cambio" name="organizador_txt" placeholder="Nombre de tu Organización" title="Escribe el Nombre de tu Entidad u Organización" value="<?php echo $organizador; ?>" />
		</div>
		<div>
			<label for="logo" class="rotulo">Logo Organizador: </label>&nbsp;&nbsp;
			<div class="adjuntar-archivo cambio">
				<input type="file" id="logo" name="logo_fls" title="sube el logo de tu Entidad u Organización"/>
				<input type="hidden" name="foto_hdn" value="<?php echo $imagen; ?>" >
			</div>
			&nbsp;&nbsp;
			<img src="<?php echo 'img/fotos/'.$imagen; ?>" width="8%"/>
		</div>

		<div>
			<label for="telefono" class="rotulo">Teléfono Contacto: </label>&nbsp;
			<input type="tel" id="telefono" class="cambio" name="telefono_txt" placeholder="Número de teléfono" title="Escribe el Número de Teléfono de Contacto" value="<?php echo $telefono; ?>" />
		</div>
		<div>
			<label for="email" class="rotulo">Email Contacto: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="email" id="email" class="cambio" name="email_txt" placeholder="email para contactarte" title="Escribe el email a donde te pueden contactar" value="<?php echo $email; ?>" />
		</div>

<!-- 		<div>
			<?php if (!$decimales) $decimales=2; ?>
			<label for="decimales" class="rotulo">Decimales en puntajes: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="number" id="decimales" class="cambio" min="0" max="4" step="1" name="decimales_nbr" title="Escribe la cantidad de decimales a usar en los puntajes" value="<?php echo $decimales; ?>" />
		</div>
 -->
		<div>
			<label for="b"  class="rotulo">Resultados: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" id="b" name="resultados_rdo" title="Los resultados los puede ver el público en general" value="B" <?php if ($resultados=='B') echo " checked"; ?> />&nbsp<label for="b">Públicos</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" id="v" name="resultados_rdo" title="Resultados de carácter privado, sólo usuarios registrados en esta competencia" value="V" <?php if ($resultados=='V') echo " checked"; ?> />&nbsp<label for="v">Privados</label>
		</div>

		<div>
			<label for="max-2" class="rotulo">Cada equipo, Maximo 2 competidores por evento : </label>
			<input id="max-2" type="checkbox" name="max_2_competidores_chk" value="max2" <?php if ($max_2_competidores==1) echo " checked"; ?> ><br>
		</div>
		<div>
			<label for="banderas" class="rotulo">Usar banderas de países: </label>
			<input id="banderas" type="checkbox" name="banderas_chk" <?php if ($banderas) echo " checked"; ?> ><br>
		</div>
		<div>
			<?php 
				if(navegador_compatible("iPhone") || navegador_compatible("iPod")  || !navegador_compatible("chrome")){
					include("fecha-edad-deportiva-iphone.php");
				}
				else
					include("fecha-edad-deportiva.php");
			?>
		</div>

<!-- 		<div>
			<label for="competencia2">Competencia Alterna: </label>
			<input type="text" id="competencia2" class="cambio" name="competencia2_txt" placeholder="Escribe el nombre de la competencia alterna" title="Nombre de la Competencia Alterna"  value="<?php echo $competencia2; ?>" />
			<input type="hidden" id="alta" name="alta_hdn" value="<?php echo $alta; ?>">
		</div>
 -->