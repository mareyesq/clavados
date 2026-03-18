<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo, $logo2,$organizador,$header,$header1,$row;

	$horizontal=TRUE;
	include('impr-titulo-competencia.php');
	$descripcion=utf8_decode($descripcion);
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,'PLANILLA DE CLAVADOS',0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',10);
	$this->ln(8);
	$this->Cell(0,10,'Evento No. '.$evento,0,0,'C');
	$this->ln(4);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->ln(4);
	$this->Cell(0,10,utf8_decode($fecha),0,0,'C');
	$this->ln(8);
}

function encabezado($w,$row,$header,$header1)
{
	$this->SetFont('Arial','',8);
	$this->ln(3);
//	$clavadista=ucwords(strtolower($row[3]));
	$this->Cell(15,6,'Clavadista:','',0,'L',false);
	$this->SetFont('','B',10);
	$this->Cell(90,6,$row['3'],'',0,'L',false);
	$this->SetFont('Arial','',8);
	$this->Cell(8,6,'Sexo:','',0,'L',false);
	$this->SetFont('','B',10);
	$this->Cell(18,6,$row[4],'',0,'L',false);
	$this->SetFont('Arial','',8);
	$this->Cell(10,6,'Equipo:','',0,'L',false);
	$this->SetFont('','B',10);
	$this->Cell(50,6,$row[5],'',0,'L',false);
	$this->SetFont('Arial','',8);
	//	$this->Cell(30,6,$row[0],'',0,'L',false);
	$this->SetLineWidth(.1);
	$this->Cell(15,6,utf8_decode('Categoría:'),'',0,'L',false);
	$this->SetFont('','B',10);
	$this->Cell(40,6,$row[1],'',0,'L',false);
	$this->SetFont('','B',10);
	$this->Ln(3);
	$this->SetX(230);
	$this->Cell(35,6,utf8_decode('Orden de Salida: ').number_format($row[2]),'',0,'R',false);

	$this->Ln(10);
	$this->SetFont('','B',8);
//	$w = array(6,12,75,7,7,8,8,8,8,8,8,8,8,8,8,8,9,14,14,14,10);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],10,$header[$i],'LTR',0,'C',false);

	$this->Ln(4);
	for($i=0;$i<count($header1);$i++)
		$this->Cell($w[$i],10,$header1[$i],'LRB',0,'C',false);
	$this->ln(10);
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
//	$this->ln(8);
	$fill = false;
}

// completa rayas
function rayas($w,$linea,$tot_categoria,$lugar,$tot_abierto,$puesto_abierto)
{
	global $modalidad, $mixto, $dec;
	$bordes='LRB';
	$t=14;
	for ($i=$linea; $i < $t; $i++) { 
		if ($i==($t-1)) 
			$bordes='LRB';
		$this->Cell($w[0],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[1],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[2],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[3],6,'',$bordes,0,'L',$fill);
		$this->Cell($w[4],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[5],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[6],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[7],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[8],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[9],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[10],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[11],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[12],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[13],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[14],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[15],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[16],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[17],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[18],6,'',$bordes,0,'R',$fill);
		$this->Cell($w[19],6,'',$bordes,0,'R',$fill);
		if (!$mixto)
			$this->Cell($w[20],6,'',$bordes,0,'R',$fill);
		$this->Ln();
		$fill = !$fill;
	}
//	$this->Cell(251,2,"",'T',0,'R',false);
	$this->Ln(1);
	$this->SetFont('Arial','',8);
//	$this->SetY(-30);
	$this->Cell(194);
	$this->Cell(40,8,"Total Puntos Categoria:",0,0,'R');
	if ($tot_categoria>0) {
		$this->Cell(20,8,number_format($tot_categoria,$dec),0,0,'L');
	}

	$this->Ln(4);
	$this->Cell(194);
	$this->Cell(40,8,"Lugar en la Categoria:",0,0,'R');
	if ($tot_categoria AND $lugar>0) {
		$this->Cell(10,8,number_format($lugar,0),0,0,'C');
	}
	//.number_format($puesto,0)
	$this->Ln(4);
	$this->Cell(55,8,"Deportista: ________________________    ",0,0,'L');
	$this->Cell(60,8,"Representante: ________________________    ",0,0,'L');
	$this->Cell(55,8,"Arbitro: ________________________",0,0,'L');
	$this->Cell(24);
	$this->Cell(40,8,"Total Puntos Abierta:",0,0,'R');
	
	if ($tot_abierto>0) {
		$this->Cell(20,8,number_format($tot_abierto,$dec),0,0,'L');
	}
	$this->Ln(4);
	$this->Cell(194);
	$this->Cell(40,8,"Lugar en Abierta:",0,0,'R');
	if ($tot_abierto AND $puesto_abierto>0) {
		$this->Cell(10,8,number_format($puesto_abierto,0),0,0,'C');
	}
	$this->Ln(4);

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
function FancyTable($header, $header1, $data)
{
	global $modalidad, $mixto, $dec;
	// Colores, ancho de línea y fuente en negrita
//	$this->SetFillColor(255,0,0);
//	$this->SetTextColor(255);
//	$this->SetDrawColor(128,0,0);
	if ($modalidad=="E") {
		$w = array(6,20,85,7,7,8,8,8,8,8,8,8,8,8,8,8,9,14,14,14);
	}elseif ($mixto) {
		$w = array(6,20,85,7,7,8,8,8,8,8,8,8,8,8,8,8,9,14,14,14);
	}
	else
		$w = array(6,12,75,7,7,8,8,8,8,8,8,8,8,8,8,8,9,14,14,14,10);
	$orden=0;
	$linea=0;
	// Restauración de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$fill = false;
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach($data as $row)
	{
		if ($orden!==$row[2]) {
			if ($orden>0)
				$this->rayas($w,$linea,$tot_categoria,$lugar,$tot_abierto,$puesto_abierto);
			$this->encabezado($w,$row,$header,$header1);
			$linea=0;
			$orden=$row[2];
		}

		$linea++;
		$this->Cell($w[0],6,$row[6],'LRB',0,'C',$fill);
		if ($mixto) 
			$this->Cell($w[1],6,$row[7]." ".$row[9]."  ".$row[32]." ".$row[33],'LRB',0,'L',$fill);
		else
			$this->Cell($w[1],6,$row[7]."-".$row[9],'LRB',0,'C',$fill);
		$this->Cell($w[2],6,primera_mayuscula_frase($row[8]),'LRB',0,'L',$fill);
		if ($row[10]>0)
			$this->Cell($w[3],6,number_format($row[10],1),'LRB',0,'R',$fill);
		else
			$this->Cell($w[3],6,$row[10],'LRB',0,'R',$fill);

		if ($row[11]>0)
			$this->Cell($w[4],6,number_format($row[11],1),'LRB',0,'R',$fill);
		else
			$this->Cell($w[4],6,$row[11],'LRB',0,'R',$fill);
		if ($row[12]>0)
			$this->Cell($w[5],6,number_format($row[12],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[5],6,$row[12],'LRB',0,'C',$fill);
		if ($row[13]>0)
			$this->Cell($w[6],6,number_format($row[13],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[6],6,$row[13],'LRB',0,'C',$fill);
		if ($row[14]>0)
			$this->Cell($w[7],6,number_format($row[14],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[7],6,$row[14],'LRB',0,'C',$fill);
		if ($row[15]>0)
			$this->Cell($w[8],6,number_format($row[15],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[8],6,$row[15],'LRB',0,'C',$fill);
		if ($row[16]>0)
			$this->Cell($w[9],6,number_format($row[16],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[9],6,$row[16],'LRB',0,'C',$fill);
		if ($row[17]>0)
			$this->Cell($w[10],6,number_format($row[17],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[10],6,$row[17],'LRB',0,'C',$fill);
		if ($row[18]>0)
			$this->Cell($w[11],6,number_format($row[18],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[11],6,$row[18],'LRB',0,'C',$fill);
		if ($row[19]>0)
			$this->Cell($w[12],6,number_format($row[19],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[12],6,$row[19],'LRB',0,'C',$fill);
		if ($row[20]>0)
			$this->Cell($w[13],6,number_format($row[20],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[13],6,$row[20],'LRB',0,'C',$fill);
		if ($row[21]>0)
			$this->Cell($w[14],6,number_format($row[21],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[14],6,$row[21],'LRB',0,'C',$fill);
		if ($row[22]>0)
			$this->Cell($w[15],6,number_format($row[22],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[15],6,$row[22],'LRB',0,'C',$fill);
		if ($row[26]>0)
			$this->Cell($w[16],6,number_format($row[26],1),'LRB',0,'C',$fill);
		else
			$this->Cell($w[16],6,$row[26],'LRB',0,'C',$fill);
		if ($row[23]>0)
			$this->Cell($w[17],6,number_format($row[23],$dec),'LRB',0,'C',$fill);
		else
			$this->Cell($w[17],6,$row[23],'LRB',0,'C',$fill);
		if ($row[24]>0)
			$this->Cell($w[18],6,number_format($row[24],$dec),'LRB',0,'C',$fill);
		else
			$this->Cell($w[18],6,$row[24],'LRB',0,'C',$fill);
		if ($row[25]>0)
			$this->Cell($w[19],6,number_format($row[25],$dec),'LRB',0,'C',$fill);
		else
			$this->Cell($w[19],6,$row[25],'LRB',0,'C',$fill);
		if ($modalidad!=="E" and !$mixto) 
			$this->Cell($w[20],6,$row[27],'LRB',0,'C',$fill);
		$tot_categoria=$row[28];
		$lugar=$row[29];
		$tot_abierto=$row[30];
		$puesto_abierto=$row[31];
		$this->Ln();
		$fill = !$fill;
	}
	$this->rayas($w,$linea,$tot_categoria,$lugar,$tot_abierto,$puesto_abierto);

}
}

	// Datos
$dec=decimales($cod_competencia);
$data=array();

$n=0;
$data=array();
while ($row=$ejecutar_consulta->fetch_assoc()){
	if ($n==0) {
		$organizador=quitar_tildes($row["organizador"]);
		$desde=$row["fecha_inicia"];
		$fec=explode("-", $desde);
		$dia_des=$fec[2];
		$mes_des=$fec[1];
		$ano_des=$fec[0];
		$hasta=$row["fecha_termina"];
		$fec=explode("-", $hasta);
		$dia_has=$fec[2];
		$mes_has=$fec[1];
		$ano_has=$fec[0];
		$subtitulo="Del ".$dia_des."-".$mes_des."-".$ano_des." al ".$dia_has."-".$mes_has."-".$ano_has;
		$subtitulo1=$row["City"]." - ".$row["Country"] ;
		$logo=$row["logo_organizador"];
		$logo2=$row["logo2"];
		$n=1;
	}

	$nom_cla=primera_mayuscula_palabra($row["nom_cla"]);
	$nom_cla2=primera_mayuscula_palabra($row["nom_cla2"]);
	$nom_cla3=primera_mayuscula_palabra($row["nom_cla3"]);
	$nom_cla4=primera_mayuscula_palabra($row["nom_cla4"]);
	
	if (strlen($nom_cla2)>0) 
		$nom_cla = "(1) ".substr($nom_cla, 0, 15)." (2) ".substr($nom_cla2, 0, 15);
	if (strlen($nom_cla3)>0) 
		$nom_cla .= "(3) ".substr($nom_cla3, 0, 14);
	if (strlen($nom_cla4)>0) 
		$nom_cla .= "(4) ".substr($nom_cla4, 0, 14);

	$ronda = array();
	$ronda[1]=primera_mayuscula_palabra($row["nom_cat"]);
	$ronda[2]=$row["orden_salida"];
	$ronda[3]=$nom_cla;
	switch ($row["sexo"]) {
		case 'F':
			$ronda[4]="Femenino";
			$mixto=FALSE;
			break;
		
		case 'M':
			$ronda[4]="Masculino";
			$mixto=FALSE;
			break;

		case 'X':
			$ronda[4]="Mixto";
			$mixto=TRUE;
			break;
	}

	$ronda[5]=strtoupper((substr($row["nom_equipo"], 0, 10)));

	$ronda[6]=$row["ronda"];
	if ($modalidad=="E") 
		$ronda[7]="(".$row["ejecutor"].") - ".$row["salto"];
	else
		$ronda[7]=$row["salto"];
	$ronda[8]=$row["nom_salto"];
//	$nom_salto2=isset($row["nom_salto2"])?$row["nom_salto2"]:NULL;
//	if (!is_null($nom_salto2))
//	  	$ronda[8] .= " - ".$nom_salto2;
	  
	$ronda[9]=$row["posicion"];
	$ronda[10]=$row["altura"];
	$ronda[11]=$row["grado_dif"];
	$ronda[12]=$row["cal1"];
	$ronda[13]=$row["cal2"];
	$ronda[14]=$row["cal3"];
	$ronda[15]=$row["cal4"];
	$ronda[16]=$row["cal5"];
	$ronda[17]=$row["cal6"];
	$ronda[18]=$row["cal7"];
	$ronda[19]=$row["cal8"];
	$ronda[20]=$row["cal9"];
	$ronda[21]=$row["cal10"];
	$ronda[22]=$row["cal11"];
	$ronda[23]=$row["total_salto"];
	$ronda[24]=$row["puntaje_salto"];
	$ronda[25]=$row["acumulado"];
	$ronda[26]=$row["penalizado"];
	$ronda[27]=$row["abierto"];
	$ronda[28]=$row["total"];
	$ronda[29]=$row["lugar"];
	$ronda[30]=$row["total_abierta"];
	$ronda[31]=$row["lugar_abierta"];
//	$ronda[32]=$row["salto2"];
//	$ronda[33]=$row["posicion2"];
	 
	$data[]=$ronda;
}
// Títulos de las columnas
if ($modalidad=="E") {
	$header = array('', '', '', '','', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'pena-', 'Total', 'Puntaje', 'Puntaje');
	$header1 = array('No.', '(Ej) Clavado', utf8_decode('Descripción del Clavado'), 'Alt.', 'G.D.', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', 'lizado', 'Salto','Salto','Acum.');
}
elseif ($mixto) {
	$header = array('', '', '', '','', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'pena-', 'Total', 'Puntaje', 'Puntaje');
	$header1 = array('No.', 'Clavado', utf8_decode('Descripción del Clavado'), 'Alt.', 'G.D.', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', 'lizado', 'Salto','Salto','Acum.');
}
else{
	$header = array('', '', '', '','', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'pena-', 'Total', 'Puntaje', 'Puntaje',' ');
	$header1 = array('No.', 'Clavado', utf8_decode('Descripción del Clavado'), 'Alt.', 'G.D.', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', 'lizado', 'Salto','Salto','Acum.','Abierto');
}

// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L', 'Letter');
$pdf->FancyTable($header,$header1,$data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>