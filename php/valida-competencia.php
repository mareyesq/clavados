<?php 
$competencia = isset($_POST["competencia_txt"]) ? quitar_tildes($_POST["competencia_txt"]) : null;
$pais=isset($_POST["pais_slc"]) ? $_POST["pais_slc"] : null;
$ciudad=isset($_POST["ciudad_slc"]) ? $_POST["ciudad_slc"] : null;
$direccion=isset($_POST["direccion_txt"]) ? quitar_tildes($_POST["direccion_txt"]) : null;

if(navegador_compatible("iPhone") || navegador_compatible("iPod")  || !navegador_compatible("chrome")){
	$dia_ini=isset($_POST["dia_ini_slc"]) ? $_POST["dia_ini_slc"]+100 : null;
	$nom_mes_ini=isset($_POST["mes_ini_slc"]) ? $_POST["mes_ini_slc"] : null;
	$ano_ini=isset($_POST["ano_ini_slc"]) ? $_POST["ano_ini_slc"]+10000 : null;
	$mes_ini=mes_num($nom_mes_ini)+100;
	$dia_ini=substr($dia_ini, 1,2);
	$mes_ini=substr($mes_ini, 1,2);
	$ano_ini=substr($ano_ini, 1,4);
	$dia_ini+=100;
	$dia_ini=substr($dia_ini, 1,2);
	$mes_ini=substr($mes_ini, 1,2);
	$mes_ini+=100;
	$mes_ini=substr($mes_ini, 1,2);
	$inicia="$ano_ini-$mes_ini-$dia_ini";
	$dia_ter=isset($_POST["dia_ter_slc"]) ? $_POST["dia_ter_slc"]+100 : null;
	$nom_mes_ter=isset($_POST["mes_ter_slc"]) ? $_POST["mes_ter_slc"] : null;
	$ano_ter=isset($_POST["ano_ter_slc"]) ? $_POST["ano_ter_slc"]+10000 : null;
	$mes_ter=mes_num($nom_mes_ter)+100;
	$dia_ter=substr($dia_ter, 1,2);
	$dia_ter+=100;
	$dia_ter=substr($dia_ter, 1,2);
	$mes_ter=substr($mes_ter, 1,2);
	$mes_ter+=100;
	$mes_ter=substr($mes_ter, 1,2);
	$ano_ter=substr($ano_ter, 1,4);
	$termina="$ano_ter-$mes_ter-$dia_ter";
	$dia_lim=isset($_POST["dia_lim_slc"]) ? $_POST["dia_lim_slc"]+100 : null;
	$nom_mes_lim=isset($_POST["mes_lim_slc"]) ? $_POST["mes_lim_slc"] : null;
	$ano_lim=isset($_POST["ano_lim_slc"]) ? $_POST["ano_lim_slc"]+10000 : null;
	$mes_lim=mes_num($nom_mes_lim)+100;
	$dia_lim=substr($dia_lim, 1,2);
	$dia_lim+=100;
	$dia_lim=substr($dia_lim, 1,2);
	$mes_lim=substr($mes_lim, 1,2);
	$mes_lim+=100;
	$mes_lim=substr($mes_lim, 1,2);
	$ano_lim=substr($ano_lim, 1,4);
	$fecha_limite="$ano_lim-$mes_lim-$dia_lim";
	$hh_lim=isset($_POST["hh_lim_slc"]) ? $_POST["hh_lim_slc"] : null;
	$mm_lim=isset($_POST["mm_lim_slc"]) ? $_POST["mm_lim_slc"] : null;
	$ampm_lim=isset($_POST["ampm_lim_slc"]) ? $_POST["ampm_lim_slc"] : null;
	if ($ampm_lim=="PM") {
		$hh_lim +=12;
	}

	$hh_lim+=100;
	$hh_lim=substr($hh_lim, 1,2);
	$mm_lim+=100;
	$mm_lim=substr($mm_lim, 1,2);
	$hora_limite="$hh_lim:$mm_lim:00.000";

	$dia_edd=isset($_POST["dia_edd_slc"]) ? $_POST["dia_edd_slc"]+100 : null;
	$nom_mes_edd=isset($_POST["mes_edd_slc"]) ? $_POST["mes_edd_slc"] : null;
	$ano_edd=isset($_POST["ano_edd_slc"]) ? $_POST["ano_edd_slc"]+10000 : null;
	$mes_edd=mes_num($nom_mes_edd)+100;
	$dia_edd=substr($dia_edd, 1,2);
	$mes_edd=substr($mes_edd, 1,2);
	$ano_edd=substr($ano_edd, 1,4);
	$dia_edd+=100;
	$dia_edd=substr($dia_edd, 1,2);
	$mes_edd=substr($mes_edd, 1,2);
	$mes_edd+=100;
	$mes_edd=substr($mes_edd, 1,2);
	$fecha_edad_deportiva="$ano_edd-$mes_edd-$dia_edd";
}
else{
	$inicia=isset($_POST["inicia_txt"]) ? $_POST["inicia_txt"] :null;
	$cadenas = explode("-", $inicia);
	$ano_ini=isset($cadenas[0])?$cadenas[0]:NULL;
	$mes_ini=isset($cadenas[1])?$cadenas[1]:NULL;
	$dia_ini=isset($cadenas[2])?$cadenas[2]:NULL;
	$termina=isset($_POST["termina_txt"]) ? $_POST["termina_txt"] :null;
	$cadenas = explode("-", $termina);
	$ano_ter=isset($cadenas[0])?$cadenas[0]:NULL;
	$mes_ter=isset($cadenas[1])?$cadenas[1]:NULL;
	$dia_ter=isset($cadenas[2])?$cadenas[2]:NULL;
	$fecha_limite=isset($_POST["fecha_limite_txt"]) ? $_POST["fecha_limite_txt"] :null;
	$ano_lim=substr($fecha_limite, 0,4);
	$mes_lim=substr($fecha_limite, 5,2);
	$dia_lim=substr($fecha_limite, 8,2);
	$hora_limite=isset($_POST["hora_limite_txt"]) ? $_POST["hora_limite_txt"] :null;
	$hh_lim=substr($hora_limite, 0,2);
	$mm_lim=substr($hora_limite, 3,2);
	$hora_limite=isset($_POST["hora_limite_txt"])?$_POST["hora_limite_txt"] :null;

	$fecha_edad_deportiva=isset($_POST["fecha_edad_deportiva_txt"]) ? $_POST["fecha_edad_deportiva_txt"] : NULL;

}
$limite=$fecha_limite." ".$hora_limite;

$organizador=isset($_POST["organizador_txt"]) ? quitar_tildes($_POST["organizador_txt"]) : null;
$convocatoria=isset($_POST["convocatoria_hdn"]) ? $_POST["convocatoria_hdn"] : null;
$instructivo=isset($_POST["instructivo_hdn"]) ? $_POST["instructivo_hdn"] : null;

$imagen=isset($_POST["foto_hdn"]) ? $_POST["foto_hdn"] : null;
$telefono=isset($_POST["telefono_txt"]) ? $_POST["telefono_txt"] : null;
$email=isset($_POST["email_txt"]) ? $_POST["email_txt"] : null;
$decimales=isset($_POST["decimales_nbr"]) ? $_POST["decimales_nbr"] : null;
$resultados=isset($_POST["resultados_rdo"]) ? $_POST["resultados_rdo"] : null;
$max_2_competidores=isset($_POST["max_2_competidores_chk"]) ? $_POST["max_2_competidores_chk"]=="max2" : null;

$competencia2=isset($_POST["competencia2_txt"]) ? quitar_tildes($_POST["competencia2_txt"]) : null;

// validando
$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
$_SESSION["competencia"]=isset($competencia)?$competencia:NULL;
$_SESSION["pais"]	= isset($pais)?$pais:null;
$_SESSION["ciudad"]=isset($ciudad)?$ciudad:null;
$_SESSION["direccion"]=isset($direccion)?$direccion:null;
$_SESSION["inicia"]=isset($inicia)?$inicia:null;
$_SESSION["termina"]=isset($termina)?$termina:null;
$_SESSION["fecha_limite"]=isset($fecha_limite)?$fecha_limite:null;
$_SESSION["hora_limite"]=isset($hora_limite)?$hora_limite:null;
$_SESSION["limite"]=isset($limite)?$limite:null;
$_SESSION["organizador"]=isset($organizador)?$organizador:null;
$_SESSION["logo"]=isset($logo)?$logo:null;
$_SESSION["convocatoria"]=isset($convocatoria)?$convocatoria:null;
$_SESSION["instructivo"]=isset($instructivo)?$instructivo:null;
$_SESSION["telefono_contacto"]=isset($telefono)?$telefono:null;
$_SESSION["email_contacto"]=isset($email)?$email:null;
$_SESSION["decimales"]=isset($decimales)?$decimales:null;	
$_SESSION["resultados"]=isset($resultados)?$resultados:null;	
$_SESSION["competencia2"]=isset($competencia2)?$competencia2:null;	
$_SESSION["max_2_competidores"]=isset($max_2_competidores)?$max_2_competidores:null;	
$_SESSION["fecha_edad_deportiva"]=isset($fecha_edad_deportiva)?$fecha_edad_deportiva:null;	

$mensaje=NULL;

if (is_null($competencia) or $competencia=="")
	$mensaje="Error: debe definir el nombre de la competencia";

if (is_null($mensaje)){
	if ($alta){
		$conexion=conectarse();
		$consulta="SELECT competencia FROM competencias WHERE (competencia='$competencia')";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs)
			$mensaje="Error: Ya está registrada una competencia con este nombre";
		$conexion->close();
	}
}

if (is_null($mensaje))
	if (is_null($pais) or $pais=="")
		$mensaje="Error: debe definir el nombre de la competencia";

if (is_null($mensaje))
	if (is_null($ciudad) or $ciudad=="")
		$mensaje="Error: debe definir la ciudad sede de la competencia";

if (is_null($mensaje))
	if (is_null($inicia) or $inicia=="")
		$mensaje="Error: debe definir la fecha de inicio de la competencia";
	else
		if (!checkdate ($mes_ini, $dia_ini, $ano_ini ))
			$mensaje="Error: la fecha de inicio no es válida";
	
if (is_null($mensaje))
	if (is_null($termina) or $termina=="")
		$mensaje="Error: debe definir la fecha de terminación de la competencia";
	elseif (!checkdate ($mes_ter, $dia_ter, $ano_ter ))
			$mensaje="Error: la fecha de terminación no es válida";

if (is_null($mensaje))
	if ($inicia>$termina)
		$mensaje="Error: la fecha de inicio es posterior a la fecha de terminación";

if (is_null($mensaje))
	if (is_null($fecha_limite) or $fecha_limite=="")
		$mensaje="Error: debe definir la fecha límite de inscripción de la competencia";
	elseif (!checkdate ($mes_lim,$dia_lim,$ano_lim))
		$mensaje="Error: la fecha límite de inscrpción no es válida";

if (is_null($mensaje)){
	try {
   		$fechahora = new DateTime($fecha_limite." ".$hora_limite);
	}
	catch (Exception $e) {
    	$err=$e->getMessage();
    	if ($err["warnig_count"]>0 or $err["error_count"]>0)
			$mensaje="Error: la fecha límite de inscrpción no es válida";
	}		
}

if (is_null($mensaje))
	if ($fecha_limite>$termina)
		$mensaje="Error: la fecha límite de inscripción es posterior a la fecha de terminación";

if (is_null($mensaje))
	if (is_null($organizador) or $organizador=="")
		$mensaje="Error: Debe definir el nombre del organizador";

if (is_null($mensaje))
	if (is_null($email) or $email=="")
		$mensaje="Error: Debe suministrar el email para contactarlo";

if (is_null($mensaje))
	if (is_null($resultados) or $resultados=="")
		$mensaje="Error: Debe definir si los resultados son Públicos o Privados";

/*
if (is_null($mensaje))
	if (is_null($decimales) or $decimales=="")
		$mensaje="Error: Debe definir la cantidad de decimales a usar en los puntajes";
*/
?>