<?php 
include ("funciones.php");

$email_err=$nombre_err=$nacimiento_err=$pregunta_err=$respuesta_err="";
$email=$nombre=$nacimiento=$pregunta=$respuesta="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["email"])) {
     $email_err = "debe ingresar el email";
   } else {
     $name = test_input($_POST["email"]);
   }

   if (empty($_POST["nombre"])) {
     $email_err = "debe ingresar el nombre";
   } else {
     $name = test_input($_POST["nombre"]);
   }

   if (empty($_POST["nacimiento"])) {
     $email_err = "debe ingresar la fecha de nacimiento";
   } else {
     $name = test_input($_POST["nacimiento"]);
   }

   if (empty($_POST["pregunta"])) {
     $email_err = "debe ingresar la pregunta secreta";
   } else {
     $name = test_input($_POST["pregunta"]);
   }
   if (empty($_POST["respuesta"])) {
     $email_err = "debe ingresar la respuesta a la pregunta secreta";
   } else {
     $name = test_input($_POST["respuesta"]);
   }

}   

?>

<form id="alta-usuario" name="alta-frm" action="<?php echo $pag;?>" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Alta de usuario</legend>
		<div>
			<label for="email">Email: </label>
			<input type="email" id="email" class="cambio" name="email_txt" placeholder="Escribe tu email" title="Tu email" value="<?php echo $email; ?>"  />
		</div>
		<div>
			<label for="nombre">Nombre: </label>
			<input type="text" id="nombre" class="cambio" name="nombre_txt" placeholder="Escribe tu nombre completo" title="Tu nombre completo" value="<?php echo $nombre;  ?>"   />
		</div>

		<div>
			<label for="nacimiento">Fecha de Nacimiento: </label>
			<input type="date" id="nacimiento" class="cambio" name="nacimiento_txt" title="Tu fecha de nacimiento" value="<?php echo $nacimiento; ?>" />
		</div>
		<div>
			<label for="pregunta">Pregunta Secreta: </label>
			<input type="text" id="pregunta" class="cambio" name="pregunta_txt" placeholder="Escribe tu pregunta secreta" title="Tu pregunta secreta" value="<?php echo $pregunta; ?>" />
		</div>
		<div>
			<label for="respuesta">Respuesta Secreta: </label>
			<input type="text" id="respuesta" class="cambio" name="respuesta_txt" placeholder="Escribe tu respuesta secreta" title="Tu respuesta secreta" value="<?php echo $respuesta; ?>"  />
		</div>
		<div>
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Agregar" />
		</div>
		<?php include("mensajes.php"); ?>

	</fieldset>
</form>