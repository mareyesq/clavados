<?php 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL;

	$clavadista=isset($_POST["clavadista_hdn"])?$_POST["clavadista_hdn"]:NULL;
	$cod_clavadista=isset($_POST["cod_clavadista_hdn"])?$_POST["cod_clavadista_hdn"]:NULL;
	$cod_clavadista2=isset($_POST["cod_clavadista2_hdn"])?trim($_POST["cod_clavadista2_hdn"]):NULL;
	$equipo=isset($_POST["equipo_hdn"])?$_POST["equipo_hdn"]:NULL;
	$corto=isset($_POST["corto_hdn"])?$_POST["corto_hdn"]:NULL;

	$clave=isset($_POST["clave_txt"])?$_POST["clave_txt"]:NULL;
	
	session_start();
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	$llamador="baja-clavadista-competencia.php";
	$llamado=isset($llamados[$llamador])?$llamados[$llamador]:NULL;
	$cod_usuario=$_SESSION["usuario_id"];

	if (isset($_POST["regresar_sbm"])){
		include("limpia-clavadista-competencia.php");
		$transfer="Location: ..?op=php/$llamado&cco=$cod_competencia";
		if (!is_null($llamados)) 
			if (array_key_exists($llamador, $llamados)){
				unset($llamados[$llamador]);
				$_SESSION["llamados"]=$llamados;
			}

		header($transfer);
		exit();
	}
	
	include("funciones.php");
	
	$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	$_SESSION["clavadista"]=isset($clavadista)?$clavadista:NULL;
	$_SESSION["cod_clavadista"]=isset($cod_clavadista)?$cod_clavadista:NULL;
	$_SESSION["cod_clavadista2"]=isset($cod_clavadista2)?$cod_clavadista2:NULL;
	$_SESSION["equipo"]=isset($equipo)?$equipo:NULL;
	$_SESSION["corto"]=isset($corto)?$corto:NULL;
	$_SESSION["clave"]=isset($clave)?$clave:NULL;

	$mensaje=NULL;
	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
	if (is_null($cod_usuario) or $cod_usuario=="")
		$mensaje= "Error: Debes iniciar sesión para poder eliminar inscripciones";

	if (is_null($mensaje)){
		$administrador=administrador_competencia($cod_usuario,$cod_competencia);
		if (substr($administrador, 0, 5)=="Error")
			$administrador=FALSE;
	}

	if (is_null($mensaje))
		if (!$administrador) 
			if (inscripciones($competencia)==FALSE)
				$mensaje="Error: Esta competencia ya no permite modificar inscripciones :(";

	if (is_null($mensaje)){
		if (!$administrador) {
			$p=equivalencia($clave);
			$ret=verifica_clave_inscripcion($p,$corto,$cod_competencia);
			if (substr($ret, 0, 5)=="Error")
				$mensaje=$ret;
		}
	}
	
	if (is_null($mensaje)) {
		$conexion=conectarse();
		$consulta="SELECT cod_planilla FROM planillas
			WHERE (clavadista=$cod_clavadista and competencia=$cod_competencia";
		if (strlen($cod_clavadista2)==0)
			$consulta.=")";
		else
			$consulta.=" and clavadista2=$cod_clavadista2)";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		while ($row=$ejecutar_consulta->fetch_assoc()){
			$planilla=$row["cod_planilla"];
			$borrar="DELETE FROM planillad
				WHERE (planilla=$planilla)";
			$ejecutar_borrar = $conexion->query($borrar);
		}
		$borrar="DELETE FROM planillas
			WHERE (clavadista=$cod_clavadista and competencia=$cod_competencia";
		if (strlen($cod_clavadista2)==0)
			$borrar.=")";
		else
			$borrar.=" and clavadista2=$cod_clavadista2)";
		$ejecutar_borrar = $conexion->query($borrar);

		if ($ejecutar_borrar){
			$mensaje="Se eliminó al clavadista: <b>$clavadista</b> de la competencia";
			include("limpia-clavadista-competencia.php");
			$transfer="Location: ..?op=php/$llamado&cco=$cod_competencia&mensaje=$mensaje";
			if (!is_null($llamados)) 
				if (array_key_exists($llamador, $llamados)){
					unset($llamados[$llamador]);
					$_SESSION["llamados"]=$llamados;
				}
			$conexion->close();
			header($transfer);
			exit();
		}
		else{
			$conexion->close();
			$mensaje="No se pudo eliminar al clavadista <b>$clavadista</b>";
		}	
	}
	else
		header("Location: ..?op=php/$llamador&mensaje=$mensaje");
 ?>