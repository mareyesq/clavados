<?php 
$nueva_pagina=isset($nueva_pagina)?$nueva_pagina:NULL;
$general=isset($general)?$general:NULL;

if ($nueva_pagina and !$general)
	$ruta="../img/fotos/";
else
	$ruta="img/fotos/";

	// Logo
if ($logo)
	$this->Image($ruta.$logo,10,8,35);
	// Arial bold 15
$this->SetFont('Arial','B',13);
	// Movernos a la derecha
//	$this->Cell(80);
	// Título
$this->Cell(40,4,'',0,0,'C');
$this->MultiCell(150,4,$organizador,0,'C');
$this->ln(2);
$this->Cell(40,4,'',0,0,'C');
$this->MultiCell(150,4,$competencia,0,'C');

$horizontal=isset($horizontal)?$horizontal:NULL;
if ($horizontal)
	$columna=230;
else
	$columna=170;
if ($logo2)
	$this->Image($ruta.$logo2,$columna,15,30);
$this->ln(2);
$this->SetFont('Arial','B',12);
$this->Cell(40,4,'',0,0,'C');
$this->Cell(150,4,$subtitulo,0,0,'C');
$this->ln(4);
$this->Cell(40,4,'',0,0,'C');
$this->Cell(150,4,$subtitulo1,0,0,'C');

 ?>