<?php 
if (isset($_GET["ori"])) {
	$buscar=(isset($_GET["bus"])?trim($_GET["bus"]):NULL);
	$llamo=(isset($_GET["ori"])?trim($_GET["ori"]):NULL);
	$llamo=str_replace("*", "&", $llamo);
	$todos=(isset($_GET["t"])?$_GET["t"]:NULL);
	$competencia=(isset($_GET["comp"])?$_GET["comp"]:NULL);
	$cod_competencia=(isset($_GET["cco"])?$_GET["cco"]:NULL);
	$ubi=isset($_GET['ubi'])?$_GET['ubi']:NULL;
}
$tipo=isset($_GET["tipo"])?$_GET["tipo"]:NULL;

$llamador="php/busca-juez.php*tipo=$tipo";

if (!isset($llamo)) {
	$llamo=isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL;
	if (isset($llamo))
		$llamo=str_replace("*", "&", $llamo);
}
session_start();
$logo=isset($_SESSION['logo'])?$_SESSION['logo']:NULL;
$logo2=isset($_SESSION['logo2'])?$_SESSION['logo2']:NULL;
if (isset($_GET["ubi"])) {
	$ubi=$_GET["ubi"];
	$_SESSION["ubi"]=$ubi;
}

if (is_null($ubi)) 
	$ubi=$_SESSION["ubi"];

if (!isset($buscar)) 
	$buscar=(isset($_SESSION["buscar"])?$_SESSION["buscar"]:NULL);

if (!isset($competencia))
	$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL);

$consulta="SELECT DISTINCT u.cod_usuario as cod_us, j.sombra, u.nombre as nombre, u.email as email, p.Country as pais, u.imagen
	FROM competenciasjz as j
	left join usuarios as u on u.cod_usuario=j.juez 
	left join countries as p on p.CountryId=u.pais 
	WHERE competencia=$cod_competencia";
if ($ubi<50)
	$consulta.=" and sombra is NULL";

if (strlen($buscar)>0){
	if (isset($criterio)) 
		$consulta.=" AND (u.nombre LIKE '%$buscar%' or u.email LIKE '%$buscar%' or p.Country LIKE '%$buscar%')";
}

$consulta .= " ORDER BY u.nombre";

include("funciones.php");
$conexion=conectarse();
$ejecutar_consulta = $conexion->query($consulta);
?>

<form id="buscar-jueces" name="buscar-jueces-frm" action="?op=php/busca-juez-competencia-1.php" method="post" enctype="multipart/form-data">
<fieldset>
	<?php include("titulo-competencia.php") ?>
	<h2>Buscar Jueces de la Competencia</h2>
	<div>
		<label for="buscar" >Buscar: </label>
		<input type="search" class="cambio" id="buscar" name="buscar_src" value="<?php echo $buscar; ?>">
		<input type="submit" class="cambio" name="buscar_sbm" value="Buscar" title="Busca jueces de la competencia>">
		<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	<div id='div1' class='scrollbars'>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align='center' >
		<th>Juez</th>
		<th>Pais</th>	  
		<th>Tipo</th>	  

	</tr> 

	<?php 

		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs){
			$mensaje="No hay jueces registrados en la competencia ";
			if ($buscar) $mensaje=$mensaje." con nombre $buscar";
			$mensaje=$mensaje." :(";
		}
		else
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_us=$row["cod_us"];
				$nombre=utf8_decode($row["nombre"]);
//				$nombre=strtolower($nombre);
//				$nombre=ucwords($nombre);
				$imagen=utf8_encode($row["imagen"]);
				$pais=utf8_decode($row["pais"]);
				$nom_usu=$nombre;
				$sombra=isset($row['sombra'])?$row['sombra']:NULL;
				$tipo=$sombra?"Sombra":"Oficial";
    			echo "<td align='left'><a class='enlaces' href='?op=".$llamo."&us=$nombre&cod=$cod_us&comp=$competencia&tipo=$tipo&parael2=$parael2&img=$imagen&ubi=$ubi&sx=$sexo&ed=$edad&cco=$cod_competencia'>";
				if ($imagen) 
	    			echo "<img class='textwrap'src='img/fotos/$imagen' width='4%'/>&nbsp;&nbsp;";
				echo "$nom_usu</a></td>";
    			echo "<td>".$pais."</td>";  
    			echo "<td>".$tipo."</td>";  
    			echo "</tr>"; 
		}	
		$conexion->close();
	?>
	</table> 
	</div>
	<div>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo; ?>" />
		<input type="hidden" id="llamador" name="llamador_hdn" value="<?php echo $llamador; ?>" />
		<input type="hidden" id="tipo" name="tipo_hdn" value="<?php echo $tipo ?>" />
	</div>
</fieldset>
</form>