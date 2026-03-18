<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function header()
{
global $competencia, $evento, $descripcion, $fecha, $subtitulo,$subtitulo1,$logo,$logo2, $organizador,$header1,$w1;
	include('impr-titulo-competencia.php');
	
	// Salto de línea
	$this->ln(10);
	$this->Cell(0,10,'LISTA DE INSCRITOS POR EQUIPO',0,0,'C');
	$this->SetLineWidth(.1);
	$this->SetFont('','B',8);
	$this->ln(10);
	$this->Cell($w1[0],10,$header1[0],0,0,'C');
	$this->Cell($w1[1],10,utf8_decode($header1[1]),0,0,'L');
	$this->Cell($w1[2],10,$header1[2],0,0,'L');
	$this->Cell($w1[3],10,$header1[3],0,0,'L');
	$this->Cell($w1[4],10,$header1[4],0,0,'L');
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('','',8);
	$this->ln(8);
	$fill = false;

}

// Pie de página
function Footer()
{
	global $mod_usa,$nom_mod_usa;

	$n=count($mod_usa);
	$cad=NULL;
	for ($i=0; $i < $n ; $i++) { 
		if ($cad)
			$cad .= ", ";
		$cad .= $mod_usa[$i]."=".$nom_mod_usa[$i] ;
	}

	// Posición: a 1,5 cm del final
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',7);

	// Número de página
	$this->Cell(0,8,"* Modalidades: ".$cad,0,0,'L');
	$this->ln();
	$this->Cell(100,8,"www.softneosas.com/saltos    info@softneosas.com",0,0,'L');
	$this->Cell(20,8,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}

function rompe1($equipo){
	$this->ln(5);
	$this->SetFont('','B',10);
	$this->Cell(0,8,strtoupper($equipo),0,0,'C');
	$this->SetFont('','',8);
	$this->ln(8);

}
function total1($equipo,$t1,$t1m){

	$this->ln(1);
	$this->Cell(0,6,'Total '.$equipo.' Inscripciones: '.number_format($t1,0)." Pruebas: ".number_format($t1m,0),0,0,'C',false);
//	$this->Cell(10,6,,0,0,'L',false);
	$this->ln();
}

function totalg($equipo,$t1,$tg,$t1m,$tgm){
	$this->total1($equipo,$t1,$t1m);
	$this->ln();
	$this->Cell(0,6,'Total General Inscripciones: '.number_format($tg,0)." Pruebas: ".number_format($tgm,0) ,0,0,'C',false);
//	$this->Cell(10,6,,0,0,'L',false);
	$this->ln();
}

	// Tabla coloreada
function FancyTable($data)
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
	$this->SetFont('','',7);
	$fill = false;
	$t1=0;
	$tg=0;
	$t1m=0;
	$tgm=0;
//	$this->Cell(0,2,"",'T',0,'R',false);

	foreach($data as $row)
	{
		if ($row["equipo"]!==$equipo){
			if ($equipo) 
				$this->total1($equipo,$t1,$t1m);
			
			$this->rompe1($row["equipo"]);
			$equipo=$row["equipo"];			
			$t1=0;
			$t1m=0;
		}
		$this->Cell($w1[0],6,utf8_decode($row["nom_dep"]),0,0,'L',$fill);
		$this->Cell($w1[1],6,$row["categoria"],0,0,'L',$fill);
		$this->Cell($w1[2],6,amddma($row["nacimiento"],TRUE),0,0,'L',$fill);
		$this->Cell($w1[3],6,$row["modalidades"],0,0,'L',$fill);
		$this->Cell($w1[4],6,utf8_decode($row["entrenador"]),0,0,'L',$fill);
		$this->Ln();
		$fill = !$fill;
		$arr=explode(',',$row["modalidades"]);
		$m=count($arr);
		$t1++;
		$tg++;
		$t1m += $m;
		$tgm += $m;
	}
	$this->totalg($equipo,$t1,$tg,$t1m,$tgm);

}
}

	// Datos
$data=array();

$consulta="SELECT DISTINCT 
	p.clavadista as clavadista,
	p.clavadista2 as clavadista2,
	cla.nombre as nom_cla,
	cla.nacimiento,
	cl2.nombre as nom_cl2,
	ent.nombre as nom_ent, 
	p.categoria, p.modalidad, 
	cat.categoria as nom_cat, 
	m.modalidad as des_modalidad,
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
	ORDER BY q.equipo, p.categoria, nom_cla";

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
$k=0;
$n=0;
$total=0;
$clavadistas=array();
$mod_usa=array();
$nom_mod_usa=array();
while ($row=$ejecutar_consulta->fetch_assoc()){
	$clavadista=$row["clavadista"];
	$clavadista2=isset($row["clavadista2"])?$row["clavadista2"]:NULL;
	$nom_cla=utf8_decode($row["nom_cla"]);
	if ($clavadista2){
		$clavadista.='-'.$clavadista2;
		$nom_cl2=utf8_decode($row["nom_cl2"]);
		$nom_cla.="-".$nom_cl2;
	}
//	$nom_cla=utf8_decode(primera_mayuscula_palabra($nom_cla));
	$nacimiento=$row["nacimiento"];
	$entrenador=primera_mayuscula_palabra(utf8_decode($row["nom_ent"]));
	$equipo=utf8_decode($row["equipo"]);
	$nom_cat=$row["nom_cat"];
	$nom_mod=$row["nom_mod"];
	$des_mod=$row["des_modalidad"];
	if (!in_array($nom_mod, $mod_usa)){
		$mod_usa[] = $nom_mod;
		$nom_mod_usa[]=$des_mod;
	}
	if (!in_array($clavadista, $clavadistas)){
		$k++;
		$clavadistas[$k]=$clavadista;
		$linea=array("equipo" => $equipo, "nom_dep" => $nom_cla, "nacimiento" => $nacimiento,  "categoria" => $nom_cat, "modalidades" => $nom_mod, "entrenador" => $entrenador);
		$data[$k]=$linea;
	}
	else{
		$j = array_search($clavadista, $clavadistas);
		$linea=$data[$j];
		$modalidades=$linea["modalidades"];
		$modalidades .= ", ".$nom_mod;
		$linea["modalidades"]=$modalidades;
		$data[$j]=$linea;
	}
/*
	$clavadista2=isset($row["clavadista2"])?$row["clavadista2"]:NULL;
	if ($clavadista2) {
		$nom_cla=utf8_decode($row["nom_cl2"]);
		$entrenador=utf8_decode($row["nom_ent"]);
		$equipo=utf8_decode($row["equipo"]);
		$nom_cat=$row["nom_cat"];
		$nom_mod=$row["nom_mod"];
		if (!in_array($clavadista2, $clavadistas)){
			$k++;
			$clavadistas[$k]=$clavadista2;
			$linea=array("equipo" => $equipo, "nom_dep" => $nom_cla, "categoria" => $nom_cat, "modalidades" => $nom_mod, "entrenador" => $entrenador);
			$data[$k]=$linea;
		}
		else{
			$j = array_search($clavadista2, $clavadistas);
			$linea=$data[$j];
			$modalidades=$linea["modalidades"];
			$modalidades .= ", ".$nom_mod;
			$linea["modalidades"]=$modalidades;
			$data[$j]=$linea;
		}
	}
*/

}

// Títulos de las columnas
$w1 = array(65, 40, 20, 35, 40);
$header1 = array('Clavadista', 'Categoría', utf8_decode('Nació'), 'Modalidades', 'Entrenador');
// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P', 'Letter');
$pdf->FancyTable($data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
 ?>