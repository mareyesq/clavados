<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$organizador,$encabezado1,$header0,$header1,$w1, $logo2;
	include('impr-titulo-competencia.php');
	
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,utf8_decode($encabezado1),0,0,'C');
	$this->ln(5);
	$this->Cell(0,10,$descripcion,0,0,'C');
	$this->ln(2);
	$this->Cell(0,10,'CALIFICACIONES DE LA COMPETENCIA',0,0,'C');
	$this->SetLineWidth(.1);
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
	$this->ln(1);
	$this->SetFont('','B',10);
	$this->Cell(0,8,strtoupper(utf8_decode($dia)),0,0,'C');
	$this->SetFont('','',8);
	$this->ln();

}

function rompe1($hora,$modalidad,$categoria,$sexo,$evento){
	global $cod_competencia;

	$describe_sexo=(($sexo=="M")?"Varones":(($sexo=="F")?"Damas":"Mixto"));
	if ($modalidad=="Varios") 
		$modalidad=NULL;

	$conexion=conectarse();
	$consulta="SELECT z.*, u.nombre
		from competenciasz as z 
		LEFT JOIN usuarios as u on u.cod_usuario=z.juez
		WHERE z.competencia=$cod_competencia AND z.evento=$evento
		order by z.panel, z.ubicacion";
	$ejecutar_consulta=$conexion->query($consulta);

	$this->ln(1);
	$this->SetFont('','B',10);
	$texto=$modalidad." ".$categoria." ".$describe_sexo;
	$this->Cell(0,8,utf8_decode($texto),0,0,'C');
	$this->SetFont('','',8);
	$this->ln(4);
	$this->Cell(20,5,"   Jueces:",0,0,'C');
	$this->ln(1);
	$i=5;
	while ($row=$ejecutar_consulta->fetch_assoc()) {
		$ubicacion=$row["ubicacion"];
		$nombre=primera_mayuscula_palabra(substr($row["nombre"], 0, 25));
		$i++;
		if ($i>3) {
			$this->ln(3);
			$this->Cell(2,4," ",0,0,'C');
			$i=0;
		}
		if ($ubicacion==25)
			$this->Cell(12,6,"Arbitro: ",0,0,'L');
		else
			$this->Cell(4,6,$ubicacion."- ",0,0,'L');
		$this->Cell(46,6,$nombre,0,0,'L');
	}
	$this->SetFont('','',8);
	$this->ln();
	$conexion->close();
}

function rompe2($nombre,$equipo,$categoria,$sexo,$lugar,$total,$participa_abierta,$lugar_abierta,$total_abierta,$cat,$h0,$h1,$w){
	$describe_sexo=(($sexo=="M")?"Varón":(($sexo=="F")?"Dama":"Mixto"));
	$this->ln(4);
	$this->SetFont('','B',10);
	$this->Cell(110,2,primera_mayuscula_palabra($nombre),0,0,'L');
	$this->Cell(30,2,strtoupper(utf8_decode($equipo)),0,0,'L');
	$this->ln(4);
	$this->Cell(30,2,utf8_decode($describe_sexo),0,0,'L');
	$this->Cell(25,2,primera_mayuscula_frase($categoria),0,0,'C');
	$this->Cell(20,2,'',0,0,'L');
	if ($cat=="AB") {
		$lugar=$lugar_abierta;
		$total=$total_abierta;
	}
	$this->Cell(12,2,'Lugar:',0,0,'L');
	$this->Cell(20,2,$lugar,0,0,'L');
	$this->Cell(15,2,'Puntos:',0,0,'L');
	$this->Cell(7,2,$total,0,0,'L');
	$this->ln();
	if ($participa_abierta=="*") {
		$this->Cell(35,2,utf8_decode('Categoría Abierta  Lugar:'),0,0,'R');
		$this->Cell(12,2,$lugar_abierta,0,0,'L');
		$this->Cell(15,2,'Puntos:',0,0,'L');
		$this->Cell(7,2,$total_abierta,0,0,'L');
	}
	$this->SetFont('','',8);
	$this->ln();
	$this->Cell($w[0],4,$h0[0],0,0,'L',$fill);
	$this->Cell($w[1],4,$h0[1],0,0,'L',$fill);
	$this->Cell($w[2],4,$h0[2],0,0,'L',$fill);
	$this->Cell($w[3],4,$h0[3],0,0,'C',$fill);
	$this->Cell($w[4],4,$h0[4],0,0,'C',$fill);
	$this->Cell($w[5],4,$h0[5],0,0,'C',$fill);
	$this->Cell($w[6],4,$h0[6],0,0,'C',$fill);
	$this->Cell($w[7],4,$h0[7],0,0,'C',$fill);
	$this->Cell($w[8],4,$h0[8],0,0,'C',$fill);
	$this->Cell($w[9],4,$h0[9],0,0,'C',$fill);
	$this->Cell($w[10],4,$h0[10],0,0,'C',$fill);
	$this->Cell($w[11],4,$h0[11],0,0,'C',$fill);
	$this->Cell($w[12],4,$h0[12],0,0,'C',$fill);
	$this->Cell($w[13],4,$h0[13],0,0,'C',$fill);
	$this->Cell($w[14],4,$h0[14],0,0,'C',$fill);
	$this->Cell($w[15],4,$h0[15],0,0,'C',$fill);
	$this->Cell($w[16],4,$h0[16],0,0,'C',$fill);
	$this->Cell($w[17],4,$h0[17],0,0,'C',$fill);
	$this->Cell($w[18],4,$h0[18],0,0,'C',$fill);
 	$this->Cell($w[19],4,$h0[19],0,0,'C',$fill);
	$this->Cell($w[20],4,$h0[20],0,0,'C',$fill);
	$this->ln();
	$this->Cell($w[0],5,$h1[0],0,0,'L',$fill);
	$this->Cell($w[1],5,$h1[1],0,0,'L',$fill);
	$this->Cell($w[2],5,$h1[2],0,0,'L',$fill);
	$this->Cell($w[3],5,$h1[3],0,0,'C',$fill);
	$this->Cell($w[4],5,$h1[4],0,0,'C',$fill);
	$this->Cell($w[5],5,$h1[5],0,0,'C',$fill);
	$this->Cell($w[6],5,$h1[6],0,0,'C',$fill);
	$this->Cell($w[7],5,$h1[7],0,0,'C',$fill);
	$this->Cell($w[8],5,$h1[8],0,0,'C',$fill);
	$this->Cell($w[9],5,$h1[9],0,0,'C',$fill);
	$this->Cell($w[10],5,$h1[10],0,0,'C',$fill);
	$this->Cell($w[11],5,$h1[11],0,0,'C',$fill);
	$this->Cell($w[12],5,$h1[12],0,0,'C',$fill);
	$this->Cell($w[13],5,$h1[13],0,0,'C',$fill);
	$this->Cell($w[14],5,$h1[14],0,0,'C',$fill);
	$this->Cell($w[15],5,$h1[15],0,0,'C',$fill);
	$this->Cell($w[16],5,$h1[16],0,0,'C',$fill);
	$this->Cell($w[17],5,$h1[17],0,0,'C',$fill);
	$this->Cell($w[18],5,$h1[18],0,0,'C',$fill);
 	$this->Cell($w[19],5,$h1[19],0,0,'C',$fill);
	$this->Cell($w[20],5,$h1[20],0,0,'C',$fill);
	$this->ln();

}

	// Tabla coloreada
function FancyTable($header0,$header, $data, $w)
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
	$nombre_anterior="";
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
			$this->rompe1($row[7],$row[6],$row[4],$row[5],$row[35]);
			$orden_anterior=$row[7].$row[6].$row[4].$row[5];			
		}
		$nombre=$row[1];
		if (!($nombre==$nombre_anterior)){
			$this->rompe2($nombre,$row[2], $row[4],$row[5],$row[0],$row[3],$row[33],$row[31],$row[32],$row[34],$header0,$header,$w);
			$nombre_anterior=$row[1];			
		}
		$ronda=isset($row[9])?$row[9]:'  ';
		$abierto=isset($row[29])?$row[29]:' ';
		$posicion=isset($row[11])?$row[11]:' ';
		$salto=isset($row[10])?$row[10].' '.$posicion:'    ';
		$altura=isset($row[12])?number_format($row[12],1):'    ';
		$grado_dif=isset($row[13])?number_format($row[13],1):'    ';
		$cal1=isset($row[14])?$row[14]:'    ';
		$cal2=isset($row[15])?$row[15]:'    ';
		$cal3=isset($row[16])?$row[16]:'    ';
		$cal4=isset($row[17])?$row[17]:'    ';
		$cal5=isset($row[18])?$row[18]:'    ';
		$cal6=isset($row[19])?$row[19]:'    ';
		$cal7=isset($row[20])?$row[20]:'    ';
		$cal8=isset($row[21])?$row[21]:'    ';
		$cal9=isset($row[22])?$row[22]:'    ';
		$cal10=isset($row[23])?$row[23]:'    ';
		$cal11=isset($row[24])?$row[24]:'    ';
		$penalizado=isset($row[25])?$row[25]:'     ';
		$total_salto=isset($row[26])?$row[26]:'      ';
		$puntaje_salto=isset($row[27])?$row[27]:'      ';
		$acumulado=isset($row[28])?$row[28]:'      ';

		$this->Cell($w[0],4,$ronda,0,0,'L',$fill);
		$this->Cell($w[1],4,$abierto,0,0,'L',$fill);
		$this->Cell($w[2],4,$salto,0,0,'L',$fill);
		$this->Cell($w[3],4,$altura,0,0,'C',$fill);
		$this->Cell($w[4],4,$grado_dif,0,0,'C',$fill);
		$this->Cell($w[5],4,$cal1,0,0,'C',$fill);
		$this->Cell($w[6],4,$cal2,0,0,'C',$fill);
		$this->Cell($w[7],4,$cal3,0,0,'C',$fill);
		$this->Cell($w[8],4,$cal4,0,0,'C',$fill);
		$this->Cell($w[9],4,$cal5,0,0,'C',$fill);
		$this->Cell($w[10],4,$cal6,0,0,'C',$fill);
		$this->Cell($w[11],4,$cal7,0,0,'C',$fill);
		$this->Cell($w[12],4,$cal8,0,0,'C',$fill);
		$this->Cell($w[13],4,$cal9,0,0,'C',$fill);
		$this->Cell($w[14],4,$cal10,0,0,'C',$fill);
		$this->Cell($w[15],4,$cal11,0,0,'C',$fill);
		$this->Cell($w[16],4,$penalizado,0,0,'C',$fill);
		$this->Cell($w[17],4,$total_salto,0,0,'R',$fill);
		$this->Cell($w[18],4,$puntaje_salto,0,0,'R',$fill);
 		$this->Cell($w[19],4,$acumulado,0,0,'R',$fill);
		$fill = !$fill;
		$this->ln();
	}
}
}

// Datos
$data=array();
while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
	$extraof=$reg["extraof"];
//	if ($extraof=="" or is_null($extraof)){
		$lugar=($extraof=="" or is_null($extraof))?$reg["lugar"]:'extraoficial';
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
//		$nom_cla=ucwords($nom_cla);
		$equipo=strtoupper($reg["nom_equipo"]);
		$total=number_format($reg["total"],$dec);

		$fechahora=$reg["fechahora"];
		$date = date_create($fechahora);
		$fecha=substr($fechahora,0,10);
		$hora=date_format($date,'g:i a');

		$fecha=hora_coloquial($fecha);

		$linea[0]=$lugar;
		$linea[1]=$nom_cla;
		$linea[2]=$equipo;
		$linea[3]=$total;
		$linea[4]=$nom_cat;
		$linea[5]=$sexo;
		$linea[6]=$nom_mod;
		$linea[7]=$hora;
		$linea[8]=$fecha;
		$linea[9]=$reg['ronda'];
		$linea[10]=$reg['salto'];
		$linea[11]=$reg["posicion"];
		$linea[12]=$reg['altura'];
		$linea[13]=$reg['grado_dif'];
		$linea[14]=$reg['cal1'];
		$linea[15]=$reg['cal2'];
		$linea[16]=$reg['cal3'];
		$linea[17]=$reg['cal4'];
		$linea[18]=$reg['cal5'];
		$linea[19]=$reg['cal6'];
		$linea[20]=$reg['cal7'];
		$linea[21]=$reg['cal8'];
		$linea[22]=$reg['cal9'];
		$linea[23]=$reg['cal10'];
		$linea[24]=$reg['cal11'];
		$linea[25]=$reg['penalizado'];
		$linea[26]=number_format($reg['total_salto'],$dec);
		$linea[27]=number_format($reg['puntaje_salto'],$dec);
		$linea[28]=number_format($reg['acumulado'],$dec);
		$linea[29]=$reg['abierto'];
		$linea[30]=$reg['nom_salto'];
		$linea[31]=$reg['lugar_abierta'];
		$linea[32]=number_format($reg['total_abierta'],$dec);
		$linea[33]=$reg['part_abierta'];
		$linea[34]=$categoria;
		$linea[35]=$reg["numero_evento"];
		$data[]=$linea;
//	}
}


// Títulos de las columnas
$header0 = array('', '','Cod.','','Grad.','Juez','Juez','Juez','Juez','Juez','Juez','Juez','Juez','Juez','Juez','Juez','','Total','Puntaje','');
$header1 = array('Ord.','Ab','Salto','Alt.','Dif.','1','2','3','4','5','6','7','8','9','10','11','Penal','Salto','Salto','Acum.');
$w1 = array(6,5,15,10,7,7,7,7,7,7,7,7,7,7,7,7,8,18,18,18);
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header0,$header1,$data,$w1);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>