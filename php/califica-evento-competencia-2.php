<?php 
	$separa_extraoficiales=0;
	$consulta="SELECT max_2_competidores 
		FROM competencias 
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);

	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1) {
		$row=$ejecutar_consulta->fetch_assoc();
		$separa_extraoficiales=$row["max_2_competidores"];
	}
 ?>

		<td width="45%"> 
				<span class="rotulo">Posiciones</span>
				<span><?php echo $nom_cat.'  '.$nom_sexo; ?></span>
				<table border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
					<tr> 
						<th>Lugar</th>	  
						<th>Clavadista</th>
						<th>Equipo</th>
						<th>Total</th>
						<th>Diferencia</th>
					</tr> 
			 		<?php 
			 		while ($reg=$ejecutar_consulta_posiciones->fetch_assoc()){
			 			$plani=$reg["cod_planilla"];
						$clavadista=$reg["nom_cla"];
						$nom_cla=primera_mayuscula_palabra($reg["nom_cla"]);
						$nom_cla2=primera_mayuscula_palabra($reg["nom_cla2"]);
						$nom_cla3=primera_mayuscula_palabra($reg["nom_cla3"]);
						$nom_cla4=primera_mayuscula_palabra($reg["nom_cla4"]);
						if (strlen($nom_cla2)>0){
							$nom_cla .= " / ".$nom_cla2;
						} 
						if (strlen($nom_cla3)>0){
							$nom_cla .= " / ".$nom_cla3;
						} 
						if (strlen($nom_cla4)>0){
							$nom_cla .= " / ".$nom_cla4;
						} 
//						$nom_cla=strtolower($nom_cla);
//						$nom_cla=ucwords($nom_cla);
						$cat=$reg["cat"];
						$equipo=$reg["equipo"];
						$part_abierta=$reg["part_abierta"];
						if ($categoria=="AB"){
							if ($cat="AB" or ($cat!=="AB" and $part_abierta="*")){
								$extraof=$reg["extraof_abierto"];
								$total=number_format($reg["total_abierta"],$dec);
								$lugar=$reg["lugar_abierta"];
							}
						}
						else{
							$extraof=$reg["extraof"];
							$total=number_format($reg["total"],$dec);
							$lugar=$reg["lugar"];
						}

						$calificado=$reg["calificado"];
						if ($lugar==1 and $extraof!=="*") 
							$puntaje_1=$total;

						$dif=number_format($puntaje_1-$total,$dec);

						echo "<tr";
						if ($calificado==1) 
							echo " class='linea1'";
						echo "><td align='center'>";
						if ($extraof=="*")
							echo "e.o.";
						else
							echo $lugar;
						echo "</td>";
						echo "<td align='center'>".$nom_cla."</td>";
						echo "<td align='center'>".$equipo."</td>";
/*   						echo "<td>&nbsp;<a tabindex = '21' class='enlaces_tab' href='?op=php/planilla.php&plan=$cod_planilla'>$total</td>";
*/						echo "<td align='right'><a tabindex = '21' class='enlaces_tab' href=JavaScript:abrir_ventana(";
						echo "'php/calificaciones.php','".$plani."'";
						echo ")>$total</a></td>";

   						echo "<td align='right'>$dif</td>";
    					echo "</tr>"; 
			 		}
	 				?>
 				</table> 
			</td>		
		</tr>
	</table>	
