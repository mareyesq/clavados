<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
function Header()
{
	global $competencia, $subtitulo, $subtitulo1, $logo, $logo2, $organizador;
	include('impr-titulo-competencia.php');
	$this->ln(10);
	$this->Cell(0,10,'COMPETIDORES POR EVENTO',0,0,'C');
	$this->ln(10);
}

function Footer()
{
	$this->SetY(-23);
	$this->SetFont('Arial','I',6);
	$this->Cell(0,4,utf8_decode('* Saltos: código+posición-altura (GD).  Obl.=suma del grado de dificultad de los saltos obligatorios.  Tot.=suma del grado de dificultad de todos los saltos.'),0,1,'L');
	$this->SetFont('Arial','I',7);
	$this->Cell(100,5,'www.softneosas.com/clavados    info@softneosas.com',0,0,'L');
	$this->Cell(20,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}

function encabezado_evento($numero_evento, $hora, $descripcion)
{
	$this->ln(3);
	$this->SetFont('','B',10);
	$titulo_linea1 = 'Evento '.$numero_evento.'   '.$hora;
	$this->Cell(0,6,utf8_decode($titulo_linea1),0,1,'L');
	$this->SetFont('','B',8);
	$this->Cell(0,5,utf8_decode($descripcion),0,1,'L');
	$this->SetFont('','B',7);
	$this->Cell(12,5,'OS',0,0,'C');
	$this->Cell(75,5,'Competidor',0,0,'L');
	$this->Cell(58,5,'Cat / Sexo',0,0,'L');
	$this->Cell(0,5,'Equipo',0,1,'L');
}

function detalle_eventos($data)
{
	$evento_actual = '';
	$total_evento = 0;
	$total_general = 0;

	foreach ($data as $row) {
		$evento = $row['numero_evento'];
		if ($evento !== $evento_actual) {
			if ($evento_actual !== '') {
				$this->SetFont('','I',8);
				$this->Cell(0,5,'Total competidores en el evento: '.number_format($total_evento),0,1,'R');
				$this->ln(2);
			}
			$evento_actual = $evento;
			$total_evento = 0;
			$this->encabezado_evento($row['numero_evento'], $row['hora'], $row['descripcion']);
		}

		$es_placeholder = !isset($row['competidor']) || !$row['competidor'];
		if ($es_placeholder)
			$this->SetFont('','I',8);
		else
			$this->SetFont('','',8);

		$orden_salida = '';
		if (isset($row['tiene_evento']) && (int)$row['tiene_evento'] == 1 && isset($row['orden_salida']) && $row['orden_salida'] !== NULL && $row['orden_salida'] !== '')
			$orden_salida = $row['orden_salida'];

		$categoria_txt = !$es_placeholder && isset($row['nom_categoria']) && $row['nom_categoria'] ? $row['nom_categoria'] : (isset($row['categoria']) ? $row['categoria'] : '');
		$sexo_txt = !$es_placeholder ? sexo_desc_ev(isset($row['sexo']) ? $row['sexo'] : '') : '';

		$nom_mostrar = acortar_nombres_sincronizado($row['nom_competidor']);
		$this->SetFont('','',7);
		$this->Cell(12,5,utf8_decode($orden_salida),0,0,'C');
		$this->SetFont('','B',7);
		$this->Cell(75,5,utf8_decode(primera_mayuscula_palabra($nom_mostrar)),0,0,'L');
		$this->SetFont('','',7);
		$this->Cell(58,5,utf8_decode(!$es_placeholder ? $categoria_txt.' / '.$sexo_txt : ''),0,0,'L');
		$this->Cell(0,5,utf8_decode($row['equipo']),0,1,'L');

		if (!$es_placeholder && isset($row['saltos_linea']) && $row['saltos_linea']) {
			$grado_obl_txt   = isset($row['grado_obl'])   && $row['grado_obl']   !== NULL ? number_format((float)$row['grado_obl'],   1) : '0.0';
			$grado_total_txt = isset($row['grado_total']) && $row['grado_total'] !== NULL ? number_format((float)$row['grado_total'], 1) : '0.0';
			$this->SetFont('','',6);
			$this->Cell(12,4,'',0,0);
			$sumas_texto = '  Grado Dif. ';
			if ((float)$row['grado_obl'] > 0)
				$sumas_texto .= 'Obl.='.$grado_obl_txt.'  ';
			$sumas_texto .= 'Tot.='.$grado_total_txt;
			$this->SetFont('','I',6);
			$this->Cell(0,4,utf8_decode($row['saltos_linea'].$sumas_texto),0,1,'L');
			$this->SetFont('','',7);
		}

		if (!$es_placeholder) {
			$total_evento++;
			$total_general++;
		}
	}

	if ($evento_actual !== '') {
		$this->SetFont('','I',8);
		$this->Cell(0,5,'Total competidores en el evento: '.number_format($total_evento),0,1,'R');
		$this->ln(2);
	}

	$this->SetFont('','B',9);
	$this->Cell(0,6,'Total general de participaciones en eventos: '.number_format($total_general),0,1,'R');
	if ($total_general == 0) {
		$this->SetFont('','',9);
		$this->Cell(0,6,'No hay competidores inscritos en eventos para esta competencia.',0,1,'L');
	}
}
}

function sexo_desc_ev($sexo)
{
	if ($sexo == 'F') return 'Damas';
	if ($sexo == 'M') return 'Varones';
	if ($sexo == 'X') return 'Mixto';
	return '-';
}

function fase_desc_ev($tipo)
{
	if ($tipo == 'P') return 'Preliminar';
	if ($tipo == 'S') return 'Semifinal';
	return 'Final';
}

function fecha_texto_es($fechahora)
{
	$timestamp = strtotime($fechahora);
	if (!$timestamp)
		return '-';

	$dias = array('domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado');
	$meses = array(1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

	$dia_semana = $dias[(int)date('w', $timestamp)];
	$dia = (int)date('j', $timestamp);
	$mes = (int)date('n', $timestamp);
	$ano = date('Y', $timestamp);
	$hora = date('g:i a', $timestamp);

	return $dia_semana.' '.$dia.' '.$meses[$mes].' '.$ano.' '.$hora;
}

function formato_altura_salto($altura)
{
	if ($altura === NULL || $altura === '')
		return '-';
	if (is_numeric($altura))
		return number_format($altura, 1);
	return $altura;
}

function cmp_competidor_evento($a, $b)
{
	$a_asignado = isset($a['tiene_evento']) ? (int)$a['tiene_evento'] : 0;
	$b_asignado = isset($b['tiene_evento']) ? (int)$b['tiene_evento'] : 0;

	if ($a_asignado != $b_asignado)
		return ($a_asignado > $b_asignado) ? -1 : 1;

	if ($a_asignado == 1) {
		$a_orden = isset($a['orden_salida']) && $a['orden_salida'] !== NULL && $a['orden_salida'] !== '' ? (int)$a['orden_salida'] : 999999;
		$b_orden = isset($b['orden_salida']) && $b['orden_salida'] !== NULL && $b['orden_salida'] !== '' ? (int)$b['orden_salida'] : 999999;
		if ($a_orden != $b_orden)
			return ($a_orden < $b_orden) ? -1 : 1;
	} else {
		$cmp = strcasecmp(isset($a['categoria']) ? $a['categoria'] : '', isset($b['categoria']) ? $b['categoria'] : '');
		if ($cmp != 0)
			return $cmp;

		$cmp = strcasecmp(isset($a['sexo']) ? $a['sexo'] : '', isset($b['sexo']) ? $b['sexo'] : '');
		if ($cmp != 0)
			return $cmp;

		$cmp = strcasecmp(isset($a['equipo']) ? $a['equipo'] : '', isset($b['equipo']) ? $b['equipo'] : '');
		if ($cmp != 0)
			return $cmp;
	}

	return strcasecmp(isset($a['nom_competidor']) ? $a['nom_competidor'] : '', isset($b['nom_competidor']) ? $b['nom_competidor'] : '');
}

function acortar_nombres_sincronizado($nombres_completos)
{
	// Si contiene " - ", es prueba de sincronizado: toma solo nombre + primer apellido de cada uno
	if (strpos($nombres_completos, ' - ') !== false) {
		$clavadistas = array_map('trim', explode(' - ', $nombres_completos));
		$acortados = array();
		foreach ($clavadistas as $nombre) {
			$partes = preg_split('/\s+/', trim($nombre));
			if (count($partes) >= 2) {
				$acortados[] = $partes[0].' '.$partes[1];
			} elseif (count($partes) == 1) {
				$acortados[] = $partes[0];
			} else {
				$acortados[] = $nombre;
			}
		}
		return implode(' - ', $acortados);
	}
	// Si es individual, retorna el nombre como está
	return $nombres_completos;
}

// Paso 1: cargar todos los eventos de la competencia en orden
$eventos_info = array();

$consulta_eventos = "SELECT e.numero_evento, e.evento, e.fechahora, e.modalidad, m.modalidad AS nom_modalidad, e.sexos, e.categorias, e.tipo, e.tiempo_estimado_inicial
	FROM competenciaev AS e
	LEFT JOIN modalidades AS m ON m.cod_modalidad = e.modalidad
	WHERE e.competencia=$cod_competencia
	ORDER BY e.fechahora, e.numero_evento";

$ejecutar_eventos = $conexion->query(utf8_decode($consulta_eventos));
while ($ev = $ejecutar_eventos->fetch_assoc()) {
	$numero_evento = $ev['numero_evento'] ? $ev['numero_evento'] : $ev['evento'];
	if (!$numero_evento)
		continue;

	$fechahora = $ev['fechahora'];
	$hora = $fechahora ? fecha_texto_es($fechahora) : '-';

	$nom_modalidad  = $ev['nom_modalidad'] ? $ev['nom_modalidad'] : '-';
	$sexos_evento   = $ev['sexos']         ? $ev['sexos']         : '-';
	$categorias_evento = $ev['categorias'] ? $ev['categorias']    : '-';
	$tipo = $ev['tipo'];

	$descripcion = describe_evento($nom_modalidad, $sexos_evento, $categorias_evento, $tipo);
	if (!$descripcion)
		$descripcion = $nom_modalidad.' - '.$sexos_evento.' - '.$categorias_evento.' - '.fase_desc_ev($tipo);

	$eventos_info[$numero_evento] = array(
		'numero_evento_sort'      => $numero_evento,
		'fechahora_sort'          => $fechahora,
		'hora'                   => $hora,
		'descripcion'            => $descripcion,
		'tiempo_estimado_inicial' => isset($ev['tiempo_estimado_inicial']) ? $ev['tiempo_estimado_inicial'] : NULL,
	);
}

// Forzar orden por fecha y hora del evento
if (!empty($eventos_info)) {
	uasort($eventos_info, function($a, $b) {
		$ta = isset($a['fechahora_sort']) && $a['fechahora_sort'] ? strtotime($a['fechahora_sort']) : 0;
		$tb = isset($b['fechahora_sort']) && $b['fechahora_sort'] ? strtotime($b['fechahora_sort']) : 0;
		if ($ta == $tb) {
			$ea = isset($a['numero_evento_sort']) ? (int)$a['numero_evento_sort'] : 0;
			$eb = isset($b['numero_evento_sort']) ? (int)$b['numero_evento_sort'] : 0;
			if ($ea == $eb)
				return 0;
			return ($ea < $eb) ? -1 : 1;
		}
		return ($ta < $tb) ? -1 : 1;
	});
}

// Paso 2: obtener competidores usando el mismo UNION ALL que impr-eventos-competidor.php
$consulta_comp = "
	SELECT p.clavadista AS competidor, u.nombre AS nom_competidor,
		p.cod_planilla,
		q.equipo, p.evento AS evento_planilla, e.numero_evento, e.fechahora,
		p.categoria, c.categoria AS nom_categoria, p.sexo, p.orden_salida,
		IF(p.evento IS NULL, 0, 1) AS tiene_evento
	FROM planillas AS p
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista
	LEFT JOIN competenciaev AS e ON e.competencia = p.competencia
		AND (
			e.numero_evento = p.evento
			OR (
				p.evento IS NULL
				AND e.modalidad = p.modalidad
				AND e.sexos LIKE CONCAT('%', p.sexo, '%')
				AND e.categorias LIKE CONCAT('%', p.categoria, '%')
			)
		)
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista IS NOT NULL

	UNION ALL

	SELECT p.clavadista2 AS competidor, u.nombre AS nom_competidor,
		p.cod_planilla,
		q.equipo, p.evento AS evento_planilla, e.numero_evento, e.fechahora,
		p.categoria, c.categoria AS nom_categoria, p.sexo, p.orden_salida,
		IF(p.evento IS NULL, 0, 1) AS tiene_evento
	FROM planillas AS p
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista2
	LEFT JOIN competenciaev AS e ON e.competencia = p.competencia
		AND (
			e.numero_evento = p.evento
			OR (
				p.evento IS NULL
				AND e.modalidad = p.modalidad
				AND e.sexos LIKE CONCAT('%', p.sexo, '%')
				AND e.categorias LIKE CONCAT('%', p.categoria, '%')
			)
		)
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista2 IS NOT NULL

	UNION ALL

	SELECT p.clavadista3 AS competidor, u.nombre AS nom_competidor,
		p.cod_planilla,
		q.equipo, p.evento AS evento_planilla, e.numero_evento, e.fechahora,
		p.categoria, c.categoria AS nom_categoria, p.sexo, p.orden_salida,
		IF(p.evento IS NULL, 0, 1) AS tiene_evento
	FROM planillas AS p
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista3
	LEFT JOIN competenciaev AS e ON e.competencia = p.competencia
		AND (
			e.numero_evento = p.evento
			OR (
				p.evento IS NULL
				AND e.modalidad = p.modalidad
				AND e.sexos LIKE CONCAT('%', p.sexo, '%')
				AND e.categorias LIKE CONCAT('%', p.categoria, '%')
			)
		)
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista3 IS NOT NULL

	UNION ALL

	SELECT p.clavadista4 AS competidor, u.nombre AS nom_competidor,
		p.cod_planilla,
		q.equipo, p.evento AS evento_planilla, e.numero_evento, e.fechahora,
		p.categoria, c.categoria AS nom_categoria, p.sexo, p.orden_salida,
		IF(p.evento IS NULL, 0, 1) AS tiene_evento
	FROM planillas AS p
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista4
	LEFT JOIN competenciaev AS e ON e.competencia = p.competencia
		AND (
			e.numero_evento = p.evento
			OR (
				p.evento IS NULL
				AND e.modalidad = p.modalidad
				AND e.sexos LIKE CONCAT('%', p.sexo, '%')
				AND e.categorias LIKE CONCAT('%', p.categoria, '%')
			)
		)
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista4 IS NOT NULL

	ORDER BY fechahora, numero_evento, nom_competidor";

$ejecutar_comp = $conexion->query(utf8_decode($consulta_comp));

$competidores_por_evento = array();
$planillas_raw = array();

while ($row = $ejecutar_comp->fetch_assoc()) {
	$numero_evento = $row['numero_evento'] ? $row['numero_evento'] : $row['evento_planilla'];
	if (!$numero_evento)
		continue;

	$cod_planilla = isset($row['cod_planilla']) ? $row['cod_planilla'] : NULL;
	$cod = $row['competidor'];

	if (!isset($planillas_raw[$cod_planilla]))
		$planillas_raw[$cod_planilla] = array(
			'numero_evento' => $numero_evento,
			'fechahora' => $row['fechahora'],
			'equipo' => $row['equipo'],
			'categoria' => $row['categoria'],
			'nom_categoria' => $row['nom_categoria'],
			'sexo' => $row['sexo'],
			'orden_salida' => $row['orden_salida'],
			'tiene_evento' => $row['tiene_evento'],
			'nombres' => array()
		);

	if ($row['nom_competidor'])
		$planillas_raw[$cod_planilla]['nombres'][] = $row['nom_competidor'];
}

// Paso 2.1: concatenar los nombres de clavadistas en PHP, una fila por planilla
$planillas_usadas = array();
foreach ($planillas_raw as $cod_planilla => $data) {
	if (!$cod_planilla)
		continue;

	$planillas_usadas[$cod_planilla] = 1;
	$numero_evento = $data['numero_evento'];

	if (!isset($competidores_por_evento[$numero_evento]))
		$competidores_por_evento[$numero_evento] = array();

	$nom_competidor = implode(' - ', $data['nombres']);
	if (!$nom_competidor)
		$nom_competidor = 'Competidor';

	$competidores_por_evento[$numero_evento][] = array(
		'competidor'     => $cod_planilla,
		'cod_planilla'   => $cod_planilla,
		'nom_competidor' => $nom_competidor,
		'equipo'         => $data['equipo'] ? $data['equipo'] : '-',
		'categoria'      => $data['categoria'] ? $data['categoria'] : '',
		'nom_categoria'  => $data['nom_categoria'] ? $data['nom_categoria'] : '',
		'sexo'           => $data['sexo'] ? $data['sexo'] : '',
		'orden_salida'   => $data['orden_salida'],
		'tiene_evento'   => $data['tiene_evento'],
	);
}

$saltos_por_planilla = array();
$sumas_por_planilla  = array();

if (!empty($planillas_usadas)) {
	$lista_planillas = implode(',', array_keys($planillas_usadas));

	// Cuántos saltos son obligatorios para cada planilla, según la tabla series
	$saltos_obl_por_planilla = array();
	$consulta_obl = "SELECT p.cod_planilla, COALESCE(s.saltos_obl, 0) AS saltos_obl
		FROM planillas AS p
		LEFT JOIN series AS s ON s.categoria = p.categoria AND s.sexo = p.sexo AND s.modalidad = p.modalidad
		WHERE p.cod_planilla IN ($lista_planillas)";
	$ejecutar_obl = $conexion->query(utf8_decode($consulta_obl));
	while ($ro = $ejecutar_obl->fetch_assoc())
		$saltos_obl_por_planilla[$ro['cod_planilla']] = (int)$ro['saltos_obl'];

	$consulta_saltos = "SELECT planilla, ronda, salto, posicion, altura, grado_dif
		FROM planillad
		WHERE planilla IN ($lista_planillas)
		ORDER BY planilla, ronda";
	$ejecutar_saltos = $conexion->query(utf8_decode($consulta_saltos));
	while ($sal = $ejecutar_saltos->fetch_assoc()) {
		$pla   = $sal['planilla'];
		$codigo = isset($sal['salto'])    ? $sal['salto']    : '';
		$pos   = isset($sal['posicion'])  ? $sal['posicion'] : '';
		$alt   = formato_altura_salto(isset($sal['altura']) ? $sal['altura'] : NULL);
		$grado = isset($sal['grado_dif']) ? $sal['grado_dif'] : NULL;
		$ronda = isset($sal['ronda'])     ? (int)$sal['ronda'] : 0;
		if (!$codigo)
			continue;
		$grado_float = ($grado !== NULL && is_numeric($grado)) ? (float)$grado : 0.0;
		$grado_txt   = $grado_float > 0 ? number_format($grado_float, 1) : '-';
		$saltos_por_planilla[$pla][] = $codigo.$pos.'-'.$alt.' ('.$grado_txt.')';

		if (!isset($sumas_por_planilla[$pla]))
			$sumas_por_planilla[$pla] = array('obl' => 0.0, 'total' => 0.0);
		$sumas_por_planilla[$pla]['total'] += $grado_float;
		$num_obl = isset($saltos_obl_por_planilla[$pla]) ? $saltos_obl_por_planilla[$pla] : 0;
		if ($num_obl > 0 && $ronda <= $num_obl)
			$sumas_por_planilla[$pla]['obl'] += $grado_float;
	}
}

foreach ($competidores_por_evento as $ev => $lista) {
	foreach ($lista as $k => $c) {
		$pla = isset($c['cod_planilla']) ? $c['cod_planilla'] : NULL;
		$saltos_linea = '';
		if ($pla && isset($saltos_por_planilla[$pla]))
			$saltos_linea = implode(', ', $saltos_por_planilla[$pla]);
		$lista[$k]['saltos_linea'] = $saltos_linea;
		$lista[$k]['grado_obl']   = ($pla && isset($sumas_por_planilla[$pla])) ? $sumas_por_planilla[$pla]['obl']   : 0.0;
		$lista[$k]['grado_total'] = ($pla && isset($sumas_por_planilla[$pla])) ? $sumas_por_planilla[$pla]['total'] : 0.0;
	}
	$competidores_por_evento[$ev] = $lista;
}

foreach ($competidores_por_evento as $ev => $lista) {
	usort($lista, 'cmp_competidor_evento');
	$competidores_por_evento[$ev] = $lista;
}

// Paso 2.1: agregar tiempo estimado al texto descriptivo de cada evento
foreach ($eventos_info as $numero_evento => $info) {
	$n = 0;
	if (!empty($competidores_por_evento[$numero_evento])) {
		foreach ($competidores_por_evento[$numero_evento] as $c) {
			$pla = isset($c['cod_planilla']) ? $c['cod_planilla'] : NULL;
			if ($pla && isset($saltos_por_planilla[$pla]) && !empty($saltos_por_planilla[$pla]))
				$n += count($saltos_por_planilla[$pla]);
			else
				$n += 1;
		}
	}
	$tiempo_estimado = tiempo_estimado($n);

	$desc = isset($info['descripcion']) ? $info['descripcion'] : '';
	$eventos_info[$numero_evento]['descripcion'] = trim($desc).' Tiempo Estimado: '.$tiempo_estimado.' minutos';
}

// Paso 3: construir $data en el orden de los eventos
$data = array();
$consecutivo_evento = 1;
foreach ($eventos_info as $numero_evento => $info) {
	$numero_evento_mostrar = $consecutivo_evento;
	$consecutivo_evento++;
	if (!empty($competidores_por_evento[$numero_evento])) {
		foreach ($competidores_por_evento[$numero_evento] as $c) {
			$data[] = array(
				'competidor'     => $c['competidor'],
				'nom_competidor' => $c['nom_competidor'],
				'categoria'      => $c['categoria'],
				'nom_categoria'  => $c['nom_categoria'],
				'sexo'           => $c['sexo'],
				'saltos_linea'   => $c['saltos_linea'],
				'grado_obl'      => $c['grado_obl'],
				'grado_total'    => $c['grado_total'],
				'orden_salida'   => $c['orden_salida'],
				'tiene_evento'   => $c['tiene_evento'],
				'equipo'         => $c['equipo'],
				'numero_evento'  => $numero_evento_mostrar,
				'hora'           => $info['hora'],
				'descripcion'    => $info['descripcion'],
			);
		}
	} else {
		$data[] = array(
			'competidor'     => 0,
			'nom_competidor' => 'Sin competidores asignados',
			'categoria'      => '',
			'nom_categoria'  => '',
			'sexo'           => '',
			'saltos_linea'   => '',
			'grado_obl'      => 0,
			'grado_total'    => 0,
			'orden_salida'   => NULL,
			'tiene_evento'   => 0,
			'equipo'         => '-',
			'numero_evento'  => $numero_evento_mostrar,
			'hora'           => $info['hora'],
			'descripcion'    => $info['descripcion'],
		);
	}
}

$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->detalle_eventos($data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
?>
