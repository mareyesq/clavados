<?php 
include("funciones.php");
if (isset($_GET["com"])){
	$competencia=(isset($_GET["com"])?$_GET["com"]:NULL);
	$cod_competencia=isset($_GET["codco"])?$_GET["codco"]:NULL;
	$equipo=(isset($_GET["eq"])?$_GET["eq"]:NULL);
	$corto=(isset($_GET["cor"])?$_GET["cor"]:NULL);
	$corto_original=$corto;
	$equipo_original=$equipo;
	$conexion=conectarse();
	$consulta="SELECT * FROM competenciasq WHERE (competencia=$cod_competencia AND nombre_corto='$corto')";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0)
		$mensaje="No está inscrito este Equipo en la competencia";
	elseif ($num_regs==1){	
		$registro=$ejecutar_consulta->fetch_assoc();
		$representante=utf8_decode($registro["representante"]);
		$email=$registro["email"];
		$telefono=$registro["telefono"];
		$password=inversa($registro["clave_inscripciones"]);
		$password_conf=$password;
		$usuario_alta=$registro["usuario_alta"];
	}
	$conexion->close();
}

session_start();
$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;

if (!isset($competencia)) 
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:null;

if (!isset($cod_competencia)) 
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null;
if (!isset($logo)) 
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:null;

if (!isset($equipo))
	$equipo=isset($_SESSION["equipo"])?$_SESSION["equipo"]:NULL;

if (!isset($corto)) 
	$corto=(isset($_SESSION["corto"])?$_SESSION["corto"]:null);

if (!isset($equipo_original)) 
	$equipo_original=(isset($_SESSION["equipo_original"])?$_SESSION["equipo_original"]:null);

if (!isset($corto_original)) 
	$corto_original=(isset($_SESSION["corto_original"])?$_SESSION["corto_original"]:null);

if (!isset($representante)) 
	$representante=(isset($_SESSION["representante"])?$_SESSION["representante"]:null);

if (!isset($email)) 
	$email=(isset($_SESSION["email"])?$_SESSION["email"]:null);

if (!isset($telefono)) 
	$telefono=(isset($_SESSION["telefono"])?$_SESSION["telefono"]:null);

if (!isset($password)) 
	$password=isset($_SESSION["password"])?$_SESSION["password"]:NULL;

if (!isset($password_conf)) 
	$password_conf=isset($_SESSION["password_conf"])?$_SESSION["password_conf"]:NULL;

if (!isset($usuario_alta)) 
	$usuario_alta=isset($_SESSION["usuario_alta"])?trim($_SESSION["usuario_alta"]):NULL;

?>
<form id="edita-evento" name="edita-frm" action="php/actualiza-equipo-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
		<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='25%'/>";
				echo $competencia; 
		?>
		</legend>
		<h3>Edita Inscripción del Equipo</h3>

		<?php include ("formulario-equipo-competencia.php") ?>
		<div>
			<br>
			<input type="submit" id="enviar-act" title="Actualiza este Equipo" class="cambio" name="actualiza_sbm" value="Actualizar Equipo" />
			&nbsp;
			<input type="submit" id="recordar" class="cambio" name="recordar_sbm" value="Recordar contraseña" title="Olvidaste la clave de Inscripción? te ayudamos recordarla" />&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" id="regresar" title="Regresa a los equipos de la competencia" class="cambio" name="regresar_sbm" value="Regresar" />
			<input type="hidden" name="corto_original_hdn" value="<?php echo $corto_original; ?>">
			<input type="hidden" name="equipo_original_hdn" value="<?php echo $equipo_original; ?>">
			<input type="hidden" name="usuario_alta_hdn" value="<?php echo $usuario_alta; ?>">
			<input type="hidden" name="alta_hdn" value="edita">
		</div>
	</fieldset>
</form>