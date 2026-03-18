<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function header($header)
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$organizador,$header1,$w1;
	// Logo
	if ($logo)
		$this->Image('img/fotos/'.$logo,10,8,22);
	// Arial bold 15
	$this->SetFont('Arial','B',14);
	// Movernos a la derecha
//	$this->Cell(80);
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
	$this->ln(10);
	$this->Cell(0,10,'LISTA DE INSCRITOS POR EQUIPO',0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(10);
	$this->Cell($w1[0],10,$header1[0],0,0,'C');
	$this->Cell($w1[1],10,utf8_decode($header1[1]),0,0,'C');
	$this->Cell($w1[2],10,$header1[2],0,0,'C');
	$this->Cell($w1[3],10,$header1[3],0,0,'C');
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

function rompe1($equipo){
	$this->ln(5);
	$this->SetFont('','B',10);
	$this->Cell(0,8,strtoupper($equipo),0,0,'C');
	$this->SetFont('','',8);
	$this->ln(8);

}
function total1($equipo,$t1){

	$this->ln(1);
	$this->Cell(0,6,'Total '.$equipo.': '.number_format($t1,0),0,0,'C',false);
//	$this->Cell(10,6,,0,0,'L',false);
	$this->ln();
}

function totalg($equipo,$t1,$tg){
	$this->total1($equipo,$t1);
	$this->ln();
	$this->Cell(0,6,'Total General: '.number_format($tg,0),0,0,'C',false);
//	$this->Cell(10,6,,0,0,'L',false);
	$this->ln();
}

	// Tabla coloreada
function FancyTable($header, $data)
{
	global $w1;
	// Colores, ancho de línea y fuente en negrita
//	$this->SetFillColor(255,0,0);
//	$this->SetTextColor(255);
//	$this->SetDrawColor(128,0,0);
	$equipo="";
	// Restauración de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$fill = false;
	$t1=0;
	$tg=0;
//	$this->Cell(0,2,"",'T',0,'R',false);
	foreach($data as $row)
	{
		if (!($row[0]==$equipo)){
			if (strlen($equipo)>0) 
				$this->total1($equipo,$t1);
			
			$this->rompe1($row[0]);
			$equipo=$row[0];			
			$t1=0;
		}
		$this->Cell($w1[0],6,$row[1],0,0,'L',$fill);
		$this->Cell($w1[1],6,$row[2],0,0,'L',$fill);
		$this->Cell($w1[2],6,$row[3],0,0,'L',$fill);
		$this->Cell($w1[3],6,$row[4],0,0,'L',$fill);
		$this->Ln();
		$fill = !$fill;
		$t1++;
		$tg++;
	}
	$this->totalg($equipo,$t1,$tg);

}
}

	// Datos
$data=array();

$consulta="SELECT DISTINCT 
	cla.nombre as nom_cla,
	cl2.nombre as nom_cl2,
	ent.nombre as nom_ent, 
	p.categoria, p.modalidad, 
	cat.categoria as nom_cat, 
	m.abreviado as nom_mod,
	q.equipo
	FROM planillas as p 
	LEFT JOIN usuarios as cla on cla.cod_usuario=p.clavadista 
	LEFT JOIN usuarios as cl2 on cl2.cod_usuario=p.clavadista2 
	LEFT JOIN usuarios as ent on ent.cod_usuario=p.entrenador 
	LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria 
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
	WHERE p.competencia=$cod_competencia and usuario_retiro IS NULL 
	ORDER BY q.equipo, cat.categoria, nom_cla";

/*
$consulta=
	"SELECT  q.equipo, cl.nombre as nom_cla, ct.categoria, modalidades,en.nombre as nom_ent
		FROM planillas as p
		LEFT JOIN usuarios as cl on cl.cod_usuario=p.clavadista
		LEFT JOIN usuarios as en on en.cod_usuario=p.entrenador
		LEFT JOIN categorias as ct on ct.cod_categoria=p.categoria
		LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
		WHERE p.competencia=$cod_competencia
 		ORDER BY q.equipo, ct.categoria, nom_cla";
*/

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

$total_clavadistas=$ejecutar_consulta->num_rows;
$clavadista="";
$n=0;
$total=0;
while ($row=$ejecutar_consulta->fetch_assoc()){
	$n++;
	$nuevo1=$row["nom_cla"];
	$nuevo2=$row["nom_cl2"];
	if ($nuevo2) {
		$nuevo= utf8_decode($nuevo1." - ".$nuevo2);
	}
	else
		$nuevo=$nuevo1;
	if ($nuevo!==$clavadista) {
		if ($clavadista!=="") {
			$linea = array();
			$linea[0]=$equipo;
			$linea[1]=ucwords(strtolower($clavadista));
			$linea[2]=$nom_cat;
			$linea[3]=$modalidades;
			$linea[4]=ucwords(strtolower($entrenador));
			$data[]=$linea;
		}
		$clavadista=$nuevo;
		$modalidades="";
	}
	if ($n==$total_clavadistas) {
		$entrenador=utf8_decode($row["nom_ent"]);
		$equipo=utf8_decode($row["equipo"]);
		$nom_cat=$row["nom_cat"];
		$nom_mod=$row["nom_mod"];
		if ($modalidades=="") 
			$modalidades=$nom_mod;
		else
			$modalidades.=", ".$nom_mod;
		$linea = array();
		$linea[0]=$equipo;
		$linea[1]=ucwords(strtolower($clavadista));
		$linea[2]=$nom_cat;
		$linea[3]=$modalidades;
		$linea[4]=ucwords(strtolower($entrenador));
		$data[]=$linea;
	}
	else{
		$entrenador=utf8_decode($row["nom_ent"]);
		$equipo=utf8_decode($row["equipo"]);
		$nom_cat=$row["nom_cat"];
		$nom_mod=$row["nom_mod"];
		if ($modalidades=="") 
			$modalidades=$nom_mod;
		else
			$modalidades.=", ".$nom_mod;
	}
}

// Títulos de las columnas
$w1 = array(90,35,30,50);
$header1 = array('Clavadista', 'Categoría', 'Modalidades', 'Entrenador');
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($header1,$data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>