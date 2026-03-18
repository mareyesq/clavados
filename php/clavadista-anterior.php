<?php 
$turno=$_POST["turno_hdn"];
$orden_salida=$_POST["orden_salida_hdn"];
$cod_competencia=$_POST["cod_competencia_hdn"];
$evento=$_POST["evento_hdn"];
$numero_evento=$_POST["numero_evento_hdn"];
$mensaje=NULL;
if ($ejecutor){
	$turno_orden = $turno."-".$orden_salida."-".$ejecutor;
	$consulta="SELECT DISTINCT orden_salida, turno, ejecutor, 
	CONCAT (turno,'-',orden_salida,'-',ejecutor) as turno_orden";
}
else{
	$turno_orden=$turno."-".$orden_salida;
	$consulta="SELECT DISTINCT orden_salida, turno, 
	CONCAT (turno,'-',orden_salida) as turno_orden";
}

$consulta .= " FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
	WHERE competencia=$cod_competencia
		AND evento=$numero_evento
		AND calificado=1";
if ($ejecutor) 
	$consulta .= " AND CONCAT (turno,'-',orden_salida,'-',ejecutor)<'$turno_orden'";
else
	$consulta .= " AND CONCAT (turno,'-',orden_salida)<'$turno_orden'";

$consulta .= " ORDER BY turno_orden DESC
 			LIMIT 1";
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;

if ($num_regs==0) {
	$mensaje="no hay competidor anterior ";
}
while ($row=$ejecutar_consulta->fetch_assoc()){
	$turno=$row["turno_orden"];
}

if (is_null($mensaje)){
	$cad=explode("-", $turno);
	$_SESSION["turno"]=$cad[0];
	$_SESSION["orden_salida"]=$cad[1];
	$_SESSION["ejecutor"]=isset($cad[2])?$cad[2]:NULL;
}
else
	$mensaje="no hay competidor anterior ";

if (strlen($mensaje)>0){
	unset($_SESSION["turno"]);
	unset($_SESSION["orden_salida"]);
}
?>