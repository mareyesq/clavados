<?php 
if (isset($_GET["com"])){
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:NULL;
	$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
}

session_start();
include("funciones.php");
if ($_SESSION["autentificado"]){
	$autentificado=$_SESSION["autentificado"];
	$usuario=$_SESSION["usuario"];
	$cod_usuario=$_SESSION["usuario_id"];
	$nombre_usuario=utf8_decode($_SESSION["nombre_usuario"]);
	$administrador_sistema=$_SESSION["admin_sist"];

	$rol=defina_rol($cod_usuario);
	$administrador=((in_array("A", $rol)?"A":null));
	$clavadista=((in_array("C", $rol)?"C":null));
	$entrenador=((in_array("E", $rol)?"E":null));
	$juez=((in_array("J", $rol)?"J":null));
}
if (!isset($competencia))
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL;

if (!isset($cod_competencia))
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:NULL;
if (isset($cod_competencia)){
	$conexion=conectarse();
	$consulta="SELECT * FROM competencias WHERE (cod_competencia=$cod_competencia)";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
   	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0)
		$mensaje="No hay registros para esta consulta :(";
	else{
		$row=$ejecutar_consulta->fetch_assoc();
		$cod_ciudad=$row["ciudad"];
		$sede=ciudad_sede($cod_ciudad);
		$inicia=hora_coloquial($row["fecha_inicia"]);
		$termina=hora_coloquial($row["fecha_termina"]);
		$lim=$row["limite_inscripcion"];
		$limite=hora_coloquial($lim);
		$organizador=utf8_decode($row["organizador"]);
		$logo=$row["logo_organizador"];
		$logo2=$row["logo2"];
		$telefono=$row["telefono"];
		$email=$row["email"];
		$direccion=utf8_decode($row["direccion_sede"]);
		$resultados=$row["public_result"];
		$competencia2=utf8_decode($row["competencia2"]);
		$pagado=$row["pagado"];
		$convocatoria=isset($row["convocatoria"])?$row["convocatoria"]:NULL;
//		$instructivo="instructivo".$cod_competencia.".pdf";
		$instructivo=isset($row["instructivo"])?$row["instructivo"]:NULL;
		$en_inscripciones=limite_inscripcion($cod_competencia);
		$_SESSION["logo"]=isset($logo)?$logo:NULL;
		$_SESSION["logo2"]=isset($logo2)?$logo2:NULL;
		$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	}
	$conexion->close();
}
?>
<form id="cambio-competencia" name="cambio-competencia-frm" action="php/menu-muestra-competencia.php" method="post" enctype="multipart/form-data">

	<fieldset>
		<?php include("titulo-competencia.php") ?>
	<br><br>
	<table>
		<tr>
			<td align="right"><span class="consulta">Dirección: </span></td>
			<td><?php echo "$direccion" ?></td>
		</tr>
		<tr>
			<td align="right"><span class="consulta">Inicia el: </span></td>
			<td><?php echo "$inicia"; ?></td>
		</tr>
		<tr>
			<td align="right"><span class="consulta">Termina el: </span></td>
			<td><?php echo "$termina"; ?></td>
		</tr>
		<tr>
			<td align="right"><span class="consulta">Límite para inscripciones: </span></td>
			<td><?php echo "$limite" ?></td>
		</tr>
		<tr>
			<td align="right"><span class="consulta">Organizador: </span></td>
			<td><?php echo "$organizador" ?></td>
		</tr>
		<tr>
			<td align="right"><span class="consulta">Teléfono Contacto: </span></td>
			<td><?php echo "$telefono" ?></td>
		</tr>
		<tr>
			<td align="right"><span class="consulta">Email Contacto: </span></td>
			<td><?php echo "$email" ?> &nbsp; &nbsp;  </td>
			<td>			
				<?php 
					if ($convocatoria) {
						echo "<a target='_blank'  class='enlaces' href='img/fotos/$convocatoria'>Convocatoria</a>&nbsp;&nbsp;";
					}
					if ($instructivo) {
						echo "<a target='_blank'  class='enlaces' href='img/fotos/$instructivo'>Instructivo de la Competencia</a>&nbsp;&nbsp;";
					}
				?>
<!-- 				<a target="_blank"  class='enlaces' href="https://resources.fina.org/fina/document/2021/01/12/916f78f6-2a42-46d6-bea8-e49130211edf/2017-2021_diving_16032018.pdf">Reglamento FINA</a><br>
-->
				<a target="_blank"  class='enlaces' href="https://softneosas.com/saltos/img/rules/diving rules 2026-02-18_World-Aquatics.pdf">Reglamento World Aquatics</a><br>
 				<?php if ($competencia2!="") 
					echo "<span class='consulta'>Se realiza la competencia alterna </span>$competencia2;?><br>";
	 			?>
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="competencia_hdn" value="<?php echo $competencia; ?>">
	<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>" >
	<input type="hidden" name="logo_hdn" value="<?php echo $logo; ?>" >
	<input type="hidden" name="organizador_hdn" value="<?php echo $organizador; ?>" >
	<input type="hidden" name="email_hdn" value="<?php echo $email; ?>" >
	<?php 
		if (is_null($pagado)) {
			echo "<div>";
			echo '<input type="submit" id="pagar" title="Pagar el servicio de la Competencia" class="cambio" name="pagar_sbm" value="Pagar" />';
			echo '		<input type="submit" id="regresar" title="Regresa a competencias" class="cambio" name="regresar_sbm" value="Regresar" />';
			if ($administrador_sistema==TRUE) {
				echo '&nbsp;<input type="submit" id="autorizar" title="Aurotiza la competencia" class="cambio" name="autorizar_sbm" value="Autorizar" />';
			}
			echo "</div>";
		}
		else
			include("botones-muestra-competencia.php");
	 ?>
	</fieldset>
</form>
