<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$organizador,$encabezado1,$header1,$w1, $logo2;
	include('impr-titulo-competencia.php');
	
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,utf8_decode($encabezado1),0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->ln(2);
	$this->Cell(0,10,'RESUMEN DE PUNTOS GENERAL',0,0,'C');
	$this->ln(12);
	$this->Cell($w1[0],6,$header1[0],0,0,'C',FALSE);
	$this->Cell($w1[1],6,$header1[1],0,0,'C',FALSE);
	$this->Cell($w1[2],6,$header1[2],0,0,'C',FALSE);
	$this->Cell($w1[3],6,$header1[3],0,0,'C',FALSE);
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(8);
	$fill = false;

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
function FancyTable($header, $data, $w)
{
// Colores, ancho de línea y fuente en negrita
//	$this->SetFillColor(255,0,0);
//	$this->SetTextColor(255);
//	$this->SetDrawColor(128,0,0);
	// Restauración de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$fill = false;
	$tg=0;
	$k=0;
	$n=count($data);

//	$this->Cell(0,2,"",'T',0,'R',false);
//	foreach($data as $row)
	for ($i=0; $i < $n; $i++) 
	{
		$row=explode("-", $data[$i]);
		$puntos=substr($row[0],1,4);
		$puntos=number_format($puntos,0);
		$k++;
		$this->Cell($w[0],6," ",0,0,'C',$fill);
		$this->Cell($w[1],6,$k,0,0,'C',$fill);
		$this->Cell($w[2],6,$row[1],0,0,'C',$fill);
		$this->Cell($w[3],6,$puntos,0,0,'R',$fill);
		$fill = !$fill;
		$this->Ln();
	}

}
}

	// Datos
$equipos=array();
$puntajes=array();
$num_regs=$ejecutar_consulta_posiciones_ind->num_rows;
if ($num_regs){
	while ($reg=$ejecutar_consulta_posiciones_ind->fetch_assoc()){
		$linea = array();
		$equipo=strtoupper($reg["nom_equipo"]);
		$total=$reg["total_puntos"];
		$equipos[]=$equipo;
		$puntajes[]=$total;
	}
}

// Sincros
$num_regs=$ejecutar_consulta_posiciones_sin->num_rows;
if ($num_regs){
	while ($reg=$ejecutar_consulta_posiciones_sin->fetch_assoc()){
		$linea = array();
		$equipo=strtoupper($reg["nom_equipo"]);
		$total=$reg["total_puntos"];
		if (in_array($equipo, $equipos)) {
			$n=array_search($equipo, $equipos);
			$puntajes[$n] += $total;
		}
		else{
			$equipos[]=$equipo;
			$puntajes[]=$total;
		}
	}
}

//              Abierta 
$num_regs=$ejecutar_consulta_abierta->num_rows;
if ($num_regs){
	while ($reg=$ejecutar_consulta_abierta->fetch_assoc()){
		$linea = array();
		$equipo=strtoupper($reg["nom_equipo"]);
		$total=$reg["total_puntos"];
		if (in_array($equipo, $equipos)) {
			$n=array_search($equipo, $equipos);
			$puntajes[$n] += $total;
		}
		else{
			$equipos[]=$equipo;
			$puntajes[]=$total;
		}
	}
}

$data=array();
$n=count($equipos);
for ($i=0; $i < $n; $i++) { 
	$puntaje=$puntajes[$i]+10000;
	$data[]=$puntaje."-".$equipos[$i];
}
rsort($data);

// Títulos de las columnas
$header1 = array(' ','Puesto', 'Equipo','Puntos');
$w1 = array(50,5,80,12);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header1,$data,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>