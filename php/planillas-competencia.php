<?php 
session_start();
include_once('funciones.php');
if ($_GET["com"]) {
	$competencia=$_GET["com"];
	$cod_competencia=$_GET["cco"];
	$logo=$_GET["lg"];
	$equipo=$_GET["eq"];
	$clavadista1=isset($_GET["cl"])?utf8_decode(trim($_GET["cl"])):NULL;
	$clavadista2=isset($_GET["cl2"])?utf8_decode(trim($_GET["cl2"])):NULL;
	$clavadista3=isset($_GET["cl3"])?utf8_decode(trim($_GET["cl3"])):NULL;
	$clavadista4=isset($_GET["cl4"])?utf8_decode(trim($_GET["cl4"])):NULL;
	if (strlen($clavadista2)>0){
		$clavadista=$clavadista1." - ". $clavadista2;
		if (!is_null($clavadista3))
			$clavadista .= " - ".$clavadista3;
		if (!is_null($clavadista4))
			$clavadista .= " - ".$clavadista4;
	}
	else
		$clavadista=$clavadista1;

	$clavadista=primera_mayuscula_palabra($clavadista);
	$entrenador=$_GET["en"];
	$cod_clavadista=$_GET["ccl"];
	$cod_clavadista2=isset($_GET["ccl2"])? trim($_GET["ccl2"]):NULL;
	$cod_clavadista3=isset($_GET["ccl3"])? trim($_GET["ccl3"]):NULL;
	$cod_clavadista4=isset($_GET["ccl4"])? trim($_GET["ccl4"]):NULL;
	$cod_entrenador=$_GET["cen"];
	$imagen=isset($_GET["img"])?$_GET["img"]:NULL;
	$imagen2=isset($_GET["img2"])?$_GET["img2"]:NULL;
	$imagen3=isset($_GET["img3"])?$_GET["img3"]:NULL;
	$imagen4=isset($_GET["img4"])?$_GET["img4"]:NULL;
	$buscar=$_GET["bus"];
	$mensaje=isset($_GET["mensaje"])?$_GET["mensaje"]:NULL;

}
session_start();
if (!isset($competencia))
	$competencia=$_SESSION["competencia"];
if (!isset($cod_competencia))
	$cod_competencia=$_SESSION["cod_competencia"];
if (!isset($logo))
	$logo=$_SESSION["logo"];
if (!isset($logo2))
	$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
if (!isset($equipo))
	$equipo=$_SESSION["equipo"];
if (!isset($entrenador))
	$entrenador=$_SESSION["entrenador"];
if (!isset($clavadista1))
	$clavadista1=$_SESSION["clavadista1"];
if (!isset($clavadista2))
	$clavadista2=$_SESSION["clavadista2"];
if (!isset($clavadista3))
	$clavadista3=$_SESSION["clavadista3"];
if (!isset($clavadista4))
	$clavadista4=$_SESSION["clavadista4"];
if (!isset($cod_clavadista))
	$cod_clavadista=$_SESSION["cod_clavadista"];
if (!isset($cod_clavadista2))
	$cod_clavadista2=$_SESSION["cod_clavadista2"];
if (!isset($cod_clavadista3))
	$cod_clavadista3=$_SESSION["cod_clavadista3"];
if (!isset($cod_clavadista4))
	$cod_clavadista4=$_SESSION["cod_clavadista4"];
if (!isset($cod_entrenador))
	$cod_entrenador=$_SESSION["cod_entrenador"];
if (!isset($imagen))
	$imagen=$_SESSION["imagen"];
if (!isset($imagen2))
	$imagen2=$_SESSION["imagen2"];
if (!isset($imagen3))
	$imagen3=$_SESSION["imagen3"];
if (!isset($imagen4))
	$imagen4=$_SESSION["imagen4"];
if (!isset($buscar))
	$buscar=$_SESSION["bus"];

$conexion=conectarse();

$criterio=null;
if (isset($competencia)){
	if (is_null($criterio))
		$criterio=" WHERE (pla.competencia=$cod_competencia ";	
}

if (isset($equipo)){
	$cod_equipo=determina_equipo($equipo,$cod_competencia);
	if (is_null($criterio))
		$criterio=" WHERE pla.equipo='$cod_equipo'";	
	else
		$criterio.=" AND pla.equipo='$cod_equipo'";	
}

if (isset($cod_entrenador)){
	if (is_null($criterio))
		$criterio=" WHERE (pla.entrenador=$cod_entrenador";	
	else
		$criterio.=" AND pla.entrenador=$cod_entrenador";	
}
if (isset($cod_clavadista)){
	if (is_null($criterio))
		$criterio=" WHERE (pla.clavadista=$cod_clavadista";	
	else
		$criterio.=" AND pla.clavadista=$cod_clavadista";	
}

if (strlen($cod_clavadista2)>0){
	if (is_null($criterio))
		$criterio=" WHERE (pla.clavadista2=$cod_clavadista2";	
	else
		$criterio.=" AND pla.clavadista2=$cod_clavadista2";	
}
else{
	if (is_null($criterio))
		$criterio=" WHERE (pla.clavadista2 IS NULL ";	
	else
		$criterio.=" AND pla.clavadista2 IS NULL ";	
}
if (strlen($cod_clavadista3)>0){
	if (is_null($criterio))
		$criterio=" WHERE (pla.clavadista3=$cod_clavadista3";	
	else
		$criterio.=" AND pla.clavadista3=$cod_clavadista3";	
}
else{
	if (is_null($criterio))
		$criterio=" WHERE (pla.clavadista3 IS NULL ";	
	else
		$criterio.=" AND pla.clavadista3 IS NULL ";	
}
if (strlen($cod_clavadista4)>0){
	if (is_null($criterio))
		$criterio=" WHERE (pla.clavadista4=$cod_clavadista4";	
	else
		$criterio.=" AND pla.clavadista4=$cod_clavadista4";	
}
else{
	if (is_null($criterio))
		$criterio=" WHERE (pla.clavadista4 IS NULL ";	
	else
		$criterio.=" AND pla.clavadista4 IS NULL ";	
}

$consulta="SELECT DISTINCT 
	cla.nombre AS nom_cla, 
	ent.nombre AS nom_ent, 
	cl2.nombre AS nom_cla2, 
	cl3.nombre AS nom_cla3, 
	cl4.nombre AS nom_cla4, 
	pla.cod_planilla as cod_planilla, 
	pla.modalidad as cod_modalidad,
	pla.categoria as cod_categoria,
	eq.equipo AS equipo, 
	cat.categoria AS categoria, 
	pla.sexo AS sexo, 
	pla.sexo2 AS sexo2, 
	pla.sexo3 AS sexo3, 
	pla.sexo4 AS sexo4, 
	modalidades.modalidad AS modalidad, 
	pla.clavadista, pla.clavadista2, pla.clavadista3, pla.clavadista4, pla.entrenador, pla.equipo as corto
	FROM planillas AS pla 
	LEFT JOIN usuarios as cla on (cla.cod_usuario=pla.clavadista)  
	LEFT JOIN usuarios as cl2 on (cl2.cod_usuario=pla.clavadista2)  
	LEFT JOIN usuarios as cl3 on (cl3.cod_usuario=pla.clavadista3)  
	LEFT JOIN usuarios as cl4 on (cl4.cod_usuario=pla.clavadista4)  
	LEFT JOIN usuarios as ent on (ent.cod_usuario=pla.entrenador)
	LEFT JOIN competenciasq as eq on (eq.nombre_corto=pla.equipo AND eq.competencia=pla.competencia) 
	LEFT JOIN modalidades on (modalidades.cod_modalidad=pla.modalidad)	
	LEFT JOIN categorias AS cat on (cat.cod_categoria=pla.categoria)";
if (isset($criterio)) {
	$criterio=$criterio.") ";
	$consulta=$consulta.$criterio;
}

$consulta=$consulta."ORDER BY nom_cla";
$ejecutar_consulta = $conexion->query($consulta);

$clavadista=utf8_encode($clavadista);
$clavadista2=utf8_encode($clavadista2);

?>

<form id="planillas-clavadista" name="alta-frm" action="?op=php/alta-planilla.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<h2>Planillas de: 
			<?php 
				echo $clavadista;
				if ($imagen) 
					echo "<img class='textwrap' src='img/fotos/$imagen' width='5%' height='90'/>";
				if ($imagen2) 
					echo "<img class='textwrap' src='img/fotos/$imagen2' width='5%' height='90'/>";
				if ($imagen3) 
					echo "<img class='textwrap' src='img/fotos/$imagen3' width='5%' height='90'/>";
				if ($imagen4) 
					echo "<img class='textwrap' src='img/fotos/$imagen4' width='5%' height='90'/>";
			 ?>
		</h2>
	<div id='div1'>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align='center' >
		<?php 
			if ($clavadista2){
				echo "<th>Clavadista 1</th><th>Clavadista 2</th>";
				if ($clavadista3)
					echo "<th>Clavadista 3</th>";
				if ($clavadista4)
					echo "<th>Clavadista 4</th>";
			}
			else
				echo "<th>Clavadista</th>";

		 ?>
		<th>Entrenador</th>	  
		<th>Equipo</th>	  
		<th>Categoría</th>	  
		<th>Sexo</th>	  
		<th>Modalidad</th>	  
		<th colspan="4">Opciones</th>	  
	</tr> 

	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs)
			$mensaje="No haz registrado tus planillas de clavados para la competencia $competencia :(";
		else
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$plan=$row["cod_planilla"];
			$clav=utf8_decode($row["nom_cla"]);
//			$clav=strtolower($clav);
//			$clav=ucwords($clav);
			$cla2=utf8_decode($row["nom_cla2"]);
//			$cla2=strtolower($cla2);
//			$cla2=ucwords($cla2);
			$cla3=utf8_decode($row["nom_cla3"]);
//			$cla3=strtolower($cla3);
//			$cla3=ucwords($cla3);
			$cla4=utf8_decode($row["nom_cla4"]);
//			$cla4=strtolower($cla4);
//			$cla4=ucwords($cla4);
			$entr=utf8_decode($row["nom_ent"]);
//			$entr=strtolower($entr);
//			$entr=ucwords($entr);
			$ccla=$row["clavadista"];
			$ccl2=$row["clavadista2"];
			$ccl3=$row["clavadista3"];
			$ccl4=$row["clavadista4"];
			$corto=$row["corto"];
			$cent=$row["entrenador"];
			$equi=utf8_decode($row["equipo"]);
			$cate=utf8_encode($row["categoria"]);
			$cod_modalidad=utf8_encode($row["cod_modalidad"]);
			$cod_categoria=$row["cod_categoria"];
			$sexo=$row["sexo"];
			$sexo2=isset($row["sexo2"])?$row["sexo2"]:NULL;
			$sexo3=isset($row["sexo3"])?$row["sexo3"]:NULL;
			$sexo4=isset($row["sexo4"])?$row["sexo4"]:NULL;
			$sex=NULL;

			if (!is_null($sexo2) ) {
				if ($sexo!=$sexo2)
					$sex="Mixto";
				else
					if (!is_null($sexo3))
						if ($sexo!=$sexo3 or $sexo2!=$sexo3)
							$sex="Mixto";
						else
							if (!is_null($sexo4))
								if ($sexo!=$sexo4 or $sexo2!=$sexo4 or $sexo3!=$sexo4)					
									$sex="Mixto";
			}

			if (is_null($sex)) {
				if ($sexo=="M") $sex="Masculino";
				if ($sexo=="F") $sex="Femenino";
			}
			$moda=utf8_decode($row["modalidad"]);
			$llamador="php/planillas-competencia.php*com=$competencia*eq=$equi*cl=$clav*en=$entr*ccl=$ccla*ccl2=$ccl2*ccl3=$ccl3*ccl4=$ccl4*cl2=$cla2*cl3=$cla3*ccl4=$ccl4*bus=$buscar*cco=$cod_competencia";
    		echo "<tr>";
			if ($cla2){
				echo "<td>$clav</td><td>$cla2</td>";
				if ($cla3)
					echo "<td>$cla3</td>";
				if ($cla4)
					echo "<td>$cla4</td>";
			}
			else
				echo "<td>$clav</td>";
    		echo "<td>$entr</td>";      		
    		echo "<td>$equi</td>";  
    		echo "<td>$cate</td>";  
    		echo "<td>$sex</td>";  
			$cons_saltos="SELECT * FROM planillad WHERE planilla=$plan";
			$men="(sin saltos)";
			$ejecutar_cons_saltos = $conexion->query(utf8_decode($cons_saltos));
			if ($ejecutar_cons_saltos)
				$saltos=$ejecutar_cons_saltos->num_rows;
				if ($saltos) 
					$men=NULL;

			if ($sex=="Mixto") 
				if ($cod_modalidad=="E") 
					if ($cod_categoria=='Q1')
						$programa="cambio-planilla-equ-ju.php";
					else
						$programa="cambio-planilla-equ.php";
				else
					$programa="cambio-planilla-mix.php";
			else
				$programa="cambio-planilla.php";

    		echo "<td>$moda.$men</td>";  
    		echo "<td align='center'><a href='?op=php/".$programa."&pl=$plan&ccl=$ccla&cen=$cent&ccl2=$ccla2&ori=$llamador&img=$imagen&img2=$imagen2&com=$competencia&cco=$cod_competencia&lg=$logo'>Modificar</a></td>";
    		echo "<td align='center'><a href='?op=php/baja-planilla.php&pl=$plan&eq=$equi&cl=$clav&cat=$cate&md=$moda&ccl=$ccla&cen=$cent&comp=$competencia&cco=$cod_competencia&cor=$corto&ori=$llamador'>Eliminar</a></td>";
		}	
		$conexion->close();
		if ($mensaje){
			echo "<tr><td colspan='8' class='mensaje error' align='center'>$mensaje</td></tr>";
		}
	?>
	</table> 
	</div>
	<div>
		<br>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
		<input type="hidden" id="logo" name="logo_hdn" value="<?php echo $logo; ?>" />
		<input type="hidden" id="equipo" name="equipo_hdn" value="<?php echo $equipo; ?>" />
		<input type="hidden" id="imagen" name="imagen_hdn" value="<?php echo $imagen; ?>" />
		<input type="hidden" id="imagen2" name="imagen2_hdn" value="<?php echo $imagen2; ?>" />
		<input type="hidden" id="clavadista" name="clavadista_hdn" value="<?php echo $clavadista1; ?>" />
		<input type="hidden" id="clavadista2" name="clavadista2_hdn" value="<?php echo $clavadista2; ?>" />
		<input type="hidden" id="cod-clavadista" name="cod_clavadista_hdn" value="<?php echo $cod_clavadista; ?>" />
		<input type="hidden" id="cod-clavadista2" name="cod_clavadista2_hdn" value="<?php echo $cod_clavadista2; ?>" />
		<input type="hidden" id="entrenador" name="entrenador_hdn" value="<?php echo $entrenador; ?>" />
		<input type="hidden" id="cod-entrenador" name="cod_entrenador_hdn" value="<?php echo $cod_entrenador; ?>" />		
		<input type="hidden" id="buscar" name="buscar_hdn" value="<?php echo $buscar; ?>" />
<!--		<input type="submit" id="nuevo" class="cambio" name="registra_planilla_btn" title="Registra una planilla nueva" value="Nueva Planilla" />&nbsp;&nbsp; -->
		<input type="submit" id="regresar" title="Regresa a planillas del equipo" class="cambio" name="regresar_sbm" value="Regresar" />

	</div>
	</fieldset>
</form>