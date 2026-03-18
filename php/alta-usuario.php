<?php 
session_start();
if (isset($_GET["ori"])){
	$llamo=isset($_GET["ori"])?trim($_GET["ori"]):NULL;
	if (isset($llamo))
		if (isset($_GET["com"]))
			$llamo=$llamo."&com=".$_GET["com"];
	if (isset($_GET["tipo"])){
		$tipo=isset($_GET["tipo"])?$_GET["tipo"]:NULL;
		switch ($tipo) {
			case 'A':
				$administrador="A";
				break;
			case 'E':
				$entrenador="E";
				break;
			case 'A':
				$clavadista="C";
				break;
			case 'A':
				$juez="J";
				break;
			default:
				# code...
				break;
		}
	}
	unset($_SESSION["cod_usuario"]);
	unset($_SESSION["llamo"]);
	unset($_SESSION["nombre"]);
	unset($_SESSION["sexo"]);
	unset($_SESSION["ano"]);
	unset($_SESSION["mes"]);
	unset($_SESSION["dia"]);
	unset($_SESSION["nacimiento"]);
	unset($_SESSION["administrador"]);
	unset($_SESSION["clavadista"]);
	unset($_SESSION["entrenador"]);
	unset($_SESSION["juez"]);
	unset($_SESSION["pais"]);
	unset($_SESSION["email"]);	
	unset($_SESSION["telefono"]);
	unset($_SESSION["password"]);
	unset($_SESSION["password_conf"]);
	unset($_SESSION["imagen"]);
}
	 
include ("funciones.php");
if (is_null($llamo) or $llamo=="" or $llamo==" ")
	$llamo=isset($_SESSION["llamo"])?$_SESSION["llamo"]:NULL;
if (is_null($tipo) or $tipo=="")
	$tipo=isset($_SESSION["tipo_us"])?$_SESSION["tipo_us"]:NULL;
if (!isset($nombre)) 
	$nombre=(isset($_SESSION["nombre"])?$_SESSION["nombre"]:NULL);
if (!isset($sexo)) 
	$sexo=(isset($_SESSION["sexo"])?$_SESSION["sexo"]:NULL);
if (!isset($nacimiento)) 
	$nacimiento=(isset($_SESSION["nacimiento"])?$_SESSION["nacimiento"]:NULL);
if (!isset($administrador)) 
	$administrador=(isset($_SESSION["administrador"])?$_SESSION["administrador"]:NULL);
if (!isset($clavadista)) 
	$clavadista=(isset($_SESSION["clavadista"])?$_SESSION["clavadista"]:NULL);
if (!isset($entrenador)) 
	$entrenador=(isset($_SESSION["entrenador"])?$_SESSION["entrenador"]:NULL);
if (!isset($juez)) 
	$juez=(isset($_SESSION["juez"])?$_SESSION["juez"]:NULL);
if (!isset($pais)) 
	$pais=(isset($_SESSION["pais"])?$_SESSION["pais"]:NULL);
if (!isset($email)) 
	$email=(isset($_SESSION["email"])?$_SESSION["email"]:NULL);
if (!isset($telefono)) 
	$telefono=(isset($_SESSION["telefono"])?$_SESSION["telefono"]:NULL);
if (!isset($password)) 
	$password=(isset($_SESSION["password"])?$_SESSION["password"]:NULL);
if (!isset($password_conf)) 
	$password_conf=(isset($_SESSION["password_conf"])?$_SESSION["password_conf"]:NULL);
if (!isset($imagen)) 
	$imagen=(isset($_SESSION["imagen"])?$_SESSION["imagen"]:NULL);

$alta=1;
$desde_anio=1900;
$hasta_anio=date("Y");
$ord="d";

?>
<form id="alta-usuario" name="alta-frm" action="php/agrega-usuario.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">Alta de usuario</legend>
		<?php include ("formulario-usuario.php"); ?>
		</div>
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Agregar" />
			<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" />
			<input type="hidden" name="llamo_hdn" value="<?php echo $llamo; ?>">
		</div>
	</fieldset>
</form>