<?php 

// Asigno variables de PHP a los valores que vienen del formulario
//$competencia = isset($_POST["competencia_txt"]) ? $_POST["competencia_txt"] : null;
$equipo=isset($_POST["equipo_txt"]) ? $_POST["equipo_txt"] : null;
$representante=isset($_POST["representante_hdn"]) ? $_POST["representante_hdn"] : null;
$email=isset($_POST["email_hdn"]) ? $_POST["email_hdn"] : null;

// ejecuto función para tomar el código de la competencia
$cod_competencia=determina_competencia($competencia,$conexion);
$cod_equipo=determina_equipo($equipo,$conexion);
$cod_usuario=$_SESSION["usuario_id"];
$consulta="INSERT INTO competenciasq (competencia, equipo, representante, email, usuario_alta) VALUES ('$cod_competencia',$cod_equipo,'$representante', '$email', $cod_usuario)";

$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
if ($ejecutar_consulta){
	$mensaje="Se inscribió el equipo <b>$equipo</b> en la competencia <b>$competencia</b> :)";
}
else{
	$mensaje="No se pudo inscribir el equipo <b>$equipo</b> en la competencia <b>$competencia</b> :(";
}
	
$conexion->close();

header("Location: ../index.php?op=php/muestra-competencia.php&mensaje=$mensaje&com=$competencia");
?>