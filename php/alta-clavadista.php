<?php 
	session_start();
	$nombre=(isset($_SESSION["nombre"])?$_SESSION["nombre"]:NULL);
	$sexo=(isset($_SESSION["sexo"])?$_SESSION["sexo"]:NULL);
	$nacimiento=(isset($_SESSION["nacimiento"])?$_SESSION["nacimiento"]:NULL);
	$email=(isset($_SESSION["email"])?$_SESSION["email"]:NULL);
	$pais=(isset($_SESSION["pais"])?$_SESSION["pais"]:NULL);
	$telefono=(isset($_SESSION["telefono"])?$_SESSION["telefono"]:NULL);

	$nombre_err=(isset($_SESSION["nombre_err"])?$_SESSION["nombre_err"]:NULL);
	$sexo_err=(isset($_SESSION["sexo_err"])?$_SESSION["sexo_err"]:NULL);
	$nacimiento_err=(isset($_SESSION["nacimiento_err"])?$_SESSION["nacimiento_err"]:NULL);
	$pais_err=(isset($_SESSION["pais_err"])?$_SESSION["pais_err"]:NULL);

?>
<form id="alta-clavadista" name="alta-frm" action="agrega-clavadista.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Alta de clavadista</legend>
		<div>
			<label for="nombre">Nombre: </label>
			<input type="text" id="nombre" class="cambio" name="nombre_txt" placeholder="Escribe tu nombre completo" title="Tu nombre completo" value="<?php echo '$nombre' ?>" required />
		</div>
		<div>
			<label for="m" >Sexo: </label>
			<input type="radio" id="m" name="sexo_rdo" title="Tu sexo" value="M" required />&nbsp<label for="m">Masculino</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" id="f" name="sexo_rdo" title="Tu sexo" value="F" required />&nbsp<label for="f">Femenino</label>
		</div>
		<div>
			<label for="nacimiento">Fecha de Nacimiento: </label>
			<input type="date" id="nacimiento" class="cambio" name="nacimiento_txt" title="Tu fecha de nacimiento" required />
		</div>
		<div>
			<label for="pais">Pa&iacute;s: </label>
			<select id="pais" class="cambio" name="pais_slc">
				<option value="">- - -</option>	
				<?php include("php/conexion.php"); include("php/select-pais.php"); ?>
			</select>
			<input type="hidden" name="conexion_hdn" value="si" />
		</div>
		<div>
			<label for="email">Email: </label>
			<input type="email" id="email" class="cambio" name="email_txt" placeholder="Escribe tu email" title="Tu email" required/>
		</div>
		<div>
			<label for="telefono">Tel&eacute;fono: </label>
			<input type="tel" id="telefono" class="cambio" name="telefono_txt" placeholder="Escribe tu telefono" title="Tu teléfono" required />
		</div>
		<div>
			<label for="password">Contraseña: </label>
			<input type="password" id="passw" class="cambio" name="passw_txt" placeholder="Escribe tu contraseña" title="Tu contraseña" required />
		</div>

		<div>
			<label for="password_conf">Confirmar Contraseña: </label>
			<input type="password" id="passw_conf" class="cambio" name="passw_conf_txt" placeholder="Confirma tu contraseña" title="Confirma tu contraseña" required />
		</div>
		<div>
			<label for="foto">Foto: </label>
			<div class="adjuntar-archivo cambio">
				<input type="file" id="foto" name="foto_fls" title="sube tu foto"/>
			</div>
		</div>
		<div>
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Agregar" />
		</div>
		<?php include("php/mensajes.php"); ?>

	</fieldset>
</form>