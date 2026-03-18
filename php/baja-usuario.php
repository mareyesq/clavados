<?php 
if (isset($_GET["us"])) {
	$cod_usuario=$_GET["us"];
	$llamo=trim($_GET["ori"]);
	$nombre=trim($_GET["nom"]);
	$email=trim($_GET["em"]);
}
session_start();
if (!$cod_usuario)
	$cod_usuario=trim($_SESSION["cod_usuario"]);

if (!$nombre)
	$nombre=trim($_SESSION["nombre"]);

if (!$email)
	$email=trim($_SESSION["email"]);

if (!$password)
	$password=trim($_SESSION["password"]);

if (!$llamo)
	$llamo=trim($_SESSION["llamo"]);

$mensaje=NULL;

if (!$_SESSION["autentificado"]){
	$mensaje="Para eliminar tu usuario, debes iniciar sesión";
	header("Location: ?op=php/inicia-sesion.php&mensaje=$mensaje");
	exit();
}
if (!isset($usuario_id))
	$usuario_id=trim($_SESSION["usuario_id"]);

if (!($cod_usuario==$usuario_id)) {
	$mensaje="Solamente el mismo usuario puede eliminar su registro del sistema";
	header("Location: ?php/".$llamo."&mensaje=$mensaje");
	exit();
}
?>
<form id="baja-usuario" name="baja-usuario-frm" action="php/elimina-usuario.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Eliminar Usuario</legend>
		<div>
			<label for="nombre">Nombre: </label>
			<input type="text" id="nombre" class="cambio" name="nombre_txt" value="<?php echo $nombre; ?>" readonly/>
		</div>	
		<div>
			<label for="email">Email: </label>
			<input type="text" id="email" class="cambio" name="email_txt" value="<?php echo $email; ?>" readonly/>
		</div>	
		<div>
			<label for="clave">Contraseña: </label>
			<input type="password" id="clave" class="cambio" name="password_txt" placeholder="Escribe la contraseña" title="Contraseña del Usuario" value="<?php echo $password; ?>" />
			<input type="hidden" name="cod_usuario_hdn" value="<?php echo $cod_usuario; ?> ">
			<input type="hidden" name="llamo_hdn" value="<?php echo $llamo; ?> ">
		</div>
		<div>
			<br>
			<input type="submit" id="eliminar" class="cambio" name="eliminar_btn" value="Eliminar" title="Elimina el usuario del Sistema" />
			<input type="submit" id="regresar" title="Regresa a lista de Usuarios" class="cambio" name="regresar_sbm" value="Regresar" />
		</div> 
	</fieldset>
</form>
