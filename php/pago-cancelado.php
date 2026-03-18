<?php 
session_start();
$cod_competencia=$_SESSION["cod_competencia"];
$competencia=$_SESSION["competencia"];
$logo=$_SESSION["logo"];

$llamados=$_SESSION["llamados"];
$origen=$llamados["pagar-competencia.php"];
unset($llamados["pagar-competencia.php"]);
$_SESSION["llamados"]=$llamados;

 ?>
<form id="pago-no-ok" name="pago-no-ok-frm" action="<?php echo '?op=php/'.$origen; ?>" method="post" enctype="multipart/form-data">
	<div>
		<legend>
			<?php 
				if ($logo) 
					echo "<img class='textwrap' src='img/fotos/$logo' width='18%'/>";
		 	?>Competencia: <?php echo $competencia; ?>
		</legend>
		<br>
		<span>Sigue pendiente el pago de la competencia, su pago no fué aplicado :(</span>
	</div>
	<br>
	<div>
		<input type="submit" id="regresar" title="Regresa a la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
	</div>
</form>