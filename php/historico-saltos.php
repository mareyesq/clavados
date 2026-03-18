<?php 

session_start();
include("funciones.php");

$salto_sel=isset($_SESSION["salto_sel"])?trim($_SESSION["salto_sel"]):NULL;

if (!is_null($salto_sel) and $salto_sel!=="") {
	$ar=explode("-", $salto_sel);
	$salto=$ar[0];
}
else
	$salto_sel=NULL;

$posicion_sel=isset($_SESSION["posicion_sel"])?trim($_SESSION["posicion_sel"]):NULL;

if (!is_null($posicion_sel) and $posicion_sel!=="") {
	$pos=$posicion_sel;
}
else
	$posicion_sel=NULL;

$altura_sel=isset($_SESSION["altura_sel"])?trim($_SESSION["altura_sel"]):NULL;

if (!is_null($altura_sel) and $altura_sel!=="") {
	$alt=$altura_sel;
}
else
	$altura_sel=NULL;

$sexo_sel=isset($_SESSION["sexo_sel"])?trim($_SESSION["sexo_sel"]):NULL;

if (!is_null($sexo_sel) and $sexo_sel!=="") {
	$sex=$sexo_sel;
}
else
	$sexo_sel=NULL;

if (isset($_GET["cod"])) {
	$clavadista_sel=$_GET["cod"];
	$buscar=$_GET["us"];
}

if (!isset($clavadista_sel)) {
	$clavadista_sel=isset($_SESSION["clavadista_sel"])?trim($_SESSION["clavadista_sel"]):NULL;
	$buscar=isset($_SESSION["buscar"])?$_SESSION["buscar"]:NULL;
}

if (!is_null($clavadista_sel) and $clavadista_sel!=="") {
	$clavadista=$clavadista_sel;
}
else
	$clavadista_sel=NULL;

$conexion=conectarse();
$consulta="SELECT DISTINCT d.planilla, d.salto as cod_salto, d.posicion, d.altura, d.puntaje_salto, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, c.fecha_inicia, c.fecha_termina, s.salto, cl.nombre, m.abreviado, m.fijo, c.competencia, t.City, o.Country, c.logo_organizador 
	FROM planillad as d
	LEFT JOIN planillas as p on p.cod_planilla=d.planilla
	LEFT JOIN categorias as k on k.cod_categoria=p.categoria
	LEFT JOIN competencias as c on c.cod_competencia=p.competencia 
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN cities as t on t.CityId=c.ciudad 
	LEFT JOIN countries as o on o.CountryId=t.CountryID 
	LEFT JOIN usuarios as cl on cl.cod_usuario=p.clavadista
	LEFT JOIN saltos as s on s.cod_salto=d.salto
	WHERE k.individual=1";
if (!is_null($salto_sel))
	$consulta.=" AND d.salto='$salto'";
if (!is_null($posicion_sel))
	$consulta.=" AND d.posicion='$pos'";
if (!is_null($altura_sel))
	$consulta.=" AND d.altura='$alt'";
if (!is_null($sexo_sel))
	$consulta.=" AND p.sexo='$sex'";
if (!is_null($clavadista_sel))
	$consulta.=" AND p.clavadista=$clavadista";
$consulta.=" ORDER BY d.puntaje_salto DESC, d.salto, d.posicion, d.altura";

$ejecutar_saltos = $conexion->query($consulta);
?>
<form id="historico-saltos" name="historico-saltos-frm" action="?op=php/historico-saltos-1.php" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend align="center">Hist&oacute;rico de Saltos</legend>
		<label for='salto-sel' class='rotulo'>Salto:</label>&nbsp;&nbsp;
		<select id='salto-sel' class='cambio' name='salto_sel_slc' onChange="this.form.submit()" >
			<option value='' >- - -</option>";
			<?php 
				$sal=$salto_sel;
				include("php/select-salto.php"); 
			 ?>
		</select>
		<label for='posicion-sel' class='rotulo'>Posición:</label>
		<select id='posicion-sel' class='cambio' name='posicion_sel_slc' onChange="this.form.submit()" >
			<option value='' >- - -</option>";
			<?php 
				$pos=$posicion_sel;
				include("php/select-posicion.php"); 
			 ?>
		</select>
		<label for='altura-sel' class='rotulo'>Altura:</label>
		<select id='altura-sel' class='cambio' name='altura_sel_slc' onChange="this.form.submit()" >
			<option value='' >- - -</option>";
			<?php 
				$alt=$altura_sel;
				include("php/select-altura.php"); 
			 ?>
		</select>
		<label for='sexo-sel' class='rotulo'>Sexo:</label>
		<select id='sexo-sel' class='cambio' name='sexo_sel_slc' onChange="this.form.submit()" >
			<option value='' >- - -</option>";
			<option value='F' 
			<?php  
				if ($sex=='F') {
					echo " selected";
				}
			?> 
			 >Femenino</option>";
			<option value='M' 
			<?php  
				if ($sex=='M') {
					echo " selected";
				}
			?> 
			>Masculino</option>";
		</select>
		<div>
			<label for="buscar" class="rotulo">Buscar: </label>
			<input type="search" class="cambio" id="buscar" name="buscar_src" value="<?php echo $buscar; ?>">
			<input type="submit" class="cambio" name="buscar_sbm" value="Buscar Clavadista" title="Busca Clavadistas">
			<input type="hidden" id="clavadista" class="cambio" name="clavadista_hdn" value="<?php echo $clavadista; ?>">
			<input type="submit" class="cambio" name="sin_filtros_sbm" value="Elimina Filtros" title="Quita todos los filtros de la consulta">
		</div>

	<div class='scrollbars'>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.7em' cellpadding='0.7em' class="tablas">
		<thead>
			<tr align='center' >
				<th>Salto</th>
				<th>Puntaje</th>
				<th>Clavadista</th>
				<th>Evento</th>
			</tr>  
		</thead>
		<tbody>
	<?php 
		while ($row=$ejecutar_saltos->fetch_assoc()){
			$salto=$row["cod_salto"]."-".$row["posicion"]." ".$row["abreviado"];	
			if (!$row["fijo"]) 
				$salto .= " ".number_format($row["altura"],1)." mts";
			$puntaje=$row["puntaje_salto"];
			$cal1=isset($row["cal1"])?$row["cal1"]:NULL;
			$cal2=isset($row["cal2"])?$row["cal2"]:NULL;
			$cal3=isset($row["cal3"])?$row["cal3"]:NULL;
			$cal4=isset($row["cal4"])?$row["cal4"]:NULL;
			$cal5=isset($row["cal5"])?$row["cal5"]:NULL;
			$cal6=isset($row["cal6"])?$row["cal6"]:NULL;
			$cal7=isset($row["cal7"])?$row["cal7"]:NULL;
			$cal8=isset($row["cal8"])?$row["cal8"]:NULL;
			$cal9=isset($row["cal9"])?$row["cal9"]:NULL;
			$cal10=isset($row["cal10"])?$row["cal10"]:NULL;
			$cal11=isset($row["cal11"])?$row["cal11"]:NULL;
			$calificaciones=$cal1."  ".$cal2."  ".$cal3;
			if ($cal4) 
				$calificaciones.="  ".$cal4;
			if ($cal5) 
				$calificaciones.="  ".$cal5;
			if ($cal6) 
				$calificaciones.="  ".$cal6;
			if ($cal7) 
				$calificaciones.="  ".$cal7;
			if ($cal8) 
				$calificaciones.="  ".$cal8;
			if ($cal9) 
				$calificaciones.="  ".$cal9;
			if ($cal10) 
				$calificaciones.="  ".$cal10;
			if ($cal11) 
				$calificaciones.="  ".$cal11;

			$nombre=utf8_decode($row['nombre']);
			$nombre=ucwords(strtolower($nombre));
			$planilla=$row["planilla"];		
			$competencia=$row["competencia"];		
			$ciudad=utf8_decode($row['City']);
			$pais=utf8_decode($row['Country']);
			$inicia=$row["fecha_inicia"];
			$termina=$row["fecha_termina"];
			$fec=fecha_desde_hasta($inicia,$termina);
			$logo=$row["logo_organizador"];
			echo "<tr align='center'>";
			echo "<td>$salto</td>";
			echo "<td title='$calificaciones' >$puntaje</td>";
			echo "<td>$nombre</td>";
			echo "<td>";
   			if ($logo) 
   				echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>&nbsp;&nbsp;";
   			echo $competencia.'<br>'.$fec.' - '.$ciudad.'-'.$pais.'<br>'.'</td>';
    		echo "</tr>"; 
		}
		$conexion->close();

		if ($num_regs==0) 		
			$mensaje="No hay registros para esta consulta :(";
	?>
		</tbody>
	</table> 
	</div>
	</fieldset>
</form>
