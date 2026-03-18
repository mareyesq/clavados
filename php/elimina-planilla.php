<?php 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	
	$clavadista=isset($_POST["clavadista_txt"])?$_POST["clavadista_txt"]:NULL;
	$categoria=isset($_POST["categoria_txt"])?$_POST["categoria_txt"]:NULL;

	$modalidad=isset($_POST["modalidad_txt"])?$_POST["modalidad_txt"]:NULL;
	$clave=isset($_POST["clave_txt"])?$_POST["clave_txt"]:NULL;
	$corto=(isset($_POST["corto_hdn"])?$_POST["corto_hdn"]:null);
	$cod_clavadista=isset($_POST["cod_clavadista_hdn"])?$_POST["cod_clavadista_hdn"]:NULL;
	$cod_entrenador=isset($_POST["cod_entrenador_hdn"])?$_POST["cod_entrenador_hdn"]:NULL;
	$corto=isset($_POST["corto_hdn"])?$_POST["corto_hdn"]:NULL;
	$planilla=isset($_POST["planilla_hdn"])?$_POST["planilla_hdn"]:NULL;
	$btn_eliminar=isset($_POST["eliminar_sbm"])?$_POST["eliminar_sbm"]:NULL;

	$llamo=isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:NULL;
	
	session_start();
	$_SESSION["competencia"]= $competencia;
	$_SESSION["corto"]= $corto;
	$_SESSION["clavadista"]=$clavadista;
	$_SESSION["cod_clavadista"]=$cod_clavadista;
	$_SESSION["cod_entrenador"]=$cod_entrenador;
	$_SESSION["corto"]=$corto;
	$_SESSION["categoria"]=$categoria;
	$_SESSION["modalidad"]=$modalidad;
	$_SESSION["clave"]=$clave;
	$_SESSION["planilla"]=$planilla;
	$_SESSION["llamo"]=$llamo;
	
	if (isset($_POST["regresar_sbm"])){
		unset($_SESSION["competencia"]);
		unset($_SESSION["corto"]);
		unset($_SESSION["clavadista"]);
		unset($_SESSION["cod_clavadista"]);
		unset($_SESSION["cod_entrenador"]);
		unset($_SESSION["categoria"]);
		unset($_SESSION["modalidad"]);
		unset($_SESSION["clave"]);
		unset($_SESSION["planilla"]);
		unset($_SESSION["llamo"]);
		$transfer="Location: ..?op=$llamo";
		header($transfer);
		exit();
	}
	
	include("funciones.php");
	
	$mensaje=NULL;
	if (is_null($mensaje))
		if (inscripciones($competencia)==FALSE)
			$mensaje="Error: Esta competencia ya no permite modificar inscripciones :(";

	if (!isset($cod_usuario))
		$cod_usuario=trim($_SESSION["usuario_id"]);

	$es_administrador=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($es_administrador,0,5)=="Error")
		$es_administrador=FALSE;
	

	if (is_null($mensaje)){
		if (!$es_administrador){
			$p=equivalencia($clave);
			$ret=verifica_clave_inscripcion($p,$corto,$cod_competencia);
			if (substr($ret, 0, 5)=="Error")
				$mensaje=$ret;
		}
	}
	
	if (is_null($mensaje) AND $btn_eliminar) {
		$conexion=conectarse();
		$borrar="DELETE FROM planillad
			WHERE (planilla=$planilla)";
		$ejecutar_borrar = $conexion->query($borrar);

		$borrar="DELETE FROM planillas
			WHERE (cod_planilla=$planilla)";
		$ejecutar_borrar = $conexion->query($borrar);

		if ($ejecutar_borrar){
			unset($_SESSION["competencia"]);
			unset($_SESSION["corto"]);
			unset($_SESSION["clavadista"]);
			unset($_SESSION["cod_clavadista"]);
			unset($_SESSION["cod_entrenador"]);
			unset($_SESSION["categoria"]);
			unset($_SESSION["modalidad"]);
			unset($_SESSION["clave"]);
			unset($_SESSION["planilla"]);
			unset($_SESSION["llamo"]);
			$conexion->close();
			$mensaje="Se eliminó la planilla del clavadista: <b>$clavadista</b>";
			$transfer="Location: ..?op=$llamo&mensaje=$mensaje";
			header($transfer);
			exit();
		}
		else{
			$conexion->close();
			$mensaje="No se pudo eliminar la planilla del clavadista <b>$clavadista</b>";
		}	
	}
	else {
			$transfer="Location: ..?op=$llamo&mensaje=$mensaje";
			header($transfer);
	}
 ?>