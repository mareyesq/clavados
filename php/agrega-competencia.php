<?php 

include("funciones.php");
// Asigno variables de PHP a los valores que vienen del formulario
session_start();
if (isset($_POST["regresar_sbm"])){
	header("Location: ..?op=php/todas-competencias.php");
	exit();
}

$alta=1;
include("valida-competencia.php");
if (is_null($mensaje) and $_POST["enviar_btn"]){
	// ejecuto función para tomar el código del país
	$cod_pais=determina_pais($pais);
	$cod_ciudad=determina_ciudad($ciudad,$cod_pais);
	$limite="$ano_lim-$mes_lim-$dia_lim $hh_lim:$mm_lim:00.000";
	$ahora=ahora($cod_ciudad);
//		$ahora = date('Y-m-d H:i:s');
	// ejecuto función para subir la convocatoria
	$tipo=$_FILES["convocatoria_fls"]["type"];
	$archivo=$_FILES["convocatoria_fls"]["tmp_name"];
	$destino="convoca";
	$se_subio_conv=subir_archivo($tipo,$archivo,$competencia,$destino);
	$nombre_convocatoria=empty($archivo)?$convocatoria:$se_subio_conv;

	// ejecuto función para subir el instructivo
	$tipo=$_FILES["instructivo_fls"]["type"];
	$archivo=$_FILES["instructivo_fls"]["tmp_name"];
	$destino="instructivo";
	$se_subio_inst=subir_archivo($tipo,$archivo,$competencia,$destino);
	$nombre_instructivo=empty($archivo)?$instructivo:$se_subio_inst;

	// ejecuto función para subir la imagen del logo
	$tipo=$_FILES["logo_fls"]["type"];
	$archivo=$_FILES["logo_fls"]["tmp_name"];
	$se_subio_imagen=subir_imagen($tipo,$archivo,$competencia);
		// Si la foto en el formulario viene vacía, se deja vacía, sino entonces el nombre de la foto que se subio (operador ternario)
	$imagen=empty($archivo)?"":$se_subio_imagen;

	$conexion=conectarse();
	$consulta="INSERT INTO competencias 
		(competencia, ciudad, fecha_inicia, fecha_termina, limite_inscripcion, organizador, logo_organizador, telefono, email, direccion_sede, estado, fecha_hora_grabacion";
	if ($resultados)
		$consulta.=", public_result";
	if ($nombre_convocatoria)
		$consulta.=", convocatoria)";
	if ($nombre_instructivo)
		$consulta.=", instructivo)";
	if ($competencia2)
		$consulta.=", competencia2)";
	if ($max_2_competidores)
		$consulta.=", max_2_competidores";
	if ($fecha_edad_deportiva)
		$consulta.=", fecha_edad_deportiva";
	
	$consulta.=") VALUES ('$competencia',$cod_ciudad,'$inicia','$termina', '$limite', '$organizador', '$imagen', '$telefono', '$email', '$direccion' ,'P', '$ahora'";
	if ($resultados)
		$consulta.=", '$resultados'";
	if ($nombre_convocatoria)
		$consulta.=", '$nombre_convocatoria'";
	if ($nombre_instructivo)
		$consulta.=", '$nombre_instructivo'";
	if ($competencia2)
		$consulta.=", $competencia2";

	if ($max_2_competidores)
		$consulta.=", $max_2_competidores";

	if ($fecha_edad_deportiva)
		$consulta.=", '$fecha_edad_deportiva')";
	else
		$consulta.=")";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

	if ($ejecutar_consulta){
		$rec=$conexion->insert_id;
		$cod_usuario=$_SESSION["usuario_id"];
		$consulta="INSERT INTO competenciasa (competencia, administrador, principal) VALUES ($rec, $cod_usuario, 1)";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		$mensaje="Se registró la competencia <b>$competencia</b> :)";
		$conexion->close();

		notifica_alta_competencia($competencia,$organizador,$email);

		header("Location: ..?op=php/todas-competencias.php&mensaje=$mensaje");
		exit();
	}
	else{
		$mensaje="No se pudo registrar la competencia <b>$competencia</b> :(";
	}
	$conexion->close();
}
header("Location: ..?op=php/alta-competencia.php&mensaje=$mensaje");
?>