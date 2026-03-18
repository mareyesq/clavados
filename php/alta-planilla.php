<?php 
if (isset($_GET["competencia"])){
	$competencia=isset($_GET["comp"])?$_GET["comp"]:NULL;
	$equipo=isset($_GET["eq"])?$_GET["eq"]:NULL;
	$entrenador=isset($_GET["ent"])?$_GET["ent"]:NULL;
	$clavadista1=isset($_GET["cl"])?$_GET["cl"]:NULL;
	$cod_clavadista=isset($_GET["ccl"])?$_GET["ccl"]:NULL;
	$imagen=isset($_GET["img"])?$_GET["img"]:NULL;
	$buscar=isset($_GET["bus"])?$_GET["bus"]:NULL;
}

if (isset($_GET["parael2"])){
	if ($_GET["parael2"]=="si") {
		$clavadista2=$_GET["us"];
		$cod_clavadista2=$_GET["cod"];
		$imagen2=$_GET["img"];
	}
}

if (!isset($competencia)) 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);

if (!isset($cod_competencia)) 
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
if (!isset($logo)) 
	$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null);

if (!isset($equipo)) 
	$equipo=(isset($_POST["equipo_hdn"])?$_POST["equipo_hdn"]:null);

if (!isset($clavadista))
	$clavadista=(isset($_POST["clavadista_hdn"])?$_POST["clavadista_hdn"]:null);

if (!isset($clavadista2))
	$clavadista2=(isset($_POST["clavadista2_hdn"])?$_POST["clavadista2_hdn"]:null);

if (!isset($entrenador))
	$entrenador=(isset($_POST["entrenador_hdn"])?$_POST["entrenador_hdn"]:null);

if (!isset($cod_clavadista))
	$cod_clavadista=(isset($_POST["cod_clavadista_hdn"])?$_POST["cod_clavadista_hdn"]:null);
if (!isset($cod_clavadista2))
	$cod_clavadista2=(isset($_POST["cod_clavadista2_hdn"])?$_POST["cod_clavadista2_hdn"]:null);

if (!isset($cod_entrenador)) 
	$cod_entrenador=(isset($_POST["cod_entrenador_hdn"])?$_POST["cod_entrenador_hdn"]:null);

if (!isset($imagen)) 
	$imagen=(isset($_POST["imagen_hdn"])?$_POST["imagen_hdn"]:null);

if (!isset($imagen2))
	$imagen2=(isset($_POST["imagen2_hdn"])?$_POST["imagen2_hdn"]:null);

if (!isset($buscar))
	$buscar=(isset($_POST["buscar_hdn"])?$_POST["buscar_hdn"]:null);

if (!isset($participa_extraof)) 
	$participa_extraof=(isset($_POST["participa_extraof_hdn"])?$_POST["participa_extraof_hdn"]:null);

if (isset($_POST["regresar_sbm"])){
	header("Location: ?op=php/clavadistas-competencia.php&com=$competencia&cco=$cod_competencia&eq=$equipo&bus=$buscar");
	exit();
}

session_start();
$cod_usuario=(isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:null);

if (!isset($cod_usuario)){
	$llamados=$_SESSION["llamados"];
	if (isset($llamados)){
		$llamados["inicia-sesion.php"]="alta-planilla.php&comp=$competencia&eq=$equipo&ent=$entrenador&cl=$clavadista&ccl=$cod_clavadista";
		$_SESSION["llamados"]=$llamados;
	}
	$mensaje="Debe iniciar sesión para poder registrar planillas";
	header("Location: ?op=php/inicia-session.php&mensaje=$mensaje&ok=FALSE");
	exit();
}
$ok=(isset($_GET["ok"])?$_GET["ok"]:0);

$copia_saltos=isset($_SESSION["copia_saltos"])?$_SESSION["copia_saltos"]:NULL;

if (!isset($competencia))
	$competencia=$_SESSION["competencia"];
if (!isset($cod_competencia))
	$cod_competencia=$_SESSION["cod_competencia"];
if (!isset($logo))
	$logo=$_SESSION["logo"];
if (!isset($equipo))
	$equipo=$_SESSION["equipo"];
if (!$clave)
	$clave=trim($_SESSION["clave"]);
if (!isset($clavadista))
	$clavadista=$_SESSION["clavadista"];
if (!isset($entrenador))
	$entrenador=$_SESSION["entrenador"];
if (!isset($cod_clavadista))
	$cod_clavadista=$_SESSION["cod_clavadista"];
if (!isset($cod_entrenador))
	$cod_entrenador=$_SESSION["cod_entrenador"];
if (!isset($clavadista2))
	$clavadista2=$_SESSION["clavadista2"];
if (!isset($cod_clavadista2))
	$cod_clavadista2=$_SESSION["cod_clavadista2"];
if (!isset($categoria))
	$categoria=$_SESSION["categoria"];
if (!isset($imagen))
	$imagen=$_SESSION["imagen"];
if (!isset($imagen2))
	$imagen2=$_SESSION["imagen2"];
if (!isset($buscar))
	$buscar=$_SESSION["buscar"];
if (!isset($modalidad))
	$modalidad=$_SESSION["modalidad"];
if (!isset($reglamento))
	$reglamento=$_SESSION["reglamento"];
if (!isset($saltos))
	$saltos=$_SESSION["saltos"];

if (!isset($participa_extraof))
	$participa_extraof=$_SESSION["participa_extraof"];

if ($_GET["co"]) {
	$ronda=$_GET["ron"];
	$cod_salto=$_GET["co"];
	$nom_salto=$_GET["sal"];
	$salto=explode("/", $saltos[$ronda]);
	$cod=$salto[0];
	$pos=$salto[1];
	$alt=$salto[2];
	$abi=$salto[3];
	$sal=$salto[4];
	$dif=number_format($salto[5],1);
	$saltos[$ronda] = $cod_salto."/".$pos."/".$alt."/".$abi."/".$nom_salto; 
}
//	include ("saltos.php");

include("funciones.php");
$conexion=conectarse();
if (!($cod_clavadista==0)) {
	$consulta="SELECT nacimiento,sexo 
		from usuarios 
		WHERE cod_usuario=$cod_clavadista";
	$ejecutar_consulta=$conexion->query($consulta);

	$registro=$ejecutar_consulta->fetch_assoc();
	$nacimiento=$registro["nacimiento"];
}
if (!($cod_clavadista2==0)) {
	$consulta="SELECT nacimiento,sexo, imagen
		from usuarios 
		WHERE cod_usuario=$cod_clavadista2";
	$ejecutar_consulta=$conexion->query($consulta);

	$registro=$ejecutar_consulta->fetch_assoc();
	$nacimiento2=$registro["nacimiento"];
	$imagen2=$registro["imagen"];
	if ($registro["sexo"]=="M") 
		$sexo2="Masculino";
	if ($registro["sexo"]=="F") 
		$sexo2="Femenino";
}
$conexion->close();
if (!isset($categoria))
	$categoria=busca_categoria($nacimiento);
if ($registro["sexo"]=="M") 
	$sexo="Masculino";
if ($registro["sexo"]=="F") 
	$sexo="Femenino";
$alta=1;
$llamo="alta-planilla.php";
?>

<form id="alta-planilla" name="alta-frm" action="php/agrega-planilla.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
		<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
				echo $competencia; 
		?>
		</legend>
		<h3>Alta de Planilla</h3>

		<?php include ("formulario-planilla.php"); ?>
</form>
