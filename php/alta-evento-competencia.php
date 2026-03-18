<?php 
	include("funciones.php");

	$competencia=isset($_GET["com"])?trim($_GET["com"]):NULL;
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:NULL;
	if (!isset($competencia)) 
		$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);

	session_start();
	if (isset($_POST["regresar_sbm"])){
		$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
		if ($llamados){
			$llamo=isset($llamados["eventos-competencia.php"])?$llamados["eventos-competencia.php"]:NULL;
		}
		unset($llamados["eventos-competencia.php"]);
		$_SESSION["llamados"]=$llamados;
		$transfer="?op=php/$llamo";
		header("Location: $transfer");
		exit();
	}


	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
	if (is_null($cod_usuario)) {
		$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
		$llamo="?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);
	if (!isset($cod_competencia)) 
		$cod_competencia=(isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null);

	$es_administrador_competencia=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($es_administrador_competencia,0,5)=="Error"){
		$mensaje=$es_administrador_competencia;
		$llamo="?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}
	
	if (!isset($fecha)) 
		$fecha=(isset($_SESSION["fecha"])?$_SESSION["fecha"]:null);

	if (isset($fecha)){
		$car=explode("-", $fecha);
		$ano=$car[0];
		$mes=$car[1];
		$dia=$car[2];
		$fecha=$ano."-".$mes."-".$dia;
	}

	if (!isset($hora)) {
		$hora=(isset($_SESSION["hora"])?$_SESSION["hora"]:null);
	}

	if (!isset($modalidad)) 
		$modalidad=(isset($_SESSION["modalidad"])?$_SESSION["modalidad"]:null);

	if (!isset($categorias)) 
		$categorias=(isset($_SESSION["categorias"])?$_SESSION["categorias"]:null);

	if (!isset($sexos)) 
		$sexos=isset($_SESSION["sexos"])?$_SESSION["sexos"]:NULL;
	if (!isset($tipo)) 
		$tipo=isset($_SESSION["tipo"])?$_SESSION["tipo"]:NULL;
	if (!isset($primero_libres)) 
		$primero_libres=isset($_SESSION["primero_libres"])?$_SESSION["primero_libres"]:NULL;
	if (!isset($usa_dispositivos)) 
		$usa_dispositivos=isset($_SESSION["usa_dispositivos"])?$_SESSION["usa_dispositivos"]:NULL;
	if (!isset($calentamiento)) 
		$calentamiento=isset($_SESSION["calentamiento"])?$_SESSION["calentamiento"]:NULL;

	$alta=1;
	$desde_anio=1900;
	$hasta_anio=date("Y");
	$ord="d";

?>
<form id="alta-evento" name="alta-frm" action="php/agrega-evento-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center">Registro de Eventos de la Competencia: <?php echo $competencia; ?></legend>
		<?php include("formulario-evento-competencia.php"); ?>

		<div>
			<br>
			<input type="submit" id="enviar-alta" title="Registra este Evento de la Competencia" class="cambio" name="registrar_sbm" value="Registrar Prueba" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a la Competencia" class="cambio" name="regresar_sbm" value="Regresar" >
		</div>
	</fieldset>
</form>