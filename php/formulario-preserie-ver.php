		<div>
			<label for="categoria" class="rotulo">Categoría: </label>&nbsp;
			<span><?php echo "$nom_categoria"; ?></span>
			<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="hidden" name="logo_hdn" value="<?php echo $logo; ?>">
			<input type="hidden" name="categoria_hdn" value="<?php echo $categoria; ?>">
			<input type="hidden" name="modalidad_hdn" value="<?php echo $modalidad; ?>">
			<label for="modalidad" class="rotulo">Modalidad: </label>
			<span><?php echo "$nom_modalidad"; ?></span>
		</div>
		<fieldset width="50%">
		<div id="div1">
		<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
			<caption><b>Detalle de la Serie<b></caption>
			<thead align="center">
				<tr>
					<th width="1%">No.</th>
					<th width="1%">Orden</th>
					<th width="20%">Salto</th>
					<th width="2%">Grado</th>
					<th width="10%">Observación</th>
					<th width="10%">Libre</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$j=0;
					foreach ($items_ser as $i => $row) {
						$ord=isset($row["orden"])?$row["orden"]:NULL;
						$sal=isset($row["salto"])?$row["salto"]:NULL;
						$pos=isset($row["posicion"])?$row["posicion"]:NULL;
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
						echo "<td width='10%'>$gra</td>";
						echo "<td width='10%'>$obs</td>";
						echo "<td width='10%'>$lib</td>";
						echo "</tr>";
					}
				 ?>
			</tbody>
		</table>
		</div>
		</fieldset>

