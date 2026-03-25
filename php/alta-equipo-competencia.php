<?php 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);

	session_start();
	$logo=(isset($_SESSION["logo"])?$_SESSION["logo"]:null);
	$logo2=(isset($_SESSION["logo2"])?$_SESSION["logo2"]:null);
	if (isset($_POST["regresar_sbm"])){
		$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
		if ($llamados){
			$llamo=isset($llamados["equipos-competencia.php"])?$llamados["equipos-competencia.php"]:NULL;
		}
		unset($llamados["equipos-competencia.php"]);
		$_SESSION["llamados"]=$llamados;
		$transfer="?op=php/$llamo";
		header("Location: $transfer");
		exit();
	}

include("funciones.php");

if (!isset($competencia))
	$competencia=$_SESSION["competencia"];
if (!isset($cod_competencia))
	$cod_competencia=$_SESSION["cod_competencia"];
if (!isset($logo))
	$logo=$_SESSION["logo"];
if (!isset($logo2))
	$logo2=$_SESSION["logo2"];
if (isset($_POST["lista_clavadistas_btn"])){
	$conexion=conectarse();
	$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, logo2, City, Country
		FROM competencias 
		LEFT JOIN cities  on cities.CityId=competencias.ciudad
		LEFT JOIN countries  on countries.CountryId=cities.CountryId
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$row=$ejecutar_consulta->fetch_assoc();
	$organizador=quitar_tildes($row["organizador"]);
	$desde=$row["fecha_inicia"];
	$fec=explode("-", $desde);
	$dia_des=$fec[2];
	$mes_des=$fec[1];
	$ano_des=$fec[0];
	$hasta=$row["fecha_termina"];
	$fec=explode("-", $hasta);
	$dia_has=$fec[2];
	$mes_has=$fec[1];
	$ano_has=$fec[0];
	$subtitulo="Del ".$dia_des."-".$mes_des."-".$ano_des." al ".$dia_has."-".$mes_has."-".$ano_has;
	$subtitulo1=$row["City"]." - ".$row["Country"] ;
	$logo=$row["logo_organizador"];
	$logo2=$row["logo2"];
	include("impr-lista-clavadistas.php");
	$conexion->close();
	$transfer="Location: $llamo";
	header($transfer);
	exit();
}

if (isset($_POST["eventos_competidor_btn"])){
	$conexion=conectarse();
	$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, logo2, City, Country
		FROM competencias 
		LEFT JOIN cities  on cities.CityId=competencias.ciudad
		LEFT JOIN countries  on countries.CountryId=cities.CountryId
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$row=$ejecutar_consulta->fetch_assoc();
	$organizador=quitar_tildes($row["organizador"]);
	$desde=$row["fecha_inicia"];
	$fec=explode("-", $desde);
	$dia_des=$fec[2];
	$mes_des=$fec[1];
	$ano_des=$fec[0];
	$hasta=$row["fecha_termina"];
	$fec=explode("-", $hasta);
	$dia_has=$fec[2];
	$mes_has=$fec[1];
	$ano_has=$fec[0];
	$subtitulo="Del ".$dia_des."-".$mes_des."-".$ano_des." al ".$dia_has."-".$mes_has."-".$ano_has;
	$subtitulo1=$row["City"]." - ".$row["Country"] ;
	$logo=$row["logo_organizador"];
	$logo2=$row["logo2"];
	include("impr-eventos-competidor.php");
	$conexion->close();
	$transfer="Location: $llamo";
	header($transfer);
	exit();
}

if (isset($_POST["competidores_prueba_btn"])){
	$conexion=conectarse();
	$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, logo2, City, Country
		FROM competencias 
		LEFT JOIN cities  on cities.CityId=competencias.ciudad
		LEFT JOIN countries  on countries.CountryId=cities.CountryId
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$row=$ejecutar_consulta->fetch_assoc();
	$organizador=quitar_tildes($row["organizador"]);
	$desde=$row["fecha_inicia"];
	$fec=explode("-", $desde);
	$dia_des=$fec[2];
	$mes_des=$fec[1];
	$ano_des=$fec[0];
	$hasta=$row["fecha_termina"];
	$fec=explode("-", $hasta);
	$dia_has=$fec[2];
	$mes_has=$fec[1];
	$ano_has=$fec[0];
	$subtitulo="Del ".$dia_des."-".$mes_des."-".$ano_des." al ".$dia_has."-".$mes_has."-".$ano_has;
	$subtitulo1=$row["City"]." - ".$row["Country"] ;
	$logo=$row["logo_organizador"];
	$logo2=$row["logo2"];
	include("impr-competidores-prueba.php");
	$conexion->close();
	$transfer="Location: $llamo";
	header($transfer);
	exit();
}
	$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
	if (is_null($cod_usuario)) {
		$mensaje="Error: Debes iniciar sesión para actualizar la información :(";
		$llamo="?op=php/equipos-competencia.php&com=$competencia&cco=$cod_competencia";
		header("Location: $llamo&mensaje=$mensaje");
		exit();
	}

	if (!isset($competencia)) 
		$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:null);
	if (!isset($corto)) 
		$corto=(isset($_SESSION["corto"])?$_SESSION["corto"]:null);

	if (!isset($equipo)) 
		$equipo=(isset($_SESSION["equipo"])?$_SESSION["equipo"]:null);

	if (!isset($representante)) 
		$representante=(isset($_SESSION["representante"])?$_SESSION["representante"]:null);

	if (!isset($email)) 
		$email=(isset($_SESSION["email"])?$_SESSION["email"]:null);
	
	if (!isset($telefono)) 
		$telefono=(isset($_SESSION["telefono"])?$_SESSION["telefono"]:null);

	if (!isset($password)) 
		$password=(isset($_SESSION["password"])?$_SESSION["password"]:null);

	if (!isset($password_conf)) 
		$password_conf=(isset($_SESSION["password_conf"])?$_SESSION["password_conf"]:null);

?>
<form id="alta-equipo-comp" name="alta-equi-comp-frm" action="php/agrega-equipo-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<?php include("titulo-competencia.php") ?>
		<h2>Inscripción de Equipo</h2>
		<?php include("formulario-equipo-competencia.php"); ?>
		<div>
			<input type="submit" id="enviar-alta" class="cambio" name="enviar_btn" value="Inscribir" />
			<input type="submit" id="regresar" title="Regresa a equipos en Competencia" class="cambio" name="regresar_sbm" value="Regresar" />
			<input type="hidden" name="alta_hdn" value="alta">
		</div>
	</fieldset>
</form>