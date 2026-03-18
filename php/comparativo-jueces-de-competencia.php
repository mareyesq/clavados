<?php
global $jueces;

if (isset($_GET["nev"])) {
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:NULL;
	$fechahora=trim($_GET["fh"]);
	$competencia=trim($_GET["com"]);
	$modalidad=trim($_GET["mod"]);
	$cat=trim($_GET["cat"]) ;
	$sx=trim($_GET["sx"]);
	$tipo=trim($_GET["tp"]);
	$evento=trim($_GET["ev"]);
	$numero_evento=trim($_GET["nev"]);
	$descripcion=utf8_encode(trim($_GET["des"]));
	$fecha=trim($_GET["ho"]);
	include("funciones.php");
}
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
switch ($tipo) {
	case 'P':
		$tipo_competencia="Preliminar";
		break;
	case 'S':
		$tipo_competencia="Semifinal";
		break;
	case 'F':
		$tipo_competencia="Final";
		break;
}
$categorias=explode("-", $cat) ;
$sexos=explode("-", $sx);

$jueces=array();
$eval=evaluacion_jueces($cod_competencia,$numero_evento);

$descripcion=utf8_decode($descripcion);
$encabezado="Evento No. ".$evento." ".$fecha." - ".$descripcion;
?>
<form id="comparativo-jueces-de-competencia" name="comparativo-jueces-de-competencia-frm" action="?op=php/comparativo-jueces-de-competencia-1.php" method="post" enctype="multipart/form-data">
	<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" >
	<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" >
	<?php include("titulo-competencia.php") ?>
	<h2>Comparativo de Jueces de Competencia</h2>
	<h3><?php echo $encabezado ?></h3>
	<input type="submit" name="regresar_sbm" value="Regresar" >
 	<div id="div1">
	<table width='100%'  class="tablas">
	
	<?php 
		$columnas=3+count($jueces)*2;
		$cat_sex_ant=NULL;
		$max_fila=8;
		$fila=$max_fil;
		foreach ($eval as $key => $row) {
			$cat_sex=isset($row['cat_sex'])?$row['cat_sex']:NULL;
			if ($cat_sex!=$cat_sex_ant){
				include('comparativo-jueces-de-competencia-encab.php'); 
				$fila=0;
				$des_cat_sex=descripcion_cat_sex($cat_sex);
				echo "<tr align='center'><td colspan='$columnas'>$des_cat_sex</td></tr>" ;
				$cat_sex_ant=$cat_sex;
				$fila++;
			} 
			$fila++;
			if ($fila>$max_fila){
				include('comparativo-jueces-de-competencia-encab.php'); 
				$fila=0;
			}
			$lugar=isset($row['lugar'])?$row['lugar']:NULL;
			$nom_clavadista=isset($row['nom_clavadista'])?$row['nom_clavadista']:NULL;
			$total=isset($row['total'])?number_format($row['total'],2):NULL;
			$clavadista1=isset($row['clavadista1'])?$row['clavadista1']:NULL;
			$puntaje1=isset($row['puntaje1'])?number_format($row['puntaje1'],2):NULL;
			$clavadista2=isset($row['clavadista2'])?$row['clavadista2']:NULL;
			$puntaje2=isset($row['puntaje2'])?number_format($row['puntaje2'],2):NULL;
			$clavadista3=isset($row['clavadista3'])?$row['clavadista3']:NULL;
			$puntaje3=isset($row['puntaje3'])?number_format($row['puntaje3'],2):NULL;
			$clavadista4=isset($row['clavadista4'])?$row['clavadista4']:NULL;
			$puntaje4=isset($row['puntaje4'])?number_format($row['puntaje4'],2):NULL;
			$clavadista5=isset($row['clavadista5'])?$row['clavadista5']:NULL;
			$puntaje5=isset($row['puntaje5'])?number_format($row['puntaje5'],2):NULL;
			$clavadista6=isset($row['clavadista6'])?$row['clavadista6']:NULL;
			$puntaje6=isset($row['puntaje6'])?number_format($row['puntaje6'],2):NULL;
			$clavadista7=isset($row['clavadista7'])?$row['clavadista7']:NULL;
			$puntaje7=isset($row['puntaje7'])?number_format($row['puntaje7'],2):NULL;
			echo "<tr align='center'>";  
    		echo "<td>".$lugar."</td>";
    		echo "<td>".$nom_clavadista."</td>";
    		echo "<td>".$total."</td>";
			if ($jueces[1]){
				if ($clavadista1!=$nom_clavadista)
					$cl='error';
				else
					$cl='cambio';
				echo "<td class='".$cl."'>$clavadista1</td><td class='".$cl."'>$puntaje1</td>";
			} 
			if ($jueces[2]) {
				if ($clavadista2!=$nom_clavadista)
					$cl='error';
				else
					$cl='cambio';
				echo "<td class='".$cl."'>$clavadista2</td><td class='".$cl."'>$puntaje2</td>";
			}
			if ($jueces[3]) {
				if ($clavadista3!=$nom_clavadista)
					$cl='error';
				else
					$cl='cambio';
				echo "<td class='".$cl."'>$clavadista3</td><td class='".$cl."'>$puntaje3</td>";
			}
			if ($jueces[4]) {
				if ($clavadista4!=$nom_clavadista)
					$cl='error';
				else
					$cl='cambio';
				echo "<td class='".$cl."'>$clavadista4</td><td class='".$cl."'>$puntaje4</td>";
			}
			if ($jueces[5]) {
				if ($clavadista5!=$nom_clavadista)
					$cl='error';
				else
					$cl='cambio';
				echo "<td class='".$cl."'>$clavadista5</td><td class='".$cl."'>$puntaje5</td>";
			}
			if ($jueces[6]) {
				if ($clavadista6!=$nom_clavadista)
					$cl='error';
				else
					$cl='cambio';
				echo "<td class='".$cl."'>$clavadista6</td><td class='".$cl."'>$puntaje6</td>";
			}
			if ($jueces[7]) {
				if ($clavadista7!=$nom_clavadista)
					$cl='error';
				else
					$cl='cambio';
				echo "<td class='".$cl."'>$clavadista7</td><td class='".$cl."'>$puntaje7</td>";
			}
			echo "</tr>"; 
		}				
	?>
	</table> 
	</div>
</form>