<?php 
	$op=$_GET["op"];
	switch ($op) {
		case 'alta':
			$contenido="alta-competidor.php";
			$titulo="Alta de Competidor";
			break;
		case 'baja':
			$contenido="baja-competidor.php";
			$titulo="Baja de Competidor";
			break;
		case 'cambios':
			$contenido="cambios-competidor.php";
			$titulo="Cambio en Competidor";
			break;
		case 'consultas':
			$contenido="consultas-competidor.php";
			$titulo="Consultas de Competidores";
			break;
		
		default:
			$contenido="home.php";
			$titulo="Competencias de Clavados";
			break;
	}
 ?>

<!DOCTYPE html>
<html Lang="es">
<head>
	<title><?php echo $titulo; ?></title>
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