<?php 
	$cod_planilla=isset($_POST["cod_planilla_hdn"])?$_POST["cod_planilla_hdn"]:NULL;
	$ronda=isset($_POST["ronda_hdn"])?$_POST["ronda_hdn"]:NULL;
	$turno=isset($_POST["turno_hdn"])?$_POST["turno_hdn"]:NULL;
	$orden_salida=isset($_POST["orden_salida_hdn"])?$_POST["orden_salida_hdn"]:NULL;
	$ejecutor=isset($_POST["ejecutor_hdn"])?$_POST["ejecutor_hdn"]:NULL;
	$actualiza="UPDATE planillad 
		SET calificando=1, 
			calificado=NULL,
			total_salto=NULL,
			puntaje_salto=NULL,
			acumulado=NULL,
			penalizado=NULL,
			hora_salto=NULL, 
			juez1=NULL, 
			juez2=NULL, 
			juez3=NULL,
			juez4=NULL,
			juez5=NULL, 
			juez6=NULL, 
			juez7=NULL, 
			juez8=NULL, 
			juez9=NULL, 
			juez10=NULL, 
			juez11=NULL, 
			cal1=NULL, 
			cal2=NULL, 
			cal3=NULL, 
			cal4=NULL, 
			cal5=NULL, 
			cal6=NULL, 
			cal7=NULL, 
			cal8=NULL, 
			cal9=NULL, 
			cal10=NULL, 
			cal11=NULL
		WHERE planilla=$cod_planilla AND ronda=$ronda";  
	if ($ejecutor)
		$actualiza .= " AND ejecutor=$ejecutor";
	$conexion=conectarse();
	$ejecutar_actualiza=$conexion->query($actualiza);
	$conexion->close();
	$_SESSION["ronda"]=$ronda;
	$_SESSION["turno"]=$turno;
	$_SESSION["orden_salida"]=$orden_salida;
	if ($ejecutor)
		$_SESSION["ejecutor"]=$ejecutor;
	$transfer="Location: $origen";
	$_SESSION["err"]=1;
	unset($_SESSION["cal"]);
	$_SESSION["edita"]=1;
	header($transfer);
	exit();
?>