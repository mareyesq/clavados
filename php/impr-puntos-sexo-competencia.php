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
	$this->Cell(0,10,'RESUMEN DE PUNTOS POR SEXO',0,0,'C');
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

function rompe1($sexo,$w,$header){
	if ($sexo=="M")
		$describe_sexo="Varones";
	elseif ($sexo=="F") 
		$describe_sexo="Damas";
	elseif ($sexo=="X") 
		$describe_sexo="Mixto";
	$this->SetFont('','B',10);
	$this->Cell(0,8,'       '.strtoupper(utf8_decode($describe_sexo)),0,0,'L');
	$this->SetFont('','B',8);
	$this->ln(3);
	$this->Cell($w[0],10,$header[0],0,0,'C');
	$this->Cell($w[1],10,$header[1],0,0,'C');
	$this->Cell($w[2],10,$header[2],0,0,'C');
	$this->Cell($w[3],10,$header[3],0,0,'C');
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$this->ln(8);

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
	$sexo_anterior="";
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach($data as $row)
	{
		
		if (!($row['sexo']==$sexo_anterior)){
			$this->rompe1($row['sexo'],$w,$header);
			$sexo_anterior=$row['sexo'];
			$k=0;
		}
		$k++;
		$this->Cell($w[0],6," ",0,0,'C',$fill);
		$this->Cell($w[1],6,$k,0,0,'C',$fill);
		$this->Cell($w[2],6,$row['equipo'],0,0,'C',$fill);
		$this->Cell($w[3],6,$row['total'],0,0,'C',$fill);
		$fill = !$fill;
		$this->Ln();
	}

}
}

	// Datos
$data=array();
while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
	$linea = array();
	$sexo=$reg["sexo"];
	$equipo=strtoupper($reg["nom_equipo"]);
	$total=number_format($reg["puntos"],0);
	$linea['sexo']=$sexo;
	$linea['equipo']=$equipo;
	$linea['total']=$total;
	$data[]=$linea;
}

//              Sincronizados y Equipo
/*while ($reg=$ejecutar_consulta_posiciones_sinc->fetch_assoc()){
	$linea = array();
	$sexo=$reg["sexo"];
	$equipo=strtoupper($reg["nom_equipo"]);
	$total=number_format($reg["total_puntos"],2);
	$linea['sexo']=$sexo;
	$linea['equipo']=$equipo;
	$linea['total']=$total;
	$data[]=$linea;
}

//              Abierta
while ($reg=$ejecutar_consulta_abierta->fetch_assoc()){
	$linea = array();
	$sexo=$reg["sexo"];
	$equipo=strtoupper($reg["nom_equipo"]);
	$total=number_format($reg["total_puntos"],2);
	$linea['sexo']=$sexo;
	$linea['equipo']=$equipo;
	$linea['total']=$total;
	$data[]=$linea;
}
*/
// Títulos de las columnas
$header1 = array(' ','Puesto', 'Equipo','Puntos');
$w1 = array(50,10,80,12);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header1,$data,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>