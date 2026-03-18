<?php 
	$llamo=isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):NULL;
	session_start();
	if (isset($_POST["regresar_sbm"])){
		$_SESSION["competencia"]="";
		$_SESSION["cod_competencia"]="";
		$_SESSION["puntos"]="";
		$_SESSION["llamo"]="";
		header("Location: ..?op=php/$llamo");
		exit();
	}
	include("funciones.php");
	include ("validacion-puntos-competencia.php");

	if (is_null($mensaje)) {
		for ($i=1; $i < 12; $i++) { 
			if (isset($puntos[$i])){
				$puntaje=explode("/", $puntos[$i]);	
				if (!is_null($puntaje[1]) OR !is_null($puntaje[2])){
					$puesto=$puntaje[0];
					$individual=isset($puntaje[1])?$puntaje[1]:0;
					$sincro=isset($puntaje[2])?$puntaje[2]:0;
					$conexion=conectarse();
					$consulta="INSERT INTO competenciast (competencia, puesto, puntos, puntos_sinc) VALUES ($cod_competencia, $puesto, $individual, $sincro)";
					$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

					if (!$ejecutar_consulta) {
						$mensaje="Error: no se pudo crear la tabla de puntos";
						break;
					}
					$conexion->close();
				}

			}
		}

		if (is_null($mensaje)) {
			$_SESSION["competencia"]="";
			$_SESSION["cod_competencia"]="";
			$_SESSION["puntos"]="";
			$mensaje="se dió de alta la tabla de puntos de la competencia :)";
			header("Location: ..?op=php/$llamo&mensaje=$mensaje");
			exit();
		}
		else
			$mensaje="No se pudo dar de alta la tabla de puntos de la competencia :(";
	}
	header("Location: ?op=php/puntos-competencia.php&com=$competencia&mensaje=$mensaje");
 ?>