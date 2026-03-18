<?php 
include("funciones.php");
// Asigno variables de PHP a los valores que vienen del formulario

$competencia=(isset($_POST["competencia_hdn"]) ? $_POST["competencia_hdn"] :null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"]) ? $_POST["cod_competencia_hdn"] :null);
$logo=(isset($_POST["logo_hdn"]) ? $_POST["logo_hdn"] :null);
$equipo=(isset($_POST["equipo_txt"]) ? $_POST["equipo_txt"] :null);
$clavadista=(isset($_POST["clavadista_txt"]) ? $_POST["clavadista_txt"] :null);

$cod_clavadista=(isset($_POST["cod_clavadista_hdn"])?$_POST["cod_clavadista_hdn"]:null);
$cod_clavadista2=(isset($_POST["cod_clavadista2_hdn"])?$_POST["cod_clavadista2_hdn"]:null);

session_start();
$_SESSION["cod_clavadista"]=isset($cod_clavadista)?$cod_clavadista:NULL;
$_SESSION["cod_clavadista2"]=isset($cod_clavadista2)?$cod_clavadista2:NULL;

$buscar=isset($_SESSION["buscar"])?trim($_SESSION["buscar"]):NULL;
if (isset($_POST["regresar_sbm"])) {
	$_SESSION["saltos"]="";
	$_SESSION["modalidad"]="";
	$_SESSION["clave"]="";
	header("Location: ..?op=php/planillas-competencia.php&com=$competencia&eq=$equipo&cl=$clavadista&ccl=$cod_clavadista&bus=$buscar");
	exit();
}
	
$llamo="alta-planilla.php";

include("valida-planilla.php");

if (is_null($mensaje)){
	$verificado=(isset($_POST["verifica_btn"]) ? 1: 0);
	$modificar=(isset($_POST["modifica_btn"]) ? 1 :0);
	$guardar=(isset($_POST["guarda_btn"]) ? 1:0);
	if ($verificado==1) {
		$mensaje="Planilla verificada y lista para guardar";
		header("Location: ..?op=php/$llamo&mensaje=$mensaje&ok=1");
		exit();
	}

	if ($modificar==1) {
		$mensaje="";
		header("Location: ..?op=php/$llamo&ok=0");
		exit();
	}

	if ($guardar==0) {
		header("Location: ..?op=php/$llamo&ok=1");
		exit();
	}

	$cod_categoria=substr($categoria,0,2);
	if ($sexo=="Masculino") 
		$cod_sexo="M";
	else
		$cod_sexo="F";

	$cod_equipo=determina_equipo($equipo,$cod_competencia);
	$cod_usuario=$_SESSION["usuario_id"];
	$conexion=conectarse();
	$consulta="SELECT ciudad FROM competencias WHERE (cod_competencia=$cod_competencia)";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$registro=$ejecutar_consulta->fetch_assoc();

	$cod_ciudad=$registro["ciudad"];
	$ahora=ahora($cod_ciudad,$conexion).".000";
	if (is_null($clavadista2))
		$consulta="INSERT INTO planillas (clavadista, entrenador, competencia, modalidad, categoria, sexo, equipo, usuario_alta, momento_alta, participa_extraof) VALUES ($cod_clavadista, $cod_entrenador, $cod_competencia,'$cod_modalidad','$cod_categoria','$cod_sexo','$cod_equipo',  $cod_usuario,' $ahora', '$participa_extraof')";
	else
		$consulta="INSERT INTO planillas (clavadista, entrenador, competencia, modalidad, categoria, sexo, equipo, clavadista2, usuario_alta, momento_alta, participa_extraof) VALUES ($cod_clavadista, $cod_entrenador, $cod_competencia,'$cod_modalidad','$cod_categoria','$cod_sexo','$cod_equipo', $cod_clavadista2, $cod_usuario,' $ahora', '$participa_extraof')";

	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	if ($ejecutar_consulta){
		$rec=$conexion->insert_id;
		$tot_sal=count($saltos);
		for ($i=1; $i<=$tot_sal ; $i++) { 
			$salto=explode("/", $saltos[$i]);
			$cod=$salto[0];
			$pos=$salto[1];
			$alt=$salto[2];
			$abi=$salto[3];
			$dif=$salto[5];
			$consulta="INSERT INTO planillad (planilla, ronda, salto, posicion, altura, grado_dif, abierto) VALUES ($rec,$i,'$cod','$pos', $alt, $dif,'$abi')";
			$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		}
		$mensaje="Se ha dado de alta la planilla :)";
	}
	else
		$mensaje="No se pudo dar de alta la planilla :(";

	$conexion->close();
	$_SESSION["participa_extraof"]="";
	$_SESSION["saltos"]="";
	$_SESSION["modalidad"]="";
	header("Location: ..?op=php/$llamo&comp=$competencia&mensaje=$mensaje&bus=$buscar");
	exit();
}
else
	header("Location: ..?op=php/$llamo&mensaje=$mensaje");
?>