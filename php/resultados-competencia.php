<?php 
	$competencia=isset($_GET["com"])?trim($_GET["com"]):NULL;
	$cod_competencia=isset($_GET["cco"])?trim($_GET["cco"]):NULL;
	$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;

 ?>

<form id="resultados-competencia" name="resultados-competencia-frm" action="?op=php/menu-resultados-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
	<legend align="center" class="rotulo">
		<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
				echo $competencia; 
		?>
	</legend>
	<h3>Resultados de la Competencia</h3>
	<br>
	<div>
		<span>Detalle:</span><br>
		<input type="submit" id="resultados-competencia" class="cambio" name="resultados_sbm" value="Resultados" title="Resultados de la competencia por eventos" />
		<input type="submit" id="medallas-competencia" class="cambio" name="medallas_sbm" value="Medallas" title="Medallas de la competencia por eventos" />
		<input type="submit" id="calificaciones-competencia" class="cambio" name="calificaciones_sbm" value="Calificaciones" title="Calificaciones de la competencia por eventos" />
		<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		<br><span>Resumen por Puntos:</span><br>
		<input type="submit" id="puntos-categorias-sexo-competencia" class="cambio" name="puntos_cat_sexo_sbm" value="Categoría y Sexo" title="Resumen de puntos por categoría y sexo de la competencia" />
		<input type="submit" id="puntos-sexo-competencia" class="cambio" name="puntos_sexo_sbm" value="Por Sexo" title="Resumen de puntos por sexo de la competencia" />
		<input type="submit" id="puntos-categorias-competencia" class="cambio" name="puntos_cat_sbm" value="Categoría" title="Resumen de puntos por categoría de la competencia" />
		<input type="submit" id="puntos-general-competencia" class="cambio" name="puntos_gen_sbm" value="General" title="Resumen de puntos general de la competencia" />
		<input type="submit" id="puntos-deportista-competencia" class="cambio" name="puntos_deportista_sbm" value="Deportista" title="Resumen por Deportista (puntos), de la competencia" />
		<input type="submit" id="evaluacion-jueces-competencia" class="cambio" name="evaluacion_jueces_sbm" value="Evaluacion Jueces" title="Evaluación de Jueces de la competencia" />
			<br><span>Resumen por Medallas:</span><br>
		<input type="submit" id="medallas-categorias-sexo-competencia" class="cambio" name="medallas_cat_sexo_sbm" value="Categoría y Sexo" title="Resumen de medallas por categoría y sexo de la competencia" />
		<input type="submit" id="medallas-categorias-competencia" class="cambio" name="medallas_cat_sbm" value="Categoría" title="Resumen de medallas por categoría de la competencia" />
		<input type="submit" id="medallas-general-competencia" class="cambio" name="medallas_gen_sbm" value="General" title="Resumen de medallas general de la competencia" />
		<input type="submit" id="medallas-deportista-competencia" class="cambio" name="medallas_deportista_sbm" value="Deportista" title="Resumen por Deportista (medallas), de la competencia" />
</div>
<!-- 		<a target=\"_blank\" href="info/Copa_Marlins_Medallas.pdf" title=\"\">Medallas</a><br>
		<a target=\"_blank\" href="info/Copa_Marlins_Resultados_Dia_1.pdf" title=\"\">Resultados Agosto 6</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a target=\"_blank\" href="info/Copa_Marlins_Calificaciones_Dia_1.pdf" title=\"\">Calificaciones Agosto 6</a><br>
		<a target=\"_blank\" href="info/Copa_Marlins_Resultados_Dia_2.pdf" title=\"\">Resultados Agosto 7</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a target=\"_blank\" href="info/Copa_Marlins_Calificaciones_Dia_2.pdf" title=\"\">Calificaciones Agosto 7</a><br>
		<a target=\"_blank\" href="info/copa_marlins_resultados_dia_3.pdf" title=\"\">Resultados Agosto 8</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a target=\"_blank\" href="info/copa_marlins_calificaciones_dia_3.pdf" title=\"\">Calificaciones Agosto 8</a><br>
		<a target=\"_blank\" href="info/copa_marlins_resultados_dia_4.pdf" title=\"\">Resultados Agosto 9</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a target=\"_blank\" href="info/copa_marlins_calificaciones_dia_4.pdf" title=\"\">Calificaciones Agosto 9</a><br>		
		<a target=\"_blank\" href="info/copa_marlins_resultados_dia_5.pdf" title=\"\">Resultados Agosto 10</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a target=\"_blank\" href="info/copa_marlins_calificaciones_dia_5.pdf" title=\"\">Calificaciones Agosto 10</a><br>				
 -->
		<input type='hidden' name='competencia_hdn' value="<?php echo $competencia; ?>">
		<input type='hidden' name='cod_competencia_hdn' value="<?php echo $cod_competencia; ?>">
		<input type='hidden' name='logo_hdn' value="<?php echo $logo; ?>">

	</div>
	</fieldset>
</form>