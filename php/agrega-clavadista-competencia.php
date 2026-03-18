<?php 
$alta=1;

include("valida-clavadista-competencia.php");

if (is_null($mensaje)){
	$conexion=conectarse();
	$consulta="SELECT ciudad FROM competencias WHERE (cod_competencia=$cod_competencia)";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$registro=$ejecutar_consulta->fetch_assoc();
	$cod_ciudad=$registro["ciudad"];
	$ahora=ahora($cod_ciudad);
	$ar_modal=explode("-", $modalidades);
	$n=count($ar_modal);
	if ($sexo2) 
		if ($sexo!=$sexo2) 
			$sexo="X";
	if ($sexo!="X")
		if ($sexo3) 
			if ($sexo!=$sexo2 or $sexo!=$sexo3 or $sexo2!=$sexo3) 
				$sexo="X";
	if ($sexo!="X")
		if ($sexo4) 
			if ($sexo!=$sexo2 or $sexo!=$sexo3 or $sexo!=$sexo4 
				or $sexo2!=$sexo3 or $sexo2!=$sexo4
				or $sexo3!=$sexo4) 
				$sexo="X";

		
	for ($i=0; $i < $n; $i++) { 
		$cod_modalidad=$ar_modal[$i];
		$consulta="INSERT INTO planillas (clavadista, entrenador, competencia, modalidad, categoria,  sexo,  equipo, usuario_alta, momento_alta";

		if ($clavadista2) 
			$consulta .= ", clavadista2";
		if ($sexo2) 
			$consulta .= ", sexo2";
		if ($clavadista3) 
			$consulta .= ", clavadista3";
		if ($sexo3) 
			$consulta .= ", sexo3";
		if ($clavadista4) 
			$consulta .= ", clavadista4";
		if ($sexo4) 
			$consulta .= ", sexo4";

		$consulta .= ") VALUES ($cod_clavadista, $cod_entrenador, $cod_competencia, '$cod_modalidad', '$cod_categoria', '$sexo', '$cod_equipo', $cod_usuario, '$ahora'";
		 
		if ($clavadista2) 
			$consulta .= ", $cod_clavadista2";
		if ($sexo2) 
			$consulta .= ", '$sexo2'";
		if ($clavadista3) 
			$consulta .= ", $cod_clavadista3";
		if ($sexo3) 
			$consulta .= ", '$sexo3'";

		if ($clavadista4) 
			$consulta .= ", $cod_clavadista4";
		if ($sexo4) 
			$consulta .= ", '$sexo4'";
		$consulta .= ")";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	}

	if ($ejecutar_consulta){
		unset($_SESSION["competencia"]);
		unset($_SESSION["equipo"]); 
		unset($_SESSION["clave"]);
		unset($_SESSION["clavadista"]);
		unset($_SESSION["cod_clavadista"]);
		unset($_SESSION["edad"]);
		unset($_SESSION["sexo"]);
		unset($_SESSION["imagen"]);
		unset($_SESSION["clavadista2"]);
		unset($_SESSION["cod_clavadista2"]);
		unset($_SESSION["edad2"]);
		unset($_SESSION["imagen2"]);
		unset($_SESSION["clavadista3"]);
		unset($_SESSION["cod_clavadista3"]);
		unset($_SESSION["edad3"]);
		unset($_SESSION["imagen3"]);
		unset($_SESSION["clavadista4"]);
		unset($_SESSION["cod_clavadista4"]);
		unset($_SESSION["edad4"]);
		unset($_SESSION["imagen4"]);
		unset($_SESSION["categoria"]);
		unset($_SESSION["modalidades"]);
		unset($_SESSION["sexo2"]);
		unset($_SESSION["sexo3"]);
		unset($_SESSION["sexo4"]);
		unset($_SESSION["individual"]);
		unset($_SESSION["pareja"]);
		unset($_SESSION["eq_juv"]);
		$mensaje="Quedó inscrito el clavadista <b>$clavadista</b> en la competencia :)";
		$conexion->close();
		if (is_null($llamo))
			header("Location: ..?op=php/alta-clavadista-competencia.php&mensaje=$mensaje");
		else
			header("Location: ..?op=php/$llamo&mensaje=$mensaje");
		
		exit();
	}	
	else{
		$conexion->close();
		$mensaje="No se pudo inscribir al clavadista <b>$clavadista</b> en la competencia :(";
	}
}

header("Location: ..?op=php/alta-clavadista-competencia.php&mensaje=$mensaje :(");


 ?>