		<div>
			<label for="nombre" class="rotulo">Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input type="text" id="nombre" class="cambio" name="nombre_txt" size="50" placeholder="Escribe tu nombre" title="Tu nombre" value="<?php echo $nombre; ?>" />
			<input type="hidden" name="llamo_hdn" value=" <?php echo $llamo; ?>">
		</div>
		<div>
			<label for="m" class="rotulo" >Sexo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input type="radio" id="m" name="sexo_rdo" title="Tu sexo" value="M" <?php if($sexo=="M") echo " checked"; ?> />&nbsp<label for="m">Masculino</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" id="f" name="sexo_rdo" title="Tu sexo" value="F" <?php if($sexo=="F") echo " checked"; ?> />&nbsp<label for="f">Femenino</label>
		</div>
		<div>
			<label for="nacimiento" class="rotulo">Fecha de Nacimiento (oblig.Clavadistas): </label>
			<?php 
				if(navegador_compatible("iPhone") || navegador_compatible("iPod")  || !navegador_compatible("chrome")){
					$fec=explode('-', $nacimiento);

					echo "&nbsp;";
					echo "<select id='dia-nac' class='cambio' name='dia_nac_slc' >
							<option value='' >- - -</option>";
					include("php/select-dia.php"); 
					echo "</select>";
					echo "&nbsp;<select id='mes-nac' class='cambio' name='mes_nac_slc' >
							<option value='' >- - -</option>";
					include("php/select-mes.php"); 
					echo "</select>";

					echo "&nbsp;<select id='ano-nac' class='cambio' name='ano_nac_slc' >
							<option value='' >- - -</option>";
					include("php/select-anio.php"); 
					echo "</select>";
				}
				else{
					echo "<input type='date' id='nacimiento' class='cambio' name='nacimiento_txt' title='Tu fecha de nacimiento' value='$nacimiento'/>";
				}
			 ?>
		</div>

		<div>
			<label for="administrador" class="rotulo">Tipo Usuario:&nbsp;&nbsp;&nbsp; </label>
			<input type="checkbox" id="administrador" class="cambio" name="administrador_chk" value="A" <?php if($administrador=='A') echo " checked"; ?>/>Administrador&nbsp;
			<input type="checkbox" id="clavadista" class="cambio" name="clavadista_chk" value="C" <?php if ($clavadista=='C') echo " checked"; ?>/>Clavadista
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" id="entrenador" class="cambio" name="entrenador_chk" value="E" <?php if ($entrenador=='E') echo " checked"; ?>/>Entrenador&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" id="juez" class="cambio" name="juez_chk" value="J" <?php if($juez=='J') echo " checked"; ?>/>Juez
			<span class="error"> <?php echo "$tipo_usuario_err" ?> 
			</span>
		</div>

		<div>
			<label for="pais" class="rotulo">Pa&iacute;s:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<select id="pais" class="cambio" name="pais_slc" >
				<option value="" >- - -</option>	
				<?php include("php/select-pais.php"); ?>
			</select>
			<span class="error"> <?php echo "$pais_err" ?> </span>
		</div>
		<div>
			<label for="email" class="rotulo">Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input type="email" id="email" class="cambio" name="email_txt" placeholder="Escribe tu email" title="Tu email" value="<?php echo $email; ?>" />
			<input type="hidden" name="cod_usuario_hdn" value="<?php echo $cod_usuario; ?>">
		</div>
		<div>
			<label for="telefono" class="rotulo">Tel&eacute;fono:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input type="tel" id="telefono" class="cambio" name="telefono_txt" placeholder="Escribe tu telefono" title="Tu teléfono" value="<?php echo $telefono; ?>" />
		</div>
		<div>
			<label for="password" class="rotulo">Contraseña:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input type="password" id="passw" class="cambio" name="passw_txt" placeholder="Escribe tu contraseña" title="Tu contraseña" value="<?php echo $password; ?>" />
		</div>
		<div>
			<label for="password_conf" class="rotulo">Confirmar Contraseña: </label>
			<input type="password" id="passw_conf" class="cambio" name="passw_conf_txt" placeholder="Confirma tu contraseña" title="Confirma tu contraseña" value="<?php echo $password_conf; ?>" />
		</div>
		<div>
			<label for="foto" class="rotulo">Foto:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<div class="adjuntar-archivo cambio">
			<input type="file" id="foto" name="foto_fls" title="sube tu foto" />
			<input type="hidden" name="foto_hdn" value="<?php echo $imagen; ?>" >
			</div>
			&nbsp;&nbsp;
			<img width="8%" src="<?php echo 'img/fotos/'.$imagen; ?>">
		</div>
