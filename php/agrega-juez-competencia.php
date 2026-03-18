<?php 
	$regresar=isset($_POST["regresar_sbm"])?$_POST["regresar_sbm"]:NULL;
	$busca_juez=(isset($_POST["busca_juez_sbm"])?$_POST["busca_juez_sbm"]:null);
	$nuevo_juez=(isset($_POST["nuevo_juez_sbm"])?$_POST["nuevo_juez_sbm"]:null);
	$registrar=(isset($_POST["registrar_sbm"])?$_POST["registrar_sbm"]:null);
	$origen=(isset($_POST["origen_hdn"])?$_POST["origen_hdn"]:null);
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	$juez=(isset($_POST["juez_txt"])?$_POST["juez_txt"]:null);
	$cod_juez=(isset($_POST["cod_juez_hdn"])?$_POST["cod_juez_hdn"]:null);
	$sombra=(isset($_POST["sel_sombra_slc"])?$_POST["sel_sombra_slc"]:null);

	session_start();
	$_SESSION["competencia"]=$competencia;
	$_SESSION["cod_competencia"]=$cod_competencia;
	$_SESSION["juez"]=$juez;
	$_SESSION["cod_juez"]=$cod_juez;
	include("funciones.php");

	if (isset($regresar)) {
		header("Location: ../?op=php/jueces-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}
	if (isset($busca_juez)) {
		header("Location: ../?op=php/busca-usuario.php&bus=$juez&tipo=J&ori=$origen&comp=$competencia");
		exit();
	}
	
	if (isset($nuevo_juez)) {
		$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
		header("Location: ../?op=php/alta-usuario.php&bus=$juez&ori=$origen&tipo=J");
		exit();
	}


	$mensaje=NULL;
	if (strlen($juez)==0)
		$mensaje="Error: escribe el nombre o busca el juez";

	if (is_null($mensaje))
		if (isset($cod_juez)){
			$roles=defina_rol($cod_juez);
			if (!$roles["juez"]=="J") 
				$mensaje=$juez." no está registrado(a) como juez";
		}

	if (is_null($mensaje)) {
		$conexion=conectarse();		
		$consulta="INSERT INTO competenciasjz (competencia, juez, sombra) VALUES ($cod_competencia, $cod_juez";
		if ($sombra)
			$consulta.=", 1)";
		else
			$consulta.=", NULL)";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		if ($ejecutar_consulta){
			$_SESSION["cod_juez"]="";
			$_SESSION["juez"]="";
			$mensaje="Se ha dado de alta al juez <b>$juez</b> :)";
			$conexion->close();
			header("Location: ..?op=php/jueces-competencia.php&com=$competencia&mensaje=$mensaje");
			exit();
		}
		else{
			$conexion->close();
			$mensaje="No se pudo dar de alta al juez <b>$juez</b> :(";
		}
	}
	header("Location: ..?op=php/alta-juez-competencia.php&mensaje=$mensaje");
 	
 ?>