<?php 
include("funciones.php");
if (isset($_GET["us"])){
	$llamo=isset($_GET["ori"])?$_GET["ori"]:NULL;
	$cod_usuario=isset($_GET["us"])?$_GET["us"]:NULL;
	if ($cod_usuario){
		$conexion=conectarse();
		$consulta="SELECT us.nombre as nombre , us.email as email, us.administrador as administrador, us.entrenador as entrenador, us.clavadista as clavadista, us.juez as juez, us.sexo as sexo, us.nacimiento as nacimiento, us.imagen as imagen, us.telefono as telefono, us.password as password, countries.Country as pais FROM usuarios as us LEFT JOIN countries ON countries.CountryId=us.pais WHERE us.cod_usuario=$cod_usuario";
		$ejecutar_consulta = $conexion->query($consulta);
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0)
			$mensaje="No está registrado este usuario";
		elseif ($num_regs==1){	
			$registro=$ejecutar_consulta->fetch_assoc();
			$nombre=utf8_decode($registro["nombre"]);
			$sexo=$registro["sexo"];
			$nacimiento=$registro["nacimiento"];
			$email=utf8_decode($registro["email"]);
			$administrador=$registro["administrador"];
			$clavadista=$registro["clavadista"];
			$entrenador=$registro["entrenador"];
			$juez=$registro["juez"];
			$telefono=$registro["telefono"];
			$pais=$registro["pais"];
			$imagen=$registro["imagen"];
			$password=inversa($registro["password"]);
			$password_conf=$password;
			$_SESSION["llamo"]	= null;
			$_SESSION["nombre"]	= null;
			$_SESSION["sexo"]=null;
			$_SESSION["nacimiento"]=null;
			$_SESSION["administrador"]=null;
			$_SESSION["clavadista"]=null;
			$_SESSION["entrenador"]=null;
			$_SESSION["juez"]=null;
			$_SESSION["pais"]=null;
			$_SESSION["email"]=null;	
			$_SESSION["telefono"]=NULL;
			$_SESSION["foto"]=NULL;
			$_SESSION["dia"]=null;
			$_SESSION["mes"]=null;
			$_SESSION["ano"]=null;
			$_SESSION["password"]=NULL;
			$_SESSION["password_conf"]=NULL;
			$_SESSION["imagen"]=NULL;
		}
		$conexion->close();
	}
}

session_start();
if (!isset($cod_usuario))
	$cod_usuario=(isset($_SESSION["cod_usuario"])?$_SESSION["cod_usuario"]:NULL);
if (is_null($llamo)) 
	$llamo=isset($_SESSION["llamo"])?$_SESSION["llamo"]:NULL;
if (!isset($nombre)) 
	$nombre=(isset($_SESSION["nombre"])?quitar_tildes($_SESSION["nombre"]):NULL);
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
if (!isset($imagen)) 
	$imagen=(isset($_SESSION["imagen"])?$_SESSION["imagen"]:NULL);
if (!isset($password)) 
	$password=(isset($_SESSION["password"])?$_SESSION["password"]:NULL);
if (!isset($password_conf)) 
	$password_conf=(isset($_SESSION["password_conf"])?$_SESSION["password_conf"]:NULL);
$alta=0;
$desde_anio=1900;
$hasta_anio=date("Y");
$ord="d";
?>
<form id="cambio-usuario" name="cambio-frm" action="php/modifica-usuario.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Modificar usuario</legend>
		<?php include ("php/formulario-usuario.php"); ?>
		<div>
			<input type="submit" id="enviar-cambio" class="cambio" name="enviar_btn" value="Actualizar" />
			<input type="submit" id="regresar" title="Regresar" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>