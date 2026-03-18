<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$logo2,$organizador,$encabezado1,$header1,$w1;

	include('impr-titulo-competencia.php');
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,'MEDALLISTAS DEL EVENTO',0,0,'C');
	$this->ln(10);
	$this->Cell(0,10,utf8_decode($encabezado1),0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(10);
	$this->Cell($w1[0],10,'Medalla',0,0,'C');
	$this->Cell($w1[1],10,'Clavadistas',0,0,'C');
	$this->Cell($w1[2],10,'Equipo',0,0,'C');
	$this->Cell($w1[3],10,'Puntaje',0,0,'C');
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

function rompe1($categoria,$sexo){
	$describe_sexo=($sexo=="M")?"Masculino":"Femenino";
	$this->ln(2);
	$this->SetFont('','B',10);
	$texto=$categoria." ".$describe_sexo;
	$this->Cell(0,8,strtoupper(utf8_decode($texto)),0,0,'C');
	$this->SetFont('','',8);
	$this->ln();

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
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach($data as $row)
	{
		$orden=$row[4].$row[5];
		if (!($orden==$orden_anterior)){
			$this->rompe1($row[4],$row[5]);
			$orden_anterior=$row[4].$row[5];			
		}
		$this->Cell($w[0],6,$row[0],0,0,'C',$fill);
		$this->Cell($w[1],6,$row[1],0,0,'L',$fill);
		$this->Cell($w[2],6,$row[2],0,0,'L',$fill);
		$this->Cell($w[3],6,$row[3],0,0,'C',$fill);
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
			$categoria=$reg["categoria"];
			$sexo=$reg["sexo"];
			$nom_cat=utf8_decode($reg["nom_cat"]);
			$nom_mod=utf8_decode($reg["nom_mod"]);
			$nom_cla=utf8_decode($reg["nom_cla"]);
			$nom_cla2=utf8_decode($reg["nom_cla2"]);
			$nom_cla3=utf8_decode($reg["nom_cla3"]);
			$nom_cla4=utf8_decode($reg["nom_cla4"]);
			if (strlen($nom_cla2)>0){
				$nom_cla = substr($nom_cla, 0, 15);
				$nom_cla .= " / ".$nom_cla2;
			}
			if (strlen($nom_cla3)>0){
				$nom_cla .= " / ".substr($nom_cla3, 0, 15);
			}
			if (strlen($nom_cla4)>0){
				$nom_cla .= " / ".substr($nom_cla4, 0, 15);
			}
			$nom_cla=strtolower($nom_cla);
			$nom_cla=ucwords($nom_cla);
			$equipo=strtoupper($reg["equipo"]);
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

			$linea[0]=$medalla;
			$linea[1]=$nom_cla;
			$linea[2]=$equipo;
			$linea[3]=$total;
			$linea[4]=$nom_cat;
			$linea[5]=$sexo;
			$linea[6]=$nom_mod;
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
			$sexo=$reg["sexo"];
			$categoria="AB";
			$nom_cat="Abierta";
			$nom_mod=utf8_decode($reg["nom_mod"]);
			$clavadista=$reg["nom_cla"];
			$nom_cla=utf8_decode($reg["nom_cla"]);
			$nom_cla2=utf8_decode($reg["nom_cla2"]);
			$nom_cla3=utf8_decode($reg["nom_cla3"]);
			$nom_cla4=utf8_decode($reg["nom_cla4"]);
			if (strlen($nom_cla2)>0){
				$nom_cla = substr($nom_cla, 0, 15);
				$nom_cla .= " / ".substr($nom_cla2, 0, 15);
			}
	
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
			$linea[0]=$medalla;
			$linea[1]=$nom_cla;
			$linea[2]=$equipo;
			$linea[3]=$total;
			$linea[4]=$nom_cat;
			$linea[5]=$sexo;
			$linea[6]=$nom_mod;
			$data[]=$linea;
		}
	}
}
// Títulos de las columnas
$header1 = array('Medalla', 'Clavadistas    ','Equipo','Total');
$w1 = array(20,120,15,15);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header1,$data,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>