<form id="cambios-categoria" name="cam-cat-frm" action="modificar-categoria.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Modifica Categor&iacute;a</legend>
		<div>
			<label for="cod_categoria">C&oacute;digo Categor&iacute;a: </label>
			<input type="text" id="codcategoria" class="cambio" name="codcategoria_txt" placeholder="Escribe el C&oacute;digo de la Categor&iacute;a" title="Código de Categoría" value="<?php echo $registro_principal["codcategoria"]; ?>" required/>
			<input type="hidden" name="codcategoria_hdn" value="<?php echo $registro_principal["codcategoria"]; ?>">
		</div>
		<div>
			<label for="descripcion">Descripci&oacute;n: </label>
			<input type="text" id="descripcion" class="cambio" name="descripcion_txt" placeholder="Describe la Categor&iacute;a" title="Categoría" value="<?php echo $registro_principal["descripcion"]; ?>" required />
		</div>
		<div>
			<label for="edaddesde">Edad Desde: </label>
			<input type="text" id="edaddesde" class="cambio" name="edaddesde_txt" placeholder="Edad inicio" title="Edad en que inicia la categoría" value="<?php echo $registro_principal["edaddesde"]; ?>" required />
		</div>
		<div>
			<label for="edadhasta">Edad Hasta: </label>
			<input type="text" id="edadhasta" class="cambio" name="edadhasta_txt" placeholder="Edad termina" title="Edad en que termina la categoría" value="<?php echo $registro_principal["edadhasta"]; ?>"required />
		</div>
		<div>
			<label for="s" >Verifica Edad: </label>
			<input type="radio" id="s" name="verificaedad_rdo" title="Sí verifica edad" value="sí" <?php if(!$registro_principal["verificaedad"]==0) echo " checked"; ?> required />&nbsp<label for="s">S&iacute;</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" id="n" name="verificaedad_rdo" title="No" value="no" <?php if($registro_principal["verificaedad"]==0) echo " checked"; ?>required />&nbsp<label for="n">No</label>
		</div>
		<div>
			<label for="tipocategoria">Tipo de Categor&iacute;a: </label>
			<input type="text" id="tipocategoria" class="cambio" name="tipocategoria_txt" title="Local, Nacional, FINA" value="<?php echo $registro_principal["tipocategoria"]; ?>"required />
		</div>
		<div>
			<label for="i" >Uso de Categor&iacute;a: </label>
			<input type="radio" id="i" name="categoria_ind_sin_rdo" title="Individual" value="i" <?php if(!$registro_principal["individual"]==0) echo " checked"; ?> required />&nbsp<label for="i">Individual</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" id="s" name="categoria_ind_sin_rdo" title="Sincronizado" value="s" <?php if($registro_principal["individual"]==0) echo " checked"; ?>required />&nbsp<label for="s">Sincronizado</label>
		</div>

		<div>
			<input type="submit" id="enviar-cambios" class="cambio" name="enviar_btn" value="Cambiar" />
		</div>
		<?php include("mensajes.php"); ?>
	</fieldset>
</form>
