<?php 
include("funciones.php");
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$evento=(isset($_POST["evento_hdn"])?$_POST["evento_hdn"]:null);
$numero_evento=(isset($_POST["numero_evento_hdn"])?$_POST["numero_evento_hdn"]:null);
$regresar_btn=isset($_POST["regresar_sbm"])?$_POST["regresar_sbm"]:NULL;
$enviar_btn=isset($_POST["enviar_sbm"])?$_POST["enviar_sbm"]:NULL;
$siguiente_btn=isset($_POST["siguiente_sbm"])?$_POST["siguiente_sbm"]:NULL;
$corregir_btn=isset($_POST["corregir_sbm"])?$_POST["corregir_sbm"]:NULL;
$origen=isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL;
if (!is_null($origen))
	$origen=str_replace("*", "&", $origen);

session_start();
unset($_SESSION["calificando"]);
if ($regresar_btn){
	$llamo="?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia&lg=$logo";
	unset($_SESSION["llamo"]);
	unset($_SESSION["protege"]);
	unset($_SESSION["calificacion"]);
	header("Location: $llamo");
	exit();
}

$calificacion=isset($_POST["calificacion_slc"])?$_POST["calificacion_slc"]:NULL;
if (!$calificacion) 
	$calificacion=isset($_POST["calificacion_hdn"])?$_POST["calificacion_hdn"]:NULL;

$mi_ubicacion=isset($_POST["mi_ubicacion_hdn"])?$_POST["mi_ubicacion_hdn"]:NULL;
$sombra=isset($_POST["sombra_hdn"])?trim($_POST["sombra_hdn"]):NULL;
$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
$conexion=conectarse();
if ($siguiente_btn){
	$conexion=conectarse();
	$consulta="SELECT calificacion FROM calificaciones WHERE evento=$numero_evento AND juez=$cod_usuario";
	$ejecutar_consulta=$conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	$conexion->close();
	if (!$num_regs){
		unset($_SESSION["calificacion"]);
		$_SESSION["protege"]=FALSE;
		header("Location: $origen&mensaje");
		exit();
	}
	else{
		$mensaje="Aún No puedes enviar la calificación, espera a que anuncien al siguiente :(";
		header("Location: $origen&mensaje=$mensaje");
		exit();
	}
}
$evento=(isset($_POST["evento_hdn"])?$_POST["evento_hdn"]:null);

//$calificacion=isset($_POST["calificacion_slc"]) ? number_format($_POST["calificacion_slc"],1) : NULL;
$descripcion=(isset($_POST["descripcion_hdn"])?$_POST["descripcion_hdn"]:null);
$fecha=(isset($_POST["fecha_hdn"])?$_POST["fecha_hdn"]:NULL);
$llamo=(isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):null);
$llamo=str_replace("*", "&", $llamo);

$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["evento"]=$evento;
$_SESSION["numero_evento"]=$numero_evento;
$_SESSION["descripcion"]=$descripcion;
$_SESSION["fecha"]=$fecha;
$_SESSION["calificacion"]=$calificacion;
$_SESSION["mi_ubicacion"]=$mi_ubicacion;
$_SESSION["sombra"]=$sombra;

$criterio=(isset($_POST["criterio_hdn"])?$_POST["criterio_hdn"]:null);
$criterio_sexos=(isset($_POST["criterio_sexos_hdn"])?$_POST["criterio_sexos_hdn"]:null);
$criterio_categorias=(isset($_POST["criterio_categorias_hdn"])?$_POST["criterio_categorias_hdn"]:null);

$mensaje=NULL;
if (is_null($calificacion)) 
	$mensaje="Error: debes ingresar la calificación :(";

if (!$mensaje){
	if ($calificacion<0.0 or $calificacion>10.0)
	$mensaje="Error: La calificación debe estar en el rango de 0 a 10:(";
}

if (!$mensaje)
	if (is_null($mi_ubicacion) and is_null($sombra)) 
		$mensaje="Error: no tengo mi ubicación, avise a Mesa de Control :(";

if (!$mensaje and $corregir_btn){
	$conexion=conectarse();
	$consulta="SELECT calificacion FROM calificaciones WHERE evento=$numero_evento AND juez=$cod_usuario";
	$ejecutar_consulta=$conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	$conexion->close();
	if (!$num_regs){
		$_SESSION["protege"]=fALSE;
		$mensaje="Puedes cambiar la calificación en este momento";
		header("Location: $origen&mensaje");
		exit();
	}
	else{
		$mensaje="No puedes cambiar la calificación, ya calificaron todos los jueces :(";
		header("Location: $origen&mensaje=$mensaje");
		exit();
	}
}

if (!$mensaje  AND $enviar_btn){
	$consulta="SELECT calificacion 
				FROM calificaciones 
				WHERE evento=$numero_evento AND juez=$cod_usuario";
	$conexion=conectarse();
	$ejecutar_consulta=$conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs){
		$consulta="UPDATE calificaciones 
					SET calificacion=$calificacion";
		if ($mi_ubicacion)
			$consulta.=", ubicacion=$mi_ubicacion";
		elseif ($sombra) {
			$consulta.=", 1";
		}
		$consulta.=" WHERE evento=$numero_evento AND juez=$cod_usuario";
	}
	else{
		$consulta="INSERT INTO calificaciones 
			(evento, juez, ubicacion, sombra, calificacion) VALUES ($numero_evento, $cod_usuario";
		if ($mi_ubicacion)
			$consulta.=", $mi_ubicacion";
		else
			$consulta.", NULL";
		if ($sombra)
			$consulta.=", 1";
		else
			$consulta.=", NULL";
		$consulta.=", $calificacion)";

	}
	$ejecutar_consulta = $conexion->query($consulta);
	if (!$ejecutar_consulta){
		$mensaje="Error: No se pudo registrar tu calificación, Avisa a Mesa de Control :(";
	}
	else{
		$_SESSION["protege"]=TRUE;
		$mensaje="Cuando estén anunciando el próximo salto, pulsa en Calificar ;)";
		$conexion->close();
		header("Location: $origen&mensaje");
		exit();
	}		
}

$_SESSION["protege"]=FALSE;
$conexion->close();
header("Location: $origen&mensaje=$mensaje");
exit();
?>