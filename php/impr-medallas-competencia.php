<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$organizador,$encabezado1,$header1,$w1,$logo2;
	include('impr-titulo-competencia.php');
	
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,utf8_decode($encabezado1),0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->ln(2);
	$this->Cell(0,10,'RESUMEN DE MEDALLAS',0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(8);
	$fill = false;
	$this->ln(3);
	$this->Cell($w1[0],10,$header1[0],0,0,'C');
	$this->Cell($w1[1],10,$header1[1],0,0,'C');
	$this->Cell($w1[2],10,$header1[2],0,0,'C');
	$this->Cell($w1[3],10,$header1[3],0,0,'C');
	$this->Cell($w1[4],10,$header1[4],0,0,'C');
	$this->Cell($w1[5],10,$header1[5],0,0,'C');
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->ln(8);

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
function FancyTable($data,$header, $w)
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
	$j=0;
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach ($data as $key => $valor) {
		$linea=explode("-", $valor);

		$equipo=strtoupper($linea[3]);
		$oro=$linea[0]-1000;
		$plata=$linea[1]-1000;
		$bronce=$linea[2]-1000;

		$j++;
		$this->Cell($w[0],6," ",0,0,'C',$fill);
		$this->Cell($w[1],6,$j,0,0,'C',$fill);
		$this->Cell($w[2],6,$equipo,0,0,'C',$fill);
		$this->Cell($w[3],6,$oro,0,0,'C',$fill);
		$this->Cell($w[4],6,$plata,0,0,'C',$fill);
		$this->Cell($w[5],6,$bronce,0,0,'C',$fill);
		$fill = !$fill;
		$this->Ln();
	}
}
}

// Datos
$llave=array();
$equipos=array();
$oro=array();
$plata=array();
$bronce=array();
while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
	acumula_medallas($reg["lugar"],$reg["equipo"],$reg["nom_equipo"]);
}
//              Abierta
while ($reg=$ejecutar_consulta_abierta->fetch_assoc()){
	acumula_medallas($reg["lugar"],$reg["equipo"],$reg["nom_equipo"]);
}
$n=count($llave);
$data=array();
for ($i=0; $i < $n ; $i++) { 
	$oro[$i]+=1000;
	$plata[$i]+=1000;
	$bronce[$i]+=1000;
	$data[]=$oro[$i]."-".$plata[$i]."-".$bronce[$i]."-".$equipos[$i];
}
arsort($data);

// Títulos de las columnas
$header1 = array(' ','Puesto', 'Equipo','Oro','Plata','Bronce');
$w1 = array(30,10,90,10,10,10);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($data,$header1,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
?>