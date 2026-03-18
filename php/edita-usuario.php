<?php 
include("conexion.php");
include("funciones.php");
$llamador="edita-usuario.php";

session_start();
if (isset($_GET["email"])){
	$email=isset($_GET["email"])?$_GET["email"]:NULL;
	$consulta="SELECT DISTINCT 
		u.cod_usuario, u.nombre, u.pais, u.administrador, u.entrenador,
		u.clavadista, u.juez, u.sexo, u.nacimiento, u.imagen, 
		u.telefono, u.password, p.Country 
		from usuarios as u 
		left join countries as p on p.CountryId=u.pais
		WHERE (u.email='$email')";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0)
		$mensaje="No está registrado el usuario con email: <b>$email<b> :(";
	elseif ($num_regs==1){	
		$registro=$ejecutar_consulta->fetch_assoc();
		$cod_usuario=$registro["cod_usuario"];
		$nombre=$registro["nombre"];
		$sexo=$registro["sexo"];
		$nacimiento=$registro["nacimiento"];
		$administrador=$registro["administrador"];
		$clavadista=$registro["clavadista"];
		$entrenador=$registro["entrenador"];
		$juez=$registro["juez"];
		$pais=$registro["Country"];
		$telefono=$registro["telefono"];
		$passw=inversa($registro["password"]);
		$passw_conf=$password;
		$imagen=$registro["imagen"];
	}
}

session_start();
if (!isset($cod_usuario)) 
	$cod_usuario=isset($_SESSION["cod_usuario"])?$_SESSION["cod_usuario"]:null;

if (!isset($nombre)) 
	$nombre=isset($_SESSION["nombre"])?$_SESSION["nombre"]:null;

if (!isset($sexo)) 
	$sexo=isset($_SESSION["sexo"])?$_SESSION["sexo"]:null;

if (!isset($nacimiento))
	$nacimiento=isset($_SESSION["nacimiento"])?$_SESSION["nacimiento"]:NULL;

if (!isset($administrador)) 
	$administrador=(isset($_SESSION["administrador"])?$_SESSION["administrador"]:null);

if (!isset($clavadista)) 
	$clavadista=(isset($_SESSION["clavadista"])?$_SESSION["clavadista"]:null);

if (!isset($entrenador)) 
	$entrenador=(isset($_SESSION["entrenador"])?$_SESSION["entrenador"]:null);

if (!isset($juez)) 
	$juez=(isset($_SESSION["juez"])?$_SESSION["juez"]:null);

if (!isset($pais)) 
	$pais=(isset($_SESSION["pais"])?$_SESSION["pais"]:null);

if (!isset($email)) 
	$email=(isset($_SESSION["email"])?$_SESSION["email"]:null);

if (!isset($telefono)) 
	$telefono=(isset($_SESSION["telefono"])?$_SESSION["telefono"]:null);

if (!isset($passw)) 
	$passw=(isset($_SESSION["passw"])?$_SESSION["passw"]:null);

if (!isset($password_conf)) 
	$passw_conf=(isset($_SESSION["passw_conf"])?$_SESSION["passw_conf"]:null);

if (!isset($imagen)) 
	$imagen=(isset($_SESSION["imagen"])?$_SESSION["imagen"]:null);

$alta=0;
$desde_anio=1900;
$hasta_anio=date("Y");
$ord="d";

?>
<form id="edita-usuario" name="edita-frm" action="php/actualiza-usuario.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend class="cambio">Edita usuario:</legend>
		<?php include ("formulario-usuario.php") ?>
		<div>
			<br>
			<input type="submit" id="enviar-act" title="Actualiza datos" class="cambio" name="actualiza_sbm" value="Actualizar" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa " class="cambio" name="regresar_sbm" value="Regresar" />
			<input type="hidden" name="cod_usuario_hdn" value="<?php echo $cod_usuario; ?>" >
		</div>
	</fieldset>
</form>