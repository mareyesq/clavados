<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$organizador,$encabezado1,$header,$w,$logo2;
	include('impr-titulo-competencia.php');
	
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,utf8_decode($encabezado1),0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->ln(2);
	$this->Cell(0,10,'MEDALLAS POR DEPORTISTA',0,0,'C');
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

function rompe0($sexo,$header,$w){
	if ($sexo=="F") 
		$describe_sexo="Damas";
	else
		$describe_sexo="Varones";

	$this->ln(2);
	$this->SetFont('','B',10);
	$this->Cell(0,8,$describe_sexo,0,0,'L');
	$this->SetFont('','B',8);
	$this->ln(3);
	$this->Cell($w[0],10,$header[0],0,0,'C');
	$this->Cell($w[1],10,$header[1],0,0,'C');
	$this->Cell($w[2],10,$header[2],0,0,'C');
	$this->Cell($w[3],10,$header[3],0,0,'C');
	$this->Cell($w[4],10,$header[4],0,0,'C');
	$this->Cell($w[5],10,$header[5],0,0,'C');
	$this->Cell($w[6],10,$header[6],0,0,'C');
	$this->Cell($w[7],10,$header[7],0,0,'C');
	$this->SetFont('','',8);
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->ln(8);

}

	// Tabla coloreada
function FancyTable($data,$header, $w)
{
	// Restauración de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$fill = false;
	$sexo_anterior="";
	$n=count($data);
	$j=number_format(0,0);
	$ant="";
	foreach ($data as $cla => $row) {
		$sexo 	=$row["sexo"];
		$oro =substr($row["oro"], 2,2);
		$pla =substr($row["plata"], 2,2);
		$bro =substr($row["bronce"], 2,2);
		$total=$oro+$pla+$bro;
		$puntos =substr($row["puntos"], 1,5);
		$puntos =number_format($puntos,1);
		$este=$sexo."-".$oro."-".$pla."-".$bro."-".$puntos;
//		$nombre =ucwords(strtolower($row["nombre"]));
		$nombre =primera_mayuscula_palabra(substr($row["nombre"], 0,30));
		$equipo =strtoupper(substr($row["equipo"], 0,35));
		if ($sexo!==$sexo_anterior){
			$this->rompe0($sexo,$header,$w);
			$sexo_anterior=$sexo;
			$j=0;
		}

		if ($este!==$ant) {
			$j++;
		}
		$ant=$este;
		if ($total+$puntos>0) {
			$this->Cell($w[0],6,$j,0,0,'C',$fill);
			$this->Cell($w[1],6,$nombre,0,0,'L',$fill);
			$this->Cell($w[2],6,$equipo,0,0,'L',$fill);
			$this->Cell($w[3],6,$oro,0,0,'C',$fill);
			$this->Cell($w[4],6,$pla,0,0,'C',$fill);
			$this->Cell($w[5],6,$bro,0,0,'C',$fill);
			$this->Cell($w[6],6,$total,0,0,'C',$fill);
			$this->Cell($w[7],6,$puntos,0,0,'R',$fill);
			$fill = !$fill;
			$this->Ln();
		}
	}
}
}
// Títulos de las columnas
$header = array('Lugar','Deportista', 'Equipo','Oro','Plata','Bronce','Total','Puntos');
$w = array(10,60,70,10,10,10,10,10);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($datos,$header,$w);
$pdf->AliasNbPages();
$pdf->Output();
exit();
?>