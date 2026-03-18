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
	$this->Cell(0,10,'MEDALLISTAS DE LA COMPETENCIA',0,0,'C');
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

function rompe0($dia){
	$this->ln(2);
	$this->SetFont('','B',10);
	$this->Cell(0,8,strtoupper(utf8_decode($dia)),0,0,'C');
	$this->SetFont('','',8);
	$this->ln();

}

function rompe1($hora,$modalidad,$categoria,$sexo,$header,$w){
	if ($sexo=="M")
		$describe_sexo="Varones";
	elseif ($sexo=="F") 
		$describe_sexo="Damas";
	elseif ($sexo=="X") 
		$describe_sexo="Mixto";
	$this->ln(2);
	$this->SetFont('','B',10);
	if ($modalidad=="Varios") 
		$modalidad=NULL;
	
	$texto=$modalidad." ".$categoria." ".$describe_sexo;
	$this->Cell(0,8,strtoupper(utf8_decode($texto)),0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(5);
	$this->Cell($w[0],10,$header[0],0,0,'C');
	$this->Cell($w[1],10,$header[1],0,0,'C');
	$this->Cell($w[2],10,$header[2],0,0,'C');
	$this->Cell($w[3],10,$header[3],0,0,'C');
	$this->Cell($w[4],10,$header[4],0,0,'C');
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
	$orden_anterior="";
	$dia_anterior="";
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach($data as $row)
	{
		
		if (!($row[8]==$dia_anterior)){

			$this->rompe0($row[8]);
			$dia_anterior=$row[8];
			$orden_anterior="";			
		}

		$orden=$row[7].$row[6].$row[4].$row[5];
		if (!($orden==$orden_anterior)){
			$this->rompe1($row[7],$row[6],$row[4],$row[5],$header,$w);
			$orden_anterior=$row[7].$row[6].$row[4].$row[5];			
		}
		$this->Cell($w[0],6," ",0,0,'C',$fill);
		$this->Cell($w[1],6,primera_mayuscula_palabra($row[0]),0,0,'C',$fill);
		$this->Cell($w[2],6,$row[1],0,0,'L',$fill);
		$this->Cell($w[3],6,$row[2],0,0,'L',$fill);
		$this->Cell($w[4],6,$row[3],0,0,'C',$fill);
		$fill = !$fill;
		$this->Ln();
	}

}
}

	// Datos
$data=array();
while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
	$extraof=$reg["extraof"];
	if ($extraof=="" or is_null($extraof)){
		$lugar=$reg["lugar"];
		if ($lugar<4) {
			$linea = array();
			$evento=$reg["evento"];
			$categoria=$reg["categoria"];
			$sexo=$reg["sexo"];
			$nom_cat=utf8_decode($reg["nom_cat"]);
			$nom_mod=utf8_decode($reg["nom_mod"]);
			$nom_cla=utf8_decode($reg["nom_cla"]);
			$nom_cla2=utf8_decode($reg["nom_cla2"]);
			if (strlen($nom_cla2)>0)
				$nom_cla .= " / ".$nom_cla2;
		
			$nom_cla=strtolower($nom_cla);
			$nom_cla=ucwords($nom_cla);
			$equipo=strtoupper(substr($reg["equipo"], 0, 25));
			$total=number_format($reg["total"],2);
			switch ($lugar) {
				case '1':
					$medalla="ORO";
					break;
				case '2':
					$medalla="PLATA";
					break;
				case '3':
					$medalla="BRONCE";
					break;
			}

			$fechahora=$reg["fechahora"];
			$date = date_create($fechahora);
			$fecha=substr($fechahora,0,10);
			$hora=date_format($date,'g:i a');

			$fecha=hora_coloquial($fecha);

			$linea[0]=$medalla;
			$linea[1]=$nom_cla;
			$linea[2]=$equipo;
			$linea[3]=$total;
			$linea[4]=$nom_cat;
			$linea[5]=$sexo;
			$linea[6]=$nom_mod;
			$linea[7]=$hora;
			$linea[8]=$fecha;
			$data[]=$linea;
		}
	}
}

//              Abierta
while ($reg=$ejecutar_consulta_abierta->fetch_assoc()){
	$extraof=$reg["extraof_abierto"];
	if ($extraof=="" or is_null($extraof)){
		$lugar=$reg["lugar_abierta"];
		if ($lugar<4) {
			$linea = array();
			$evento=$reg["evento"];
			$sexo=$reg["sexo"];
			$categoria="AB";
			$nom_cat="Abierta";
			$nom_mod=utf8_decode($reg["nom_mod"]);
			$clavadista=$reg["nom_cla"];
			$nom_cla=utf8_decode($reg["nom_cla"]);
			$nom_cla2=utf8_decode($reg["nom_cla2"]);
			if (strlen($nom_cla2)>0)
				$nom_cla .= " / ".$nom_cla2;
	
			$nom_cla=strtolower($nom_cla);
			$nom_cla=ucwords($nom_cla);
			$equipo=strtoupper($reg["equipo"]);
			$total=number_format($reg["total_abierta"],2);
			switch ($lugar) {
				case '1':
					$medalla="ORO";
					break;
				case '2':
					$medalla="PLATA";
					break;
				case '3':
					$medalla="BRONCE";
					break;
			}
			$fechahora=$reg["fechahora"];
			$date = date_create($fechahora);
			$fecha=substr($fechahora,0,10);
			$hora=date_format($date,'g:i a');
			$fecha=hora_coloquial($fecha);

			$linea[0]=$medalla;
			$linea[1]=$nom_cla;
			$linea[2]=$equipo;
			$linea[3]=$total;
			$linea[4]=$nom_cat;
			$linea[5]=$sexo;
			$linea[6]=$nom_mod;
			$linea[7]=$hora;
			$linea[8]=$fecha;
			$data[]=$linea;
		}
	}
}
// Títulos de las columnas
$header1 = array(' ','Medalla', 'Clavadistas','Equipo','Total');
$w1 = array(30,20,80,12,12);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header1,$data,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>