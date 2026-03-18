<?php 
session_start();
$cod_competencia=$_SESSION["cod_competencia"];
$competencia=$_SESSION["competencia"];
$logo=$_SESSION["logo"];
include("conexion.php");
$consulta="UPDATE competencias
	SET pagado='P' 
	WHERE cod_competencia=$cod_competencia";
$ejecutar_consulta = $conexion->query($consulta);

$llamados=$_SESSION["llamados"];
$origen=$llamados["pagar-competencia.php"];
unset($llamados["pagar-competencia.php"]);
$_SESSION["llamados"]=$llamados;
?>
<form id="pago-ok" name="pago-ok-frm" action="<?php echo '?op=php/'.$origen; ?>" method="post" enctype="multipart/form-data">
	<div>
		<legend>
			<?php 
				if ($logo) 
					echo "<img class='textwrap' src='img/fotos/$logo' width='18%'/>";
	 		?>Competencia: <?php echo $competencia; ?>
		</legend>
		<br>
		<span class='rotulo'>Su pago fué aplicado satisfactoriamente. Puede configurar su competencia :)</span>
	</div>
	<br>
	<div>
		<input type="submit" id="regresar" title="Regresa a la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
</form>