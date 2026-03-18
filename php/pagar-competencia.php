<?php 
session_start();
if (isset($_GET["com"])) {
	$competencia=$_GET["com"];
	$cod_competencia=$_GET["cco"];
	$logo=$_GET["lg"];
	$_SESSION["cod_competencia"]=$cod_competencia;
	$_SESSION["competencia"]=$competencia;
	$_SESSION["logo"]=$logo;
}
?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<div>
		<legend>
			<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='18%'/>";
		 	?>Competencia: <?php echo $competencia; ?>
		</legend>
		<br><br>&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="8L2LJX998XQL4">
		<input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
		<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">

		<script src="https://www.paypal.com/sdk/js?client-id=test"></script>
		<script>paypal.Buttons().render('body');</script>

	</div>
</form>
