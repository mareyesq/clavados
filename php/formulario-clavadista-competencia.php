<?php 
	if ($alta==1) 
		$enlace="php/alta-clavadista-competencia.php";
	else
		$enlace="php/edita-clavadista-competencia.php";

	$cod_categoria=isset($categoria)?substr($categoria, 0, 2):NULL;
	$mixto=NULL;

	if ($cod_clavadista) {
		$edad=edad_usuario($cod_clavadista,$conexion);
		if ($sexo=="M") 
			$desc_sexo="Varón";
		else
			if ($sexo=="F") 
				$desc_sexo="Dama";
			else
				$desc_sexo="";
	}
	
	if ($cod_clavadista2) {
		$edad2=edad_usuario($cod_clavadista2,$conexion);
		if ($sexo2=="M") 
			$desc_sexo2="Varón";
		else
			if ($sexo2=="F") 
				$desc_sexo2="Dama";
			else
				$desc_sexo2="";
		if ($sexo and $sexo2)
			if ($sexo!=$sexo2) 
				$mixto=1;
	}

	if ($cod_clavadista3) {
		$edad3=edad_usuario($cod_clavadista3,$conexion);
		if ($sexo3=="M") 
			$desc_sexo3="Varón";
		else
			if ($sexo3=="F") 
				$desc_sexo3="Dama";
			else
				$desc_sexo2="";
		if ($sexo and $sexo2)
			if ($sexo!=$sexo2 or $sexo!=$sexo3) 
				$mixto=1;
	}

	if ($cod_clavadista4) {
		$edad4=edad_usuario($cod_clavadista4,$conexion);
		if ($sexo4=="M") 
			$desc_sexo4="Varón";
		else
			if ($sexo4=="F") 
				$desc_sexo4="Dama";
			else
				$desc_sexo4="";
		if ($sexo and $sexo2 and $sexo3 and $sexo4)
			if ($sexo!=$sexo2 or $sexo!=$sexo3 or $sexo!=$sexo4 
				or $sexo2!=$sexo3 or $sexo2!=$sexo4 
				or $sexo3!=$sexo4) 
				$mixto=1;
	}

 	echo "<br><h3 class='rotulo' align='center'>Inscripción ";
 	

	if ($individual)	
		echo "Individual de Clavadista";

	if ($pareja)	
		if ($mixto) 
			echo " de Pareja Mixta de Clavadistas";		
		else
			echo " de Pareja de Clavadistas";		

	if ($eq_juv)	
		echo " de Equipo Juvenil Mixto";		

	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Equipo: $equipo</h3>";

 ?>
		<div>
			<input type="hidden" id="competencia" name="competencia_hdn"  value="<?php echo "$competencia"; ?>"/>
			<input type="hidden" id="cod_competencia" name="cod_competencia_hdn"  value="<?php echo "$cod_competencia"; ?>"/>
			<input type="hidden" id="logo" name="logo_hdn"  value="<?php echo "$logo"; ?>"/>
			<input type="hidden" id="equipo" name="equipo_txt"value="<?php echo "$equipo"; ?>"/>
		</div>
		<div>
			<label for="clavadista"  class="rotulo">Clavadista 1: </label>
			<input type="text" id="clavadista" class="cambio" name="clavadista_txt" size="50" maxlength="50" placeholder="Si ya está registrado, escribe parte del nombre y Click en Buscar" title="nombre completo del clavadista" value="<?php echo $clavadista; ?>"> 
			<?php 
				if ($alta) {
					echo '<input type="submit" id="busca_clavadista" name="busca_clavadista_sbm" value="Buscar" title="Busca el clavadista">&nbsp;
						<input type="submit" id="nuevo_clavadista" name="nuevo_clavadista_sbm" value="Nuevo" title="Regístrate como clavadista">';
				}
				if ($imagen) 
	    			echo "<img src='img/fotos/$imagen' width='5%' height='70'/>&nbsp;&nbsp;";
    			if ($cod_clavadista) {
					echo '<span class="cambio">'.$desc_sexo.'</span>
						<span class="cambio">'.$edad." años".'</span>'; 
				}
 			?>
			<input type="hidden" name="cod_clavadista_hdn" value="<?php echo $cod_clavadista; ?>">
			<input type="hidden" name="imagen_hdn" value="<?php echo $imagen; ?>">
			<input type="hidden" name="llamo_hdn" value="<?php echo $llamo; ?>">
			<input type="hidden" name="origen_txt" value="<?php echo $enlace; ?>">
			<input type="hidden" name="individual_hdn" value="<?php echo $individual; ?>">
			<input type="hidden" name="pareja_hdn" value="<?php echo $pareja; ?>">
			<input type="hidden" name="eq_juv_hdn" value="<?php echo $eq_juv; ?>">
			<input type="hidden" name="edad" value="<?php echo $edad; ?>">
		</div>
		<?php 
			if ($pareja==1) include("formulario-clavadista2.php");
			if ($eq_juv) include("formulario-clavadista2_3_4.php");
		 ?>
		<div>
			<label for="categoria" class="rotulo">Categoría:&nbsp;&nbsp;&nbsp;</label>
			<?php 
				if ($alta) {
					if ($eq_juv){
						$categoria="Q1-Equipo Juvenil";
						echo '&nbsp;<input type="text" id="categoria" class="cambio" name="categoria_slc" size="35" title="Categoría a Competir" value="'.$categoria.'" readonly/>';
					}
					else{
						echo '<select id="categoria" class="cambio" name="categoria_slc" onChange="this.form.submit()" title="selecciona la categoría en que va a participar"  >
						<option value="" >- - -</option>';
						include ("php/select-categoria-competencia.php");
						echo '</select>';
					}
				}
				else
					echo '<input type="text" id="categoria" class="cambio" name="categoria_slc" size="35" title="Categoría a Competir" value="'.$categoria.'" readonly/>';
					
			 ?>
			<label for="entrenador" class="rotulo">Entrenador: </label>
			<input type="text" id="entrenador" class="cambio" name="entrenador_txt" placeholder="Si ya está registrado, escribe el nombre y Click en Buscar" title="nombre completo del entrenador" size="50" maxlength="50" value="<?php echo "$entrenador"; ?>">
			<input type="hidden" name="cod_entrenador_hdn" value="<?php echo $cod_entrenador; ?>">
			<input type="submit" id="busca_entrenador" name="busca_entrenador_sbm" value="Buscar" title="Busca el entrenador">
			<input type="submit" id="nuevo_entrenador" name="nuevo_entrenador_sbm" value="Nuevo" title="Regístrate como entrenador">

		</div>

		<div>
			<label for="s"  class="rotulo">Modalidades: </label>
			<?php 
//				if ($cod_categoria)
//					include("php/radio-modalidades-competencia.php"); 
//				else
					include("radio-modalidades.php"); 
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="hidden" name="modalidades_hdn" value="<?php echo $modalidades; ?>">
			<label for="clave" class="rotulo">Clave Inscripción: </label>
			<input type="password" id="clave" class="cambio" name="clave_txt" placeholder="Escribe la clave de inscripción" title="Clave de inscripción de tu equipo para esta competencia" value="<?php echo $clave; ?>" />
		</div>
		