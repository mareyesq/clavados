<?php 
include("funciones.php");
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$evento=(isset($_POST["evento_hdn"])?$_POST["evento_hdn"]:null);
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

/*
$cod_planilla=isset($_POST["cod_planilla_hdn"])?$_POST["cod_planilla_hdn"]:NULL;
$ronda=isset($_POST["ronda_hdn"])?$_POST["ronda_hdn"]:NULL;
$turno=isset($_POST["turno_hdn"])?$_POST["turno_hdn"]:NULL;
$orden_salida=isset($_POST["orden_salida_hdn"])?$_POST["orden_salida_hdn"]:NULL;
*/
$calificacion=isset($_POST["calificacion_slc"])?$_POST["calificacion_slc"]:NULL;
if (!$calificacion) 
	$calificacion=isset($_POST["calificacion_hdn"])?$_POST["calificacion_hdn"]:NULL;

$mi_ubicacion=isset($_POST["ubicacion_hdn"])?$_POST["ubicacion_hdn"]:NULL;
$conexion=conectarse();
if ($siguiente_btn){
	$conexion=conectarse();
	$consulta="SELECT calificacion FROM calificaciones WHERE juez=$mi_ubicacion";
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
$_SESSION["descripcion"]=$descripcion;
$_SESSION["fecha"]=$fecha;
$_SESSION["calificacion"]=$calificacion;
$_SESSION["mi_ubicacion"]=$mi_ubicacion;

$criterio=(isset($_POST["criterio_hdn"])?$_POST["criterio_hdn"]:null);
$criterio_sexos=(isset($_POST["criterio_sexos_hdn"])?$_POST["criterio_sexos_hdn"]:null);
$criterio_categorias=(isset($_POST["criterio_categorias_hdn"])?$_POST["criterio_categorias_hdn"]:null);

$cod_usuario=$_SESSION["usuario_id"];

$mensaje=NULL;
if (is_null($calificacion)) 
	$mensaje="Error: debes ingresar la calificación :(";

if (!$mensaje){
	if ($calificacion<0.0 or $calificacion>10.0)
	$mensaje="Error: La calificación debe estar en el rango de 0 a 10:(";
}

if (!$mensaje)
	if (is_null($mi_ubicacion)) 
		$mensaje="Error: no tengo mi ubicación, avise a Mesa de Control :(";

if (!$mensaje and $corregir_btn){
	$conexion=conectarse();
	$consulta="SELECT calificacion FROM calificaciones WHERE juez=$mi_ubicacion";
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
				WHERE juez=$mi_ubicacion";
	$conexion=conectarse();
	$ejecutar_consulta=$conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs)
		$consulta="UPDATE calificaciones 
					SET calificacion=$calificacion
					WHERE juez=$mi_ubicacion";
	else
		$consulta="INSERT INTO calificaciones 
			(juez, calificacion) values ($mi_ubicacion, $calificacion)";
	$ejecutar_consulta = $conexion->query($consulta);
	if (!$ejecutar_consulta){
		$mensaje="Error: No se pudo registrar tu calificación, Avisa a Mesa de Control :(";
	}
	else{
		$_SESSION["protege"]=TRUE;
		$mensaje="Cuando estén anunciando el próximo salto, pulsa en Siguiente ;)";
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