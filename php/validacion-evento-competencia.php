<?php 
	$ok=TRUE;

if(navegador_compatible("iPhone") || navegador_compatible("iPod")  || !navegador_compatible("chrome")){
	$dia=isset($_POST["dia_slc"]) ? $_POST["dia_slc"] : NULL;
	$nom_mes=isset($_POST["mes_slc"]) ? $_POST["mes_slc"] : NULL;
	$ano=isset($_POST["ano_slc"]) ? $_POST["ano_slc"] : NULL;
	$mes=mes_num($nom_mes);
	$dia+=100;
	$dia=substr($dia,1,2);
	$mes+=100;
	$mes=substr($mes,1,2);
	$fecha="$ano-$mes-$dia";
	$hh=isset($_POST["hh_slc"]) ? $_POST["hh_slc"] : NULL;
	$mm=isset($_POST["mm_slc"]) ? $_POST["mm_slc"] : NULL;
	$am=isset($_POST["am_slc"]) ? $_POST["am_slc"] : NULL;
	if ($am=="pm")
		$hh=$hh+12;
	$hh+=100;
	$hh=substr($hh,1,2);
	$mm+=100;
	$mm=substr($mm,1,2);
}
else{
	$fecha=isset($_POST["fecha_txt"]) ? $_POST["fecha_txt"] :NULL;
	$cadenas = explode("-", $fecha);
	$ano=$cadenas[0];
	$mes=isset($cadenas[1])?$cadenas[1]:NULL;
	$dia=isset($cadenas[2])?$cadenas[2]:NULL;
	$hora=isset($_POST["hora_txt"])?$_POST["hora_txt"]:NULL;
	$hh=substr($hora, 0,2);
	$mm=substr($hora, 3,2);
}
$hora="$hh:$mm:00.000";

	$modalidad=isset($_POST["modalidad_slc"])?$_POST["modalidad_slc"]:NULL;
	$femenino=isset($_POST["femenino_rdo"])?$_POST["femenino_rdo"]:NULL;
	$masculino=isset($_POST["masculino_rdo"])?$_POST["masculino_rdo"]:NULL;
	$mixto=isset($_POST["mixto_rdo"])?$_POST["mixto_rdo"]:NULL;
	$tipo=isset($_POST["tipo_rdo"])?$_POST["tipo_rdo"]:NULL;
	$primero_libres=isset($_POST["primero_libres_chk"])?$_POST["primero_libres_chk"]:NULL;
	$usa_dispositivos=isset($_POST["usa_dispositivos_chk"])?$_POST["usa_dispositivos_chk"]:NULL;
	$calentamiento=isset($_POST["calentamiento_nbr"])?$_POST["calentamiento_nbr"]:NULL;
	$participantes_estimado=isset($_POST["participantes_estimado_nbr"])?$_POST["participantes_estimado_nbr"]:NULL;
	$_SESSION["fecha"]=isset($fecha)?$fecha:NULL;
	$_SESSION["hora"]=isset($hora)?$hora:NULL;
	$_SESSION["modalidad"]=isset($modalidad)?$modalidad:NULL;
	$_SESSION["tipo"]=isset($tipo)?$tipo:NULL;
	$_SESSION["primero_libres"]=isset($primero_libres)?$primero_libres:NULL;
	$_SESSION["usa_dispositivos"]=isset($usa_dispositivos)?$usa_dispositivos:NULL;
	$_SESSION["calentamiento"]=isset($calentamiento)?$calentamiento:NULL;
	$_SESSION["participantes_estimado"]=isset($participantes_estimado)?$participantes_estimado:NULL;
	$mensaje=NULL;
	$hor="$hh:$mm:00.000";
	$fechahora=$fecha." ".$hor;

	if (strlen($fecha)==0) {
		$mensaje="Error: debes ingresar la fecha de este evento de la competencia";
	}
	else
		$_SESSION["fecha"]=$fecha;

	if (is_null($mensaje) and strlen($hora)==0) {
		$mensaje="Error: ingresa la hora aproximada para este evento de la competencia";
	}
	else
		$_SESSION["hora"]=$hora;

	if (is_null($modalidad) OR !$modalidad) {
		$mensaje="Error: ingresa la modalidad de la competencia";
	}

	$conexion=conectarse();
	if (is_null($mensaje)){
		$arr=lee_modalidad($modalidad);
		if (isset($arr['error'])){
			$mensaje=$arr['error'];
		}
	}
	if (is_null($mensaje)){	
		$nom_modalidad=$arr['modalidad'];
		$tipo_modalidad=$arr["individual"];

		$consulta="SELECT fecha_inicia, fecha_termina FROM competencias WHERE cod_competencia=$cod_competencia";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		$registro=$ejecutar_consulta->fetch_assoc();
		$fecha_inicia=$registro["fecha_inicia"];
		$fecha_termina=$registro["fecha_termina"];

		if ($fecha<$fecha_inicia){
			$mensaje="Error: La fecha ".$fecha." es anterior a la fecha de inicio de la competencia";
		}
		elseif ($fecha>$fecha_termina) {
			$mensaje="Error: La fecha ".$fecha." es posterior a la fecha de terminacion de la competencia";
			$ok=FALSE;
		}
	}

	if (is_null($mensaje)){
		$categorias=NULL;
		$consulta="SELECT * FROM categorias ORDER BY categoria";
		$ejecutar_consulta=$conexion->query($consulta);
		while($registro=$ejecutar_consulta->fetch_assoc()){
			$cod_categoria=utf8_decode($registro["cod_categoria"]);
			$tipo_categoria=$registro["individual"];
			$name=$cod_categoria."_rdo";
			$categoria=isset($_POST[$name])?$_POST[$name]:NULL;
			if (isset($categoria)) {
				if ($tipo_modalidad!=$tipo_categoria) {
					$mensaje="Error: la categoría ".$categoria." no corresponde con la modalidad ".$nom_modalidad;
					break;
				}
				if (is_null($categorias))
					$categorias=$cod_categoria;
				else
		 			$categorias .= "-".$cod_categoria;
		 	} 
		}

		if (!$categorias) {
			$mensaje="Error: Define las categorías a competir en este evento";
		}
		else
		 	$_SESSION["categorias"]=isset($categorias)?$categorias:NULL;

	}
	$conexion->close();
	$_SESSION["categorias"]=isset($categorias)?$categorias:NULL;
	
	if (is_null($mensaje)) {
		$sexos=NULL;
		if (is_null($femenino) and is_null($masculino) and is_null($mixto)) {
			$mensaje="Error: Registra qué sexos participan en este evento";
		}
		if (isset($femenino))
			$sexos=$femenino;
		if (isset($masculino))
			if (is_null($sexos))
				$sexos=$masculino;
			else
				$sexos=$sexos."-".$masculino;
		if (isset($mixto))
			if (is_null($sexos))
				$sexos=$mixto;
			else
				$sexos=$sexos."-".$mixto;
		$_SESSION["sexos"]=isset($sexos)?$sexos:NULL;
	}
	if (is_null($mensaje)) {
		if (strlen($tipo)==0)
			$mensaje="Error: señala Tipo de Evento (Preliminiar, Semifinal ó Final)";
		else
			$_SESSION["tipo"]=$tipo;
	}
 ?>
