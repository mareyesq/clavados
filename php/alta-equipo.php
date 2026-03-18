<?php 
	include("php/conexion.php");
	include("php/funciones.php");
	session_start();
	if (!$_SESSION["autentificado"]){
		$mensaje="Debes iniciar sesión con tu usuario";
		header("Location: ../index.php?op=php/opciones-inscripcion-equipo.php&mensaje=$mensaje");
	}

	$competencia=$_SESSION["competencia"];

	$equipo=$_SESSION["equipo"];
	if (is_null($pais)){
		$pais=pais_usuario($conexion);
	} 
	else
		$pais=$_SESSION["pais"];

	$telefono=$_SESSION["telefono"];
	$representante=$_SESSION["representante"];
	$email=$_SESSION["email"];
	$nombre_corto=$_SESSION["nombre_corto"];
?>

<form id="alta-equipo" name="alta-frm" action="php/agrega-equipo.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Registrando un Nuevo Equipo</legend>
		<div>
			<label for="equipo">Nombre Equipo: </label>
			<input type="text" id="equipo" class="cambio" name="equipo_txt" placeholder="Nombre de tu equipo" title="Escribe el Nombre de tu Equipo" value="<?php echo $equipo; ?>"  />
			<span class="error"> <?php echo "$equipo_err" ?> </span>
		</div>
		<div>
			<label for="equipo">Nombre Corto: </label>
			<input type="text" id="equipo" class="cambio" name="nombre_corto_txt" placeholder="Nombre corto de tu equipo" title="Escribe el Nombre Corto de tu Equipo, máximo cinco letras" value="<?php echo $nombre_corto; ?>"  />
			<span class="error"> <?php echo "$nombre_corto_err" ?> </span>
		</div>
		<div>
			<label for="pais">Pa&iacute;s: </label>
			<select id="pais" class="cambio" name="pais_slc" >
				<option value="" >- - -</option>	
				<?php include("php/select-pais.php"); ?>
			</select>
			<input type="hidden" name="conexion_hdn" value="si" />
			<span class="error"> <?php echo "$pais_err" ?> </span>
		</div>

		<div>
			<label for="telefono">Teléfono: </label>
			<input type="tel" id="telefono" class="cambio" name="telefono_txt" placeholder="Número de teléfono" title="Escribe el Número de Teléfono" value="<?php echo $telefono; ?>" />
		</div>
		<div>
			<label for="organizador">Representante: </label>
			<input type="text" id="representante" class="cambio" name="representante_txt" placeholder="Nombre del Representante del Equipo" title="Escribe el Nombre de la persona que representa a tu equipo" value="<?php echo $representante; ?>"  />
			<span class="error"> <?php echo "$representante_err" ?> </span>
		</div>
		<div>
			<label for="email">Email: </label>
			<input type="email" id="email" class="cambio" name="email_txt" placeholder="email para contactarte" title="Escribe el email de este equipo" value="<?php echo $email; ?>" />
		</div>
		<div>
			<label for="password">Clave: </label>
			<input type="password" id="passw" class="cambio" name="passw_txt" placeholder="Define la clave para inscripciones de clavadistas y planillas" title="Tu contraseña" />
			<span class="error"> <?php echo "$passw_err" ?> </span>
		</div>

		<div>
			<label for="password_conf">Confirmar Clave: </label>
			<input type="password" id="passw_conf" class="cambio" name="passw_conf_txt" placeholder="Confirma la clave de inscripciones" title="Confirma la clave que definiste para inscripciones" />
			<span class="error"> <?php echo "$passw_conf_err" ?> </span>
		</div>

		<div>
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Agregar" />
			<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>