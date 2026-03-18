<?php 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);
	session_start();
	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
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
		header("Location: ..?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}
	include("funciones.php");
	include ("validacion-evento-competencia.php");

	if (is_null($mensaje)) {
		$conexion=conectarse();
		$consulta="SELECT MAX(numero_evento) AS mx_evento 
			FROM competenciaev
			WHERE competencia=$cod_competencia";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		if ($ejecutar_consulta) {
			$num_regs=$ejecutar_consulta->num_rows;
			if ($num_regs>0)
				$row=$ejecutar_consulta->fetch_assoc();	
				$n=$row["mx_evento"]+1;
		}
		if (is_null($n)) $n=1;

		$consulta="INSERT INTO competenciaev (competencia, fechahora, numero_evento, modalidad, sexos, categorias, tipo, primero_libres, usa_dispositivos, calentamiento) VALUES ($cod_competencia, '$fechahora', $n, '$modalidad', '$sexos', '$categorias', '$tipo', '$primero_libres', '$usa_dispositivos'";
		if ($calentamiento){
			$consulta.=", $calentamiento";
		}
		else{
			$consulta.=", NULL";
		}
		$consulta .= ")";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		if ($ejecutar_consulta){
			unset($_SESSION["fecha"]);
			unset($_SESSION["hora"]);
			unset($_SESSION["modalidad"]);
			unset($_SESSION["categorias"]);
			unset($_SESSION["sexos"]);
			unset($_SESSION["tipo"]);
			unset($_SESSION["primero_libres"]);
			unset($_SESSION["usa_dispositivos"]);
			unset($_SESSION["calentamiento"]);
			$mensaje="se dió de alta el evento <b>$modalidad de $categorias<b> en la competencia";
			$conexion->close();
			header("Location: ..?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
			exit();
		}
		else{
			$mensaje="No se pudo dar de alta el evento <b>$modalidad de $categorias</b> en la competencia :(";
			$conexion->close();
		}
	}
	header("Location: ..?op=php/alta-evento-competencia.php&mensaje=$mensaje");
 ?>