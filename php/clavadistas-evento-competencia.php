<?php 
session_start();
include("funciones.php");
if (isset($_GET["cco"])) {
	$fechahora=trim($_GET["fh"]);
	$cod_competencia=trim($_GET["cco"]);
	include('lee-evento-competencia.php');
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

if (is_null($competencia)) $competencia=trim($_SESSION["competencia"]);
if (is_null($cod_competencia)) $cod_competencia=trim($_SESSION["cod_competencia"]);

if (is_null($fechahora)) $fechahora=trim($_SESSION["fechahora"]);
if (!isset($sorteada)) 
	$sorteada=isset($_SESSION["sorteada"])?$_SESSION["sorteada"]:NULL;
if (!isset($primero_libres))
	$primero_libres=isset($_SESSION["primero_libres"])?$_SESSION["primero_libres"]:NULL;
if (is_null($modalidad)) $modalidad=trim($_SESSION["modalidad"]);
if (is_null($nom_modalidad)) $nom_modalidad=trim($_SESSION["nom_modalidad"]);
if (is_null($cat)) $cat=trim($_SESSION["cat"]);
if (is_null($sx)) $sx=trim($_SESSION["sx"]);
if (is_null($tipo)) $tipo=trim($_SESSION["tipo"]);
if (is_null($evento)) $evento=trim($_SESSION["evento"]);
if (is_null($numero_evento)) $numero_evento=trim($_SESSION["numero_evento"]);
if (is_null($descripcion)) $descripcion=trim($_SESSION["descripcion"]);
if (is_null($fecha)) $fecha=trim($_SESSION["fecha"]);
if (is_null($edita)) $edita=trim($_SESSION["edita"]);


$categorias=explode("-", $cat) ;
$sexos=explode("-", $sx);

$conexion=conectarse();
$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.orden_salida, d.ronda, d.salto, d.posicion, d.altura
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";

$criterio=" WHERE (p.competencia=$cod_competencia AND p.modalidad='$modalidad' and p.usuario_retiro IS NULL ";

$n=count($sexos);
$criterio_sexos=NULL;
for ($i=0; $i < $n ; $i++) { 
	if (is_null($criterio_sexos))
		$criterio_sexos=" AND (p.sexo='$sexos[$i]'";
	else
		$criterio_sexos .= " OR p.sexo='$sexos[$i]'";
}
if ($criterio_sexos) 
	$criterio_sexos .= ")"; 

$n=count($categorias);
$criterio_categorias=NULL;
for ($i=0; $i < $n ; $i++) { 
	if (is_null($criterio_categorias))
		$criterio_categorias=" AND (p.categoria='$categorias[$i]'";
	else
		$criterio_categorias .= " OR p.categoria='$categorias[$i]'";
}

if ($criterio_categorias) 
	$criterio_categorias .= ")"; 

 if ($sorteada) 
	$consulta.=" WHERE (p.competencia=$cod_competencia AND p.evento=$numero_evento and p.usuario_retiro IS NULL)";
else
	$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")";

$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;

// $tiempo_estimado=number_format(($num_regs*50/60)+2);
if ($tiempo_estimado_inicial) {
	$tiempo_estimado=$tiempo_estimado_inicial;
}
else{
	$tiempo_estimado=tiempo_estimado($num_regs);
}

$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.entrenador, p.modalidad, p.categoria, p.sexo, p.equipo, p.extraof, p.clavadista2, p.orden_salida, c.nombre as nom_cla, 
	c2.nombre as nom_cla2, 
	c3.nombre as nom_cla3, 
	c4.nombre as nom_cla4, 
	m.modalidad as nom_mod,  
	q.equipo as nom_equipo, q.bandera,
	t.categoria as nom_cat,
	p.participa_extraof
	FROM planillas as p
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN usuarios as c3 on c3.cod_usuario=p.clavadista3
	LEFT JOIN usuarios as c4 on c4.cod_usuario=p.clavadista4
	LEFT JOIN categorias as t on t.cod_categoria=p.categoria
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";

if ($sorteada) 
	$consulta.=" WHERE (p.competencia=$cod_competencia AND p.evento=$numero_evento and p.usuario_retiro IS NULL";
else
	$consulta .= $criterio.$criterio_sexos.$criterio_categorias;

if ($sorteada) 
	$consulta.=") ORDER BY p.orden_salida, nom_cla";
else
	$consulta.=") ORDER BY nom_equipo, nom_cla";
$_SESSION['consulta']=$consulta;

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
$num_regs=$ejecutar_consulta->num_rows;
$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["fechahora"]=$fechahora;
$_SESSION["sorteada"]=$sorteada;
$_SESSION["primero_libres"]=$primero_libres;
$_SESSION["modalidad"]=$modalidad;
$_SESSION["nom_modalidad"]=$nom_modalidad;
$_SESSION["cat"]=$cat;
$_SESSION["sx"]=$sx;
$_SESSION["tipo"]=$tipo;
$_SESSION["evento"]=$evento;
$_SESSION["numero_evento"]=$numero_evento;
$_SESSION["descripcion"]=$descripcion;
$_SESSION["fecha"]=$fecha;
$_SESSION["edita"]=$edita;
$_SESSION["primero_libres"]=$primero_libres;


$llamador="php/clavadistas-evento-competencia.php*com=$competencia*fh=$fechahora*mod=$modalidad*cat=$cat*cco=$cod_competencia*mod=$modalidad*sx=$sx*tp=$tipo*ev=$evento*nev=$numero_evento*des=$descripcion*ho=$fecha*sort=$sorteada*pl=$primero_libres";
//$llamador="php/clavadistas-evento-competencia.php";

$descripcion=utf8_decode($descripcion);
$encabezado="Evento No. ".$evento." ".$fecha." - ".$descripcion." Tiempo Estimado: ".$tiempo_estimado." minutos";

if ($edita==1) {
	$orden=$_SESSION["orden"];
}
?>

<form id="clavadistas-competencia" name="clavadistas-competencia-frm" action="?op=php/menu-clavadistas-competencia.php" method="post" enctype="multipart/form-data">
<fieldset>
	<?php include("titulo-competencia.php") ?>
	<h2><?php echo $encabezado ?></h2>
	<div id="div1">
	<caption>
		<?php 
			echo "<span align'center'>Competidores: $num_regs</span>";
			if ($edita==1) 
				echo "Editando"; 

		?>
			
		</caption>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' class="tablas">
		<tr align="center">
			<th>Orden Salida</th>	  
			<th>Clavadistas</th>
			<th>Equipo</th>
			<th>Categor&iacute;a</th>
			<th>Sexo</th>
			<th colspan="2">Opciones</th>	  
		</tr> 

	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs)
			$mensaje="No hay Clavadistas registrados en este evento :(";
		else{
			$i=0;
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_planilla=$row["cod_planilla"];
				$cod_cla=$row["clavadista"];
				$cod_ent=$row["entrenador"];
				$cod_equ=$row["equipo"];
				$orden_salida=$row["orden_salida"];
				$cod_modalidad=$row["modalidad"];
				$nom_cla=$row["nom_cla"];
				$nom_cla2=$row["nom_cla2"];
				$nom_cla3=isset($row["nom_cla3"])?$row["nom_cla3"]:NULL;
				$nom_cla4=isset($row["nom_cla4"])?$row["nom_cla4"]:NULL;
				if (strlen($nom_cla2)>0) 
					$nom_cla .= " / ".$nom_cla2;
				if (strlen($nom_cla3)>0) 
					$nom_cla .= " / ".$nom_cla3;
				if (strlen($nom_cla4)>0) 
					$nom_cla .= " / ".$nom_cla4;
				$nom_cla=primera_mayuscula_palabra($nom_cla);
				$nom_equipo=strtoupper(utf8_decode($row["nom_equipo"]));
				$bandera=$row["bandera"];
				$cod_categoria=$row["categoria"];
				$sexo=$row["sexo"];
				switch ($sexo) {
					case 'M':
						$n_sex="Masculino";
						break;
					case 'F':
						$n_sex="Femenino";
						break;
					case 'X':
						$n_sex="Mixto";
						break;
				}
				$participa_extraof=$row["participa_extraof"];
				$nom_cat=primera_mayuscula_palabra($row["nom_cat"]);
				$nom_mod=primera_mayuscula_palabra($row["nom_mod"]);
				$name="orden".$i."_num";
				$cons_saltos="SELECT * FROM planillad WHERE planilla=$cod_planilla";
				$men="Planilla(sin saltos)";
				$ejecutar_cons_saltos = $conexion->query(utf8_decode($cons_saltos));
				if ($ejecutar_cons_saltos)
					$saltos=$ejecutar_cons_saltos->num_rows;
				if ($saltos) 
					$men="Planilla";

/*				if ($i%2==0)
					echo "<tr class='linea1'";
				else
*/				
				if ($n_sex=="Mixto") 
					if ($cod_modalidad=="E") 
						if ($cod_categoria=='Q1')
							$programa="cambio-planilla-equ-ju.php";
						else
							$programa="cambio-planilla-equ.php";
					else
						$programa="cambio-planilla-mix.php";
				else
					$programa="cambio-planilla.php";

				echo "<tr";
				echo " align='center'><td>";
				if ($edita==1)
					echo "<input type='number' name='$name' min='1' max='99' step='1' value='$orden[$i]'>";
				if ($edita==0) 
					echo "<input type='number' name='$name'  min='1' max='99' step='1' value='$orden_salida' readonly>";
				$name="clav".$i."_hdn";
				echo "<input type='hidden' name='$name' value='$cod_cla'";
				echo "></td>";  
				$i++;
				if ($participa_extraof=="*") {
					$nom_cla="** (Extraoficial)*** ".$nom_cla;
				}
    			echo "<td align='left'>".$nom_cla."</td>";
    			echo "<td>".$nom_equipo;
				if ($bandera) 
					echo "&nbsp;<img class='textwrap' src='img/banderas/".$bandera.".png' width='12%'/>";
				echo "</td>";
    			echo "<td>".$nom_cat."</td>";
    			echo "<td>".$n_sex."</td>";
    			echo "<td><a href='?op=php/".$programa."&pl=$cod_planilla&ori=$llamador'>$men</td>";
    			echo "<td><a href='?op=php/baja-planilla.php&pl=$cod_planilla&eq=$nom_equipo&cl=$nom_cla&cat=$nom_cat&md=$nom_mod&ccl=cod_cla&cen=$cod_ent&comp=$competencia&cco=$cod_competencia&cor=$cod_equ&ori=$llamador'>Eliminar</td>";
    			echo "</tr>"; 
			}				
		}
		$conexion->close();
	?>
	</table> 
	</div>
	<div>
		<br>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="participantes" name="participantes_hdn" value="<?php echo $i; ?>" />
		<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" />
		<input type="hidden" id="evento" name="evento_hdn" value="<?php echo $evento; ?>" />
		<input type="hidden" id="numero-evento" name="numero_evento_hdn" value="<?php echo $numero_evento; ?>" />
		<input type="hidden" id="descripcion" name="descripcion_hdn" value="<?php echo $descripcion; ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamador; ?>" />
		<input type="hidden" id="fecha" name="fecha_hdn" value="<?php echo $fecha; ?>" />
		<input type="hidden" id="criterio" name="criterio_hdn" value="<?php echo $criterio; ?>" />
		<input type="hidden" id="modalidad" name="modalidad_hdn" value="<?php echo $modalidad; ?>" />
		<input type="hidden" id="criterio-sexos" name="criterio_sexos_hdn" value="<?php echo $criterio_sexos; ?>" />
		<input type="hidden" id="criterio-categorias" name="criterio_categorias_hdn" value="<?php echo $criterio_categorias; ?>" />
		<input type="hidden" id="primero-libres" name="primero_libres_hdn" value="<?php echo $primero_libres; ?>" />
		<input type="hidden" id="tiempo" name="tiempo_hdn" value="<?php echo $tiempo_estimado; ?>" />
		<input type="hidden" id="sorteada" name="sorteada_hdn" value="<?php echo $sorteada; ?>" />
		<input type="submit" id="sorteo" title="Sortea el orden de salida" class="cambio" name="sorteo_sbm" value="Sorteo" />
		<input type="submit" id="limpia-orden" title="Limpia el orden de salida" class="cambio" name="limpia_orden_sbm" value="Limpia" />
		<?php 
			if ($edita==1) 
				echo "<input type='submit' id='guardar' title='Guarda el orden de salida' class='cambio' name='guardar_sbm' value='Guardar' />";	
			else
				echo "<input type='submit' id='editar' title='Edita el orden de salida' class='cambio' name='editar_sbm' value='Editar' />";
		 ?>
		<input type="submit" id="detalle-saltos" title="Imprime el Detalle de Saltos" class="cambio" name="detalle_saltos_sbm" value="Detalle Saltos" />
		<input type="submit" id="planilla" title="Imprime planillas" class="cambio" name="imprime_planilla_sbm" value="Planillas" >
		&nbsp;
		<input type="submit" id="jueces-sombra" title="Imprime formato para jueces de sombra" class="cambio" name="jueces_sombra_sbm" value="Form.Sombra" >
<!-- 		<input type="submit" id="exporta_detalle" title="Exporta el Detalle de Saltos ;)" class="cambio" name="exporta_detalle_sbm" value="Exporta detalle">
 -->
		<input type="submit" id="regresar" title="Regresa a eventos de la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />

	</div>
</form>
</fieldset>
