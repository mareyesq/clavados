<?php 
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$encabezado=isset($_POST["encabezado_hdn"])?trim($_POST["encabezado_hdn"]):NULL;
$encabezado1=isset($_POST["encabezado1_hdn"])?trim($_POST["encabezado1_hdn"]):NULL;
$numero_evento=isset($_POST["numero_evento_hdn"])?trim($_POST["numero_evento_hdn"]):NULL;
$evento=isset($_POST["evento_hdn"])?trim($_POST["evento_hdn"]):NULL;
$fechahora=isset($_POST["fechahora_hdn"])?trim($_POST["fechahora_hdn"]):NULL;
$modalidad=isset($_POST["modalidad_hdn"])?trim($_POST["modalidad_hdn"]):NULL;
$cat=isset($_POST["cat_hdn"])?trim($_POST["cat_hdn"]):NULL;
$sexos=isset($_POST["sexos_hdn"])?trim($_POST["sexos_hdn"]):NULL;
$tipo=isset($_POST["tipo_hdn"])?trim($_POST["tipo_hdn"]):NULL;
$descripcion=isset($_POST["descripcion_hdn"])?trim($_POST["descripcion_hdn"]):NULL;
$fecha=isset($_POST["fecha_hdn"])?trim($_POST["fecha_hdn"]):NULL;
$hora=isset($_POST["hora_hdn"])?trim($_POST["hora_hdn"]):NULL;
$origen=isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL;

session_start();
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["competencia"]=$competencia;
$_SESSION["encabezado"]=$encabezado;
$_SESSION["encabezado1"]=$encabezado1;
$_SESSION["numero_evento"]=$numero_evento;
$_SESSION["evento"]=$evento;
$_SESSION["fechahora"]=$fechahora;
$_SESSION["modalidad"]=$modalidad;
$_SESSION["cat"]=$cat;
$_SESSION["sexos"]=$sexos;
$_SESSION["tipo"]=$tipo;
$_SESSION["descripcion"]=$descripcion;
$_SESSION["fecha"]=$fecha;
$_SESSION["hora"]=$hora;
$_SESSION["origen"]=$origen;

include("funciones.php");
$dec=decimales($cod_competencia);
$conexion=conectarse();

if (isset($_POST["imprime_resultados_btn"])){
	$separa_extraoficiales=0;
	$consulta="SELECT max_2_competidores 
		FROM competencias 
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);

	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1) {
		$row=$ejecutar_consulta->fetch_assoc();
		$separa_extraoficiales=$row["max_2_competidores"];
	}

	$consulta_posiciones="SELECT DISTINCT p.cod_planilla, p.equipo, p.orden_salida, p.total, p.lugar, p.extraof, p.categoria, p.sexo, p.extraof, CONCAT (p.categoria, p.sexo) as orden_categorias, cat.categoria as nom_cat, c.nombre as nom_cla, c2.nombre as nom_cla2, c3.nombre as nom_cla3, c4.nombre as nom_cla4, m.modalidad as nom_mod, m.individual, r.marca_damas, r.grado_damas, r.promedio_damas, r.marca_varones, r.grado_varones, r.promedio_varones, t.puntos, t.puntos_sinc, q.equipo as nom_equipo
		FROM planillas as p
		LEFT JOIN competenciasm as r on r.competencia=p.competencia and r.categoria=p.categoria and r.modalidad=p.modalidad";
	$consulta_posiciones.=" LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
		LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
		LEFT JOIN usuarios as c3 on c3.cod_usuario=p.clavadista3
		LEFT JOIN usuarios as c4 on c4.cod_usuario=p.clavadista4
		LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria
		LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
		LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar
		LEFT JOIN competenciasq	as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
		WHERE p.competencia=$cod_competencia
			AND p.evento=$numero_evento
			AND p.categoria<>'AB'
			AND p.usuario_retiro IS NULL";
	$consulta_posiciones.=" ORDER BY p.categoria, p.sexo, p.extraof, p.lugar";
	$ejecutar_consulta_posiciones = $conexion->query(utf8_decode($consulta_posiciones));

	$consulta_abierta="SELECT DISTINCT p.cod_planilla, p.equipo, p.orden_salida, p.total_abierta, p.part_abierta, p.lugar_abierta, p.extraof_abierto, p.sexo, m.modalidad as nom_mod,  m.individual, r.marca_damas, r.grado_damas, r.promedio_damas, r.marca_varones, r.grado_varones, r.promedio_varones, t.puntos, t.puntos_sinc, q.equipo as nom_equipo";

	$consulta_abierta.=", c.nombre as nom_cla, c2.nombre as nom_cla2
		FROM planillas as p
		LEFT JOIN competenciasm as r on r.competencia=p.competencia and r.categoria=p.categoria and r.modalidad=p.modalidad";

	$consulta_abierta.=" LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
		LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
		LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
		LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar_abierta
		LEFT JOIN competenciasq	as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
		WHERE p.competencia=$cod_competencia
			AND p.evento=$numero_evento 		
			AND (p.part_abierta='*' or p.categoria='AB')
			AND p.usuario_retiro IS NULL";
	$consulta_abierta.=" ORDER BY p.sexo, p.extraof_abierto, p.lugar_abierta";
	$ejecutar_consulta_abierta = $conexion->query(utf8_decode($consulta_abierta));

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
	include("impr-resultados-evento.php");
	$transfer="Location: $llamo";
	header($transfer);
	$conexion->close();
	exit();
}

if (isset($_POST["imprime_medallistas_btn"])){
	$separa_extraoficiales=0;
	$consulta="SELECT max_2_competidores 
		FROM competencias 
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);

	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1) {
		$row=$ejecutar_consulta->fetch_assoc();
		$separa_extraoficiales=$row["max_2_competidores"];
	}

	$consulta_posiciones="SELECT DISTINCT p.cod_planilla, p.equipo, p.orden_salida, p.total, p.lugar, p.extraof, p.categoria, p.sexo, p.extraof, CONCAT (p.categoria, p.sexo) as orden_categorias, cat.categoria as nom_cat, c.nombre as nom_cla, c2.nombre as nom_cla2, c3.nombre as nom_cla3, c4.nombre as nom_cla4, m.modalidad as nom_mod
		FROM planillas as p ";
	$consulta_posiciones.=" LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
		LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
		LEFT JOIN usuarios as c3 on c3.cod_usuario=p.clavadista3
		LEFT JOIN usuarios as c4 on c4.cod_usuario=p.clavadista4
		LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria
		LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
		WHERE p.competencia=$cod_competencia
			AND p.evento=$numero_evento
			AND p.categoria<>'AB'
			AND p.usuario_retiro IS NULL";
	$consulta_posiciones.=" ORDER BY p.categoria, p.sexo, p.extraof, p.lugar";
	$ejecutar_consulta_posiciones = $conexion->query(utf8_decode($consulta_posiciones));

	$consulta_abierta="SELECT DISTINCT p.cod_planilla, p.equipo, p.orden_salida, p.total_abierta, p.part_abierta, p.lugar_abierta, p.extraof_abierto, p.sexo, m.modalidad as nom_mod";

	$consulta_abierta.=", c.nombre as nom_cla, c2.nombre as nom_cla2
		FROM planillas as p";

	$consulta_abierta.=" LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
		LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
		LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
		WHERE p.competencia=$cod_competencia
			AND p.evento=$numero_evento 		
			AND (p.part_abierta='*' or p.categoria='AB')
			AND p.usuario_retiro IS NULL";
	$consulta_abierta.=" ORDER BY p.sexo, p.extraof_abierto, p.lugar_abierta";
	$ejecutar_consulta_abierta = $conexion->query(utf8_decode($consulta_abierta));

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

	include("impr-medallistas-evento.php");
	$transfer="Location: $llamo";
	$conexion->close();
	header($transfer);
	exit();
}

?>