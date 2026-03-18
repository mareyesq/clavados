<?php 
if (!isset($cod_competencia)) 
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:NULL;
if (!isset($competencia)) 
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL;
if (!isset($categoria)) 
	$categoria=isset($_SESSION["categoria"])?$_SESSION["categoria"]:NULL;
if (!isset($modalidad)) 
	$modalidad=isset($_SESSION["modalidad"])?$_SESSION["modalidad"]:NULL;
if (!isset($item_modificar)) 
	$item_modificar=(isset($_SESSION["item_modificar"])?$_SESSION["item_modificar"]:NULL);
if (!isset($indice)) 
	$indice=(isset($_SESSION["indice"])?$_SESSION["indice"]:NULL);
if (!isset($orden)) 
	$orden=(isset($_SESSION["orden"])?$_SESSION["orden"]:NULL);
if (!isset($salto)) 
	$salto=(isset($_SESSION["salto"])?$_SESSION["salto"]:NULL);
if (!isset($posicion)) 
	$posicion=(isset($_SESSION["posicion"])?$_SESSION["posicion"]:NULL);
if (!isset($altura)) 
	$altura=(isset($_SESSION["altura"])?$_SESSION["altura"]:NULL);
if (!isset($plataforma)) 
	$plataforma=(isset($_SESSION["plataforma"])?$_SESSION["plataforma"]:NULL);
if (!isset($grado)) 
	$grado=(isset($_SESSION["grado"])?$_SESSION["grado"]:NULL);
if (!isset($observacion)) 
	$observacion=(isset($_SESSION["observacion"])?$_SESSION["observacion"]:NULL);
if (!isset($libre)) 
	$libre=(isset($_SESSION["libre"])?$_SESSION["libre"]:NULL);

if (!isset($items_ser)) 
	$items_ser=(isset($_SESSION["items_ser"])?$_SESSION["items_ser"]:NULL);
 ?>