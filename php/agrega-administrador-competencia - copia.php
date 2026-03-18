<?php 
	$regresar=isset($_POST["regresar_sbm"])?$_POST["regresar_sbm"]:NULL;
	$busca_administrador=(isset($_POST["busca_administrador_sbm"])?$_POST["busca_administrador_sbm"]:null);
	$nuevo_administrador=(isset($_POST["nuevo_administrador_sbm"])?$_POST["nuevo_administrador_sbm"]:null);
	$registrar=(isset($_POST["registrar_sbm"])?$_POST["registrar_sbm"]:null);
	$origen=(isset($_POST["origen_hdn"])?$_POST["origen_hdn"]:null);
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	$administrador=(isset($_POST["administrador_txt"])?$_POST["administrador_txt"]:null);
	$cod_administrador=(isset($_POST["cod_administrador_hdn"])?$_POST["cod_administrador_hdn"]:null);

	$principal=(isset($_POST["principal_rdo"])?$_POST["principal_rdo"]:null);
	session_start();
	$_SESSION["competencia"]=$competencia;
	$_SESSION["cod_competencia"]=$cod_competencia;
	$_SESSION["administrador"]=$administrador;
	$_SESSION["cod_administrador"]=$cod_administrador;
	$_SESSION["principal"]=$principal;
	include("funciones.php");
	
	if (isset($regresar)) {
		header("Location: ../?op=php/administradores-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}
	if (isset($busca_administrador)) {
		header("Location: ../?op=php/busca-usuario.php&bus=$administrador&ori=$origen&comp=$competencia&cco=$cod_competencia&tipo=A");
		exit();
	}
	
	if (isset($nuevo_administrador)) {
		$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
		$es_administrador_ppal_competencia=administrador_ppal_competencia($cod_usuario,$cod_competencia);
		if (substr($es_administrador_ppal_competencia,0,5)=="Error"){
			$mensaje=$es_administrador_ppal_competencia;
			$llamo="..?op=php/administradores-competencia.php&com=$competencia&cco=$cod_competencia";
			header("Location: $llamo&mensaje=$mensaje");
			exit();
		}

		header("Location: ../?op=php/alta-usuario.php&bus=$administrador&ori=$origen&tipo=A");
		exit();
	}


	$mensaje=NULL;
	if (strlen($administrador)==0)
		$mensaje="Error: escribe el nombre o busca el Administrador";

	if (is_null($mensaje))
		if (isset($cod_administrador)){
			$roles=defina_rol($cod_administrador);
			if (!$roles["administrador"]=="A") 
				$mensaje=$administrador." no está registrado(a) como administrador";
		}

	if (is_null($mensaje)) {
		$cod_competencia=determina_competencia($competencia);
		$es_principal=$principal=="S"?1:0;
		$conexion=conectarse();
		$consulta="INSERT INTO competenciasa (competencia, administrador, principal) VALUES ($cod_competencia, $cod_administrador, $es_principal)";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		if ($ejecutar_consulta){
			$_SESSION["cod_administrador"]=NULL;
			$_SESSION["administrador"]=NULL;
			$_SESSION["principal"]=NULL;
			$mensaje="Se ha dado de alta el administrador <b> $administrador</b> :)";
			$conexion->close();
			header("Location: ..?op=php/administradores-competencia.php&com=$competencia&mensaje=$mensaje");
			exit();
		}
		else{
			$conexion->close();
			$mensaje="No se pudo dar de alta al administrador <b>$administrador</b> :(";
		}
	}
	else
		header("Location: ..?op=php/alta-administrador-competencia.php&mensaje=$mensaje");
 	
 ?>