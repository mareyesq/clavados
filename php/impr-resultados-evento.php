<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo, $logo2,$organizador,$encabezado1,$header1,$w1;
	include('impr-titulo-competencia.php');

	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,utf8_decode($encabezado1),0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(10);
	$this->Cell($w1[0],10,'Lugar',0,0,'C');
	$this->Cell($w1[1],10,'Clavadistas',0,0,'C');
	$this->Cell($w1[2],10,'Equipo',0,0,'C');
	$this->Cell($w1[3],10,'Puntaje',0,0,'C');
	$this->Cell($w1[4],10,'Puntos',0,0,'C');
	$this->Cell($w1[5],10,'Marca','C');
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
		$orden=$row[6].$row[7];
		if (!($orden==$orden_anterior)){
			$this->rompe1($row[6],$row[7]);
			$orden_anterior=$row[6].$row[7];			
		}
//		$w = array(30,12,80,12,12,12);
		if ($row[1]=="*") 
			$this->Cell($w[0],6,'Extr.Of',0,0,'C',$fill);
		else
			$this->Cell($w[0],6,$row[0],0,0,'C',$fill);
		$this->Cell($w[1],6,$row[2],0,0,'L',$fill);
		$this->Cell($w[2],6,$row[3],0,0,'L',$fill);
		$this->Cell($w[3],6,$row[4],0,0,'R',$fill);
		$this->Cell($w[4],6,$row[5],0,0,'R',$fill);
		$this->Cell($w[5],6,$row[9],0,0,'R',$fill);
		$fill = !$fill;
		$this->Ln();
	}

}
}

	// Datos
$data=array();
$i=0;
while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
	$linea = array();
	$i++;
	$categoria=$reg["categoria"];
	$sexo=$reg["sexo"];

	if ($sexo=="F") 
		$marca=isset($reg["marca_damas"])?$reg["marca_damas"]:NULL;
	else 
		$marca=isset($reg["marca_varones"])?$reg["marca_varones"]:NULL;

	$nom_cat=utf8_decode($reg["nom_cat"]);
	$nom_mod=utf8_decode($reg["nom_mod"]);
	$extraof=$reg["extraof"];
	$lugar=$reg["lugar"];
	$nom_cla=utf8_decode($reg["nom_cla"]);
	$nom_cla2=utf8_decode($reg["nom_cla2"]);
	$nom_cla3=utf8_decode($reg["nom_cla3"]);
	$nom_cla4=utf8_decode($reg["nom_cla4"]);
	if (strlen($nom_cla)>15) {
		$cadenas=explode(" ", $nom_cla);
		$n=count($cadenas);
		if ($n>3) 
			$nom_cla=$cadenas[0]." ".$cadenas[2];
		else
			$nom_cla=$cadenas[0]." ".$cadenas[1];
	}
	if (strlen($nom_cla2)>0){
		if (strlen($nom_cla2)>15) {
			$cadenas=explode(" ", $nom_cla2);
			$n=count($cadenas);
			if ($n>3) 
				$nom_cla2=$cadenas[0]." ".$cadenas[2];
			else
				$nom_cla2=$cadenas[0]." ".$cadenas[1];
		}
		$nom_cla .= "/ ".$nom_cla2;
	} 
	if (strlen($nom_cla3)>0){
		if (strlen($nom_cla3)>15) {
			$cadenas=explode(" ", $nom_cla3);
			$n=count($cadenas);
			if ($n>3) 
				$nom_cla3=$cadenas[0]." ".$cadenas[2];
			else
				$nom_cla3=$cadenas[0]." ".$cadenas[1];
		}
		$nom_cla .= "/ ".$nom_cla3;
	} 
	if (strlen($nom_cla4)>0){
		if (strlen($nom_cla4)>15) {
			$cadenas=explode(" ", $nom_cla4);
			$n=count($cadenas);
			if ($n>3) 
				$nom_cla4=$cadenas[0]." ".$cadenas[2];
			else
				$nom_cla4=$cadenas[0]." ".$cadenas[1];
		}
		$nom_cla .= "/ ".$nom_cla4;
	} 

//	$nom_cla=strtolower($nom_cla);
//	$nom_cla=ucwords($nom_cla);
	$nom_cla=utf8_decode($nom_cla);
//	$equipo=ucwords(strtolower(substr($reg["nom_equipo"],0,30)));
	$equipo=substr($reg["nom_equipo"],0,30);
	$individual=$reg["individual"];
	if ($individual) {
		$puntos=number_format($reg["puntos"],0);
	}
	else
		$puntos=number_format($reg["puntos_sinc"],0);

	$total=number_format($reg["total"],$dec);
	if ($extraof!=="*") 
		$linea[0]=$lugar;
	else
		$linea[0]="";
	$linea[1]=$extraof;
	$linea[2]=$nom_cla;
	$linea[3]=$equipo;
	$linea[4]=$total;
	$linea[5]=$puntos;
	$linea[6]=$nom_cat;
	$linea[7]=$sexo;
	$linea[8]=$nom_mod;
	$linea[9]=NULL;
	if ($marca)
		if ($total>=$marca) 
			$linea[9]="Realizó";
	$data[]=$linea;
}
//              Abierta
while ($reg=$ejecutar_consulta_abierta->fetch_assoc()){
	$sexo=$reg["sexo"];
	$categoria="AB";
	if ($sexo=="F") 
		$marca=isset($reg["marca_damas"])?$reg["marca_damas"]:NULL;
	else 
		$marca=isset($reg["marca_varones"])?$reg["marca_varones"]:NULL;
	$nom_cat="Abierta";
	$nom_mod=utf8_decode($reg["nom_mod"]);
	$extraof=$reg["extraof_abierto"];
	$lugar=$reg["lugar_abierta"];
	$clavadista=$reg["nom_cla"];
	$nom_cla=utf8_decode($reg["nom_cla"]);
	$nom_cla2=utf8_decode($reg["nom_cla2"]);
	if (strlen($nom_cla2)>0){
		if (strlen($nom_cla2)>15) {
			$cadenas=explode(" ", $nom_cla2);
			$n=count($cadenas);
			if ($n>3) 
				$nom_cla2=$cadenas[0]." ".$cadenas[2];
			else
				$nom_cla2=$cadenas[0]." ".$cadenas[1];
		}
		$nom_cla .= "/ ".$nom_cla2;
	} 
	$nom_cla=strtolower($nom_cla);
	$nom_cla=ucwords($nom_cla);
	$equipo=ucwords(strtolower(substr($reg["nom_equipo"],0,30)));
	$puntos=number_format($reg["puntos"],0);
	$total=number_format($reg["total_abierta"],$dec);
	$sexo=$reg["sexo"];
	$calificado=$reg["calificado"];
	if ($extraof!=="*") 
		$linea[0]=$lugar;
	else
		$linea[0]="";
	$linea[1]=$extraof;
	$linea[2]=$nom_cla;
	$linea[3]=$equipo;
	$linea[4]=$total;
	$linea[5]=$puntos;
	$linea[6]=$nom_cat;
	$linea[7]=$sexo;
	$linea[8]=$nom_mod;
	$linea[9]=NULL;
	if ($marca){
		if ($total>=$marca)
			$linea[9]="Realizo";
	}
	$data[]=$linea;
}
// Títulos de las columnas
$header1 = array('Lugar', 'Clavadistas','Equipo','Total','Puntos','Marca');
$w1 = array(20,85,40,14,12,12);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header1,$data,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>