<?php 
	session_start();
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);
	if (isset($_POST["regresar_sbm"])){
		unset($_SESSION["fecha"]);
		unset($_SESSION["hora"]);
		unset($_SESSION["modalidad"]);
		unset($_SESSION["categorias"]);
		unset($_SESSION["sexos"]);
		unset($_SESSION["tipo"]);
		unset($_SESSION["primero_libres"]);
		unset($_SESSION["usa_dispositivos"]);
		unset($_SESSION["calentamiento"]);
		unset($_SESSION["participantes_estimado"]);
		header("Location: ..?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}
	$fechahora_original=isset($_POST["fechahora_original_hdn"])?$_POST["fechahora_original_hdn"]:NULL;

	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	$_SESSION["fechahora_original"]=$fechahora_original;
	
	include("funciones.php");
	include ("validacion-evento-competencia.php");

	if (is_null($mensaje)) {
		$conexion=conectarse();
		$consulta="UPDATE competenciaev 
			SET `fechahora`='$fechahora',
				`modalidad`='$modalidad',
				`sexos`='$sexos',
				`categorias`='$categorias',
				`tipo`='$tipo'";
		if ($primero_libres) {
			$consulta .= ", `primero_libres`='$primero_libres'";
		}
		else{
			$consulta .= ", `primero_libres`=NULL";
		}
		if ($usa_dispositivos) {
			$consulta .= ", `usa_dispositivos`='$usa_dispositivos'";
		}
		else{
			$consulta .= ", `usa_dispositivos`=NULL";
		}
		if ($calentamiento) {
			$consulta .= ", `calentamiento`='$calentamiento'";
		}
		else{
			$consulta .= ", `calentamiento`=NULL";
		}
		if ($participantes_estimado) {
			$consulta .= ", `participantes_estimado`='$participantes_estimado'";
		}
		else{
			$consulta .= ", `participantes_estimado`=NULL";
		}
		$consulta .= " WHERE (competencia=$cod_competencia 
			 AND  fechahora='$fechahora_original')";
				
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		if (!$ejecutar_consulta){
			$mensaje="Error: No puedo actualizar el evento para <b>$fechahora</b>";
		}
		else {
			unset($_SESSION["fecha"]);
			unset($_SESSION["hora"]);
			unset($_SESSION["modalidad"]);
			unset($_SESSION["categorias"]);
			unset($_SESSION["sexos"]);
			unset($_SESSION["tipo"]);
			unset($_SESSION["primero_libres"]);
			unset($_SESSION["usa_dispositivos"]);
			unset($_SESSION["calentamiento"]);
			unset($_SESSION["participantes_estimado"]);
			$conexion->close();
			renumera_eventos($cod_competencia);
			$mensaje="se actualizó el evento para <b>$fechahora<b> en la competencia";
			header("Location: ..?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
			exit();
		}
	}
	header("Location: ..?op=php/edita-evento-competencia.php&mensaje=$mensaje");
 ?>