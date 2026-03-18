<?php 
include("funciones.php");
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$evento=(isset($_POST["evento_hdn"])?$_POST["evento_hdn"]:null);
$numero_evento=(isset($_POST["numero_evento_hdn"])?$_POST["numero_evento_hdn"]:null);

$llamo=isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):NULL;
if (!is_null($llamo))
	$llamo=str_replace("*", "&", $llamo);

session_start();
if (isset($_POST["regresar_sbm"])){
	unset($_SESSION["edita"]);
	unset($_SESSION["llamo"]);
	unset($_SESSION["competencia"]);
	unset($_SESSION["turno"]);
	unset($_SESSION["ejecutor"]);
	unset($_SESSION["usa_dispositivos"]);
	header("Location: $llamo");
	exit();
}
$origen=isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL;
$usa_dispositivos=isset($_POST["usa_dispositivos_hdn"])?$_POST["usa_dispositivos_hdn"]:NULL;

if (!is_null($origen)){
	$_SESSION["origen"]=$origen;
	$origen=str_replace("*", "&", $origen);
}
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["evento"]=$evento;
$_SESSION["numero_evento"]=$numero_evento;
$_SESSION["usa_dispositivos"]=$usa_dispositivos;

$descripcion=(isset($_POST["descripcion_hdn"])?$_POST["descripcion_hdn"]:null);
$fecha=(isset($_POST["fecha_hdn"])?$_POST["fecha_hdn"]:NULL);
$modalidad=(isset($_POST["modalidad_hdn"])?$_POST["modalidad_hdn"]:NULL);

$sexo=(isset($_POST["sexo_hdn"])?$_POST["sexo_hdn"]:NULL);
$categoria=(isset($_POST["categoria_hdn"])?$_POST["categoria_hdn"]:NULL);
$sx=isset($_POST["sx_hdn"])?trim($_POST["sx_hdn"]):NULL;
$cat=isset($_POST["cat_hdn"])?trim($_POST["cat_hdn"]):NULL;
$llamo=(isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):null);
$llamo=str_replace("*", "&", $llamo);
$cod_planilla=isset($_POST["cod_planilla_hdn"])?$_POST["cod_planilla_hdn"]:NULL;

$ronda=isset($_POST["ronda_hdn"])?$_POST["ronda_hdn"]:NULL;
$turno=isset($_POST["turno_hdn"])?$_POST["turno_hdn"]:NULL;
$ejecutor=isset($_POST["ejecutor_hdn"])?$_POST["ejecutor_hdn"]:NULL;
$num_jueces=isset($_POST["num_jueces_hdn"])?trim($_POST["num_jueces_hdn"]):NULL;

$_SESSION["competencia"]=$competencia;
$_SESSION["descripcion"]=$descripcion;
$_SESSION["fecha"]=$fecha;
$_SESSION["modalidad"]=$modalidad;
$_SESSION["sexo"]=$sexo;
$_SESSION["categoria"]=$categoria;
$_SESSION["sx"]=$sx;
$_SESSION["cat"]=$cat;

if (isset($_POST["iniciar_sbm"])){
	$conexion=conectarse();
	$consulta="SELECT ciudad FROM competencias WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);
	if ($ejecutar_consulta){
		$row=$ejecutar_consulta->fetch_assoc();
		$cod_ciudad=$row["ciudad"];
	}
	$ahora=ahora($cod_ciudad);
	$actualiza="UPDATE competenciaev
		SET inicio_comp='$ahora'
		WHERE competencia=$cod_competencia and numero_evento=$numero_evento";
	$ejecutar_actualiza = $conexion->query($actualiza);
	$conexion->close();
	header("Location: $origen");
	exit();
}


$criterio=(isset($_POST["criterio_hdn"])?$_POST["criterio_hdn"]:null);
$criterio_sexos=(isset($_POST["criterio_sexos_hdn"])?$_POST["criterio_sexos_hdn"]:null);
$criterio_categorias=(isset($_POST["criterio_categorias_hdn"])?$_POST["criterio_categorias_hdn"]:null);

$criterio_sexo=(isset($_POST["criterio_sexo_hdn"])?$_POST["criterio_sexo_hdn"]:null);
$criterio_categoria=(isset($_POST["criterio_categoria_hdn"])?$_POST["criterio_categoria_hdn"]:null);

$cod_usuario=$_SESSION["usuario_id"];

/* marzo 25/2019 estaban estas instrucciones, pero esto solo se registra al iniciar
$conexion=conectarse();
$actualiza="UPDATE competenciaev
	SET inicio_comp='$ahora'
	WHERE competencia=$cod_competencia and numero_evento=$numero_evento";
$ejecutar_actualiza = $conexion->query($actualiza);
$conexion->close();
*/
if (isset($_POST["totaliza_sbm"])){
	include("totaliza-salto.php");
	$transfer="Location: $origen";
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["fallado_sbm"])){
	$salto_fallado=TRUE;
	include("totaliza-salto.php");
	$salto_fallado=FALSE;
	$transfer="Location: $origen";
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["siguiente_sbm"])){
	$conexion=conectarse();
	$consulta="UPDATE planillad
		SET calificando=NULL
		WHERE planilla=$cod_planilla and ronda=$ronda";
	if ($ejecutor)
		$consulta .= " and ejecutor=$ejecutor";
	$ejecutar_consulta = $conexion->query($consulta);

	if ($usa_dispositivos){
		$consulta="DELETE FROM calificaciones WHERE evento=$numero_evento";
		$ejecutar_consulta = $conexion->query($consulta);
	}

	$conexion->close();
	$_SESSION["edita"]=1;
 	unset($_SESSION["ronda"]);
 	unset($_SESSION["turno"]);
 	unset($_SESSION["ejecutor"]);
 	unset($_SESSION["orden_salida"]);
	$transfer="Location: $origen";
	header($transfer);
	exit();
}

if (isset($_POST["anterior_sbm"])){
	$conexion=conectarse();
	$consulta="UPDATE planillad
		SET calificando=NULL
		WHERE planilla=$cod_planilla and ronda=$ronda and calificando=1";
	$ejecutar_consulta = $conexion->query($consulta);
	include("clavadista-anterior.php");
	$conexion->close();
	$_SESSION["edita"]=0;
	$transfer="Location: $origen";
	header($transfer);
	exit();
}

if (isset($_POST["iguales_sbm"])){
	$iguales=TRUE;
	include("totaliza-salto.php");
	$iguales=FALSE;
	$transfer="Location: $origen";
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["corregir_sbm"])){
	$_SESSION["edita"]=1;
	$_SESSION["ronda"]=$ronda;
	$_SESSION["turno"]=$turno;
	$_SESSION["orden_salida"]=$_POST["orden_salida_hdn"];
	if ($ejecutor)
		$_SESSION["ejecutor"]=$ejecutor;
	if ($usa_dispositivos){
		$cor=array();
		$cor[1]=isset($_POST["cor1_chk"])?$_POST["cor1_chk"]:NULL;
		$cor[2]=isset($_POST["cor2_chk"])?$_POST["cor2_chk"]:NULL;
		$cor[3]=isset($_POST["cor3_chk"])?$_POST["cor3_chk"]:NULL;
		$cor[4]=isset($_POST["cor4_chk"])?$_POST["cor4_chk"]:NULL;
		$cor[5]=isset($_POST["cor5_chk"])?$_POST["cor5_chk"]:NULL;
		$cor[6]=isset($_POST["cor6_chk"])?$_POST["cor6_chk"]:NULL;
		$cor[7]=isset($_POST["cor7_chk"])?$_POST["cor7_chk"]:NULL;
		$cor[8]=isset($_POST["cor8_chk"])?$_POST["cor8_chk"]:NULL;
		$cor[9]=isset($_POST["cor9_chk"])?$_POST["cor9_chk"]:NULL;
		$cor[10]=isset($_POST["cor10_chk"])?$_POST["cor10_chk"]:NULL;
		$cor[11]=isset($_POST["cor11_chk"])?$_POST["cor11_chk"]:NULL;
		$n=count($cor);
	}
	$conexion=conectarse();
	$consulta="UPDATE planillad
		SET calificando=1, 
			calificado=NULL";
	if ($usa_dispositivos){
		for ($i=1; $i <=$n ; $i++) { 
			if ($cor[$i])
				$consulta .=", cal".$i."=NULL";
		}
	}
	$consulta .= " WHERE planilla=$cod_planilla and ronda=$ronda";
	if ($ejecutor)
		$consulta .= " and ejecutor=$ejecutor";
	$ejecutar_consulta = $conexion->query($consulta);
	if ($usa_dispositivos){
		for ($i=1; $i<=$n ; $i++) { 
			if ($cor[$i]) {
				$consulta="DELETE FROM calificaciones WHERE evento=$numero_evento AND ubicacion=$i";
				$ejecutar_consulta = $conexion->query($consulta);
			}
		}

	}
	$conexion->close();
	$transfer="Location: $origen";
	header($transfer);
	exit();
}

if (isset($_POST["limpia_sbm"])){
	include("limpia-salto.php");
}

if (isset($_POST["penalizar_sbm"])){
	$_SESSION["penalidad_ss"]=2;
	$_SESSION["edita"]=1;
	$_SESSION["ronda"]=$ronda;
	$_SESSION["turno"]=$turno;
	$_SESSION["ejecutor"]=isset($_POST["ejecutor_hdn"])?$_POST["ejecutor_hdn"]:NULL;
	$_SESSION["orden_salida"]=$_POST["orden_salida_hdn"];
	include("totaliza-salto.php");
	$transfer="Location: $origen";
	$_SESSION["err"]=1;
	header($transfer);
	exit();
}

if (isset($_POST["despenalizar_sbm"])){
	unset($_SESSION["penalidad"]);
	$_SESSION["edita"]=1;
	$_SESSION["ronda"]=$ronda;
	$_SESSION["turno"]=$turno;
	$_SESSION["ejecutor"]=isset($_POST["ejecutor_hdn"])?$_POST["ejecutor_hdn"]:NULL;
	$_SESSION["orden_salida"]=$_POST["orden_salida_hdn"];
	include("totaliza-salto.php");
	$transfer="Location: $origen";
	$_SESSION["err"]=1;
	header($transfer);
	exit();
}

if (isset($_POST["recibe_cal_sbm"]) AND $usa_dispositivos){
	include("recibe-calificaciones.php");
	include("totaliza-salto.php");
	$transfer="Location: $origen";
	if (strlen($mensaje)>0)
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

header("Location: $origen");
exit();
?>