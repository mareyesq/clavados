<?php 
if (isset($_GET["us"])){
	$usuario=$_GET["us"];
	$nombre_usuario=$_GET["nu"];
	$llamo=$_GET["ori"];
}

if (!isset($usuario)) 
	$usuario=isset($_SESSION["usuario"])?$_SESSION["usuario"]:NULL;

if (!isset($nombre_usuario)) 
	$nombre_usuario=isset($_SESSION["nombre_usuario"])?$_SESSION["nombre_usuario"]:NULL;

if (!isset($llamo)) 
	$llamo=isset($_SESSION["llamo"])?$_SESSION["llamo"]:NULL;

include("funciones.php");

$consulta="SELECT DISTINCT c.competencia, c.fecha_inicia, c.fecha_termina, c.logo_organizador, ci.City, co.Country
FROM planillas as p
LEFT JOIN competencias as c on c.cod_competencia=p.competencia
LEFT JOIN cities as ci on ci.CityId=c.ciudad
LEFT JOIN countries as co on co.CountryId=ci.CountryID
WHERE clavadista = $usuario
ORDER BY C.fecha_inicia DESC ";

$conexion=conectarse();
$ejecutar_consulta=$conexion->query($consulta);
 ?>

<form id="competencias-usuarios" name="competencias-usuarios-frm" action="?op=php/competencias-usuario-1.php" method="post" enctype="multipart/form-data">
<fieldset>
<legend align='center' >Competencias de <?php echo "$nombre_usuario"; ?></legend>
	<div>
		<label for="buscar" >Buscar: </label>
		<input type="search" class="cambio" id="buscar" name="buscar_src" value="<?php echo $buscar; ?>">
		<input type="submit" class="cambio" name="buscar_sbm" value="Buscar <?php echo $tipo_us; ?>" title="Busca solo usuarios de tipo <?php echo $tipo_us; ?>">
		<input type="submit" class="cambio" name="buscar_todos_sbm" value="Buscar Todos" title="Busca en todos los usuarios registrados">
		<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
	<div class='scrollbars'>
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align='center' >
		<th>Competencias</th>
		<th colspan="4">Opciones</th>	  
	</tr> 

	<?php 

		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs){
			$mensaje="No hay registros ";
			if ($buscar) $mensaje=$mensaje." con nombre $buscar";
			$mensaje=$mensaje." :(";
		}
		else
			$linea=FALSE;

			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_us=$row["cod_us"];
				$competencia=$row["competencia"];
				$inicia=$row["fecha_inicia"];
				$ar=explode("-", $fecha_inicia);
				$termina=$row["fecha_termina"];;
				$fec=fecha_desde_hasta($inicia,$termina);
				$ciudad=$row["City"];
				$pais=$row["Country"];
				$imagen=utf8_decode($row["logo_organizador"]);

				if ($linea)
					echo "<tr class='linea1'>";
				else
					echo "<tr>";
				$linea=!$linea;

    			echo "<td>";
				if ($imagen) 
	    			echo "<img class='textwrap'src='img/fotos/$imagen' width='4%'/>&nbsp;&nbsp;";
   				echo $competencia.'  '.$fec.' - '.$ciudad.'-'.$pais.'<br>'. '</a></td>';

    			echo "<td><a class='enlaces_tab' href='?op=php/cambio-usuario.php&us=$cod_us&ori=busca-usuario.php'>Modificar</a></td>";
    			echo "<td><a class='enlaces_tab' href='?op=php/competencias-usuario.php&us=$cod_us&ori=busca-usuario.php'>Competencias</a></td>";
    			echo "<td><a class='enlaces_tab' href='?op=php/saltos-usuario.php&us=$cod_us&ori=busca-usuario.php'>Saltos</a></td>";
    			echo "</tr>"; 
		}	
		$conexion->close();
	?>
	</table> 
	</div>
	<div>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo; ?>" />
		<input type="hidden" id="llamador" name="llamador_hdn" value="<?php echo $llamador; ?>" />
		<input type="hidden" id="tipo" name="tipo_hdn" value="<?php echo $tipo ?>" />
	</div>
</fieldset>
</form>
