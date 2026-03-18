<?php
require 'fpdf/fpdf.php';

class PDF extends FPDF {
// Cabecera de página
	function Header() {
		global $competencia, $evento, $descripcion, $fecha, $tot_saltos, $tiempo_estimado, $subtitulo, $subtitulo1, $logo, $logo2, $organizador;
		$horizontal = TRUE;
		include 'impr-titulo-competencia.php';
		$descripcion = utf8_decode($descripcion);
		// Salto de línea
		$this->Ln(10);
		$this->Cell(0, 10, 'DETALLE DE SALTOS POR ORDEN DE SALIDA', 0, 0, 'C');
		$this->ln(10);
//	$this->Cell(0,10,'Evento No. '.$evento.": Modalidad ".$descripcion,0,0,'L');
		$this->Cell(0, 10, 'Evento No. ' . $evento . ": " . $descripcion, 0, 0, 'C');
		$this->Ln(5);
		$this->Cell(0, 10, utf8_decode($fecha . ' Total Saltos ' . $tot_saltos . '  Duración Estimada: ' . $tiempo_estimado . ' min.'), 0, 0, 'C');
	}

// Pie de página
	function Footer() {
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial', 'I', 7);
		// Número de página
		$this->Cell(100, 8, "www.softneosas.com/saltos    info@softneosas.com", 0, 0, 'L');
		$this->Cell(20, 8, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
	}
	// Tabla coloreada
	function FancyTable($header, $header1, $rondas, $data) {
		global $modalidad, $mixto;
		// Colores, ancho de línea y fuente en negrita
		//	$this->SetFillColor(255,0,0);
		//	$this->SetTextColor(255);
		//	$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.1);
		$this->SetFont('', 'B', 7);
		$this->ln(10);
		// Cabecera

		if ($rondas == 8) {
//			$w = array(6, 92, 18, 18, 18, 18, 18, 18, 18, 18);
			$w = array(6, 92, 18, 18, 18, 18, 18, 18, 18);
		} elseif ($rondas == 11) {
			$w = array(6, 62, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18);
		} elseif ($modalidad == "E" or $mixto) {
			$w = array(6, 80, 25, 25, 25, 25, 25, 25);
		} elseif ($rondas < 8) {
			$w = array(6, 92, 22, 22, 22, 22, 22, 22, 22);
		} else {
			$w = array(6, 65, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19);
		}

		for ($i = 0; $i < count($header); $i++) {
			$this->Cell($w[$i], 10, $header[$i], 'LTR', 0, 'C', false);
		}

		$this->Ln(4);
		for ($i = 0; $i < count($header1); $i++) {
			$this->Cell($w[$i], 10, $header1[$i], 'LRB', 0, 'C', false);
		}

		$this->ln(10);

		// Restauración de colores y fuentes
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('', '', 7);
		$fill = false;

		foreach ($data as $row) {
			$this->Cell($w[0], 6, $row[0], 'LRB', 0, 'L', $fill);
			$this->Cell($w[1], 6, $row[1], 'LRB', 0, 'L', $fill);
			$this->Cell($w[2], 6, $row[2], 'LRB', 0, 'L', $fill);
			$this->Cell($w[3], 6, $row[3], 'LRB', 0, 'L', $fill);
			$this->Cell($w[4], 6, $row[4], 'LRB', 0, 'L', $fill);
			$this->Cell($w[5], 6, $row[5], 'LRB', 0, 'L', $fill);
			$this->Cell($w[6], 6, $row[6], 'LRB', 0, 'L', $fill);
			$this->Cell($w[7], 6, $row[7], 'LRB', 0, 'L', $fill);
			if ($modalidad !== "E" and !$mixto) {
				$this->Cell($w[8], 6, $row[8], 'LRB', 0, 'L', $fill);
				if ($rondas > 7) {
					$this->Cell($w[9], 6, $row[9], 'LRB', 0, 'L', $fill);
					$this->Cell($w[10], 6, $row[10], 'LRB', 0, 'L', $fill);
					$this->Cell($w[11], 6, $row[11], 'LRB', 0, 'L', $fill);
					if ($rondas == 11) {
						$this->Cell($w[12], 6, $row[12], 'LRB', 0, 'L', $fill);
					}

				}
			}
			$this->Ln();
			$fill = !$fill;
		}
//	$this->Cell(0,2,"",'T',0,'R',false);
	}
}

// Datos

$data = array();
$consulta = "SELECT DISTINCT p.cod_planilla, p.orden_salida, p.categoria, c.nombre as nom_cla, c2.nombre as nom_cla2, q.nombre_corto, q.equipo as nombre_equipo, m.mixto
	FROM planillas as p
	LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
	LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
if ($sorteada) {
	$consulta .= " WHERE (p.competencia=$cod_competencia AND p.evento=$numero_evento and p.usuario_retiro IS NULL";
} else {
	$consulta .= $criterio . $criterio_sexos . $criterio_categorias;
}

if ($sorteada) {
	$consulta .= ") ORDER BY p.orden_salida, nom_cla";
} else {
	$consulta .= ") ORDER BY nombre_equipo, nom_cla";
}

//$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")
// 		ORDER BY p.orden_salida";
$ejecutar_consulta = $conexion->query($consulta);
$tot_saltos = 0;
$cat_ab = strpos($criterio_categorias, "AB");
$cat_ga = strpos($criterio_categorias, "GA");
$cat_gb = strpos($criterio_categorias, "GB");

if ($cat_ab > 0 and ($cat_ga > 0 or $cat_gb > 0)) {
	$mezcla = TRUE;
	if (!$primero_libres) {
		$varones = strpos($criterio_sexos, "M");
		if ($varones > 0) {
			$ronda_mayores = 5;
		} else {
			$ronda_mayores = 5;
		}

	}
} else {
	$mezcla = FALSE;
	$ronda_mayores = 0;
}
$rondas = 0;
$ron_mx = NULL;
while ($row = $ejecutar_consulta->fetch_assoc()) {
	$clavadista = array();
	$cod_planilla = $row["cod_planilla"];
	$clavadista[0] = $row["orden_salida"];
	$nom_cla = primera_mayuscula_palabra($row["nom_cla"]);
	$categoria = isset($row["categoria"])?$row["categoria"]:NULL;
	$mixto = isset($row["mixto"]) ? $row["mixto"] : NULL;
	if (strlen($nom_cla) > 30) {
		$cadenas = explode(" ", $nom_cla);
		$n = count($cadenas);
		if ($n > 3) {
			$nom_cla = $cadenas[0] . " " . $cadenas[2];
		} else {
			$nom_cla = $cadenas[0] . " " . $cadenas[1];
		}

	}
	$nom_cla2 = isset($row['nom_cla2']) ? primera_mayuscula_palabra($row["nom_cla2"]) : NULL;
	if (strlen($nom_cla2) > 0) {
		if (strlen($nom_cla2) > 30) {
			$rondas = 8;
			$cadenas = explode(" ", $nom_cla2);
			$n = count($cadenas);
			if ($n > 3) {
				$nom_cla2 = $cadenas[0] . " " . $cadenas[2];
			} else {
				$nom_cla2 = $cadenas[0] . " " . $cadenas[1];
			}

		}
		$nom_cla = "(1) " . $nom_cla . "- (2) " . $nom_cla2;
	}
	$nom_equ = strtoupper(substr($row["nombre_equipo"], 0, 35));
	$nom_cla = substr($nom_cla, 0, 70) . " - " . $nom_equ;
	$clavadista[1] = utf8_decode($nom_cla);
	if ($mezcla and $primero_libres and ($categoria == "GB" or $categoria = "GA")) {
		$consulta = "SELECT DISTINCT ronda, ejecutor, salto, posicion, altura, grado_dif, abierto, salto2, posicion2
			FROM planillad
			WHERE planilla=$cod_planilla
			ORDER BY abierto DESC, ronda";
	} else {
		$consulta = "SELECT DISTINCT ronda, ejecutor, salto, posicion, altura, grado_dif, abierto, salto2, posicion2
			FROM planillad
			WHERE planilla=$cod_planilla
			ORDER BY ronda";
	}
	$ejecutar_rondas = $conexion->query(utf8_decode($consulta));
	$rondas = $ejecutar_rondas->num_rows;

	$i = 1;

	if ($categoria == "AB") {
		if ($ronda_mayores) {
			$i = $ronda_mayores;
		}
	}

	while ($ronda = $ejecutar_rondas->fetch_assoc()) {
		$ron = isset($ronda["ronda"]) ? $ronda["ronda"] : NULL;
		if ($ron > $ron_mx) {
			$ron_mx = $ron;
		}

		$i++;
		$tot_saltos++;
		if ($modalidad == "E") {
			$clavadista[$i] = "(" . $ronda["ejecutor"] . ")   " . $ronda["abierto"] . " " . $ronda["salto"] . $ronda["posicion"] . " " . number_format($ronda["altura"], 1) . "  " . number_format($ronda["grado_dif"], 1);
		} elseif ($mixto) {
			if ($ronda["salto2"]) {
				$sal2 = $ronda["salto2"] . $ronda["posicion2"];
			} else {
				$sal2 = "       ";
			}

			$clavadista[$i] = $ronda["abierto"] . " " . $ronda["salto"] . $ronda["posicion"] . number_format($ronda["altura"], 1) . "  " . number_format($ronda["grado_dif"], 1);} else {
			$clavadista[$i] = $ronda["abierto"] . " " . $ronda["salto"] . $ronda["posicion"] . " " . number_format($ronda["altura"], 1) . " " . number_format($ronda["grado_dif"], 1);
		}

	}
	$data[] = $clavadista;
}
if ($rondas=='') {
	$rondas=0;
}
$rondas=(int) $rondas;
$ron_mx=(int) $ron_mx;
if ($ron_mx > $rondas) {
	$rondas = $ron_mx;
}


//$rondas=11;
// Títulos de las columnas
if ($rondas == 8) {
	$header = array('Ord.', 'Clavadistas', 'Ronda 1', 'Ronda 2', 'Ronda 3', 'Ronda 4', 'Ronda 5', 'Ronda 6', 'Ronda 7', 'Ronda 8');
	$header1 = array('Sal.', '', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.');
} elseif ($rondas == 11) {
	$header = array('Ord.', 'Clavadistas', 'Ronda 1', 'Ronda 2', 'Ronda 3', 'Ronda 4', 'Ronda 5', 'Ronda 6', 'Ronda 7', 'Ronda 8', 'Ronda 9', 'Ronda 10', 'Ronda 11');
	$header1 = array('Sal.', '', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.');
} elseif ($modalidad == "E") {
	$header = array('Ord.', 'Clavadistas', 'Ronda 1', 'Ronda 2', 'Ronda 3', 'Ronda 4', 'Ronda 5', 'Ronda 6');
	$header1 = array('Sal.', '', 'Ej.  Sal. Alt. GD.', 'Ej.  Sal. Alt. GD.', 'Ej.  Sal. Alt. GD.', 'Ej.  Sal. Alt. GD.', 'Ej.  Sal. Alt. GD.', 'Ej.  Sal. Alt. GD.');
} elseif ($mixto) {
	$header = array('Ord.', 'Clavadistas', 'Ronda 1', 'Ronda 2', 'Ronda 3', 'Ronda 4', 'Ronda 5', 'Ronda 6');
	$header1 = array('Sal.', '', 'Sal.  Sal2.  Alt. GD.', 'Sal.  Sal2.  Alt. GD.', 'Sal.  Sal2.  Alt. GD.', 'Sal.  Sal2.  Alt. GD.', 'Sal.   Sal2.  Alt. GD.', 'Sal.  Sal2.  Alt. GD.');
} elseif ($rondas < 8) {
	$header = array('Ord.', 'Clavadistas', 'Ronda 1', 'Ronda 2', 'Ronda 3', 'Ronda 4', 'Ronda 5', 'Ronda 6', 'Ronda 7');
	$header1 = array('Sal.', '', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.');
} else {
	$header = array('Ord.', 'Clavadistas', 'Ronda 1', 'Ronda 2', 'Ronda 3', 'Ronda 4', 'Ronda 5', 'Ronda 6', 'Ronda 7', 'Ronda 8', 'Ronda 9', 'Ronda 10');
	$header1 = array('Sal.', '', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.', 'Sal. Alt. GD.');

}

// Carga de datos
$pdf = new PDF();
$pdf->SetFont('Arial', '', 12);
$pdf->AddPage('L', 'Letter');
$pdf->FancyTable($header, $header1, $rondas, $data);
$pdf->AliasNbPages();
$pdf->Output();
exit();
?>