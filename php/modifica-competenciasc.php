<?php 
	include("conexion.php");
	$sql = "ALTER TABLE `competenciasc` ADD `clavadista2` INT NULL , ADD `modalidad` CHAR(1) NULL ;";
	$ejecutar_sql = $conexion->query($sql);

	$sql = "ALTER TABLE `competenciasc`
	  DROP PRIMARY KEY,
   		ADD PRIMARY KEY(
	     `competencia`,
    	 `clavadista`,
	     `clavadista2`,
	     `categoria`,
    	 `modalidad`,
    	 `equipo`)";
	$ejecutar_sql = $conexion->query($sql);
 ?>