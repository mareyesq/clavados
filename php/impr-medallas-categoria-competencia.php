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
	$this->Cell(0,10,'RESUMEN DE MEDALLAS POR CATEGORIA',0,0,'C');
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

function rompe0($categoria){
	$this->ln(2);
	$this->SetFont('','B',10);
	$this->Cell(0,8,strtoupper(utf8_decode('Categoría: '.$categoria)),0,0,'L');
	$this->SetFont('','',8);
	$this->ln(3);
	$this->Cell($w[0],10,$header[0],0,0,'C');
	$this->Cell($w[1],10,$header[1],0,0,'C');
	$this->Cell($w[2],10,$header[2],0,0,'C');
	$this->Cell($w[3],10,$header[3],0,0,'C');
	$this->Cell($w[4],10,$header[4],0,0,'C');
	$this->Cell($w[5],10,$header[5],0,0,'C');
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->ln(4);

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
	$categoria_anterior="";
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach ($data as $key => $valor) {
		$linea=explode("-", $valor);
		$equipo=strtoupper($linea[4]);
		$oro=$linea[1];
		$plata=$linea[2];
		$bronce=$linea[3];
		$categoria=$linea[5];
		if (!($categoria==$categoria_anterior)){
			$this->rompe0($categoria);
			$categoria_anterior=$categoria;
			$sexo_anterior="";
			$j=0;			
		}

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
$cat=array();
$categorias=array();
$equipos=array();
$oro=array();
$plata=array();
$bronce=array();
while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
	acumula_medallas_categoria($reg["categoria"],$reg["nom_cat"],$reg["lugar"],$reg["equipo"],$reg["nom_equipo"]);
}
//              Abierta
while ($reg=$ejecutar_consulta_abierta->fetch_assoc()){
	acumula_medallas_categoria("AB","Abierta",$reg["lugar"],$reg["equipo"],$reg["nom_equipo"]);
}
//              Sincronizados y Equipo
while ($reg=$ejecutar_consulta_sinc->fetch_assoc()){
	acumula_medallas_categoria($reg["categoria"],$reg["nom_cat"],$reg["lugar"],$reg["equipo"],$reg["nom_equipo"]);
}

$n=count($llave);
$data=array();
for ($i=0; $i < $n ; $i++) { 
	$data[]=$cat[$i]."-".$oro[$i]."-".$plata[$i]."-".$bronce[$i]."-".$equipos[$i]."-".$categorias[$i];
}
rsort($data);
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