	<?php 
	session_start();
	$competencia = isset($_POST["competencia_txt"]) ? $_POST["competencia_txt"] : null;
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);

	if (isset($_POST["regresar_sbm"])){
		unset($_SESSION["pais"]	);
		unset($_SESSION["ciudad"]);
		unset($_SESSION["direccion"]);
		unset($_SESSION["inicia"]);
		unset($_SESSION["termina"]);
		unset($_SESSION["limite"]);
		unset($_SESSION["organizador"]);
		unset($_SESSION["logo_organizador"]);
		unset($_SESSION["telefono_contacto"]);
		unset($_SESSION["email_contacto"]);
		unset($_SESSION["resultados"]);
		unset($_SESSION["decimales"]);
		unset($_SESSION["competencia2"]);	
		unset($_SESSION["max_2_competidores"]);
		unset($_SESSION["convocatoria"]);
		unset($_SESSION["instructivo"]);
		unset($_SESSION["fecha_edad_deportiva"]);
		header("Location: ..?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}

	if (!$_SESSION["autentificado"]){
		$mensaje="Para modificar una competencia, debes iniciar sesión";
		$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
		$llamar="edita-competencia.php&cco=$cod_competencia";
		$llamados["inicia-sesion.php"]=$llamar;
		$_SESSION["llamados"]=$llamados;
		header("Location: ?op=php/inicia-sesion.php&mensaje=$mensaje");
		exit();
	}
	include("funciones.php");
	$alta=0;
	include ("valida-competencia.php");

	if (is_null($mensaje)){
		$cod_pais=determina_pais($pais);
		$cod_ciudad=determina_ciudad($ciudad,$cod_pais);
		$limite="$ano_lim-$mes_lim-$dia_lim $hh_lim:$mm_lim:00.000";
		$ahora=ahora($cod_ciudad);
		// ejecuto función para subir la convocatoria
		$tipo=$_FILES["convocatoria_fls"]["type"];
		$archivo=$_FILES["convocatoria_fls"]["tmp_name"];
		$destino="convoca";
		$se_subio_conv=subir_archivo($tipo,$archivo,$cod_competencia,$destino);
		$nombre_convocatoria=empty($archivo)?$convocatoria:$se_subio_conv;
		// ejecuto función para subir el instructivo
		$tipo=$_FILES["instructivo_fls"]["type"];
		$archivo=$_FILES["instructivo_fls"]["tmp_name"];
		$destino="instructivo";
		$se_subio_inst=subir_archivo($tipo,$archivo,$cod_competencia,$destino);
		$nombre_instructivo=empty($archivo)?$instructivo:$se_subio_inst;

		// ejecuto función para subir la imagen del logo
		$tipo=$_FILES["logo_fls"]["type"];
		$archivo=$_FILES["logo_fls"]["tmp_name"];
		$se_subio_imagen=subir_imagen($tipo,$archivo,$competencia);
		// Si la foto en el formulario viene vacía, se deja vacía, sino entonces el nombre de la foto que se subio (operador ternario)
		$imagen=empty($archivo)?$imagen:$se_subio_imagen;
		$consulta="UPDATE `competencias` 
			SET `competencia`='$competencia',
				`ciudad`=$cod_ciudad,
				`fecha_inicia`='$inicia',
				`fecha_termina`='$termina',
				`limite_inscripcion`='$limite',
				`organizador`='$organizador',
				`logo_organizador`='$imagen',
				`telefono`='$telefono',
				`email`='$email',
				`direccion_sede`='$direccion',
				`competencia2`='$competencia2',
				`public_result`='$resultados',
				`convocatoria`='$nombre_convocatoria', 
				`instructivo`='$nombre_instructivo'"; 
		if (!is_null($max_2_competidores)) {
			$consulta .= ", `max_2_competidores`=$max_2_competidores ";
		}
		else
			$consulta .= ", max_2_competidores= NULL";
		if (!is_null($fecha_edad_deportiva)) {
			$consulta .= ", `fecha_edad_deportiva`='$fecha_edad_deportiva' ";
		}
		else
			$consulta .= ", fecha_edad_deportiva= NULL";
				
		$consulta .= " WHERE (cod_competencia=$cod_competencia)";
		$conexion=conectarse();
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		if ($ejecutar_consulta){
			unset($_SESSION["pais"]	);
			unset($_SESSION["ciudad"]);
			unset($_SESSION["direccion"]);
			unset($_SESSION["inicia"]);
			unset($_SESSION["termina"]);
			unset($_SESSION["limite"]);
			unset($_SESSION["organizador"]);
			unset($_SESSION["logo_organizador"]);
			unset($_SESSION["telefono_contacto"]);
			unset($_SESSION["email_contacto"]);
			unset($_SESSION["resultados"]);
			unset($_SESSION["decimales"]);
			unset($_SESSION["competencia2"]);	
			unset($_SESSION["max_2_competidores"]);
			unset($_SESSION["fecha_edad_deportiva"]);
			unset($_SESSION["convocatoria"]);
			unset($_SESSION["instructivo"]);
			$mensaje="se actualizó la competencia <b>$competencia<b> :)";
			$conexion->close();
			header("Location: ..?op=php/muestra-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
			exit();
		}
		else{
			$conexion->close();
			$mensaje="No se pudo actualizar la competencia <b>$competencia</b>";
		}
	}	
	header("Location: ..?op=php/edita-competencia.php&cco=$cod_competencia&mensaje=$mensaje");
 ?>