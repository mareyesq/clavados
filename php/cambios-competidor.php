<script>
	window.onload=function(){
		var lista=document.getElementById("competidor-lista");
		lista.onchange=seleccionarCompetidor;

		function seleccionarCompetidor()
		{
			window.location="?op=cambios&competidor_slc="+lista.value
		}
	}

</script>
<form id="cambio-competidor" name="cambio-frm" action="php/modificar-competidor.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Cambios en Competidor</legend>
		<div>
			<label for="competidor-lista">Competidor: </label>
			<select id="competidor-lista" class="cambio" name="competidor_slc" required>
				<option value="">- - -</option>
				<?php include "select-email.php" ?>
			</select>
		</div>
		<?php 
			if($_GET["competidor_slc"]!==null){
				$conexion2=conectarse();
				$competidor=$_GET["competidor_slc"];
				$consulta_competidor="SELECT * FROM competidores WHERE email='$competidor' ";
				$ejecutar_consulta_competidor=$conexion2->query($consulta_competidor);
				$registro_principal=$ejecutar_consulta_competidor->fetch_assoc();
				include("php/cambio-competidor.php");
			}
			include("php/mensajes.php");
		 ?>
	</fieldset>
</form>