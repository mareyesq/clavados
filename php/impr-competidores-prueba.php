<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
function Header()
{
	global $competencia, $subtitulo, $subtitulo1, $logo, $logo2, $organizador;
	include('impr-titulo-competencia.php');
	$this->ln(10);
	$this->Cell(0,10,'COMPETIDORES POR PRUEBA',0,0,'C');
	$this->ln(10);
}

function Footer()
{
	$this->SetY(-15);
	$this->SetFont('Arial','I',7);
	$this->Cell(100,8,'www.softneosas.com/saltos    info@softneosas.com',0,0,'L');
	$this->Cell(20,8,utf8_decode('Pagina ').$this->PageNo().'/{nb}',0,0,'C');
}

function describe_sexo($sexo)
{
	if ($sexo == 'F')
		return 'Damas';
	if ($sexo == 'M')
		return 'Varones';
	if ($sexo == 'X')
		return 'Mixto';
	return '-';
}

function encabezado_prueba($modalidad, $categoria, $sexo)
{
	$this->ln(3);
	$this->SetFont('','B',10);
	$titulo = $modalidad.' - '.$categoria.' - '.$this->describe_sexo($sexo);
	$this->Cell(0,6,utf8_decode($titulo),0,1,'L');
	$this->SetFont('','B',8);
	$this->Cell(95,6,'Competidor',0,0,'L');
	$this->Cell(0,6,'Equipo',0,1,'L');
	$this->SetFont('','',8);
}

function detalle_pruebas($data)
{
	$prueba_actual = '';
	$total_prueba = 0;
	$total_general = 0;

	foreach ($data as $row) {
		$prueba = $row['modalidad'].'|'.$row['categoria'].'|'.$row['sexo'];
		if ($prueba !== $prueba_actual) {
			if ($prueba_actual !== '') {
				$this->SetFont('','I',8);
				$this->Cell(0,5,'Total competidores en la prueba: '.number_format($total_prueba),0,1,'R');
				$this->ln(2);
			}
			$prueba_actual = $prueba;
			$total_prueba = 0;
			$this->encabezado_prueba($row['modalidad'], $row['categoria'], $row['sexo']);
		}

		$this->Cell(95,6,utf8_decode(primera_mayuscula_palabra($row['competidor'])),0,0,'L');
		$this->Cell(0,6,utf8_decode($row['equipo']),0,1,'L');
		$total_prueba++;
		$total_general++;
	}

	if ($prueba_actual !== '') {
		$this->SetFont('','I',8);
		$this->Cell(0,5,'Total competidores en la prueba: '.number_format($total_prueba),0,1,'R');
		$this->ln(2);
	}

	$this->SetFont('','B',9);
	$this->Cell(0,6,'Total general de participaciones por prueba: '.number_format($total_general),0,1,'R');
	if ($total_general == 0) {
		$this->SetFont('','',9);
		$this->Cell(0,6,'No hay competidores inscritos en pruebas para esta competencia.',0,1,'L');
	}
}
}

$data = array();
$lineas = array();

$consulta = "
	SELECT p.modalidad, p.categoria, p.sexo,
		m.modalidad AS nom_modalidad,
		c.categoria AS nom_categoria,
		u.nombre AS nom_competidor,
		q.equipo AS nom_equipo,
		p.clavadista AS cod_competidor
	FROM planillas AS p
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista IS NOT NULL

	UNION ALL

	SELECT p.modalidad, p.categoria, p.sexo,
		m.modalidad AS nom_modalidad,
		c.categoria AS nom_categoria,
		u.nombre AS nom_competidor,
		q.equipo AS nom_equipo,
		p.clavadista2 AS cod_competidor
	FROM planillas AS p
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista2
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista2 IS NOT NULL

	UNION ALL

	SELECT p.modalidad, p.categoria, p.sexo,
		m.modalidad AS nom_modalidad,
		c.categoria AS nom_categoria,
		u.nombre AS nom_competidor,
		q.equipo AS nom_equipo,
		p.clavadista3 AS cod_competidor
	FROM planillas AS p
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista3
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista3 IS NOT NULL

	UNION ALL

	SELECT p.modalidad, p.categoria, p.sexo,
		m.modalidad AS nom_modalidad,
		c.categoria AS nom_categoria,
		u.nombre AS nom_competidor,
		q.equipo AS nom_equipo,
		p.clavadista4 AS cod_competidor
	FROM planillas AS p
	LEFT JOIN modalidades AS m ON m.cod_modalidad = p.modalidad
	LEFT JOIN categorias AS c ON c.cod_categoria = p.categoria
	LEFT JOIN usuarios AS u ON u.cod_usuario = p.clavadista4
	LEFT JOIN competenciasq AS q ON q.competencia = p.competencia AND q.nombre_corto = p.equipo
	WHERE p.competencia = $cod_competencia
		AND p.usuario_retiro IS NULL
		AND p.clavadista4 IS NOT NULL

	ORDER BY nom_modalidad, nom_categoria, sexo, nom_equipo, nom_competidor
";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
while ($row = $ejecutar_consulta->fetch_assoc()) {
	$cod_competidor = isset($row['cod_competidor']) ? $row['cod_competidor'] : NULL;
	if (!$cod_competidor)
		continue;

	$modalidad = isset($row['nom_modalidad']) ? $row['nom_modalidad'] : '-';
	$categoria = isset($row['nom_categoria']) ? $row['nom_categoria'] : '-';
	$sexo = isset($row['sexo']) ? $row['sexo'] : '-';
	$competidor = isset($row['nom_competidor']) ? $row['nom_competidor'] : '';
	$equipo = isset($row['nom_equipo']) ? $row['nom_equipo'] : '-';

	$clave = $modalidad.'|'.$categoria.'|'.$sexo.'|'.$cod_competidor;
	if (isset($lineas[$clave]))
		continue;
	$lineas[$clave] = 1;

	$data[] = array(
		'modalidad' => $modalidad,
		'categoria' => $categoria,
		'sexo' => $sexo,
		'competidor' => $competidor,
		'equipo' => $equipo
	);
}

$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->detalle_pruebas($data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
?>