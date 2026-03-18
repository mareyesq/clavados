<?php 
session_start();
include("funciones.php");

if (isset($_GET["fh"])) {
	$fechahora=trim($_GET["fh"]);
	$cod_competencia=trim($_GET["cco"]);
	include('lee-evento-competencia.php');

/*	$fechahora=trim($_GET["fh"]);
	$cod_competencia=trim($_GET["codco"]);
	$competencia=trim($_GET["com"]);
	$modalidad=trim($_GET["mod"]);
	$cat=trim($_GET["cat"]) ;
	$sx=trim($_GET["sx"]);
	$tipo=trim($_GET["tp"]);
	$numero_evento=trim($_GET["nev"]);
	$descripcion=trim($_GET["des"]);
	$fecha=trim($_GET["ho"]);
	$hora=trim($_GET["hor"]);
	$evento=trim($_GET["ev"]);
*/}

$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
if (is_null($competencia)) $competencia=trim($_SESSION["competencia"]);
if (is_null($cod_competencia)) $cod_competencia=trim($_SESSION["cod_competencia"]);
if (is_null($fechahora)) $fechahora=trim($_SESSION["fechahora"]);
if (is_null($modalidad)) $modalidad=trim($_SESSION["modalidad"]);
if (is_null($nom_modalidad)) $nom_modalidad=trim($_SESSION["nom_modalidad"]);
if (is_null($cat)) $cat=trim($_SESSION["cat"]);
if (is_null($sexos)) $sexos=trim($_SESSION["sexos"]);
if (is_null($tipo)) $tipo=trim($_SESSION["tipo"]);
if (strlen($evento)==0) $evento=trim($_SESSION["evento"]);
if (is_null($descripcion)) $tipo=trim($_SESSION["descripcion"]);
if (is_null($fecha)) $fecha=trim($_SESSION["fecha"]);
if (is_null($hora)) $hora=trim($_SESSION["hora"]);
if (strlen($numero_evento)==0) $numero_evento=trim($_SESSION["numero_evento"]);

$cod_usuario=$_SESSION["usuario_id"];
if (isset($cod_usuario)) {
	$es_administrador=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($es_administrador,0,5)=="Error")
		$es_administrador=0;
	else
		$es_administrador=1;
}
else
	$es_administrador=0;

if (isset($_GET["ubi"])) 
	$ubi=$_GET["ubi"];

$panel=NULL;

if ($ubi>0 and $ubi<12)
	$panel=1;
elseif ($ubi>11 and $ubi<23){
	$panel=2;
	$ubi=$ubi-11;
}
elseif ($ubi==25){
}
else {
	$panel=3;
	$ubi=$ubi-51;
}
$conexion=conectarse();

if ($panel==1 OR $panel==2){
	$cod_juez=$_GET["cod"];
	$consulta="SELECT * FROM competenciasz 
		WHERE competencia=$cod_competencia 
			and	evento=$numero_evento 
			and panel=$panel 
			and ubicacion=$ubi";
	$ejecutar_consulta = $conexion->query($consulta);
	if ($ejecutar_consulta){
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$consulta="INSERT INTO competenciasz 
				(competencia, evento, panel, ubicacion, juez) VALUES ($cod_competencia, $numero_evento, $panel, $ubi, $cod_juez)";
		}
		else{
			$consulta="UPDATE competenciasz 
				SET juez=$cod_juez 
				WHERE competencia=$cod_competencia 
					and	evento=$numero_evento 
					and panel=$panel 
					and ubicacion=$ubi";
		}
		$ejecutar_consulta = $conexion->query($consulta);
	}
}
elseif ($panel==3) {
	$cod_juez=$_GET["cod"];
	$esta=esta_en_evento($cod_juez);
	if (!$esta){
		$consulta="INSERT INTO competenciass
		(competencia, evento, juez) VALUES ($cod_competencia, $numero_evento, $cod_juez)";
		$ejecutar_consulta = $conexion->query($consulta);
	}
}
elseif ($ubi==25) {
	$cod_juez=$_GET["cod"];
	$consulta="SELECT * FROM competenciasz 
		WHERE competencia=$cod_competencia 
			and	evento=$numero_evento 
			and panel=1 
			and ubicacion=25";
	$ejecutar_consulta = $conexion->query($consulta);
	if ($ejecutar_consulta){
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$consulta="INSERT INTO competenciasz 
				(competencia, evento, panel, ubicacion, juez) VALUES ($cod_competencia, $numero_evento, 1, 25, $cod_juez)";
		}
		else{
			$consulta="UPDATE competenciasz SET juez=$cod_juez WHERE competencia=$cod_competencia 
					and	evento=$numero_evento 
					and panel=1 
					and ubicacion=25";
		}
		$ejecutar_consulta = $conexion->query($consulta);
	}
}

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

$panel_1=array();
$panel_2=array();
$panel_sombra=array();

$consulta1="SELECT DISTINCT z.panel, z.ubicacion, z.juez, u.nombre, u.imagen
	FROM competenciasz as z
	LEFT JOIN usuarios as u on u.cod_usuario=z.juez 
	WHERE z.competencia=$cod_competencia and z.evento=$numero_evento and z.panel=1
	ORDER BY ubicacion";
$ejecutar_consulta1 = $conexion->query($consulta1);

if ($ejecutar_consulta1){
	$num_regs1=$ejecutar_consulta1->num_rows;
	if (!$num_regs1)
		$mensaje="No hay jueces en Panel 1 registrados para este evento :(";
	else
		while ($row=$ejecutar_consulta1->fetch_assoc()){
			$ub=$row["ubicacion"];
			if ($ub==25) {
				$cod_arbitro=$row["juez"];
				$arbitro=$row["nombre"];
				$img_arbitro=$row["imagen"];
			}
			else
				$panel_1[$ub]=$row;
		}
}

$consulta2="SELECT DISTINCT z.panel, z.ubicacion, z.juez, u.nombre, u.imagen
	FROM competenciasz as z
	LEFT JOIN usuarios as u on u.cod_usuario=z.juez 
	WHERE z.competencia=$cod_competencia and z.evento=$numero_evento and z.panel=2
	ORDER BY ubicacion";
$ejecutar_consulta2 = $conexion->query($consulta2);
if ($ejecutar_consulta2){
	$num_regs2=$ejecutar_consulta2->num_rows;
	while ($row=$ejecutar_consulta2->fetch_assoc()){
		$ub=$row["ubicacion"];
		$panel_2[$ub]=$row;
	}
}

$consulta_sombra="SELECT DISTINCT s.juez, u.nombre, u.imagen
	FROM competenciass as s
	LEFT JOIN usuarios as u on u.cod_usuario=s.juez 
	WHERE s.competencia=$cod_competencia and s.evento=$numero_evento 
	ORDER BY u.nombre";
$ejecutar_consulta_sombra = $conexion->query($consulta_sombra);
if ($ejecutar_consulta_sombra){
	$num_regs_sombra=$ejecutar_consulta_sombra->num_rows;
	$j=1;
	while ($row=$ejecutar_consulta_sombra->fetch_assoc()){		
		$panel_sombra[$j]=$row;
		$j++;
	}
}
$conexion->close();

$llamador="?op=php/eventos-competencia.php*com=$competencia*cco=$cod_competencia";

$origen="php/jueces-evento-competencia.php*com=$competencia*fh=$fechahora*mod=$cod_modalidad*cat=$categorias*sx=$sexos*tp=$tipo*codco=$cod_competencia*nev=$numero_evento*des=$descripcion*ho=$fecha*ev=$evento*hor=$hora";

$encabezado="Evento No. ".$evento." ".$fecha." hora: ".$hora." - ".$descripcion;

$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["fechahora"]=$fechahora;
$_SESSION["modalidad"]=$modalidad;
$_SESSION["nom_modalidad"]=$nom_modalidad;
$_SESSION["cat"]=$cat;
$_SESSION["sexos"]=$sexos;
$_SESSION["tipo"]=$tipo;
$_SESSION["evento"]=$evento;
$_SESSION["descripcion"]=$descripcion;
$_SESSION["fecha"]=$fecha;
$_SESSION["hora"]=$hora;
$_SESSION["numero_evento"]=$numero_evento;
?>

<form id="jueces-evento" name="jueces-evento-frm" action="?op=php/alta-juez-evento.php" method="post" enctype="multipart/form-data">
<fieldset>
	<?php include("titulo-competencia.php") ?>
	<h2><?php echo $encabezado ?></h2>
	<div>
		<label for="arbitro">Arbitro:</label>
		<input type='text' id="arbitro" name="arbitro_txt" value="<?php echo $arbitro; ?>" readonly>
		<?php 
			if ($img_arbitro) 
				echo "<img class='textwrap' src='img/fotos/$img_arbitro' width='3%'/>&nbsp;&nbsp;";
			if ($es_administrador)
				echo '<input type="submit" id="buscar-arbitro" title="buscar arbitro" name="buscar_arbitro_sbm" value="Buscar">&nbsp;&nbsp;';
		?>
		<input type="submit" id="regresar" title="Regresa a eventos de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
<!-- 		<input type="submit" id="copia1" title="Copia los jueces del panel 1 ;)" class="cambio" name="copia1_sbm" value="Copiar" >
 -->		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
		<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
		<input type="hidden" id="evento" name="evento_hdn" value="<?php echo $evento; ?>" />
		<input type="hidden" id="numero_evento" name="numero_evento_hdn" value="<?php echo $numero_evento; ?>" />
		<input type="hidden" id="descripcion" name="descripcion_hdn" value="<?php echo $descripcion; ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamador; ?>" />
		<input type="hidden" id="origen" name="origen_hdn" value="<?php echo $origen; ?>" />
		<input type="hidden" id="encabezado" name="encabezado_hdn" value="<?php echo $encabezado; ?>" />
	</div>
	<table class="tablas" width="100%"> 
		<tr align="center"> 
			<td width="50%"> 
				<span>Panel 1</span>
				<div id="div1" >
				<table class="tablas" width="100%">
					<tr> 
						<th>Ubicaci&oacute;n</th>	  
						<th>Juez</th>
						<?php 
							if ($es_administrador)
								echo '<th colspan="4">Opciones</th>';
						 ?>
					</tr> 
			 		<?php 
			 			for ($i=1; $i < 12; $i++) { 
			 				if (isset($panel_1[$i])) {
				 				$row=$panel_1[$i];
								$ubicacion=$row["ubicacion"];
								$cod_juez=$row["juez"];
								$nom_juez=$row["nombre"];
								$imagen=$row["imagen"];
			 				}
			 				else{
								$ubicacion=$i;
								$cod_juez=NULL;
								$nom_juez=NULL;
								$imagen=NULL;
			 				}
							echo "<tr><td align='center'>";
							$name="cod_juez_".$i+1;
							echo "<input type='hidden' name='$name' value='$cod_juez'";
							echo ">".$ubicacion."</td>";  
    						echo "<td>";
							if ($imagen) 
	   							echo "<img class='textwrap'src='img/fotos/$imagen' width='8%'/>&nbsp;&nbsp;";

	   						echo $nom_juez."</td>";
	   						if ($es_administrador) {
	    						echo "<td><a href='?op=php/busca-juez-competencia.php&tipo=J&ori=$origen&ubi=$ubicacion&com=$competencia&cco=$cod_competencia&fh=$fechahora'>&nbsp;Buscar&nbsp;</td>";
	    						echo "<td><a href='?op=php/baja-juez-evento.php&ccom=$cod_competencia&nev=$numero_evento&panel=1&ubi=$ubicacion&ori=$origen'>&nbsp;Eliminar&nbsp;</td>";
	    					}
			    			echo "</tr>"; 
			 			}
	 				?>
 				</table> 
 				</div>
			</td>		
			<td>&nbsp</td>
			<td width="45%"> 
				<span>Panel 2</span>
				<div id="div1">
				<table class="tablas" width="100%">
					<tr> 
						<th>Ubicaci&oacute;n</th>	  
						<th>Juez</th>
						<?php 
							if ($es_administrador)
								echo '<th colspan="4">Opciones</th>';
						 ?>
					</tr> 
			 		<?php 
			 			for ($i=1; $i < 12; $i++) { 
			 				if (isset($panel_2[$i])) {
				 				$row=$panel_2[$i];
								$ubicacion=$row["ubicacion"];
								$cod_juez=$row["juez"];
								$nom_juez=$row["nombre"];
								$imagen=$row["imagen"];
			 				}
			 				else{
								$ubicacion=$i;
								$cod_juez=NULL;
								$nom_juez=NULL;
								$imagen=NULL;
			 				}
							echo "<tr><td align='center'>";
							$name="cod_juez_".$i+12;
							echo "<input type='hidden' name='$name' value='$cod_juez'";
							echo ">".$ubicacion."</td>";  
    						echo "<td>";
							if ($imagen) 
	   							echo "<img class='textwrap'src='img/fotos/$imagen' width='8%'/>&nbsp;&nbsp;";

	   						echo $nom_juez."</td>";
	   						$ubi=$ubicacion+11;
	   						if ($es_administrador) {
    							echo "<td><a href='?op=php/busca-juez-competencia.php&tipo=J&ori=$origen&ubi=$ubi&com=$competencia&cco=$cod_competencia'>&nbsp;Buscar&nbsp;</td>";
	    						echo "<td><a href='?op=php/baja-juez-evento.php&ccom=$cod_competencia&nev=$numero_evento&panel=1&ubi=$ubi&ori=$origen'>&nbsp;Eliminar&nbsp;</td>";
	   						}
			    			echo "</tr>"; 
			 			}
	 				?>
 				</table> 
 				</div>
			</td>		
		</tr>
		<tr>
			<td>
				<span>Jueces de Sombra</span><br>
				<?php 
				if ($es_administrador){
					echo '&nbsp;<input type="submit" name="agrega_juez_sombra_sbm" value="Agregar Juez" title="Agregar juez de sombra ;)" >';
					echo '&nbsp;<input type="submit" name="jueces_sombra_sbm" value="Todos" title="Toma todos los jueces de sombra del evento ;)" >&nbsp;&nbsp;';
					echo '<input type="submit" name="limpia_sombra_sbm" value="Limpia" title="Limpia la lista de jueces de sombra del evento ;)" >&nbsp;&nbsp;';
				}
				?>
				<div id="div1">
					<table class="tablas" width="100%">
						<tr> 							  
							<th>Juez</th>
							<?php 
							if ($es_administrador)
								echo '<th>Opciones</th>';
							?>
						</tr> 
						<?php 
						foreach ($panel_sombra as $i => $row) {
							$cod_juez=isset($row["juez"])?$row["juez"]:NULL;
							$nom_juez=isset($row["nombre"])?$row["nombre"]:NULL;
							$imagen=isset($row["imagen"])?$row["imagen"]:NULL;
							echo "<tr><td>";
							if ($imagen) 
								echo "<img class='textwrap'src='img/fotos/$imagen' width='8%'/>&nbsp;&nbsp;";

							echo $nom_juez."</td>";

	   						$ubi=$i+51;
							if ($es_administrador) {
								echo "<td><a href='?op=php/baja-juez-sombra-evento.php&ccom=$cod_competencia&nev=$numero_evento&ju=$cod_juez&ori=$origen'>&nbsp;Eliminar&nbsp;</td>";
							}
							echo "</tr>"; 
						}
						?>
					</table> 
				</div>				
			</td>
			<td></td>
		</tr>		
	</table>
	
</fieldset>

</form>