<?php 
// Asigno a variables php los valores que vienen en el formulario
$llamo=isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):NULL;
$cod_planilla=isset($_POST["cod_planilla_hdn"])?$_POST["cod_planilla_hdn"]:NULL;

$competencia=(isset($_POST["competencia_hdn"]) ? $_POST["competencia_hdn"] :null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"]) ? $_POST["cod_competencia_hdn"] :null);
$logo=(isset($_POST["logo_hdn"]) ? $_POST["logo_hdn"] :null);
$equipo=(isset($_POST["equipo_txt"]) ? $_POST["equipo_txt"] :null);
$clavadista=(isset($_POST["clavadista_txt"]) ? $_POST["clavadista_txt"] :null);
$cod_clavadista=(isset($_POST["cod_clavadista_hdn"])?$_POST["cod_clavadista_hdn"]:null);

$clavadista2=(isset($_POST["clavadista2_txt"]) ? $_POST["clavadista2_txt"] :null);
$cod_clavadista2=(isset($_POST["cod_clavadista2_hdn"])?$_POST["cod_clavadista2_hdn"]:null);

$participa_extraof=(isset($_POST["participa_extraof_chk"])?$_POST["participa_extraof_chk"]:null);

session_start();
if (isset($_POST["regresar_sbm"])){
	include("limpia-planilla.php");
	$llamo=str_replace("*", "&", $llamo);
	header("Location: ..?op=$llamo");
	exit();
}
$cod_sexo=isset($_POST["cod_sexo_hdn"])?$_POST["cod_sexo_hdn"]:NULL;
$cod_sexo2=isset($_POST["cod_sexo2_hdn"])?$_POST["cod_sexo2_hdn"]:NULL;
$_SESSION["cod_sexo"]=$cod_sexo;
$_SESSION["cod_sexo2"]=$cod_sexo2;
$nacimiento=isset($_POST["nacimiento_hdn"])?$_POST["nacimiento_hdn"]:NULL;
$nacimiento2=isset($_POST["nacimiento2_hdn"])?$_POST["nacimiento2_hdn"]:NULL;
$_SESSION["nacimiento"]=$nacimiento;
$_SESSION["nacimiento2"]=$nacimiento2;
$_SESSION["llamo"]= isset($llamo)?$llamo:NULL;
$_SESSION["cod_planilla"]=isset($cod_planilla)?$cod_planilla:NULL;
$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["logo"]=$logo;
$_SESSION["participa_extraof"]=$participa_extraof;

include("funciones.php");
include("valida-planilla-equ.php");

if (is_null($mensaje)){
	$verificado=(isset($_POST["verifica_btn"]) ? 1: 0);
	$modificar=(isset($_POST["modifica_btn"]) ? 1 :0);
	$guardar=(isset($_POST["guarda_btn"]) ? 1:0);

	if ($verificado==1) {
		// si ya verificó y está OK habilita para guardar.
		$mensaje="La planilla está verificada y lista para guardar";
		if (!is_null($menos_saltos))
			$mensaje=$menos_saltos.". Pero puede guardar la planilla";
		header("Location: ..?op=php/cambio-planilla-equ.php&mensaje=$mensaje&ok=1");
		exit();
	}

	if ($modificar==1) {
		// habilita para modificar.
		$mensaje=NULL;
		header("Location: ..?op=php/cambio-planilla-equ.php&ok=0");
		exit();
	}

	if ($guardar==0) {
		// está todo OK pero no seleccionó Guardar
		header("Location: ..?op=php/cambio-planilla-equ.php&ok=1");
		exit();
	}

	// procedo a actualizar la base de datos
	$cod_categoria=substr($categoria,0,2);
	$cod_equipo=determina_equipo($equipo,$cod_competencia);
	$usuario=$_SESSION["usuario_id"];
	$conexion=conectarse();
	$consulta="SELECT ciudad FROM competencias 
		WHERE (cod_competencia=$cod_competencia)";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

	$registro=$ejecutar_consulta->fetch_assoc();
	$cod_ciudad=$registro["ciudad"];
	$ahora=ahora($cod_ciudad);

	// Actualizo el encabezado de la planilla
	$consulta="UPDATE planillas
		SET modalidad='$cod_modalidad',
			categoria='$cod_categoria',
			usuario_modifico=$usuario,
			momento_modifica='$ahora',
			participa_extraof='$participa_extraof'";
	if (isset($cod_clavadista2))
		$consulta .= ", clavadista2=$cod_clavadista2 ";
	$consulta .= " WHERE cod_planilla=$cod_planilla";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	
	// leo las rondas de los saltos grabados anteriormente
	$rondas=array();
	$consulta="SELECT ronda FROM planillad
			WHERE planilla=$cod_planilla";	
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	while ($reg=$ejecutar_consulta->fetch_assoc())
		$rondas[]=$reg["ronda"];

	// borro los saltos que quitaron
	$n=count($rondas);
	$tot_sal=count($saltos);
	for ($i=0; $i < $n; $i++) { 
		$ronda=$rondas[$i];
		if ($ronda>$tot_sal){
			$consulta="DELETE FROM planillad 
				WHERE planilla=$cod_planilla 
					and ronda=$ronda and calificado is null";
			$ejecutar_consulta=$conexion->query($consulta);
		}
	}
	// borro los saltos que quitaron  (Efectiva)
	for ($i=0; $i < $n; $i++) { 
		if ($rondas[$i]){
			$ronda=$rondas[$i];
			if (!($saltos[$ronda])) {
				$consulta="DELETE FROM planillad 
					WHERE planilla=$cod_planilla 
					and ronda=$ronda";
				$ejecutar_consulta=$conexion->query($consulta);
			}
		}
	}
		
	// si una ronda estaba grabada la modifico sino la agrego
	for ($i=1; $i<=$tot_sal ; $i++) { 
		$salto=$saltos[$i];
		$cod=$salto["cod_salto"];
		$pos=$salto["pos"];
		$alt=$salto["alt"];
		$dif=$salto["gra_dif"];
		$obl=$salto["obl"]?$salto["obl"]:'NULL';
		$eje=$salto["eje"];

		if (in_array($i, $rondas)){
			$consulta=
				"UPDATE planillad 
					SET salto='$cod', 
						posicion='$pos',
						altura=$alt,
						grado_dif=$dif, 
						obligatorio=$obl";
/*			if ($obl) 
				$consulta .= ", obligatorio=$obl";
			else
				$consulta .= ", obligatorio=NULL";
*/
			if ($eje) 
				$consulta .= ", ejecutor=$eje";
			else
				$consulta .= ", ejecutor=NULL";

			$consulta .= " WHERE  planilla=$cod_planilla and ronda=$i";
		}
		else{
			$consulta=
				"INSERT INTO planillad (planilla, ronda, salto, posicion, altura, grado_dif, obligatorio, ejecutor";
			
			$consulta .= ") VALUES ($cod_planilla,$i,'$cod','$pos', $alt, $dif";
			if ($obl) 
				$consulta .= ", $obl";
			else
				$consulta .= ", NULL";
			if ($eje) 
				$consulta .= ", $eje";
			else
				$consulta .= ", NULL";
			
			$consulta .= ")";
		}
		$ejecutar_consulta=$conexion->query($consulta);
	}	
	// recalculo el puntaje de todos los saltos calificados
	$consulta="SELECT evento FROM planillas
		WHERE cod_planilla=$cod_planilla";
	$ejecutar_consulta=$conexion->query($consulta);
	$reg=$ejecutar_consulta->fetch_assoc();
	$numero_evento=$reg["evento"];
	
	if ($numero_evento) 
		$num_jueces=numero_jueces($cod_competencia,$numero_evento,1);
	
	$sincronizado=$cod_clavadista2==0?0:1;
	if ($sincronizado) 
		if ($categoria=="EQ") 
			$sincronizado=0;
		
	
	$consulta_saltos=
		"SELECT ronda, grado_dif, penalizado, cal1, cal2, cal3, cal4, cal5, cal6, cal7, cal8, cal9, cal10, cal11
			FROM planillad
			WHERE planilla=$cod_planilla 
				and calificado=1
			ORDER BY ronda";
	$ejecutar_consulta_saltos = $conexion->query($consulta_saltos);
	if ($ejecutar_consulta){
		while ($reg=$ejecutar_consulta_saltos->fetch_assoc()) {
			$ronda=$reg["ronda"];
			$cal=array();
			$grado_dificultad=$reg["grado_dif"];
			$cal[1]=$reg["cal1"];
			$cal[2]=$reg["cal2"];
			$cal[3]=$reg["cal3"];
			$cal[4]=$reg["cal4"];
			$cal[5]=$reg["cal5"];
			$cal[6]=$reg["cal6"];
			$cal[7]=$reg["cal7"];
			$cal[8]=$reg["cal8"];
			$cal[9]=$reg["cal9"];
			$cal[10]=$reg["cal10"];
			$cal[11]=$reg["cal11"];
			$penalidad=is_null($reg["penalizado"])?NULL:$reg["penalizado"];
			if (!is_null($penalidad))
				for ($i=1; $i <$num_jueces+1 ; $i++)  
					$cal[$i] -=$penalidad;
				
			$t_salto=suma($cal,$sincronizado,$num_jueces);
			$p_salto=$t_salto*$grado_dificultad;
			$actualiza=
				"UPDATE planillad 
					SET total_salto=$t_salto, 
						puntaje_salto=$p.salto
					WHERE  planilla=$cod_planilla and ronda=$ronda";
			$ejecutar_actualiza=$conexion->query($actualiza);
		}
		// Recalcula las posiciones de competencia

		if ($numero_evento) {
			$criterio=" WHERE (p.competencia=$cod_competencia AND p.evento=$numero_evento AND p.usuario_retiro IS NULL";
			include("ordena-posiciones.php");
		}
	}	

	$mensaje="Se ha modificado la planilla :)";
	$conexion->close();
	include("limpia-planilla.php");

	$llamo=str_replace("*", "&", $llamo);
	if (!isset($llamo) or is_null($llamo) or $llamo==" ")
		$llamo="php/todas-competencias.php";

	header("Location: ..?op=$llamo&mensaje=$mensaje");
	exit();
} 
header("Location: ..?op=php/cambio-planilla-equ.php&mensaje=$mensaje");
 ?>