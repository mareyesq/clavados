<?php 
include("funciones.php");
session_start();

if ($_GET["cco"]) {
	$cod_competencia=$_GET["cco"];
	$consulta="SELECT * FROM competencias 
		left join cities on cities.CityId=competencias.ciudad
		left join countries on countries.CountryId=cities.CountryID
		WHERE (cod_competencia=$cod_competencia)";
	$conexion=conectarse();
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0)
		$mensaje="Error: No está registrada esta competencia :/";
	else{
		$registro_competencia=$ejecutar_consulta->fetch_assoc();
		$competencia=utf8_decode($registro_competencia["competencia"]);
		$pais=$registro_competencia["Country"];
		$direccion=utf8_decode($registro_competencia["direccion_sede"]);
		$ciudad=$registro_competencia["City"];
		$inicia=$registro_competencia["fecha_inicia"];
		$termina=$registro_competencia["fecha_termina"];
		$limite=$registro_competencia["limite_inscripcion"];
		$fecha_limite=substr($limite, 0, 10);
		$hora_limite=substr($limite, 11, 5);
		$organizador=$registro_competencia["organizador"];
		$imagen=$registro_competencia["logo_organizador"];
		$telefono=$registro_competencia["telefono"];
		$email=$registro_competencia["email"];
		$competencia2=$registro_competencia["competencia2"];
		$decimales=$registro_competencia["decimales"];
		$resultados=$registro_competencia["public_result"];
		$max_2_competidores=isset($registro_competencia["max_2_competidores"])?$registro_competencia["max_2_competidores"]:NULL;
		$fecha_edad_deportiva=isset($registro_competencia["fecha_edad_deportiva"])?$registro_competencia["fecha_edad_deportiva"]:NULL;
		$convocatoria=isset($registro_competencia["convocatoria"])?$registro_competencia["convocatoria"]:NULL;
		$instructivo=isset($registro_competencia["instructivo"])?$registro_competencia["instructivo"]:NULL;
	}
}	
else {
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:NULL;
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL;
	$pais=isset($_SESSION["pais"])?$_SESSION["pais"]:NULL;
	$ciudad=isset($_SESSION["ciudad"])?$_SESSION["ciudad"]:NULL;
	$direccion=isset($_SESSION["direccion"])?$_SESSION["direccion"]:NULL;
	$inicia=isset($_SESSION["inicia"])?$_SESSION["inicia"]:NULL;
	$termina=isset($_SESSION["termina"])?$_SESSION["termina"]:NULL;
	$limite=isset($_SESSION["limite"])?$_SESSION["limite"]:NULL;
	$organizador=isset($_SESSION["organizador"])?$_SESSION["organizador"]:NULL;
	$imagen=isset($_SESSION["imagen"])?$_SESSION["imagen"]:NULL;
	$telefono=isset($_SESSION["telefono_contacto"])?$_SESSION["telefono_contacto"]:NULL;
	$email=isset($_SESSION["email_contacto"])?$_SESSION["email_contacto"]:NULL;
	$resultados=isset($_SESSION["resultados"])?$_SESSION["resultados"]:NULL;
	$decimales=isset($_SESSION["decimales"])?$_SESSION["decimales"]:NULL;
	$competencia2=isset($_SESSION["competencia2"])?$_SESSION["competencia2"]:NULL;
	$max_2_competidores=isset($_SESSION["max_2_competidores"])?$_SESSION["max_2_competidores"]:NULL;
	$fecha_edad_deportiva=isset($_SESSION["fecha_edad_deportiva"])?$_SESSION["fecha_edad_deportiva"]:NULL;
	$convocatoria=isset($_SESSION["convocatoria"])?$_SESSION["convocatoria"]:NULL;
	$instructivo=isset($_SESSION["instructivo"])?$_SESSION["instructivo"]:NULL;
}
$conexion->close();
$alta=0;
?>
<form id="edita-competencia" name="edita-frm" action="php/actualiza-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">Edita Competencia</legend>
			<?php include ("formulario-competencia.php"); ?>
		<div>
			<input type="hidden" name="cod_competencia_hdn" value="<?php echo $cod_competencia; ?>">
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Actualizar" />
			<input type="submit" id="regresar" title="Regresa a Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>
