<?php 
if (isset($_GET["com"])) {
	$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:NULL;
	$llamo=isset($_GET["ori"])?$_GET["ori"]:NULL;
}
$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;

session_start();
if (!$competencia)
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL;

if (!$cod_competencia)
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:NULL;

if (!$logo)
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
if (!$logo2)
	$logo2=isset($_SESSION["logo2"])?$_SESSION["logo2"]:NULL;

if (!$llamo)
	$llamo=$_SESSION["llamo"];

include("funciones.php");
$conexion=conectarse();
$consulta="SELECT distinct competenciasa.principal as principal, usuarios.nombre as nom_admin, usuarios.imagen, usuarios.email, competenciasa.administrador as cod_usuario 
	FROM competenciasa 
	left join usuarios on usuarios.cod_usuario=competenciasa.administrador
WHERE (competenciasa.competencia=$cod_competencia) 
ORDER BY nom_admin";

$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
?>

<form id="administradores-competencia" name="admin-competencia-frm" action="?op=php/alta-administrador-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
	<h2>Administradores de la Competencia</h2>
	<div id="div1">
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
		<tr align="center">
			<th>Administrador</th>
			<th>Contacto</th>
			<th>Principal</th>	  
			<th>Opciones</th>	  
		</tr> 
	<?php 
		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs)
			$mensaje="No hay Administradores registrados en la competencia $competencia :(";
		else
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$administrador=quitar_tildes(utf8_decode($row["nom_admin"]));
			$email=utf8_decode($row["email"]);
			$principal=utf8_decode($row["principal"]);
			$imagen=$row["imagen"];
    		$cod_usuario=$row["cod_usuario"];
    		echo "<tr align='center'>";
    		echo "<td align='left'>";
			if ($imagen) 
    			echo "<img class='textwrap'src='img/fotos/$imagen' width='5%'/>&nbsp;&nbsp;";
    		echo $administrador."</td>";
    		echo "<td><a href='mailto:$email'>$email</a></td>";
    		echo "<td>";
    		if ($principal==1) 
    			echo "*";
    		else
    			echo " ";	
    		echo "</td>";  
    		echo "<td align='center'><a href='?op=php/baja-administrador-competencia.php&com=$competencia&ad=$administrador&codco=$cod_competencia&codus=$cod_usuario'>Eliminar<td>";
    		echo "</tr>"; 
		}	
		$conexion->close();
	?>
	</table> 
	</div>
	<div>
		<br>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia ?>" />
		<input type="hidden" id="cod-competencia" name="cod_competencia_hdn" value="<?php echo $cod_competencia ?>" />
		<input type="submit" id="nuevo" class="cambio" name="registrar_administrador_btn" value="Registra Administrador" />
		<input type='submit' id='regresar' title='Regresar' class='cambio' name='regresar_sbm' value='Regresar' />
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo ?>" />
	</div>
	</fieldset>
</form>