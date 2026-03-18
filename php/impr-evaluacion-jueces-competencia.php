<?php
if (isset($evento))
	require('../fpdf/fpdf.php');
else
	require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$logo2,$organizador,$encabezado1,$header0,$header1,$w1, $nueva_pagina,$general;

	include('impr-titulo-competencia.php');
	// Salto de línea
	$this->ln(2);
	$this->Cell(0,10,utf8_decode($encabezado1),0,0,'C');
	$this->ln(3);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,'EVALUACION DE JUECES DE LA COMPETENCIA',0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$this->ln(8);
	$this->Cell($w1[0],10,utf8_decode($header0[0]),0,0,'C');
	$this->Cell($w1[1],10,utf8_decode($header0[1]),0,0,'C');
	$this->Cell($w1[2],10,utf8_decode($header0[2]),0,0,'C');
	$this->Cell($w1[3],10,utf8_decode($header0[3]),0,0,'C');
	$this->Cell($w1[4],10,utf8_decode($header0[4]),0,0,'C');
	$this->Cell($w1[5],10,utf8_decode($header0[5]),0,0,'C');
	$this->Cell($w1[6],10,utf8_decode($header0[6]),0,0,'C');
	$this->Cell($w1[7],10,utf8_decode($header0[7]),0,0,'C');
	$this->Cell($w1[8],10,utf8_decode($header0[8]),0,0,'C');
	$this->Cell($w1[9],10,utf8_decode($header0[9]),0,0,'C');
	$this->ln(3);
	$this->Cell($w1[0],10,utf8_decode($header1[0]),0,0,'C');
	$this->Cell($w1[1],10,utf8_decode($header1[1]),0,0,'C');
	$this->Cell($w1[2],10,utf8_decode($header1[2]),0,0,'C');
	$this->Cell($w1[3],10,utf8_decode($header1[3]),0,0,'C');
	$this->Cell($w1[4],10,utf8_decode($header1[4]),0,0,'C');
	$this->Cell($w1[5],10,utf8_decode($header1[5]),0,0,'C');
	$this->Cell($w1[6],10,utf8_decode($header1[6]),0,0,'C');
	$this->Cell($w1[7],10,utf8_decode($header1[7]),0,0,'C');
	$this->Cell($w1[8],10,utf8_decode($header1[8]),0,0,'C');
	$this->Cell($w1[9],10,utf8_decode($header1[9]),0,0,'C');
	$this->ln(8);
	$fill = false;

}

// Pie de página
function Footer()
{
	// Posición: a 1,5 cm del final
	$this->SetY(-20);
	// Arial italic 8
	$this->SetFont('Arial','I',7);
	// Número de página
	$this->Cell(100,8,"www.softneosas.com/clavados    info@softneosas.com",0,0,'L');
	$this->Cell(20,8,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}

function rompe0($dia){
	$this->ln(2);
	$this->SetFont('','B',10);
	$this->Cell(0,8,strtoupper(utf8_decode($dia)),0,0,'C');
	$this->SetFont('','',8);
	$this->ln();

}

	// Tabla coloreada
function FancyTable($header, $w)
{
	global $promedio,$jueces,$nombre,$real,$calif,$dif,$difn,$difp,$difa,$saltos,$orden;

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
//	$this->Cell(0,2,"",'T',0,'R',false);
	$n=count($jueces);
	$j=0;
	for ($k=0 ; $k < $n ; $k++ ) { 
		$i=$orden[$k];
		$j++;
		$this->Cell($w[0],6,$j,0,0,'C',$fill);
		$this->Cell($w[1],6,primera_mayuscula_palabra($nombre[$i]),0,0,'C',$fill);
		$this->Cell($w[2],6,number_format($real[$i],2),0,0,'C',$fill);
		$this->Cell($w[3],6,number_format($calif[$i],2),0,0,'C',$fill);
		$this->Cell($w[4],6,number_format($dif[$i],2),0,0,'C',$fill);
		$this->Cell($w[5],6,number_format($difn[$i],2),0,0,'C',$fill);
		$this->Cell($w[6],6,number_format($difp[$i],2),0,0,'C',$fill);
		$this->Cell($w[7],6,number_format($difa[$i],2),0,0,'C',$fill);
		$this->Cell($w[8],6,number_format($saltos[$i],0),0,0,'C',$fill);
		$this->Cell($w[9],6,number_format($promedio[$i],4),0,0,'C',$fill);
		$fill = !$fill;
		$this->Ln();
	}
}
}

	// Datos
$jueces=array();
$nombre=array();
$real=array();
$calif=array();
$dif=array();
$difn=array();
$difp=array();
$difa=array();
$saltos=array();

while ($reg=$ejecutar_consulta_evaluacion->fetch_assoc()){
	if (!is_null($reg["juez1"])){
		acumula_juez($reg["juez1"],$reg["puntaje_salto"],$reg["cal1"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j1"]);
	}
	if (!is_null($reg["juez2"])){
		acumula_juez($reg["juez2"],$reg["puntaje_salto"],$reg["cal2"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j2"]);
	}
	if (!is_null($reg["juez3"])){
		acumula_juez($reg["juez3"],$reg["puntaje_salto"],$reg["cal3"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j3"]);
	}
	if (!is_null($reg["juez4"])){
		acumula_juez($reg["juez4"],$reg["puntaje_salto"],$reg["cal4"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j4"]);
	}
	if (!is_null($reg["juez5"])){
		acumula_juez($reg["juez5"],$reg["puntaje_salto"],$reg["cal5"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j5"]);
	}
	if (!is_null($reg["juez6"])){
		acumula_juez($reg["juez6"],$reg["puntaje_salto"],$reg["cal6"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j6"]);
	}
	if (!is_null($reg["juez7"])){
		acumula_juez($reg["juez7"],$reg["puntaje_salto"],$reg["cal7"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j7"]);
	}
	if (!is_null($reg["juez8"])){
		acumula_juez($reg["juez8"],$reg["puntaje_salto"],$reg["cal8"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j8"]);
	}
	if (!is_null($reg["juez9"])){
		acumula_juez($reg["juez9"],$reg["puntaje_salto"],$reg["cal9"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j9"]);
	}
	if (!is_null($reg["juez10"])){
		acumula_juez($reg["juez10"],$reg["puntaje_salto"],$reg["cal10"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j10"]);
	}
	if (!is_null($reg["juez11"])){
		acumula_juez($reg["juez11"],$reg["puntaje_salto"],$reg["cal11"],$reg["grado_dif"],$reg["penalizado"],$reg["nom_j11"]);
	}
}
$promedio=array();
$n=count($jueces);
for ($i=0; $i < $n ; $i++) 
	if ($saltos[$i]>0) 
		$promedio[$i]=$difa[$i]/$saltos[$i];
	else
		$promedio[$i]=99;

$orden=array();
$j=-1;
$k=0;
while ($k<$n) {
	$item=99999;
	for ($i=0; $i < $n; $i++) { 
		if (!in_array($i, $orden)) {
			if ($promedio[$i]<$item) {
				$item=$promedio[$i];
				$pos=$i;
			}
		}
	}
	$j++;
	$orden[$j]=$pos;
	$k=count($orden);

}
// Títulos de las columnas
$header0 = array('', '','Total','Calificó','','Difer.','Difer.','Difer.','Cantidad','Difer.');
$header1 = array('Ranking', 'Juez','Real','Juez','Difer.','Negativa','Positiva','Absoluta','Saltos','Promedio');
$w1 = array(9,60,15,15,15,15,15,15,15,15);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header1,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>