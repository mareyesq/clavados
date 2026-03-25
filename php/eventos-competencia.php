<?php 
session_start();
include("funciones.php");
$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
$cod_competencia=isset($_GET["cco"])?trim($_GET["cco"]):NULL;

if (is_null($competencia))
	$competencia=isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:NULL;
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;

if (!$competencia)
	$competencia=$_SESSION["competencia"];

$cod_usuario=$_SESSION["usuario_id"];
if (isset($cod_usuario)) {
	$es_administrador=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($es_administrador,0,5)=="Error")
		$es_administrador=0;
	else
		$es_administrador=1;
}

$roles=defina_rol($cod_usuario);
$es_juez=in_array("J", $roles);

if (!isset($dia_sel)) 
	$dia_sel=(isset($_SESSION["dia_sel"])?$_SESSION["dia_sel"]:NULL);
if (!isset($modalidad_sel)) 
	$modalidad_sel=(isset($_SESSION["modalidad_sel"])?$_SESSION["modalidad_sel"]:NULL);
if (!isset($categoria_sel)) 
	$categoria_sel=(isset($_SESSION["categoria_sel"])?$_SESSION["categoria_sel"]:NULL);

$conexion=conectarse();
$consulta="SELECT DISTINCT j.numero_evento as numero_evento, j.fechahora as fechahora, j.modalidad as cod_modalidad, m.modalidad as nom_modalidad, m.individual, j.sexos as sexos, j.categorias as categorias, j.tipo, j.primero_libres, j.sorteada, j.usa_dispositivos, j.evento, j.inicio_comp, j.finalizo_comp, j.calentamiento, j.participantes_estimado, j.tiempo_estimado_inicial
FROM competenciaev as j 
LEFT JOIN modalidades as m on m.cod_modalidad=j.modalidad 
WHERE (j.competencia=$cod_competencia)";
if ($dia_sel) 
	$consulta .= " AND DATE(j.fechahora)='$dia_sel'";
if ($modalidad_sel) 
	$consulta .= " AND j.modalidad='$modalidad_sel'";
if ($categoria_sel) 
	$consulta .= " AND j.categorias IN ('$categoria_sel')";
$consulta .= " ORDER BY j.fechahora";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

$num_regs=$ejecutar_consulta->num_rows;

?>

<form id="eventos-competencia" name="eventos-competencia-frm" action="?op=php/eventos-competencia-1.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<h2>Eventos Programados de la Competencia</h2>
		<div>
			<label class="rotulo">Eventos:</label>
			<span><?php echo number_format($num_regs); ?></span>&nbsp;
			<label class="rotulo">Día:</label>

			<select id='dia-sel' class='cambio' name='dia_sel_slc' title='Filtra eventos por día' onChange="this.form.submit()" >
				<option value="" >- - -</option>
				<?php  include("select-dia-competencia.php"); ?> 
			</select>
			<label class="rotulo">Modalidad:</label>
			<select id='modalidad-sel' class='cambio' name='modalidad_sel_slc' title='Filtra eventos por modalidad' onChange="this.form.submit()" >
				<option value="" >- - -</option>
				<?php 
				$cod_modalidad=$modalidad_sel;
				include("select-modalidad.php"); 
				?> 
			</select>
			<label class="rotulo">Categoría:</label>
			<select id='categoria-sel' class='cambio' name='categoria_sel_slc' title='Filtra eventos por categoría' onChange="this.form.submit()" >
				<option value="" >- - -</option>
				<?php include("select-categoria-competencia-new.php"); ?> 
			</select>
		</div>
		<?php 
		if (navegador_compatible("Chrome"))
			echo "<div id= 'div1'>";
		?>

		<table width='100%'  class="tablas">
			<tr align="center">
				<th>Evento</th>	  
				<th>Hora</th>
				<th>Descripción</th>
				<th width="4%" >Dispositivos</th>
				<?php 
				if ($es_administrador==1)
					$columnas=22;
				elseif ($es_juez)
					$columnas=14;
				else
					$columnas=12;
				echo "<th colspan='".$columnas."' >";
				?>
			Opciones</th>	  
		</tr> 

		<?php 
		if (!$num_regs)
			if ($dia_sel or $modalidad_sel) 
				$mensaje="No hay Eventos registrados en este filtro de la competencia $competencia :(";
			else
				$mensaje="No hay Eventos registrados en la competencia $competencia :(";
			else{
				$dia="";
				while ($row=$ejecutar_consulta->fetch_assoc()){
					$evento=isset($row['evento'])?$row['evento']:NULL;
					$fechahora=$row["fechahora"];
					$fecha_coloquial=hora_coloquial($fechahora);
					$date = date_create($fechahora);
					$fecha=substr($fechahora,0,10);
					$hora=date_format($date,'g:i a');
					$fecha=hora_coloquial($fecha);
					$calentamiento=isset($row['calentamiento'])?$row['calentamiento']:NULL;
					$participantes_estimado=isset($row['participantes_estimado'])?$row['participantes_estimado']:NULL;
					$nom_modalidad=utf8_decode($row["nom_modalidad"]);
					$cod_modalidad=utf8_decode($row["cod_modalidad"]);
					$individual=$row["individual"];
					$sexos=$row["sexos"];
					$categorias=$row["categorias"];
					$tipo=$row["tipo"];
					$numero_evento=$row["numero_evento"];
					$sorteada=isset($row["sorteada"])?$row['sorteada']:NULL;
					$tiempo_estimado_inicial=isset($row["tiempo_estimado_inicial"])?$row['tiempo_estimado_inicial']:NULL;
					$inicio_comp=isset($row["inicio_comp"])?$row['inicio_comp']:NULL;
					$finalizo_comp=isset($row["finalizo_comp"])?$row['finalizo_comp']:NULL;
					$primero_libres=$row["primero_libres"];
					$usa_dispositivos=isset($row["usa_dispositivos"])?$row["usa_dispositivos"]:NULL;
					$descripcion=describe_evento($nom_modalidad,$sexos,$categorias,$tipo);
					$numero_jueces=numero_jueces($cod_competencia,$numero_evento,1);
					$numero_jueces=is_numeric($numero_jueces)?$numero_jueces:NULL;
					if ($dia!=$fecha){
						$columnas_dia=$columnas+2;
						echo "<tr align='center' ><td colspan='".$columnas_dia."' class='subtitulo' >$fecha<td></tr>";
						$dia=$fecha;
					}
					if ($calentamiento) {
						$mifecha = new DateTime($fechahora); 
						$mifecha->modify('-'.$calentamiento.' minute'); 
						$hora_calentamiento=$mifecha->format('g:i a');
						;
						echo "<tr align='center' >
						<td class='calentamiento subtitulo' ></td><td class='calentamiento'>$hora_calentamiento</td>
						<td class='calentamiento' colspan='".$columnas."' >Calentamiento<td><td class='calentamiento'></td></tr>";
					}

					echo "<tr align='center'>";  
					echo "<td>".$evento."</td>";	
					echo "<td nowrap>".$hora."</td>";
					echo "<td>".$descripcion."</td>";
					echo "<td align'center'>";
					if ($usa_dispositivos)
						echo "<img src='img/tablet.jpg' width='15%'/>";

					echo "</td>" ;
					if ($es_administrador){
						echo "<td><a class='enlaces_tab' href='?op=php/edita-evento-competencia.php&com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&pl=$primero_libres&disp=$usa_dispositivos&lg=$logo&lg2=$logo2&cal=$calentamiento&par=$participantes_estimado&tes=$tiempo_estimado_inicial'>Editar</td>";
						echo "<td><a class='enlaces_tab' href='?op=php/baja-evento-competencia.php&com=$competencia&fh=$fechahora&mod=$modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&lg=$logo&lg2=$logo2'>Eliminar</td>";
						echo "";
						if ($sorteada AND !$finalizo_comp AND $numero_jueces){
							echo "<td><a class='enlaces_tab' href='?op=php/califica-evento-competencia.php&com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&nev=$numero_evento&des=$descripcion&ho=$fecha&ev=$evento&hor=$hora&pl=$primero_libres&disp=$usa_dispositivos&lg=$logo&lg2=$logo2'>Calificaciones</td>";
						}
						else{
							echo "<td></td>";
						}

						if ($inicio_comp AND !$finalizo_comp){
							echo "<td title='anunciador'><a class='enlaces_tab' href='php/siguiente-salto.php?com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&nev=$numero_evento&des=$descripcion&ho=$fecha&ev=$evento&hor=$hora&lg=$logo&lg2=$logo2&anu=1' target='_new'>Anun.</td>";
						}
						else
							echo "<td></td>";	
						if ($inicio_comp AND !$finalizo_comp)
							echo "<td><a class='enlaces_tab' href='php/siguiente-salto.php?com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&nev=$numero_evento&des=$descripcion&ho=$fecha&ev=$evento&hor=$hora&lg=$logo&lg2=$logo2' target='_new'>Saltos</td>";
						else
							echo "<td></td>";
						if ($sorteada)
							echo "<td><a class='enlaces_tab' href='?op=php/jueces-evento-competencia.php&cco=$cod_competencia&fh=$fechahora'>Jueces</td>";
						else
							echo "<td></td>";

					}
					echo "<td><a class='enlaces_tab' href='?op=php/clavadistas-evento-competencia.php&cco=$cod_competencia&fh=$fechahora'>Clavadistas</td>";
					if (($es_juez or $juez_sombra) AND $numero_jueces AND !$finalizo_comp)
						echo "<td><a class='enlaces_tab' href='?op=php/califica-evento-juez.php&com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&nev=$numero_evento&des=$descripcion&ho=$fecha&ev=$evento&hor=$hora&pl=$primero_libres&lg=$logo&lg2=$logo2'>Calificar</td>";
					else
							echo "<td></td>";

					echo "<td><a class='enlaces_tab' href='php/resultados-evento.php?com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&nev=$numero_evento&des=$descripcion&ho=$fecha&ev=$evento&hor=$hora&lg=$logo&lg2=$logo2' target='_new'>";
					if ($inicio_comp)
						echo "Resultados</td>";
					else{
						echo "Aún no inicia</td>";
					}

					if ($finalizo_comp AND $individual){
						echo "<td><a class='enlaces_tab' href='php/evaluacion-jueces-de-competencia.php?com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&codco=$cod_competencia&nev=$numero_evento&des=$descripcion&ho=$fecha&ev=$evento&hor=$hora&lg=$logo&lg2=$logo2'>Eval.Jueces</td>";
						echo "<td><a class='enlaces_tab' href='?op=php/comparativo-jueces-de-competencia.php&com=$competencia&fh=$fechahora&mod=$cod_modalidad&cat=$categorias&sx=$sexos&tp=$tipo&cco=$cod_competencia&nev=$numero_evento&des=$descripcion&ho=$fecha_coloquial&ev=$evento&hor=$hora&lg=$logo&lg2=$logo2'>Comp.Jueces</td>";
					}
					else{
						echo "<td></td>";
						echo "<td></td>";
					}
					echo "</tr>"; 
				}
			}
			$conexion->close();
			?>
		</table> 
		<?php 
		if (navegador_compatible("Chrome"))
			echo "</div>";
		?>

		<div>
			<br>
			<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
			<input type="hidden" id="cod_competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
			<input type="submit" id="nuevo" title="Registra un nuevo evento" class="cambio" name="registrar_evento_btn" value="Nuevo Evento" />
			&nbsp;
			<input type="submit" id="programacion" title="Imprime la programación de eventos" class="cambio" name="imprime_programa_btn" value="Imprime Programación" />
			&nbsp;
			<input type="submit" id="lista" title="lista de inscritos por equipo" class="cambio" name="clavadistas_equipo_btn" value="Inscritos por Equipo" />
			&nbsp;
			<input type="submit" id="competidores_evento" title="Reporte de competidores por evento" class="cambio" name="competidores_evento_btn" value="Competidores por Evento" />
			&nbsp;
			<input type="submit" id="exporta" title="Exporta Planillas" class="cambio" name="exporta_planillas_btn" value="Exporta Planillas" />
			&nbsp;
<!-- 			<input type="submit" id="calcular_horarios" title="Calcula los horarios de los eventos ;)" class="cambio" name="calcular_horarios_btn" value="Calcular Horarios " >
			&nbsp;
 -->			<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>