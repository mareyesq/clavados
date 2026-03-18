<?php 
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);

if (!$cod_competencia) {
	$cod_competencia=(isset($_SESSION['cod_competencia'])?$_SESSION['cod_competencia']:null);
}
$categoria=isset($_POST["categoria_slc"]) ? $_POST["categoria_slc"] : null;
$cod_categoria=substr($categoria, 0, 2);
$cod_modalidad=isset($_POST["cod_modalidad_slc"]) ? $_POST["cod_modalidad_slc"] : null;
$marca_f=isset($_POST["marca_f_num"]) ? $_POST["marca_f_num"] : null;
$grado_f=isset($_POST["grado_f_num"]) ? $_POST["grado_f_num"] : null;
$prom_f=isset($_POST["prom_f_num"]) ? $_POST["prom_f_num"] : null;
$marca_m=isset($_POST["marca_m_num"]) ? $_POST["marca_m_num"] : null;
$grado_m=isset($_POST["grado_m_num"]) ? $_POST["grado_m_num"] : null;
$prom_m=isset($_POST["prom_m_num"]) ? $_POST["prom_m_num"] : null;

$_SESSION["categoria"]=isset($categoria)?$categoria:NULL;
$_SESSION["cod_modalidad"]=isset($cod_modalidad)?$cod_modalidad:NULL;
$_SESSION["marca_f"]=isset($marca_f)?$marca_f:NULL;
$_SESSION["grado_f"]=isset($grado_f)?$grado_f:NULL;
$_SESSION["prom_f"]=isset($prom_f)?$prom_f:NULL;
$_SESSION["marca_m"]=isset($marca_m)?$marca_m:NULL;
$_SESSION["grado_m"]=isset($grado_m)?$grado_m:NULL;
$_SESSION["prom_m"]=isset($prom_m)?$prom_m:NULL;

$mensaje=null;
if (is_null($mensaje)){
	if (!isset($categoria))
		$mensaje="Error: Debe definir la categoría de esta marca :(";
}

$conexion=conectarse();
$consulta="SELECT * FROM categorias WHERE cod_categoria='$cod_categoria'";
$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
$num_regs=$ejecutar_consulta->num_rows;

if (!$num_regs)
	$mensaje="No está registrada la categoría ".$cod_categoria." :(";

if (is_null($mensaje)){
	$consulta="SELECT * FROM competenciapr WHERE competencia=$cod_competencia AND categoria='$cod_categoria'";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if (!$num_regs)
		$mensaje="Error: La Categoría ".$categoria." no está programada en esta competencia:(";
}
$conexion->close();
if (is_null($mensaje)){
	if (!isset($cod_modalidad))
		$mensaje="Error: Debe definir la modalidad para esta marca :(";
}

if (is_null($mensaje)){
	$row=$ejecutar_consulta->fetch_assoc();
	$modalidades=$row["modalidades"];
	$existe=strstr($modalidades, $cod_modalidad);
	if ($existe===0)
		$mensaje="Error: La modalidad ".$modalidad." no está programada para la Categoría ".$categoria." :(";
}

if (is_null($mensaje)){
	if ($prom_f>0)
		if ($prom_f>10)
			$mensaje="Error: el promedio Femenino supera la calificación 10 :(";
}		
	
if (is_null($mensaje)){
	if ($prom_m>0)
		if ($prom_m>10)
			$mensaje="Error: el promedio Masculino supera la calificación 10 :(";
}		
?>