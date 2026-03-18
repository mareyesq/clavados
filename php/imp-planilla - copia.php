<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
	global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$organizador,$clavadista,$orden_salida,$row;
	// Logo
	$this->Image('img/fotos/'.$logo,10,8,30);
	// Arial bold 15
	$this->SetFont('Arial','B',14);
	// Título
	$this->Cell(0,10,$organizador,0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$competencia,0,0,'C');
	$this->ln(5);
	$this->SetFont('Arial','B',12);
	$this->Cell(0,10,$subtitulo,0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$subtitulo1,0,0,'C');
	
	// Salto de línea
	$this->ln(5);
	$this->Cell(0,10,'PLANILLA DE CLAVADOS',0,0,'C');
	return;
	$this->ln(10);
	$this->Cell(200,10,'Evento No. '.$evento.": Modalidad ".$descripcion,0,0,'L');

	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(10);

	$w = array(6,10,7,70,6,7,8,8,8,8,8,8,8,8,8,8,8,9,12,12,12,12);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],10,$header[$i],'LTR',0,'C',false);

	$this->Ln(4);
	for($i=0;$i<count($header1);$i++)
		$this->Cell($w[$i],10,$header1[$i],'LRB',0,'C',false);
	$this->ln(10);

	// Restauración de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',7);
	$fill = false;
}

// completa rayas
function rayas($linea,$w)
{
	global $linea;
	for ($i=$linea; $i < 12; $i++) { 
		$this->Cell($w[0],6,'','LR',0,'R',$fill);
		$this->Cell($w[1],6,'','LR',0,'R',$fill);
		$this->Cell($w[2],6,'','LR',0,'R',$fill);
		$this->Cell($w[3],6,'','LR',0,'L',$fill);
		$this->Cell($w[4],6,'','LR',0,'R',$fill);
		$this->Cell($w[5],6,'','LR',0,'R',$fill);
		$this->Cell($w[6],6,'','LR',0,'R',$fill);
		$this->Cell($w[7],6,'','LR',0,'R',$fill);
		$this->Cell($w[8],6,'','LR',0,'R',$fill);
		$this->Cell($w[9],6,'','LR',0,'R',$fill);
		$this->Cell($w[10],6,'','LR',0,'R',$fill);
		$this->Cell($w[11],6,'','LR',0,'R',$fill);
		$this->Cell($w[12],6,'','LR',0,'R',$fill);
		$this->Cell($w[13],6,'','LR',0,'R',$fill);
		$this->Cell($w[14],6,'','LR',0,'R',$fill);
		$this->Cell($w[15],6,'','LR',0,'R',$fill);
		$this->Cell($w[16],6,'','LR',0,'R',$fill);
		$this->Cell($w[17],6,'','LR',0,'R',$fill);
		$this->Cell($w[18],6,'','LR',0,'R',$fill);
		$this->Cell($w[19],6,'','LR',0,'R',$fill);
		$this->Cell($w[20],6,'','LR',0,'R',$fill);
		$this->Cell($w[21],6,'','LR',0,'R',$fill);
		$this->Ln();
		$fill = !$fill;
	}
	$this->Cell(251,2,"",'T',0,'R',false);
	$this->Ln(1);
	$linea=0;

}
function encabezado($row)
{
	$this->Ln(10);
	$this->SetFont('Arial','',12);
	$this->ln(3);
	$this->Cell(90,6,'Clavadista: '.$row[3],'',0,'L',false);
	$this->Cell(40,6,'Sexo: '.$row[4],'',0,'L',false);
	$this->Cell(60,6,'Equipo: '.$row[5],'',0,'L',false);
	//	$this->Cell(30,6,$row[0],'',0,'L',false);
	$this->Cell(30,6,utf8_decode('Categoría: ').$row[1],'',0,'L',false);
}
// Pie de página
function Footer()
{
	// Posición: a 1,5 cm del final
	$this->SetFont('Arial','',8);
	$this->SetY(-30);
	$this->Cell(180);
	$this->Cell(40,8,"Total Puntos Categoria: ",0,0,'L');
	if ($tot_categoria>0) {
		$this->Cell(20,8,number_format($tot_categoria,2),0,0,'L');
	}
	$this->Ln(3);
	$this->Cell(180);
	$this->Cell(40,8,"Lugar en la Categoria:   ",0,0,'L');
	if ($lugar>0) {
		$this->Cell(20,8,number_format($lugar,0),0,0,'L');
	}
	//.number_format($puesto,0)
	$this->Ln(3);
	$this->Cell(60,8,"Deportista: ________________________    ",0,0,'L');
	$this->Cell(60,8,"Arbitro: ________________________",0,0,'L');
	$this->Cell(60);
	$this->Cell(40,8,"Total Puntos Abierta:   ",0,0,'L');
	if ($tot_abierto>0) {
		$this->Cell(20,8,number_format($tot_abierto,2),0,0,'L');
	}
	$this->Ln(3);
	$this->Cell(180);
	$this->Cell(40,8,"Lugar en Abierta:   ",0,0,'L');
	if ($puesto_abierto>0) {
		$this->Cell(20,8,number_format($puesto_abierto,0),0,0,'L');
	}

	$this->Ln(5);
	// Arial italic 8
	$this->SetFont('Arial','I',7);
	// Número de página
	$this->Cell(100,8,"www.softneosas.com/clavados    info@softneosas.com",0,0,'L');
	$this->Cell(20,8,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
	$this->SetFont('Arial','',10);
}
	// Tabla coloreada
function FancyTable($header, $header1, $data)
{
	$w = array(6,10,7,70,6,7,8,8,8,8,8,8,8,8,8,8,8,9,12,12,12,12);
	$orden=0;
	$linea=0;
	foreach($data as $row)
	{
		if (!($orden==$row[2])) {
			if ($orden>0)
				$this->rayas($linea,$w);
			$this->encabezado($row);
			$orden=$row[2];
		}
		$linea++;
		$this->Cell($w[0],6,$row[6],'LR',0,'R',$fill);
		$this->Cell($w[1],6,$row[7],'LR',0,'R',$fill);
		$this->Cell($w[2],6,$row[9],'LR',0,'R',$fill);
		$this->Cell($w[3],6,$row[8],'LR',0,'L',$fill);
		$this->Cell($w[4],6,number_format($row[10],1),'LR',0,'R',$fill);
		$this->Cell($w[5],6,number_format($row[11],1),'LR',0,'R',$fill);
		$this->Cell($w[6],6,$row[12],'LR',0,'R',$fill);
		$this->Cell($w[7],6,$row[13],'LR',0,'R',$fill);
		$this->Cell($w[8],6,$row[14],'LR',0,'R',$fill);
		$this->Cell($w[9],6,$row[15],'LR',0,'R',$fill);
		$this->Cell($w[10],6,$row[16],'LR',0,'R',$fill);
		$this->Cell($w[11],6,$row[17],'LR',0,'R',$fill);
		$this->Cell($w[12],6,$row[18],'LR',0,'R',$fill);
		$this->Cell($w[13],6,$row[19],'LR',0,'R',$fill);
		$this->Cell($w[14],6,$row[20],'LR',0,'R',$fill);
		$this->Cell($w[15],6,$row[21],'LR',0,'R',$fill);
		$this->Cell($w[16],6,$row[22],'LR',0,'R',$fill);
		$this->Cell($w[17],6,$row[26],'LR',0,'R',$fill);
		$this->Cell($w[18],6,$row[23],'LR',0,'R',$fill);
		$this->Cell($w[19],6,$row[24],'LR',0,'R',$fill);
		$this->Cell($w[20],6,$row[25],'LR',0,'R',$fill);
		$this->Cell($w[21],6,$row[27],'LR',0,'R',$fill);
		$this->Ln();
		$fill = !$fill;
	}
	$this->rayas($linea,$w);
}
}

// Datos
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
		$n=1;
	}

	$nom_cla=utf8_decode($row["nom_cla"]);
	$nom_cla2=utf8_decode($row["nom_cla2"]);
	if (strlen($nom_cla2)>0) 
		$nom_cla .= " / ".$nom_cla2;

	$ronda = array();
	$ronda[1]=utf8_decode($row["nom_cat"]);
	$ronda[2]=$row["orden_salida"];
	$ronda[3]=$nom_cla;
	if ($sexo=="F") 
		$ronda[4]="Femenino";
	else
		$ronda[4]="Masculino";
	$ronda[5]=utf8_decode($row["nom_equipo"]);

	$ronda[6]=$row["ronda"];
	$ronda[7]=$row["salto"];
	$ronda[8]=$row["nom_salto"];
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
	$data[]=$ronda;
}
// Títulos de las columnas
$header = array('No.', utf8_decode('Código'), 'Pos.', utf8_decode('Descripción del Clavado'), 'Alt.', 'G.D.', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'Juez', 'penal', 'Total', 'Puntaje', 'Puntaje',' ');
$header1 = array('Sal.',' ', ' ', ' ', ' ', ' ', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', 'izado', 'Salto','Salto','Acum.','Abierto');
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L', 'Letter');
$pdf->FancyTable($header,$header1,$data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>