<?php 
include_once("funciones.php");
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$btn_exporta_detalle=(isset($_POST["exporta_detalle_sbm"])?$_POST["exporta_detalle_sbm"]:null);

session_start();
if (isset($_POST["regresar_sbm"])){
	unset($_SESSION["edita"]);
	unset($_SESSION["sorteada"]);
	unset($_SESSION["llamo"]);
	unset($_SESSION["competencia"]);
	header("Location: ?op=php/eventos-competencia.php&com=$competencia&cco=$cod_competencia");
	exit();
}
$evento=(isset($_POST["evento_hdn"])?$_POST["evento_hdn"]:null);
$numero_evento=(isset($_POST["numero_evento_hdn"])?$_POST["numero_evento_hdn"]:null);
$descripcion=(isset($_POST["descripcion_hdn"])?$_POST["descripcion_hdn"]:null);
$fecha=(isset($_POST["fecha_hdn"])?$_POST["fecha_hdn"]:null);
$primero_libres=isset($_POST["primero_libres_hdn"])?$_POST["primero_libres_hdn"]:null;
$modalidad=isset($_POST["modalidad_hdn"])?$_POST["modalidad_hdn"]:null;
$llamo=(isset($_POST["llamo_hdn"])?trim($_POST["llamo_hdn"]):null);
$llamo=str_replace("*", "&", $llamo);
$sorteada=(isset($_POST["sorteada_hdn"])?$_POST["sorteada_hdn"]:null);

if (is_null($cat)){
	$cat=trim($_SESSION["cat"]);
}
if (is_null($sx)){
	$sx=trim($_SESSION["sx"]);
}

$categorias=explode("-", $cat) ;
$sexos=explode("-", $sx);

$cat_sex=array();
foreach ($categorias as $categoria_w) {
	foreach ($sexos as $sexo_w) {
		$cat_sex[$categoria_w][$sexo_w]=0;
	}
}

$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["evento"]=$evento;
$_SESSION["numero_evento"]=$numero_evento;
$_SESSION["descripcion"]=$descripcion;
$_SESSION["fecha"]=$fecha;
$_SESSION["sorteada"]=$sorteada;
$_SESSION["modalidad"]=$modalidad;

$criterio=(isset($_POST["criterio_hdn"])?$_POST["criterio_hdn"]:null);
$criterio_sexos=(isset($_POST["criterio_sexos_hdn"])?$_POST["criterio_sexos_hdn"]:null);
$criterio_categorias=(isset($_POST["criterio_categorias_hdn"])?$_POST["criterio_categorias_hdn"]:null);

$cod_usuario=$_SESSION["usuario_id"];

if (isset($_POST["sorteo_sbm"])){
	$transfer="Location: ?op=$llamo";
	$mensaje=administrador_competencia($cod_usuario,$cod_competencia);

	if ((substr($mensaje, 0, 5)!=="Error")){
		sorteo($criterio,$criterio_sexos,$criterio_categorias,$cod_competencia,$numero_evento);
		$_SESSION["edita"]=0;
	}
	else
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["limpia_orden_sbm"])){
	$transfer="Location: ?op=$llamo";
	$mensaje=administrador_competencia($cod_usuario,$cod_competencia);

	if (substr($mensaje, 0, 5)!=="Error"){
		limpia_sorteo($criterio,$criterio_sexos,$criterio_categorias,$cod_competencia,$numero_evento);
		$_SESSION["edita"]=0;
		$_SESSION["sorteada"]=0;
	}
	else
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["editar_sbm"])){
	$transfer="Location: ?op=$llamo";
	$mensaje=administrador_competencia($cod_usuario,$cod_competencia);
	if (!(substr($mensaje, 0, 5)=="Error")){
		$conexion=conectarse();
		$consulta="SELECT DISTINCT cod_planilla, clavadista, orden_salida
		FROM planillas as p";
		$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")"."ORDER BY orden_salida";
		$ejecutar_consulta = $conexion->query($consulta);
	//	echo "consulta: $consulta<br>";print_r($conexion->error_list);exit();
		$orden = array();
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$orden[]=$row["orden_salida"];
		}
		$conexion->close();
		$_SESSION["orden"]=$orden;
		$_SESSION["edita"]=1;
		$mensaje=NULL;
	}
	else
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["guardar_sbm"])){
	$transfer="Location: ?op=$llamo";
	$mensaje=administrador_competencia($cod_usuario,$cod_competencia);
	if (!(substr($mensaje, 0, 5)=="Error")){
		$n=$_POST["participantes_hdn"];
		$clavadistas= array();
		$orden= array();
		for ($i=0; $i < $n; $i++) { 
			$name="clav".$i."_hdn";
			$clavadistas[$i]=$_POST[$name];
			$name="orden".$i."_num";
			$orden[$i]=$_POST[$name];
		}
		$_SESSION["orden"]=$orden;
		
		$mensaje=guarda_sorteo($criterio,$criterio_sexos,$criterio_categorias,$clavadistas,$orden,$numero_evento);

		if ((substr($mensaje, 0, 5)=="Error"))
			$transfer="Location: ?op=$llamo&mensaje=$mensaje";
		else{
			$_SESSION["edita"]=0;
			$_SESSION["orden"]="";
			$_SESSION["sorteada"]=1;
		}
	}
	else
		$transfer .= "&mensaje=$mensaje";
	header($transfer);
	exit();
}

if (isset($_POST["detalle_saltos_sbm"])){
	$tiempo_estimado=$_POST["tiempo_hdn"];
	$conexion=conectarse();
	$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, City, Country, logo2
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

	if ($modalidad=="E"){
		$categoria=$_SESSION["cat"];
		if ($categoria=="EQ")
			include("impr-detalle-saltos-eq.php");
		else
			include("impr-detalle-saltos-eq-ju.php");
	}
	else
		include("impr-detalle-saltos.php");
	$conexion->close();
	$transfer="Location: $llamo";
	header($transfer);
	exit();
}

if ($btn_exporta_detalle){
	include('exporta-detalle-xml.php');
}

if (isset($_POST["imprime_planilla_sbm"])){
	$transfer="Location: ?op=$llamo";
	$evento=$_POST["evento_hdn"];
	$conexion=conectarse();
	$consulta=
	"SELECT  p.cod_planilla, p.clavadista, p.competencia, p.evento, p.sexo, p.modalidad, p.equipo, p.clavadista2, p.orden_salida, p.total, p.lugar, p.total_abierta, p.lugar_abierta, c.organizador, c.logo_organizador, c.logo2, cl.nombre as nom_cla, cl2.nombre as nom_cla2, cl3.nombre as nom_cla3, cl4.nombre as nom_cla4, q.equipo as nom_equipo, cat.categoria as nom_cat, m.modalidad as nom_mod, c.fecha_inicia, c.fecha_termina, ci.City, co.Country, j.fechahora, d.ronda, d.salto, d.posicion, d.salto2, d.posicion2, d.altura, d.grado_dif, d.abierto, d.total_salto, d.puntaje_salto, d.acumulado, d.penalizado, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11, d.ejecutor, d.ejecutor2, s.salto as nom_salto, s2.salto as nom_salto2
		FROM planillas as p
		LEFT JOIN competencias as c on c.cod_competencia=p.competencia
		LEFT JOIN competenciaev as j on (j.competencia=p.competencia AND j.numero_evento=p.evento)
		LEFT JOIN usuarios as cl on cl.cod_usuario=p.clavadista
		LEFT JOIN usuarios as cl2 on cl2.cod_usuario=p.clavadista2
		LEFT JOIN usuarios as cl3 on cl3.cod_usuario=p.clavadista3
		LEFT JOIN usuarios as cl4 on cl4.cod_usuario=p.clavadista4
		LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria
		LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
		LEFT JOIN competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
		LEFT JOIN cities as ci on ci.CityId=c.ciudad
		LEFT JOIN countries as co on co.CountryId=ci.CountryId
		LEFT JOIN planillad as d on d.planilla=p.cod_planilla
		LEFT JOIN saltos as s on s.cod_salto=d.salto
		LEFT JOIN saltos as s2 on s2.cod_salto=d.salto2
		WHERE p.competencia=$cod_competencia and p.evento=$numero_evento
		ORDER BY orden_salida, ronda";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0){
		$mensaje="No hay planillas para imprimir :(";
		header($transfer."&mensaje=$mensaje");
		exit();
	}
	include("imp-planilla.php");
	$conexion->close();
	header($transfer);
	exit();
}
if (isset($_POST["jueces_sombra_sbm"])){
	$conexion=conectarse();
	$consulta=
	"SELECT  fecha_inicia, fecha_termina, organizador, logo_organizador, City, Country, logo2
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

	if ($modalidad=="E"){
		$categoria=$_SESSION["cat"];
		if ($categoria=="EQ")
			include("impr-jueces-sombra-eq.php");
		else
			include("impr-jueces-sombra-eq-ju.php");
	}
	else
		include("impr-jueces-sombra.php");
	$conexion->close();
	$transfer="Location: $llamo";
	header($transfer);
	exit();
}

?>