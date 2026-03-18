		<div>
			<label for="categoria" class="rotulo">Categoría: </label>&nbsp;
			<select id="categoria" name="categoria_slc" class="cambio" onChange='this.form.submit()'>
				<option value="">- - -</option>	
				<?php include("php/selector-categoria-competencia.php");?>
			</select>
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="logo_hdn" value="<?php echo $logo; ?>">
		</div>
		<div>
			<label for="modalidad" class="rotulo">Modalidad: </label>
			<select id="modalidad" name="modalidad_slc" class="cambio" > 
				<option value="">- - -</option>	
				<?php 
					$cod_modalidad=$modalidad;
					include("php/select-modalidad-categoria.php");
				?>
			</select>
		</div>
		<fieldset width="50%">
		<fieldset>
		<div>
			<label for="orden"  class='rotulo'>Orden: </label>
			<input type="number" min="1" max="15" step="1" name="orden_nbr" value="<?php echo "$orden"; ?>" >
			<select id='salto' class='cambio' name='salto_slc' title='Selecciona el salto'>
				<option value="">- - -</option>	
				<?php include("select-salto.php"); ?>
			</select>
			<label for="posicion"  class="rotulo">Posición: </label>
			<select id='posicion' class='cambio' name='posicion_slc' title='Posición de ejecución del salto'>
			<option value=''>- - -</option>";
			<option value='A' <?php if ($posicion=='A') echo ' selected'; ?>>A</option>";
			<option value='B' <?php if ($posicion=="B") echo " selected"; ?>>B</option>";
			<option value='C' <?php if ($posicion=="C") echo " selected"; ?>>C</option>";
			<option value='D' <?php if ($posicion=="D") echo " selected"; ?>>D</option>";
			</select>
			<label for="altura"  class="rotulo">Altura: </label>
			<select name='altura.nbr' align='center' >  
				<option ></option> 
				<option value="0" <?php if ($altura==0) echo " selected" ?>>0</option> 
				<option value="0.75" <?php if ($altura==0.75) echo " selected"; ?>>0.75</option> 
				<option value="1" <?php if ($altura==1) echo " selected"; ?>>1</option> 
				<option value="2" <?php if ($altura==2) echo " selected"; ?>>2</option> 
				<option value="3" <?php if ($altura==3) echo " selected";?>>3</option> 
				<option value="5" <?php if ($altura==5) echo " selected"; ?>>5</option> 
				<option value="7.5" <?php if ($altura==7.5) echo " selected";?>>7.5</option> 
				<option value="10" <?php if ($altura==10) echo " selected";  ?>>10</option> 
			</select>
			<?php 
/*				if ($altura==3) {
					include('formulario-preserie-plataforma.php');
				}
*/			 ?>
			<label for="grado"  class='rotulo'>Grado Dif.: </label>
			<input type="number" min="0.0" max="9.9" step="0.1" name="grado_nbr" placeholder="0.0" title="Grado de dificultad del salto" value="<?php echo "$grado"; ?>">
		</div>
		<div>
			<label for="observacion"  class='rotulo'>Observación: </label>
			<input id="observacion" type="text" maxlength="30" size="20" name="observacion_txt" value="<?php echo "$observacion"; ?>">
			<label for="libre"  class='rotulo'>Libre: </label>

			<input id="libre" type="checkbox" name="libre_chk" title="Señale si puede hacer cualquier salto" 
			<?php if ($libre) echo " checked"; ?>>

			<?php 
				if ($item_modificar) {
					echo "<input type='submit' class='cambio' name='modifica_salto_sbm' value='Modifica Item $item_modificar' title='Modifica item de serie de saltos $item_modificar'>";
					echo "<input type='hidden' name='item_modificar_hdn' value='".$item_modificar."' >";
					echo "<input type='hidden' name='indice_hdn' value='".$indice."' >";
				}
				else
					echo "<input type='submit' class='cambio' name='agrega_salto_sbm' value='Agrega Salto' title='Agrega Salto a la serie predefinida'>";
			 ?>
			<input type="submit" class="cambio" name="reset_sbm" value="Reset" title="Limpia lista de Saltos">
		</div>
		</fieldset>
		<div id="div1">
		<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
			<caption><b>Detalle de la Serie<b></caption>
			<thead align="center">
				<tr>
					<th width="1%">No.</th>
					<th width="1%">Orden</th>
					<th width="20%">Salto</th>
					<th width="5">Altura</th>
<!-- 					<th width="5">Plataforma</th>
 -->					<th width="2%">Grado</th>
					<th width="10%">Observación</th>
					<th width="10%">Libre</th>
					<th width="1%" colspan="4">Opciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if ($alta)
						$enlace="php/alta-preserie-competencia.php";
					else
						$enlace="php/edita-preserie-competencia.php";
					$j=0;
					$aforo=NULL;
					foreach ($items_ser as $i => $row) {
						$ord=isset($row["orden"])?$row["orden"]:NULL;
						$sal=isset($row["salto"])?$row["salto"]:NULL;
						$pos=isset($row["posicion"])?$row["posicion"]:NULL;
						$alt=isset($row["altura"])?$row["altura"]:NULL;
						$plt=isset($row["plataforma"])?$row["plataforma"]:NULL;
						$plt=($plt)?'*':NULL;
						$gra=isset($row["grado"])?$row["grado"]:NULL;
						if ($gra)
							$gra=number_format($gra,1);
						$obs=isset($row["observacion"])?$row["observacion"]:NULL;
						$lib=isset($row["libre"])?"Sí":NULL;
						$j++;
						echo "<tr align='center'>";
						echo "<td width='1%'>$j</td>";
						echo "<td width='20%'>$ord</td>";
						echo "<td width='10%'>$sal-$pos</td>";
						echo "<td width='5%'>$alt</td>";
//						echo "<td width='5%'>$plt</td>";
						echo "<td width='10%'>$gra</td>";
						echo "<td width='10%'>$obs</td>";
						echo "<td width='10%'>$lib</td>";
		    			echo "<td><a class='enlaces_tab' href='?op=php/preserie-item-edita.php&ind=$i&it=$j&ori=$enlace'>Modificar</a></td>";
    					echo "<td><a class='enlaces_tab' href='?op=php/preserie-item-baja.php&ind=$i&it=$j&ori=$enlace'>Eliminar</a></td>";
						echo "</tr>";
					}
				 ?>
			</tbody>
		</table>
		</div>
		</fieldset>

