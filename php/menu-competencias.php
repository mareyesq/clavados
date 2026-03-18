<?php 
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	$op=$_GET["op"];
	switch ($op) {
		case 'alta':
			$contenido="php/alta-competidor.php";
			$titulo="Alta de Competidor";
			break;
		case 'baja':
			$contenido="php/baja-competidor.php";
			$titulo="Baja de Competidor";
			break;
		case 'cambios':
			$contenido="php/cambios-competidor.php";
			$titulo="Cambio en Competidor";
			break;
		case 'consultas':
			$contenido="php/consultas-competidor.php";
			$titulo="Consultas de Competidores";
			break;
		
		default:
			$contenido="php/home.php";
			$titulo="Competencias de Clavados";
			break;
	}
 ?>

<!DOCTYPE html>
<html Lang="es">
<head>
	<meta charset="utf-8"/>
	<title><?php echo $titulo; ?></title>
	<link rel="stylesheet" href="css/clavados.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script>
		!window.jQuery && document.write("<script src='js/jquery.min.js'></script>"
	</script>
	<script src="js/clavados.js"></script>
</head>
<body>
	<section id="contenido">
		<nav>
			<ul>
			<li><a class="cambio" href="index.php">Inicio</a></li>	
			<li><a class="cambio" href="?op=alta">Alta de Competidor</a></li>	
			<li><a class="cambio" href="?op=baja">Baja de Competidor</a></li>	
			<li><a class="cambio" href="?op=cambios">Cambios en Competidor</a></li>	
			<li><a class="cambio" href="?op=consultas">Consultas de Competidores</a></li>	
			</ul>
		</nav>
		<section id="principal">
			<?php include($contenido); ?>
		</section>
	</section>
</body>
</html>