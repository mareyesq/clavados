<?php 
	session_start();
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	if (isset($_POST["regresar_sbm"])){
		include("limpia-equipo-competencia.php");
		header("Location: ..?op=php/equipos-competencia.php&com=$competencia&cco=$cod_competencia");
		return;
	}
	include("funciones.php");
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:NULL);
	$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:NULL);
	$equipo=isset($_POST["equipo_txt"])?quitar_tildes($_POST["equipo_txt"]):NULL;
	$corto=isset($_POST["corto_txt"])?$_POST["corto_txt"]:NULL;
	$equipo_original=isset($_POST["equipo_original_hdn"])?$_POST["equipo_original_hdn"]:NULL;
	$corto_original=isset($_POST["corto_original_hdn"])?$_POST["corto_original_hdn"]:NULL;
	$usuario_alta=(isset($_POST["usuario_alta_hdn"])?trim($_POST["usuario_alta_hdn"]):NULL);
	$representante=isset($_POST["representante_txt"])?$_POST["representante_txt"]:NULL;
	$email=isset($_POST["email_txt"])?$_POST["email_txt"]:NULL;
	$telefono=isset($_POST["telefono_txt"])?$_POST["telefono_txt"]:NULL;
	$password=isset($_POST["password_txt"])?$_POST["password_txt"]:NULL;
	$password_conf=isset($_POST["password_conf_txt"])?$_POST["password_conf_txt"]:NULL;

	$_SESSION["cod_competencia"]=isset($cod_competencia)?$cod_competencia:NULL;
	$_SESSION["logo"]=isset($logo)?$logo:NULL;
	$alta=isset($_POST["alta_hdn"])?$_POST["alta_hdn"]:NULL;
	
	include ("valida-equipo-competencia.php");
	
	if (is_null($mensaje)){
		$conexion=conectarse();
		$consulta="SELECT ciudad FROM competencias WHERE (cod_competencia=$cod_competencia)";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
		$registro=$ejecutar_consulta->fetch_assoc();
		$cod_ciudad=$registro["ciudad"];
		$ahora=ahora($cod_ciudad,$conexion);
		$password=equivalencia($password);

		if ($corto==$corto_original){
			$consulta="UPDATE competenciasq SET equipo='$equipo', representante='$representante', email='$email', telefono='$telefono', clave_inscripciones='$password', fecha_alta='$ahora', usuario_alta='$usuario'  WHERE (competencia=$cod_competencia AND nombre_corto='$corto')";
			$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
			if (!$ejecutar_consulta)
				$mensaje="Error: No se pudo actualizar el equipo <b>$equipo</b>";
		}
		else{
			$consulta="INSERT INTO competenciasq (competencia, nombre_corto, equipo, representante, email, telefono, clave_inscripciones, fecha_alta, usuario_alta) VALUES ($cod_competencia, '$corto', 'nuevoxx', '$representante', '$email', '$telefono', '$password', '$ahora', $usuario)";
			$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
			if (!$ejecutar_consulta)
				$mensaje="Error: No puedo agregar el equipo <b>$equipo</b>";
			else{
				$consulta="UPDATE competenciasc SET equipo='$corto' WHERE (competencia=$cod_competencia AND equipo='$corto_original')";
				$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
				if (!$ejecutar_consulta)
					$mensaje="Error: No se pudo actualizar el equipo <b>$equipo</b>";
				else{
					$consulta="DELETE FROM competenciasq WHERE (competencia=$cod_competencia AND nombre_corto='$corto_original')";
					$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
					if (!$ejecutar_consulta)
						$mensaje="Error: No se puedo eliminar el equipo <b>$corto_original</b>";
					else{
						$consulta="UPDATE competenciasq SET equipo='$equipo' WHERE (competencia=$cod_competencia AND nombre_corto='$corto')";
						$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
						if (!$ejecutar_consulta)
						$mensaje="Error: No se pudo actualizar el equipo <b>$$equipo</b>";

					}

				}
			}
		}
		$conexion->close();
	}	

	if (is_null($mensaje)){
		include("limpia-equipo-competencia.php");
		$mensaje="se actualizó el equipo <b>$equipo<b> en la competencia";
		header("Location: ..?op=php/equipos-competencia.php&com=$competencia&mensaje=$mensaje");
			return;
	}
	header("Location: ..?op=php/edita-equipo-competencia.php&mensaje=$mensaje");
 ?>