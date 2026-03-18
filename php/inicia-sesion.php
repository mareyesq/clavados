<?php 
	session_start();
	$email=isset($_SESSION["email"])?$_SESSION["email"]:NULL;
	$passw=isset($_SESSION["email"])?$_SESSION["email"]:NULL;
	$competencia=$_GET["com"];
?>
<form id="inicia-sesion" name="inicio-frm" action="php/control-sesion.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Inicia tu Sesión</legend>
		<table>
			<tr>
				<td align="right" ><label for="email">Email: </label></td>
				<td><input type="email" id="email" class="cambio" name="email_txt" placeholder="Escribe tu email" title="Tu email" value="<?php echo "$email"; ?>"></td>
			</tr>
			<tr>
				<td align="right"><label for="passw">Contraseña: </label></td>
				<td><input type="password" id="passw" class="cambio" name="passw_txt" placeholder="Escribe tu contraseña" title="Tu contraseña" value="<?php echo '$passw';?>"></td>
			</tr>
		</table>
		<div>
			<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo "$competencia"; ?>" />
		</div>
		<div>
			<br>
			<input type="submit" id="inicio-sesion" class="cambio" name="enviar_sbm" value="Inicia Sesión" />
			<input type="submit" id="recordar" class="cambio" name="recordar_sbm" value="Recordar contraseña" title="Olvidaste tu contraseña? te ayudamos recordarla" />
			<input type="submit" id="registrar" class="cambio" name="registrar_sbm" value="Registrate" title="Regístrate como usuario para poder participar en las competencias o configurar tu propia competencia" />
			<input type="submit" id="actualizar" class="cambio" name="actualizar_sbm" value="Actualiza" title="Actualiza la información general de tu usuario" />
		</div>
	</fieldset>
</form>