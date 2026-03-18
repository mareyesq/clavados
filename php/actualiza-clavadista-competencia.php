<?php 
$alta=0;
$llamador="edita-clavadista-competencia.php";
include("valida-clavadista-competencia.php");

if (is_null($mensaje)){
	// Verifico si hay cambios en las pruebas que va a competir
	$mod_ant=array();
	$conexion=conectarse();
	$consulta="SELECT cod_planilla, modalidad
		FROM planillas as c 
		WHERE (c.competencia=$cod_competencia 
			and c.clavadista=$cod_clavadista
			and c.total IS NULL";
	if ($cod_clavadista2>0) 
		$consulta .= " AND c.clavadista2=$cod_clavadista2)";
	else
		$consulta .= ")";
	$ejecutar_consulta = $conexion->query($consulta);
	while($reg=$ejecutar_consulta->fetch_assoc()){
		$planilla=$reg["cod_planilla"];
		$planilla_ant[]=$planilla;
		$mod_ant[]=$reg["modalidad"];
		$actualizar="UPDATE planillas
			SET entrenador=$cod_entrenador, 
				categoria='$cod_categoria'
			WHERE cod_planilla=$planilla  ";
		$ejecutar_actualizar = $conexion->query($actualizar);
	}
	$n=count($mod_ant);
	$mod_act=explode("-", $modalidades);

	// borro las planillas de las pruebas que quitó
	for ($i=0; $i < $n ; $i++) { 
		if (!in_array($mod_ant[$i], $mod_act)) {
			$planilla=$planilla_ant[$i];
			$borra="DELETE FROM planillad 
					WHERE planilla=$planilla";
			$ejecutar_borra = $conexion->query($borra);
	
			$borra="DELETE FROM planillas 
				WHERE cod_planilla=$planilla";
			$ejecutar_borra = $conexion->query($borra);
		}
	}

	// Agrego las planillas de las pruebas que incluyó
	$consulta="SELECT ciudad FROM competencias WHERE (cod_competencia=$cod_competencia)";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$registro=$ejecutar_consulta->fetch_assoc();
	$cod_ciudad=$registro["ciudad"];
	$ahora=ahora($cod_ciudad,$conexion);

	$n=count($mod_act);
	for ($i=0; $i < $n; $i++) { 
		if (!in_array($mod_act[$i], $mod_ant)) {
			$cod_modalidad=$mod_act[$i];
			$consulta="INSERT INTO planillas (clavadista, entrenador, competencia, modalidad, categoria,  sexo,  equipo, usuario_alta, momento_alta) 
			VALUES ($cod_clavadista, $cod_entrenador, $cod_competencia, '$cod_modalidad', '$cod_categoria', '$sexo', '$cod_equipo', $cod_usuario, '$ahora')";
			$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		}
	}
	$conexion->close();
	// limpio variables de sesión
	$_SESSION["competencia"]="";
	$_SESSION["equipo"]=""; 
	$_SESSION["clave"]="";
	$_SESSION["clavadista"]="";
	$_SESSION["clavadista2"]="";
	$_SESSION["cod_clavadista"]="";
	$_SESSION["cod_clavadista2"]="";
	$_SESSION["categoria"]="";
	$_SESSION["modalidades"]="";
	$_SESSION["imagen"]="";
	$_SESSION["imagen2"]="";
	$_SESSION["modalidades"]="";
	$mensaje="Se actualizó la inscripción del clavadista <b>$clavadista</b> en la competencia";
		
	if (is_null($llamo))
		$transfer="Location: ..?op=php/todas-competencias.php&mensaje=$mensaje";
	else{
		$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
		if (!is_null($llamados)) 
			if (array_key_exists($llamador, $llamados)){
				unset($llamados[$llamador]);
				$_SESSION["llamados"]=$llamados;
			}
		$transfer="Location: ..?op=php/$llamo&mensaje=$mensaje";
	}
		
	header($transfer);
	exit();
}
header("Location: ..?op=php/edita-clavadista-competencia.php&mensaje=$mensaje");
exit();
 ?>