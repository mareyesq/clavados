<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $tot_saltos,$tiempo_estimado,$subtitulo,$subtitulo1,$logo, $logo2, $organizador;

	$horizontal=TRUE;
	include('impr-titulo-competencia.php');

	$descripcion=utf8_decode($descripcion);
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,'DETALLE DE SALTOS POR ORDEN DE SALIDA',0,0,'C');
	$this->ln(10);
//	$this->Cell(0,10,'Evento No. '.$evento.": Modalidad ".$descripcion,0,0,'L');
	$this->Cell(0,10,'Evento No. '.$evento.": ".$descripcion,0,0,'C');
	$this->Ln(5);
	$this->Cell(0,10,utf8_decode($fecha.' Total Saltos '.$tot_saltos.'  Duración Estimada: '.$tiempo_estimado.' min.'),0,0,'C');
}

// Pie de página
function Footer()
{
	// Posición: a 1,5 cm del final
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',7);
	// Número de página
	$this->Cell(100,8,"www.softneosas.com/clavados    info@softneosas.com",0,0,'L');
	$this->Cell(20,8,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}
	// Tabla coloreada
function FancyTable($header, $header1, $rondas, $data)
{
	global $modalidad, $mixto;
	// Colores, ancho de línea y fuente en negrita
//	$this->SetFillColor(255,0,0);
//	$this->SetTextColor(255);
//	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.1);
	$this->SetFont('','B',10);
	$this->ln(10);
	// Cabecera
	if ($rondas==11)
	 	$w = array(6,62,18,18,18,18,18,18,18,18,18,18,18);
	elseif ($modalidad=="E" or $mixto) {
	 	$w = array(6,80,40,40,40);
	}
	else
	 	$w = array(6,70,18,18,18,18,18,18,18,18,18,18);

	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],10,$header[$i],'LTR',0,'C',false);
	$this->Ln(4);
	for($i=0;$i<count($header1);$i++)
		$this->Cell($w[$i],10,$header1[$i],'LRB',0,'C',false);
	$this->ln(10);

	// Restauración de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',10);
	$fill = false;

	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LRB',0,'C',$fill);
		$this->Cell($w[1],6,$row[1],'LRB',0,'L',$fill);
		$this->Cell($w[2],6,$row[2],'LRB',0,'C',$fill);
		$this->Cell($w[3],6,$row[3],'LRB',0,'C',$fill);
		$this->Cell($w[4],6,$row[4],'LRB',0,'C',$fill);
		if ($modalidad!=="E" and !$mixto) {
			$this->Cell($w[5],6,$row[5],'LRB',0,'L',$fill);
			$this->Cell($w[6],6,$row[6],'LRB',0,'L',$fill);
			$this->Cell($w[7],6,$row[7],'LRB',0,'L',$fill);
			$this->Cell($w[8],6,$row[8],'LRB',0,'L',$fill);
			$this->Cell($w[9],6,$row[9],'LRB',0,'L',$fill);
			$this->Cell($w[10],6,$row[10],'LRB',0,'L',$fill);
			$this->Cell($w[11],6,$row[11],'LRB',0,'L',$fill);
			if ($rondas==11)
				$this->Cell($w[12],6,$row[12],'LRB',0,'L',$fill);
		}
		$this->Ln();
		$fill = !$fill;
	}
//	$this->Cell(0,2,"",'T',0,'R',false);
}
}

	// Datos

$data=array();
$consulta="SELECT DISTINCT p.cod_planilla, p.orden_salida, p.categoria, c.nombre as nom_cla, c2.nombre as nom_cla2, q.nombre_corto, q.equipo as nombre_equipo, m.mixto
	FROM planillas as p
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")
 		ORDER BY p.orden_salida";
$ejecutar_consulta = $conexion->query($consulta);
$tot_saltos=0;
$r=0;
while ($row=$ejecutar_consulta->fetch_assoc()){
	$clavadista = array();
	$cod_planilla=$row["cod_planilla"];
//	$clavadista[0]=$row["orden_salida"];
	$nom_cla=utf8_decode($row["nom_cla"]);
	$nom_cla2=utf8_decode($row["nom_cla2"]);
	$categoria=utf8_decode($row["categoria"]);
	$mixto=isset($row["mixto"])?$row["mixto"]:NULL;
	$nom_equ=ucwords(strtolower(substr($row["nombre_equipo"],0,35)));
//	$nom_cla=substr($nom_cla,0,34)." - ".strtoupper(substr($row["nombre_corto"],0,3));
	$consulta="SELECT DISTINCT ejecutor
			FROM planillad
			WHERE planilla=$cod_planilla
			ORDER BY planilla, turno, ronda";
	$ejecutar_rondas = $conexion->query($consulta);
	$num_regs=$ejecutar_rondas->num_rows;
	$ejecutores=array();
	while ($ronda=$ejecutar_rondas->fetch_assoc()){
		$i++;
		$ejecutor=$ronda["ejecutor"];
		$ejecutores[]=$ejecutor;
	}
	foreach ($ejecutores as $eje) {
		$ejecutor=$eje;
		$consulta="SELECT DISTINCT ejecutor, salto, posicion, altura, grado_dif, abierto, salto2, posicion2
			FROM planillad
			WHERE planilla=$cod_planilla and ejecutor=$ejecutor
			ORDER BY planilla, turno, ronda";
		$ejecutar_rondas = $conexion->query($consulta);
		$r++;
		$clavadista[0]=$r;
		$i=1;
		while ($ronda=$ejecutar_rondas->fetch_assoc()){
			$tot_saltos++;
			if ($i==1) {
				$ejecutor=$ronda["ejecutor"];
				if ($ejecutor==1) 
					$nom=substr($nom_cla,0,34);
				if ($ejecutor==2) 
					$nom=substr($nom_cla2,0,34);
				$clavadista[$i]=$nom." - ".$nom_equ;
			}
			$i++;
			$clavadista[$i]=$ronda["salto"].$ronda["posicion"]."     ".number_format($ronda["altura"],1)."      ".number_format($ronda["grado_dif"],1);
		}
		$data[]=$clavadista;
	}
}
// Títulos de las columnas
$header = array('Ord.', 'Clavadistas', 'Ronda 1', 'Ronda 2', 'Ronda 3');
$header1 = array('Sal.','', 'Salto   Altura   G.D.', 'Salto   Altura   G.D.', 'Salto   Altura   G.D.');

// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('L', 'Letter');
$pdf->FancyTable($header,$header1,$rondas,$data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>