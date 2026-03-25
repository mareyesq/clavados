<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de pagina
function Header()
{
	global $competencia, $subtitulo, $subtitulo1, $logo, $logo2, $organizador;
	include('impr-titulo-competencia.php');
	$this->ln(10);
	$this->Cell(0,10,'EVENTOS POR COMPETIDOR',0,0,'C');
	$this->SetLineWidth(.1);
	$this->ln(10);
}

// Pie de pagina
function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Arial','I',7);
	$this->Cell(100,8,'www.softneosas.com/saltos    info@softneosas.com',0,0,'L');
	$this->Cell(20,8,utf8_decode('Pagina ').$this->PageNo().'/{nb}',0,0,'C');
}

function encabezado_competidor($nombre, $equipo)
{
	$this->SetFont('','B',10);
	$encabezado = primera_mayuscula_palabra($nombre).'  |  Equipo: '.$equipo;
	$this->Cell(0,6,utf8_decode($encabezado),0,1,'L');
	$this->SetFont('','B',8);
	$this->Cell(22,6,'Evento',0,0,'C');
	$this->Cell(30,6,'Hora',0,0,'C');
	$this->Cell(0,6,utf8_decode('Descripcion'),0,1,'L');
	$this->SetFont('','',8);
}

function detalle_competidor($data)
{
	$competidor_actual='';
	$eventos_competidor=0;
	$total_eventos=0;

	foreach ($data as $row) {
		$competidor = $row['competidor'];
		if ($competidor !== $competidor_actual) {
			if ($competidor_actual !== '') {
				$this->SetFont('','I',8);
				$this->Cell(0,5,'Total eventos del competidor: '.number_format($eventos_competidor),0,1,'R');
				$this->ln(2);
			}
			$competidor_actual = $competidor;
			$eventos_competidor = 0;
			$this->encabezado_competidor($row['nom_competidor'], $row['equipo']);
		}

		$descripcion = $row['modalidad'].' - '.$row['categoria'].' - '.$row['sexo'].' - '.$row['fase'];
		$this->Cell(22,6,$row['evento'],0,0,'C');
		$this->Cell(30,6,$row['hora'],0,0,'C');
		$this->Cell(0,6,utf8_decode($descripcion),0,1,'L');
		$eventos_competidor++;
		$total_eventos++;
	}

	if ($competidor_actual !== '') {
		$this->SetFont('','I',8);
		$this->Cell(0,5,'Total eventos del competidor: '.number_format($eventos_competidor),0,1,'R');
		$this->ln(2);
	}

	$this->SetFont('','B',9);
	$this->Cell(0,6,'Total general de participaciones en eventos: '.number_format($total_eventos),0,1,'R');
	if ($total_eventos == 0) {
		$this->SetFont('','',9);
		$this->Cell(0,6,'No hay eventos asignados a competidores para esta competencia.',0,1,'L');
	}
}
}

function sexo_descripcion($sexo)
{
	if ($sexo == 'F')
		return 'Damas';
	if ($sexo == 'M')
		return 'Varones';
	if ($sexo == 'X')
		return 'Mixto';
	return '-';
}

function fase_descripcion($fase)
{
	if ($fase == 'P')
		return 'Preliminar';
	if ($fase == 'S')
		return 'Semifinal';
	return 'Final';
}

$data = array();
$lineas = array();

$consulta = "
	SELECT
		p.clavadista AS competidor,
		u.nombre AS nom_competidor,
		q.equipo,
		p.evento AS evento_planilla,
		e.numero_evento,
		e.fechahora,
		m.modalidad,
		c.categoria,
		p.sexo,
		e.tipo
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
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista IS NOT NULL

	UNION ALL

	SELECT
		p.clavadista2 AS competidor,
		u.nombre AS nom_competidor,
		q.equipo,
		p.evento AS evento_planilla,
		e.numero_evento,
		e.fechahora,
		m.modalidad,
		c.categoria,
		p.sexo,
		e.tipo
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
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista2 IS NOT NULL

	UNION ALL

	SELECT
		p.clavadista3 AS competidor,
		u.nombre AS nom_competidor,
		q.equipo,
		p.evento AS evento_planilla,
		e.numero_evento,
		e.fechahora,
		m.modalidad,
		c.categoria,
		p.sexo,
		e.tipo
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
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista3 IS NOT NULL

	UNION ALL

	SELECT
		p.clavadista4 AS competidor,
		u.nombre AS nom_competidor,
		q.equipo,
		p.evento AS evento_planilla,
		e.numero_evento,
		e.fechahora,
		m.modalidad,
		c.categoria,
		p.sexo,
		e.tipo
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
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista4 IS NOT NULL

	ORDER BY nom_competidor, fechahora, numero_evento
";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
while ($row = $ejecutar_consulta->fetch_assoc()) {
	$competidor = isset($row['competidor']) ? $row['competidor'] : NULL;
	if (!$competidor)
		continue;

	$evento = isset($row['numero_evento']) ? $row['numero_evento'] : NULL;
	$evento_planilla = isset($row['evento_planilla']) ? $row['evento_planilla'] : NULL;
	if (!$evento)
		$evento = $evento_planilla;
	if (!$evento)
		$evento = 'Sin asignar';

	$fechahora = isset($row['fechahora']) ? $row['fechahora'] : NULL;
	$hora = '-';
	if ($fechahora) {
		$date = date_create($fechahora);
		if ($date)
			$hora = date_format($date, 'd-m-Y g:i a');
	}

	$sexo = sexo_descripcion(isset($row['sexo']) ? $row['sexo'] : NULL);
	$tipo_evento = isset($row['tipo']) ? $row['tipo'] : NULL;
	$fase = $tipo_evento ? fase_descripcion($tipo_evento) : 'Pendiente';
	$modalidad = isset($row['modalidad']) ? $row['modalidad'] : '-';
	$categoria = isset($row['categoria']) ? $row['categoria'] : '-';
	$equipo = isset($row['equipo']) ? $row['equipo'] : '-';
	$nom_competidor = isset($row['nom_competidor']) ? $row['nom_competidor'] : '';

	$clave_linea = $competidor.'-'.$evento.'-'.$modalidad.'-'.$categoria.'-'.$sexo.'-'.$fase;
	if (isset($lineas[$clave_linea]))
		continue;
	$lineas[$clave_linea] = 1;

	$data[] = array(
		'competidor' => $competidor,
		'nom_competidor' => $nom_competidor,
		'equipo' => $equipo,
		'evento' => $evento,
		'hora' => $hora,
		'modalidad' => $modalidad,
		'categoria' => $categoria,
		'sexo' => $sexo,
		'fase' => $fase
	);
}

$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->detalle_competidor($data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
?>