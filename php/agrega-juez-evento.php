<?php 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);
	$llamo=(isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:NULL);
	session_start();
	$llamo_r=str_replace("*", "&", $llamo);

	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	if (isset($_POST["regresar_sbm"])){
		if ($llamo_r) 
			$transfer="Location: $llamo_r";
		else
			$transfer="Location: ?op=php/eventos-competencia.php&com=$competencia";
		header($transfer);
		exit();
	}
	include("funciones.php");
	include ("validacion-evento-competencia.php");

	if (is_null($mensaje)) {
		$conexion=conectarse();
		$consulta="SELECT MAX(numero_evento) AS mx_evento 
			FROM competenciase
			WHERE competencia=$cod_competencia";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		if ($ejecutar_consulta) {
			$num_regs=$ejecutar_consulta->num_rows;
			if ($num_regs>0)
				$row=$ejecutar_consulta->fetch_assoc();	
				$n=$row["mx_evento"]+1;
		}
		if (is_null($n)) $n=1;

		$consulta="INSERT INTO competenciase (competencia, fechahora, numero_evento, modalidad, sexos, categorias, tipo) VALUES ($cod_competencia, '$fechahora', $n, '$modalidad', '$sexos', '$categorias', '$tipo')";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		if ($ejecutar_consulta){
			$_SESSION["modalidad"]=NULL;
			$_SESSION["fecha"]=NULL;
			$_SESSION["hora"]=NULL;
			$_SESSION["categorias"]=NULL;
			$_SESSION["sexos"]=NULL;
			$_SESSION["tipo"]=NULL;
			$mensaje="se dió de alta el evento <b>$modalidad de $categorias<b> en la competencia";
			$conexion->close();
			header("Location: ..?op=php/eventos-competencia.php&com=$competencia&mensaje=$mensaje");
			exit();
		}
		else
			$mensaje="No se pudo dar de alta el evento <b>$modalidad de $categorias</b> en la competencia :(";
	}
	$conexion->close();
	header("Location: ..?op=php/alta-evento-competencia.php&mensaje=$mensaje");
 ?>