<?php 
include("conexion.php");
$consulta="SELECT * FROM competenciasc ";
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
while ($row=$ejecutar_consulta->fetch_assoc()){
	$competencia=$row["competencia"];
	$clavadista=$row["clavadista"];
	$clavadista2=$row["clavadista2"];
	$categoria=$row["categoria"];
	$equipo=$row["equipo"];
	$modalidades=$row["modalidades"];
	$ar_mod=explode("-", $modalidades);
	$n=count($ar_mod);
	$modalidad=$ar_mod[0];
	$actualiza="UPDATE competenciasc 
			SET modalidad='$modalidad'
			where competencia=$competencia
				AND clavadista=$clavadista
				and equipo='$equipo'
				and categoria='$categoria'";
	if ($clavadista2>0)
		$catualiza .= " AND  clavadista2=$clavadista2";

	$ejecutar_actualiza = $conexion->query($actualiza);
	}

$consulta="SELECT * FROM competenciasc ";
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
while ($row=$ejecutar_consulta->fetch_assoc()){
	$modalidades=$row["modalidades"];
	$ar_mod=explode("-", $modalidades);
	$n=count($ar_mod);
	if ($n>1) {
		$competencia=$row["competencia"];
		$clavadista=$row["clavadista"];
		$clavadista2=$row["clavadista2"];
		$categoria=$row["categoria"];
		$equipo=$row["equipo"];
		$entrenador=$row["entrenador"];
		$momento_alta=$row["momento_alta"];
		$usuario_alta=$row["usuario_alta"];
		$momento_modif=$row["momento_modif"];
		$usuario_modif=$row["usuario_modif"];

		for ($j=1; $j < $n; $j++) { 
			$modalidad=$ar_mod[$j];
			$agrega="INSERT INTO `competenciasc`(`competencia`, `clavadista`, `clavadista2`, `categoria`, `modalidad`, `equipo`, `entrenador`, `modalidades`, `momento_alta`, `usuario_alta`";
			if (isset($usuario_modif)) 
				$agrega .= ", `momento_modif`, `usuario_modif`)";
			else
				$agrega .= ")";
			$agrega.="VALUES ($competencia,$clavadista,$clavadista2,'$categoria','$modalidad','$equipo',$entrenador,NULL,'$momento_alta',$usuario_alta";
			
			if (isset($usuario_modif)) 
				$agrega .= ",'$momento_modif',$usuario_modif)";
			else
				$agrega .= ")";

			$ejecutar_agrega = $conexion->query($agrega);

		}

	}
}

?>