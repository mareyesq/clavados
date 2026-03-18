<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo, $logo2, $organizador, $header1;
	include("impr-titulo-competencia.php");
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,'PROGRAMACION DE EVENTOS',0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(10);
	$this->Cell(40,10,$header1[0],0,0,'C');
	$this->Cell(20,10,$header1[1],0,0,'C');
	$this->Cell(40,10,utf8_decode($header1[2]),0,0,'C');
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
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

function rompe1($dia){
	$this->ln(5);
	$this->SetFont('','B',10);
	$this->Cell(0,8,strtoupper(utf8_decode($dia)),0,0,'C');
	$this->SetFont('','',8);
	$this->ln(8);

}
	// Tabla coloreada
function FancyTable($data)
{
	// Colores, ancho de línea y fuente en negrita
//	$this->SetFillColor(255,0,0);
//	$this->SetTextColor(255);
//	$this->SetDrawColor(128,0,0);
	$dia="";
	// Restauración de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$fill = false;
	$tg=0;
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach($data as $row)
	{
		
		$w1 = array(40,20,100);

		if (!($row[0]==$dia)){
			$this->rompe1($row[0]);
			$dia=$row[0];			
		}
		if ($row[4]) {
			$fechahora=$row[5];
			$mifecha = new DateTime($fechahora); 
			$mifecha->modify('-'.$row[4].' minute'); 
			$hora_calentamiento=$mifecha->format('g:i a');

			$this->Cell($w1[0],6,"",0,0,'C',$fill);
			$this->Cell($w1[1],6,$hora_calentamiento,0,0,'C',$fill);
			$this->Cell($w1[2],6,"Calentamiento",0,0,'C',$fill);
			$this->Ln();
			$fill = !$fill;
		}

		$this->Cell($w1[0],6,$row[1],0,0,'C',$fill);
		$this->Cell($w1[1],6,$row[2],0,0,'C',$fill);
		$this->Cell($w1[2],6,$row[3],0,0,'L',$fill);
		$this->Ln();
		$fill = !$fill;
	}

}
}

	// Datos
$data=array();
$i=0;
$consulta=
	"SELECT  j.fechahora, j.sexos, j.categorias, j.tipo, m.modalidad, j.calentamiento 
		FROM competenciaev as j
		LEFT JOIN modalidades as m on m.cod_modalidad=j.modalidad	
		WHERE competencia=$cod_competencia
 		ORDER BY fechahora";
$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
while ($row=$ejecutar_consulta->fetch_assoc()){
	$evento = array();
	$i++;
	$fechahora=$row["fechahora"];
	$calentamiento=$row["calentamiento"];
	$fecha_coloquial=hora_coloquial($fechahora);
	$date = date_create($fechahora);
	$fecha=substr($fechahora,0,10);
	$hora=date_format($date,'g:i a');
	$fecha=hora_coloquial($fecha);
	$modalidad=utf8_decode($row["modalidad"]);
	$sexos=$row["sexos"];
	$categorias=$row["categorias"];
	$tipo=$row["tipo"];
	$descripcion=describe_evento($modalidad,$sexos,$categorias,$tipo,$conexion);
	$evento[0]=$fecha;
	$evento[1]=$i;
	$evento[2]=$hora;
	$evento[3]=utf8_decode($descripcion);
	$evento[4]=$calentamiento;
	$evento[5]=$fechahora;
	$data[]=$evento;
}
// Títulos de las columnas
$header1 = array('Evento', 'Hora', 'Descripción');
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>