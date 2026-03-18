<?php 
session_start();
include_once("funciones.php");
if (isset($_GET["com"])) {
	$competencia=$_GET["com"];
	$cod_competencia=$_GET["cco"];
	$equipo=$_GET["eq"];
	$corto=$_GET["cor"];
	$llamo=$_GET["ori"];
}

$buscar=(isset($_GET["bus"])?trim($_GET["bus"]):NULL);


$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;

$llamados=$_SESSION["llamados"];
$llamar="clavadistas-competencia.php&com=$competencia&eq=$equipo&codco=$cod_competencia&ccl=$cod_cla&ceq=$corto&&lg=$logo";
$llamados["edita-clavadista-competencia.php"]=$llamar;
$llamados["baja-clavadista-competencia.php"]=$llamar;
$_SESSION["llamados"]=$llamados;

if (!isset($competencia))
	$competencia=$_SESSION["competencia"];
if (!isset($cod_competencia))
	$cod_competencia=$_SESSION["cod_competencia"];
if (!isset($logo))
	$logo=$_SESSION["logo"];

if (!isset($equipo))
	$equipo=$_SESSION["equipo"];

// determinar si programaron categorías individuales
$conexion=conectarse();
$consulta="SELECT * 
	FROM competenciapr 
	left join categorias on categorias.cod_categoria=competenciapr.categoria 
	where competenciapr.competencia=$cod_competencia AND individual=1";
$ejecutar_consulta=$conexion->query($consulta);
$individuales=$ejecutar_consulta->num_rows;

// determinar si programaron categorías de sincronizado
$consulta="SELECT * 
	FROM competenciapr 
	left join categorias on categorias.cod_categoria=competenciapr.categoria 
	where competenciapr.competencia=$cod_competencia AND individual=0";
$ejecutar_consulta=$conexion->query($consulta);
$parejas=$ejecutar_consulta->num_rows;

if (!$corto) 
	$corto=determina_equipo($equipo,$cod_competencia,$conexion);

if (!isset($corto))
	$corto=$_SESSION["corto"];

$criterio=" WHERE (p.competencia=$cod_competencia 
		AND p.equipo='$corto')";

if (strlen($buscar)>0)
	$criterio.=" AND (cla.nombre LIKE '%$buscar%' or cl2.nombre LIKE '%$buscar%')";

$consulta="SELECT DISTINCT 
	p.cod_planilla,	p.competencia,
	cla.nombre as nom_cla,
	cl2.nombre as nom_cl2,
	cl3.nombre as nom_cl3,
	cl4.nombre as nom_cl4,
	p.clavadista as cod_cla, 
	p.clavadista2 as cod_cl2, 
	p.clavadista3 as cod_cl3, 
	p.clavadista4 as cod_cl4, 
	ent.nombre as nom_ent, 
	p.entrenador as cod_ent, 
	cla.imagen,	
	cl2.imagen as imagen2, 
	cl3.imagen as imagen3, 
	cl4.imagen as imagen4, 
	p.equipo, p.categoria, p.modalidad, 
	cat.categoria as nom_cat, 
	m.abreviado as nom_mod
	from planillas as p 
	left join usuarios as cla on cla.cod_usuario=p.clavadista 
	left join usuarios as cl2 on cl2.cod_usuario=p.clavadista2 
	left join usuarios as cl3 on cl3.cod_usuario=p.clavadista3 
	left join usuarios as cl4 on cl4.cod_usuario=p.clavadista4 
	left join usuarios as ent on ent.cod_usuario=p.entrenador 
	left join categorias as cat on cat.cod_categoria=p.categoria 
	left join modalidades as m on m.cod_modalidad=p.modalidad";
$consulta.=$criterio." ORDER BY cla.nombre,cl2.nombre, m.abreviado";

$ejecutar_consulta = $conexion->query($consulta);

if ($ejecutar_consulta)
	$num_regs=$ejecutar_consulta->num_rows;
?>
<form id="clavadistas-competencia" name="alta-frm" action="?op=php/alta-clavadista-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<h2>Clavadistas del equipo <?php echo $equipo; ?></h2>

	<div>
		<label for="buscar" >Buscar: </label>
		<input type="search" class="cambio" id="buscar" name="buscar_src" value="<?php echo $buscar; ?>">
		<input type="submit" class="cambio" name="buscar_sbm" value="Buscar" title="Busca en los inscritos del Equipo">

		<?php 
			if ($individuales) 
				echo '<input type="submit" id="nuevo" class="cambio" name="individual_btn" value="Inscribir Individual" >&nbsp;';
			if ($parejas) 
				echo '<input type="submit" id="nuevo" class="cambio" name="pareja_btn" value="Inscribir Pareja" >';
		 ?>
		<input type="submit" id="nuevo" class="cambio" name="equipo_juv_btn" value="Inscribir Equipo Juvenil"> 
		<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	<!-- <div width="100%" class= "scrollbars"> -->
	<div width="100%" id="div1">
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<thead>
			<tr align="center">
				<th>Clavadistas</th>
				<th>Entrenador</th>	  
				<th>Categoría</th>
				<th>Participación</th>	  
				<th colspan="6">Opciones</th>	  
			</tr> 
		</thead>
		<tbody>

	<?php 
		if (!$num_regs)
			echo "<tr align='center'><td align='center'>El Equipo no ha inscrito Clavadistas !!! :(</td></tr>";
		else{
			$clavadista=NULL;
			$n=0;
			$total=0;
			while ($row=$ejecutar_consulta->fetch_assoc()){

				$n++;
				$planilla_new=$row["cod_planilla"];
				$nuevo1=isset($row["nom_cla"])?$row["nom_cla"]:NULL;
				$nuevo2=isset($row["nom_cl2"])?$row["nom_cl2"]:NULL;
				$nuevo3=isset($row["nom_cl3"])?$row["nom_cl3"]:NULL;
				$nuevo4=isset($row["nom_cl4"])?$row["nom_cl4"]:NULL;
				$nuevo=$nuevo1;
				if ($nuevo2) 
					$nuevo .= " - ".$nuevo2;
				if ($nuevo3)
					$nuevo .= " - ".$nuevo3;
				if ($nuevo4)
					$nuevo .= " - ".$nuevo4;
   				$nuevo=primera_mayuscula_palabra($nuevo);

				if ($nuevo!=$clavadista) {
					if ($clavadista!="" or $n==$num_regs) {
						if ($planilla) {
							$total++;
							$cons_saltos="SELECT * FROM planillad WHERE planilla=$planilla";
							$men="Planillas(sin saltos)";
							$ejecutar_cons_saltos = $conexion->query($cons_saltos);

							if ($ejecutar_cons_saltos)
								$saltos=$ejecutar_cons_saltos->num_rows;
							if ($saltos) 
								$men="Planillas";
							echo "<tr align='center'><td align='left'><a href='?op=php/edita-clavadista-competencia.php&com=$competencia&eq=$equipo&cco=$cod_competencia&ccl=$cod_cla&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&ceq=$corto&lg=$logo'>";
							if ($imagen) 
	   							echo "<img  class='textwrap' src='img/fotos/$imagen' width='5%' height='45' />&nbsp;&nbsp;";
							if ($imagen2) 
	   							echo "<img  class='textwrap' src='img/fotos/$imagen2' width='5%' height='45'/>&nbsp;&nbsp;";
							if ($imagen3) 
	   							echo "<img  class='textwrap' src='img/fotos/$imagen3' width='5%' height='45'/>&nbsp;&nbsp;";
							if ($imagen4) 
	   							echo "<img  class='textwrap' src='img/fotos/$imagen4' width='5%' height='45'/>&nbsp;&nbsp;";
							echo "$clavadista</a></td>";
	   						echo "<td>$entrenador</td>";  
	   						echo "<td>$nom_cat</td>";  
	    					echo "<td>$modalidades</td>";  
		    				echo "<td align='center'><a href='?op=php/planillas-competencia.php&com=$competencia&eq=$equipo&cco=$cod_competencia&cl=$clavadista1&cl2=$clavadista2&cl3=$clavadista3&cl4=$clavadista4&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&en=$entrenador&ccl=$cod_cla&cen=$cod_ent&img=$imagen&img2=$imagen2&img3=$imagen3&img4=$imagen4&bus=$buscar&lg=$logo'>$men</a></td>";
			    			echo "<td align='center'><a href='?op=php/edita-clavadista-competencia.php&com=$competencia&eq=$equipo&cco=$cod_competencia&ccl=$cod_cla&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&ceq=$corto&bus=$buscar&lg=$logo'>Editar</a></td>";
	    					echo "<td align='center'><a href='?op=php/baja-clavadista-competencia.php&com=$competencia&eq=$equipo&cl=$clavadista&ccl=$cod_cla&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&cco=$cod_competencia&ceq=$corto&bus=$buscar&lg=$logo&im=$imagen&im2=$imagen2&im3=$imagen3&im4=$imagen4'>Retirar</a></td></tr>";
						}
					}
    				$clavadista=primera_mayuscula_palabra($nuevo);
    				$clavadista1=primera_mayuscula_palabra($nuevo1);
    				$clavadista2=primera_mayuscula_palabra($nuevo2);
    				$clavadista3=primera_mayuscula_palabra($nuevo3);
    				$clavadista4=primera_mayuscula_palabra($nuevo4);
    				$planilla=$planilla_new;
    				$modalidades="";
				}
				$cod_cla=$row["cod_cla"];
				$cod_cl2=$row["cod_cl2"];
				$cod_cl3=$row["cod_cl3"];
				$cod_cl4=$row["cod_cl4"];
				$entrenador=primera_mayuscula_palabra($row['nom_ent']);			
//				$entrenador=strtolower($entrenador);
//				$entrenador=ucwords($entrenador);
				$cod_ent=$row["cod_ent"];
				$categoria=$row["categoria"];
				$modalidad=$row["modalidad"];
				$nom_cat=utf8_encode($row["nom_cat"]);
				$nom_mod=$row["nom_mod"];
				$imagen=$row["imagen"];
				$imagen2=$row["imagen2"];
				$imagen3=$row["imagen3"];
				$imagen4=$row["imagen4"];
				if ($modalidades=="") 
					$modalidades=$nom_mod;
				else
					$modalidades.=", ".$nom_mod;
    		}
			$total++;
/*			$clavadista1=strtolower($clavadista1);
			$clavadista1=ucwords($clavadista1);
			$clavadista2=strtolower($clavadista2);
			$clavadista2=ucwords($clavadista2);
			$clavadista3=strtolower($clavadista3);
			$clavadista3=ucwords($clavadista3);
			$clavadista4=strtolower($clavadista4);
			$clavadista4=ucwords($clavadista4);
*/			$cons_saltos="SELECT * FROM planillad WHERE planilla=$planilla";
			$men="Planillas(sin saltos)";
			$ejecutar_cons_saltos = $conexion->query($cons_saltos);
			if ($ejecutar_cons_saltos)
				$saltos=$ejecutar_cons_saltos->num_rows;
			if ($saltos) 
				$men="Planillas";
			echo "<tr align='center'><td align='left'><a href='?op=php/edita-clavadista-competencia.php&com=$competencia&eq=$equipo&cco=$cod_competencia&ccl=$cod_cla&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&ceq=$corto&lg=$logo'>";
			if ($imagen) 
   				echo "<img  class='textwrap' src='img/fotos/$imagen' width='12%'/>&nbsp;&nbsp;";
			if ($imagen2) 
   				echo "<img  class='textwrap' src='img/fotos/$imagen2' width='12%'/>&nbsp;&nbsp;";
			if ($imagen3) 
   				echo "<img  class='textwrap' src='img/fotos/$imagen3' width='12%'/>&nbsp;&nbsp;";
			if ($imagen4) 
   				echo "<img  class='textwrap' src='img/fotos/$imagen4' width='12%'/>&nbsp;&nbsp;";
			echo "$clavadista</a></td>";
   			echo "<td>$entrenador</td>";  
   			echo "<td>$nom_cat</td>";  
    		echo "<td>$modalidades</td>";  
	    	echo "<td align='center'><a href='?op=php/planillas-competencia.php&com=$competencia&eq=$equipo&cco=$cod_competencia&cl=$clavadista1&cl2=$clavadista2&cl3=$clavadista3&cl4=$clavadista4&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&en=$entrenador&ccl=$cod_cla&cen=$cod_ent&img=$imagen&img2=$imagen2&img3=$imagen3&img4=$imagen4&bus=$buscar&lg=$logo'>$men</a></td>";
		    echo "<td align='center'><a href='?op=php/edita-clavadista-competencia.php&com=$competencia&eq=$equipo&cco=$cod_competencia&ccl=$cod_cla&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&ceq=$corto&bus=$buscar&lg=$logo'>Editar</a></td>";
    		echo "<td align='center'><a href='?op=php/baja-clavadista-competencia.php&com=$competencia&eq=$equipo&cl=$clavadista&ccl=$cod_cla&ccl2=$cod_cl2&ccl3=$cod_cl3&ccl4=$cod_cl4&cco=$cod_competencia&ceq=$corto&bus=$buscar&lg=$logo&im=$imagen&im2=$imagen2&im3=$imagen3&im4=$imagen4'>Retirar</a></td></tr>";
		}
	$conexion->close();    	

	?>
		</tbody>
	</table> 
	</div>  
	<div>
		<br>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
		<input type="hidden" id="logo" name="logo_hdn" value="<?php echo $logo; ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo 'clavadistas-competencia.php&com='.$competencia.'&eq='.$equipo.'&cor='.$corto.'&ori='.$llamo; ?>" />
		<input type="hidden" id="equipo" name="equipo_hdn" value="<?php echo $equipo; ?>" />
	</div>
	</fieldset>
</form>