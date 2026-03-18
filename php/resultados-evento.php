<?php 
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();
include_once("funciones.php");

if (isset($_GET["fh"])) {
	$fechahora=trim($_GET["fh"]);
	$cod_competencia=trim($_GET["codco"]);
	$competencia=trim($_GET["com"]);
	$modalidad=trim($_GET["mod"]);
	$cat=trim($_GET["cat"]) ;
	$sx=trim($_GET["sx"]);
	$tipo=trim($_GET["tp"]);
	$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;
	$logo2=isset($_GET["lg2"])?$_GET["lg2"]:NULL;
	$_SESSION["logo"]=$logo;
	$_SESSION["logo2"]=$logo2;
	$numero_evento=trim($_GET["nev"]);
	$descripcion=trim($_GET["des"]);
	$fecha=trim($_GET["ho"]);
	$hora=trim($_GET["hor"]);
	$evento=trim($_GET["ev"]);
	$origen="?op=php/califica-evento-competencia.php*com=$competencia*mod=$modalidad*cat=$cat*sx=$sx*tp=$tipo*codco=$cod_competencia*nev=$evento*des=$descripcion*ho=$fecha*ev=$evento*hor=$hora";
	$_SESSION["cod_competencia"]=$cod_competencia;
	$_SESSION["modalidad"]=$modalidad;
	$_SESSION["evento"]=$evento;
	$_SESSION["numero_evento"]=$numero_evento;
}
if (!$logo)
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
if (!$logo2)
	$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;
$turno=isset($_SESSION["ultimo_turno"])?$_SESSION["ultimo_turno"]:NULL;

if ($turno) {
	$ultimo_orden_salida=isset($_SESSION["ultimo_orden_salida"])?$_SESSION["ultimo_orden_salida"]:NULL;

 	$criterio_turno=" AND turno=".$turno;
 	if ($ultimo_orden_salida)
 		$criterio_turno .= " AND orden_salida=".$ultimo_orden_salida;
 	if ($modalidad=="E" AND $cat=="EQ"){
	 	$ejecutor=isset($_SESSION["ultimo_ejecutor"])?$_SESSION["ultimo_ejecutor"]:NULL;
 		if ($ejecutor)
 			$criterio_turno .= " AND ejecutor=$ejecutor";
 	}

} 
else
 	$criterio_turno=" AND calificado IS NULL ";

if (is_null($competencia)) $competencia=trim($_SESSION["competencia"]);
if (is_null($cod_competencia)) $cod_competencia=trim($_SESSION["cod_competencia"]);
if (is_null($fechahora)) $fechahora=trim($_SESSION["fechahora"]);
if (is_null($modalidad)) $modalidad=trim($_SESSION["modalidad"]);
if (is_null($cat)) $cat=trim($_SESSION["cat"]);
if (is_null($sexos)) $sexos=trim($_SESSION["sexoss"]);
if (is_null($tipo)) $tipo=trim($_SESSION["tipo"]);
if (is_null($evento)) $evento=trim($_SESSION["evento"]);
if (is_null($numero_evento)) $numero_evento=trim($_SESSION["numero_evento"]);
if (is_null($descripcion)) $descripcion=trim($_SESSION["descripcion"]);
if (is_null($fecha)) $fecha=trim($_SESSION["fecha"]);
if (is_null($hora)) $hora=trim($_SESSION["hora"]);
if (is_null($origen)) $origen=trim($_SESSION["origen"]);

$dec=decimales($cod_competencia);
$conexion=conectarse();
$consulta="SELECT inicio_comp FROM competenciaev WHERE competencia=$cod_competencia AND numero_evento=$numero_evento and inicio_comp IS NULL";
$ejecutar_consulta = $conexion->query($consulta);

$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==0) 
	$inicio=TRUE;
else
	$inicio=FALSE;

$consulta="SELECT finalizo_comp FROM competenciaev WHERE competencia=$cod_competencia AND numero_evento=$numero_evento and finalizo_comp IS NULL";
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==0) 
	$finalizo=1;
else
	$finalizo=0;

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

$criterio=" WHERE (p.competencia=$cod_competencia AND p.modalidad='$modalidad' AND p.usuario_retiro IS NULL AND p.evento=$numero_evento) ";

$consulta="SELECT DISTINCT p.cod_planilla 
	FROM planillas as p";
$consulta .= $criterio;
$ejecutar_consulta = $conexion->query($consulta);

$competidores=$ejecutar_consulta->num_rows;

$consulta="SELECT DISTINCT MAX(d.ronda) as rondas
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";

$consulta .= $criterio;
$ejecutar_consulta = $conexion->query($consulta);
$row=$ejecutar_consulta->fetch_assoc();

$rondas=$row["rondas"];

$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.orden_salida, d.ronda, d.salto, d.posicion, d.altura, d.calificado
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla";
$consulta .= $criterio;
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;

//$tiempo_estimado=number_format($num_regs*40/60);
$tiempo_estimado=tiempo_estimado($num_regs);

$consulta="SELECT DISTINCT ubicacion, juez	FROM competenciasz
	WHERE competencia=$cod_competencia and evento=$numero_evento and panel=1
	ORDER BY ubicacion";
$ejecutar_consulta = $conexion->query($consulta);

if ($ejecutar_consulta){
	$num_regs=$ejecutar_consulta->num_rows;
	if (!$num_regs)
		$mensaje="No hay jueces en Panel 1 registrados para este evento :(";
	else{
		$num_jueces=0;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$ub=$row["ubicacion"];
			if ($ub!=25){
				$num_jueces++;
			} 
		}

	}
}

$consulta="SELECT DISTINCT p.cod_planilla, p.clavadista, p.entrenador, p.competencia, p.modalidad, p.categoria, p.sexo, p.equipo, p.extraof, p.part_abierta, p.clavadista2, p.clavadista3, p.clavadista4, p.orden_salida, p.total, p.lugar, d.ronda, d.turno, d.ejecutor, d.salto, s.salto as nom_salto, d.posicion, d.altura, d.grado_dif, d.abierto, d.total_salto, d.puntaje_salto, d.acumulado, d.penalizado, d.calificado, d.juez1, d.juez2, d.juez3, d.juez4, d.juez5, d.juez6, d.juez7, d.juez8, d.juez9, d.juez10, d.juez11, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, d.ejecutor, d.ejecutor2,  c.nombre as nom_cla, c2.nombre as nom_cla2, c3.nombre as nom_cla3, c4.nombre as nom_cla4, m.modalidad as nom_mod,  q.equipo as nom_equipo, q.bandera, t.categoria as nom_cat, c.imagen as img_1, c2.imagen as img_2, c3.imagen as img_3, c4.imagen as img_4, r.marca_damas, r.grado_damas, r.promedio_damas, r.marca_varones, r.grado_varones, r.promedio_varones
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
	LEFT JOIN competenciasm as r on r.competencia=p.competencia and r.categoria=p.categoria and r.modalidad=p.modalidad
	LEFT JOIN saltos as s on s.cod_salto=d.salto
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN usuarios as c3 on c3.cod_usuario=p.clavadista3
	LEFT JOIN usuarios as c4 on c4.cod_usuario=p.clavadista4
	LEFT JOIN categorias as t on t.cod_categoria=p.categoria
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta .= $criterio.$criterio_turno." 
 ORDER BY d.turno, p.orden_salida, d.ejecutor
 LIMIT 1";
if (!$finalizo){
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	while ($row=$ejecutar_consulta->fetch_assoc()){
		include("resultados-evento-toma-reg-planilla.php");
	}
}
else
	unset($_SESSION["ultimo_turno"]);
$separa_extraoficiales=0;
$consulta="SELECT max_2_competidores 
	FROM competencias 
	WHERE cod_competencia=$cod_competencia";
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
if ($num_regs==1) {
	$row=$ejecutar_consulta->fetch_assoc();
	$separa_extraoficiales=isset($row["max_2_competidores"])?$row["max_2_competidores"]:0;
}

$criterio_turno=isset($turno)?" AND turno=".$turno:NULL;
if ($ejecutor AND $cat=='EQ')
	$criterio_turno .= " AND ejecutor=$ejecutor";	

$consulta_posiciones="SELECT DISTINCT p.cod_planilla, p.equipo, p.orden_salida, p.total, p.lugar, p.extraof, p.categoria, p.sexo, CONCAT (p.categoria, p.sexo) as orden_categorias, cat.categoria as nom_cat";
if (!$finalizo)
	$consulta_posiciones .= ", d.calificado";

$consulta_posiciones .= ", r.marca_damas, r.grado_damas, r.promedio_damas, r.marca_varones, r.grado_varones, r.promedio_varones, q.equipo as nom_equipo, q.bandera, c.nombre as nom_cla, c.nacimiento, c2.nombre as nom_cla2, c3.nombre as nom_cla3, c4.nombre as nom_cla4
	FROM planillas as p
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
	LEFT JOIN competenciasm as r on r.competencia=p.competencia and r.categoria=p.categoria and r.modalidad=p.modalidad
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN usuarios as c3 on c3.cod_usuario=p.clavadista3
	LEFT JOIN usuarios as c4 on c4.cod_usuario=p.clavadista4
	LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria
	LEFT JOIN competenciast	as t on t.competencia=p.competencia and t.puesto=p.lugar
	LEFT JOIN competenciasq	as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
	WHERE p.competencia=$cod_competencia
		AND p.evento=$numero_evento
		AND p.categoria<>'AB'
		AND p.usuario_retiro IS NULL";

if (!$finalizo) {
	if (isset($turno))
 		$consulta_posiciones.=$criterio_turno;
	if ($categoria!=="AB") 
		$consulta_posiciones.=" AND p.categoria='$categoria'
			AND p.sexo='$sexo'";
}

$consulta_posiciones.=" ORDER BY p.categoria, p.sexo, p.extraof, p.lugar";
$ejecutar_consulta_posiciones = $conexion->query(utf8_decode($consulta_posiciones));

$encabezado="Evento No. ".$evento." ".$fecha." Hora: ".$hora." - ".$descripcion." Tiempo Estimado: ".$tiempo_estimado." minutos";
$encabezado1="Evento No. ".$evento." ".$fecha." Hora: ".$hora;

if ($categoria=="AB" or $part_abierta=="*" or $finalizo==1) {
	$consulta_abierta="SELECT DISTINCT p.cod_planilla, p.equipo, p.orden_salida, p.total_abierta, p.part_abierta, p.lugar_abierta, p.extraof_abierto, p.sexo, r.marca_damas, r.grado_damas, r.promedio_damas, r.marca_varones, r.grado_varones, r.promedio_varones, q.equipo as nom_equipo, c.nacimiento, q.bandera";
	if (!$finalizo)
		$consulta_abierta .= ", d.calificado";

	$consulta_abierta.=", c.nombre as nom_cla
		FROM planillas as p
		LEFT JOIN planillad as d on d.planilla=p.cod_planilla
		LEFT JOIN competenciasm as r on r.competencia=p.competencia and r.categoria=p.categoria and r.modalidad=p.modalidad";
	$consulta_abierta.=" LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
		LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
		LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar_abierta
		LEFT JOIN competenciasq	as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
		WHERE p.competencia=$cod_competencia
			AND p.evento=$numero_evento 		
			AND (p.categoria='AB' or p.part_abierta='*')
			AND p.usuario_retiro IS NULL";
	if (!$finalizo) {
		if (isset($turno))
 			$consulta_abierta.=$criterio_turno;
	}
	$consulta_abierta.=" ORDER BY p.sexo, p.extraof_abierto, p.lugar_abierta";
	$ejecutar_consulta_abierta = $conexion->query(utf8_decode($consulta_abierta));
}
if ($calificado) {
	unset($_SESSION["ultimo_turno"]);
	unset($_SESSION["ultimo_orden_salida"]);
	unset($_SESSION["ultimo_ejecutor"]);
}
else{
	$_SESSION["ultimo_turno"]=$turno;
	$_SESSION["ultimo_orden_salida"]=$orden_salida;
	$_SESSION["ultimo_ejecutor"]=$ejecutor;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Competencias de Clavados</title>
	<meta charset="utf-8"/>
	<meta http-equiv="refresh" content="5" >
	<meta name="description" content="Software para administrar competencias de clavados"/>
    <?php 
        $nav=$_SERVER['HTTP_USER_AGENT'];
        $cual="chrome";
        //Demilitador 'i' para no diferenciar mayus y minus
        if (preg_match("/".$cual."/i", $nav)) 
//            echo "<link rel='stylesheet' type='text/css' href='css/estilos.css'/>";
    ?>
     <link rel='stylesheet' type='text/css' href='../css/estilos.css'/>
     <script type="text/javascript">
     	function abrir_ventana(pagina,planilla){
            var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, titlebar=no, scrollbars=no, resizable=yes, width=700, height=300, top=200, left=640";
            var url=pagina+"?pla="+planilla;
     		window.open(url,"ventana",opciones);
     	}
     </script>
     <script type="text/javascript">
     	function mostrar_calificaciones(){

		    		

     	}
     </script>
</head>
<body>
	<h1>Competencias de Clavados</h1>
<!--	<header>Resultados <?php echo $encabezado; ?></header>-->
    <section id="contenedor">
		<section id="principal" >
 		<form id="resultados-evento" name="resultados-evento-frm" action="..?op=php/menu-resultados-evento.php" method="post" enctype="multipart/form-data">
		<?php
			$nueva_pagina=TRUE; 
			include("titulo-competencia.php") 
		?>
		<h2>Resultados</h2>
		<h3 class="rotulo"><?php echo $encabezado; ?></h3>
		<?php 
		if (!$inicio) {
			echo "<span aling='center'><br>Esta competencia no ha iniciado :(<br></span>";
		}elseif (!$finalizo){
			if (!$no_mostrar_salto)	{
				include("resultados-evento-1.php");
			}
		}
		else {
			echo "<input type='submit' id='imprime-resultados' title='Imprime resultados del Evento' class='cambio' name='imprime_resultados_btn' value='Imprimir' /> ";
			echo "<input type='submit' id='imprime-medallistas' title='Medallistas del Evento' class='cambio' name='imprime_medallistas_btn' value='Medallistas' /> <br>";
		}
		 ?>
		<table>
			<tr>
				<td width="50%">
		<div id="div3">
		<table  class="tablas" >
			<tr align="center"> 
				<th>Lugar</th>	  
				<th>Clavadista (edad)</th>
				<th>Equipo</th>
				<th>Total</th>
				<th>Marca</th>
				<th>Diferencia</th>
			</tr> 
 			<?php 
				if ($categoria!=="AB") {
 					$orden_anterior="";
	 				while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
						$cod_planilla=$reg["cod_planilla"];
						$categoria=$reg["categoria"];
						$sexo=$reg["sexo"];
						$nacimiento=isset($reg["nacimiento"])?$reg["nacimiento"]:NULL;
						if ($nacimiento)
							$edad=edad_deportiva($nacimiento);
						else
							$edad=NULL;
						$extraof=$reg["extraof"];
						$lugar=$reg["lugar"];
						$clavadista=$reg["nom_cla"];
						$nom_cla=primera_mayuscula_palabra($reg["nom_cla"]);
						if ($edad)
							$nom_cla.=" ($edad)";
						$nom_cla2=primera_mayuscula_palabra($reg["nom_cla2"]);
						$nom_cla3=primera_mayuscula_palabra($reg["nom_cla3"]);
						$nom_cla4=primera_mayuscula_palabra($reg["nom_cla4"]);
						if (strlen($nom_cla2)>0){
							$nom_cla .= " / ".$nom_cla2;
						} 
						if (strlen($nom_cla3)>0){
							$nom_cla .= " / ".$nom_cla3;
						} 
						if (strlen($nom_cla4)>0){
							$nom_cla .= " / ".$nom_cla4;
						} 
//						$nom_cla=strtolower($nom_cla);
						$equipo=strtoupper($reg["nom_equipo"]);
						$bandera_eq=isset($reg["bandera"])?strtolower($reg["bandera"]):NULL;
						$total=isset($reg["total"])?$reg["total"]:NULL;
						if ($lugar==1 and $extraof!="*")
							$puntaje1=$total;
						$dif=$puntaje1-$total;
						$orden_categorias=$reg["orden_categorias"];
						$calificado=$reg["calificado"];
		 				if ($orden_categorias!=$orden_anterior){
							$sexo=$reg["sexo"];
							$nom_cat=primera_mayuscula_frase($reg["nom_cat"]);
							$extraof=$reg['extraof']=='*'?"ExtraOficial":"";
	 						echo "<tr align='center'>";
	 						if ($sexo=="F") {
	 							$nom_sexo='Femenino';
								$marca=isset($reg["marca_damas"])?$reg["marca_damas"]:NULL;
								$grado=isset($reg["grado_damas"])?$reg["grado_damas"]:NULL;
	 						}
		 					else {
								$marca=isset($reg["marca_varones"])?$reg["marca_varones"]:NULL;
								$grado=isset($reg["grado_varones"])?$reg["grado_varones"]:NULL;
		 						$nom_sexo='Masculino';
		 					}
	 						echo "<td align='center' colspan='6' class='rotulo'>$nom_cat $nom_sexo $extraof</td>";
	 						echo "</tr>";	
	 						$orden_anterior=$orden_categorias;
	 					}
						echo "<tr align='center'";
						if ($calificado==1) 
							echo " class='linea1'";

						if ($extraof=="*")
							echo "><td>e.o.</td>";
						else
							echo "><td>".$lugar."</td>";
						echo "<td>".$nom_cla."</td>";
						echo "<td>".$equipo;
						if ($bandera_eq){
							echo "&nbsp;<img class='textwrap' src='../img/banderas/".$bandera_eq.".png' width='15%'/>";

						}

						echo "</td>";
						$ventana="calificaciones.php";
						$_SESSION["cod_planilla"]=$cod_planilla;
						echo "<td align='right'><a class='enlaces_tab' href=JavaScript:abrir_ventana(";
						echo "'calificaciones.php','".$cod_planilla."'";
						echo ")>".number_format($total,$dec)."</a></td>";

						if ($marca) {
							if ($total>=$marca) 
								echo "<td>Realizó</td>";
							else
								echo "<td>$marca</td>";
						}
						else
							echo "<td>&nbsp;</td>";
						echo "<td align='right'>".number_format($dif,$dec)."</td>";
						echo "</tr>";
	 				}
	 			}
//              Abierta

				if ($categoria=="AB" or $part_abierta=="*" or $finalizo) {
	 				$orden_anterior="";
		 			while ($reg=$ejecutar_consulta_abierta->fetch_assoc()){
						$cod_planilla=$reg["cod_planilla"];
						$sexo=$reg["sexo"];
						$nacimiento=isset($reg["nacimiento"])?$reg["nacimiento"]:NULL;
						if ($nacimiento)
							$edad=edad_deportiva($nacimiento);
						else
							$edad=NULL;
						$extraof=$reg["extraof_abierto"];
						$lugar=$reg["lugar_abierta"];
						$clavadista=$reg["nom_cla"];
						$nom_cla=primera_mayuscula_palabra($reg["nom_cla"]);
						if ($edad)
							$nom_cla.=" ($edad a&ntilde;os)";
						$nom_cla2=primera_mayuscula_palabra($reg["nom_cla2"]);
						if (strlen($nom_cla2)>0){
							$nom_cla .= " / ".$nom_cla2;
						} 
						$equipo=strtoupper($reg["nom_equipo"]);
						$bandera_eq=isset($row["bandera"])?strtolower($row["bandera"]):NULL;
						$total=number_format($reg["total_abierta"],$dec);
						$sexo=$reg["sexo"];
						$calificado=$reg["calificado"];

						$marcas=marcas($cod_competencia,'AB',$modalidad);

						if ($marcas) {
							$marca_damas=isset($marcas["marca_damas"])?$marcas["marca_damas"]:NULL;
							$grado_damas=isset($marcas["grado_damas"])?$marcas["grado_damas"]:NULL;
							$marca_varones=isset($marcas["marca_varones"])?$marcas["marca_varones"]:NULL;
							$grado_varones=isset($marcas["grado_varones"])?$marcas["grado_varones"]:NULL;
						}
						else{
							$marca_damas=NULL;
							$grado_damas=NULL;
							$marca_varones=NULL;
							$grado_varones=NULL;

						}
 						if ($sexo=="F") {
 							$nom_sexo='Femenino';
							$marca=$marca_damas;
							$grado=$grado_damas;
 						}
	 					else {
							$marca=$marca_varones;
							$grado=$grado_varones;
	 						$nom_sexo='Masculino';
	 					}

		 				if ($sexo.$extraof!=$orden_anterior){
							$extraof=$reg['extraof_abierto']=='*'?"ExtraOficial":"";
	 						echo "<tr align='center'> 
	 							";
		 					echo "<td align='center' colspan='6' class='rotulo'> Categoría Abierta $nom_sexo $extraof</td>";
		 					echo "</tr>";	
	 						$orden_anterior=$sexo.$extraof;
	 					}
						echo "<tr align='center'";
						if ($calificado==1) 
							echo " class='linea1'";

						if ($lugar==1)
							$puntaje1=$total;
						$dif=number_format($puntaje1-$total,$dec);
						if ($extraof=="*")
							echo "><td>e.o.</td>";
						else
							echo "><td>".$lugar."</td>";

						echo "<td>".$nom_cla."</td>";
						echo "<td>".$equipo;
						if ($bandera_eq) 
							echo "&nbsp;<img class='textwrap' src='../img/banderas/".$bandera_eq.".png' width='15%'/>";

						echo "</td>";
						$ventana="calificaciones.php";
						$_SESSION["cod_planilla"]=$cod_planilla;
						echo "<td align='right' ><a class='enlaces_tab' href=JavaScript:abrir_ventana(";
						echo "'calificaciones.php','".$cod_planilla."'";
						echo ")>$total</a></td>";
						if ($marca) {
							if ($total>=$marca) 
								echo "<td>Realizó</td>";
							else
								echo "<td>$marca</td>";
						}
						else
							echo "<td>&nbsp;</td>";

						echo "<td align'right>".$dif."</td>";
						echo "</tr>";
	 				}
	 			}
 				$conexion->close();
			?>
 		</table> 
 		</div>
		</td>
		<td></td>
			</tr>
		</table>
		<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>"> 	
		<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>"> 	
		<input type="hidden" name="evento_hdn" value="<?php echo $evento; ?>"> 	
		<input type="hidden" name="numero_evento_hdn" value="<?php echo $numero_evento; ?>"> 	
		<input type="hidden" name="encabezado_hdn" value="<?php echo $encabezado; ?>"> 	
		<input type="hidden" name="encabezado1_hdn" value="<?php echo $encabezado1; ?>"> 	
		<input type="hidden" name="fechahora_hdn" value="<?php echo $fechahora; ?>"> 	
		<input type="hidden" name="modalidad_hdn" value="<?php echo $modalidad; ?>"> 	
		<input type="hidden" name="cat_hdn" value="<?php echo $cat; ?>"> 	
		<input type="hidden" name="sexos_hdn" value="<?php echo $sexos; ?>">
		<input type="hidden" name="tipo_hdn" value="<?php echo $tipo; ?>">
		<input type="hidden" name="descripcion_hdn" value="<?php echo $descripcion; ?>">
		<input type="hidden" name="fecha_hdn" value="<?php echo $fecha; ?>">
		<input type="hidden" name="hora_hdn" value="<?php echo $hora; ?>">
		<input type="hidden" name="origen_hdn" value="<?php echo $origen; ?>">
	</fieldset>
	</form>		
	</section>
<!-- 		<aside>
			&lt;aside&gt
		</aside>
 --></section>
	<footer>
      <p>Información de Contacto: <a href="mailto:soporte@softneosas.com">
  soporte@softneosas.com</a></p>
	</footer>
</body>
</html>

