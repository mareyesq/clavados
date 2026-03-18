<?php 
	include("funciones.php");
	session_start();
	$competencia=$_SESSION["competencia"];
	if (!$_SESSION["autentificado"]){
		$mensaje="Para registrar una nueva competencia, debes iniciar sesión";
		$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
		$llamar="alta-competencia.php";
		$llamados["inicia-sesion.php"]=$llamar;
		$_SESSION["llamados"]=$llamados;
		header("Location: ?op=php/inicia-sesion.php&mensaje=$mensaje");
		exit();
	}


	$pais=$_SESSION["pais"];
	if (is_null($pais))
		$pais=pais_usuario();
	

	$ciudad=$_SESSION["ciudad"];
	$direccion=$_SESSION["direccion"];
	$inicia=$_SESSION["inicia"];
	$termina=$_SESSION["termina"];
	$fecha_limite=$_SESSION["fecha_limite"];
	$hora_limite=$_SESSION["hora_limite"];
	$organizador=$_SESSION["organizador"];
	$logo=$_SESSION["logo_organizador"];
	$telefono=$_SESSION["telefono_contacto"];
	$email=$_SESSION["email_contacto"];
	$competencia2=$_SESSION["competencia2"];
	$resultados=$_SESSION["resultados"];
	$decimales=$_SESSION["decimales"];
	$max_2_competidores=$_SESSION["max_2_competidores"];
	$fecha_edad_deportiva=$_SESSION["fecha_edad_deportiva"];
	$banderas=$_SESSION["banderas"];
	$convocatoria=$_SESSION["convocatoria"];
	$instructivo=$_SESSION["instructivo"];
	$alta=1;
	$desde_anio=1900;
	$hasta_anio=date("Y");
	$ord="d";

?>

<form id="alta-competencia" name="alta-frm" action="php/agrega-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center">Registrando una Nueva Competencia</legend>
			<?php include ("formulario-competencia.php"); ?>
		<div>
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Agregar" />
			<input type="submit" id="regresar" title="Regresa a Competencias" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>