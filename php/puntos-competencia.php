<?php 
session_start();
include("funciones.php");
if (isset($_GET["com"])){
	$competencia=trim($_GET["com"]);
	$cod_competencia=trim($_GET["cco"]);
	$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;
	$llamo="muestra-competencia.php&com=$competencia&cco=$cod_competencia";
	$puntos = array();
	$consulta="SELECT * FROM competenciast WHERE competencia=$cod_competencia";
	$conexion=conectarse();
	$ejecutar_consulta=$conexion->query(utf8_decode($consulta));
	if ($ejecutar_consulta) {
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$i=$row["puesto"];
			$puntos[$i]=$i."/".$row["puntos"]."/".$row["puntos_sinc"];
		}
	}
	$conexion->close();
	if (!isset($i)) 
		for ($i=1; $i < 12; $i++) 
			$puntos[$i]=$i."/0/0";
}
	 
if (is_null($llamo))
	$llamo=isset($_SESSION["llamo"])?trim($_SESSION["llamo"]): NULL;

if (is_null($competencia))
	$competencia=isset($_SESSION["competencia"])?trim($_SESSION["competencia"]): NULL;
if (is_null($cod_competencia))
	$cod_competencia=isset($_SESSION["cod_competencia"])?trim($_SESSION["cod_competencia"]): NULL;

if (is_null($logo))
	$logo=isset($_SESSION["logo"])?trim($_SESSION["logo"]): NULL;

if (is_null($puntos))
	$puntos=isset($_SESSION["puntos"])?$_SESSION["puntos"]:NULL;

?>
<form id="puntos-competencia" name="puntos-frm" action="php/agrega-puntos-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
			<?php 
				if ($logo) 
					echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
					echo $competencia; 
			?>
		</legend>
		<h3>Puntos por Puesto</h3>
		<?php include ("formulario-puntos.php"); ?>
	</fieldset>
</form>