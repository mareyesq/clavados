<div>
	<label class="rotulo">Ubicación:</label>&nbsp;&nbsp;
	<?php 
	if ($protegido){
		echo '<span class="cambio">'.$ubicacion.'</span>&nbsp;&nbsp;&nbsp;';

		echo '<input type="password" name="password_psw">';
	}

	else
		include('select-ubicacion.php');
	?>

</div>	
<?php 
if ($proteger)
	include("calificar-formulario-1.php");
else
	include('teclado.php');
?>
