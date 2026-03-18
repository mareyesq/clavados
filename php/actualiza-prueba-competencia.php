<?php 
	session_start();
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	if (isset($_POST["regresar_sbm"])){
		$_SESSION["modalidades"]=NULL;
		header("Location: ..?op=php/pruebas-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}
				
	$categoria=isset($_POST["categoria_txt"]) ? $_POST["categoria_txt"] : null;
	$cod_categoria=substr($categoria, 0, 2);
	$_SESSION["competencia"]=$competencia;
	$_SESSION["categoria"]=isset($categoria)?$categoria:NULL;
	$_SESSION["individual"]=isset($individual)?$individual:NULL;
	include("funciones.php");
	$conexion=conectarse();
	$consulta="SELECT * FROM categorias WHERE cod_categoria='$cod_categoria'";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$registro=$ejecutar_consulta->fetch_assoc();
	$tipo_categoria=$registro["individual"];
	
	$modalidades=NULL;
	$ok=TRUE;
	$consulta="SELECT * FROM modalidades ORDER BY modalidad";
	$ejecutar_consulta=$conexion->query($consulta);
	while($registro=$ejecutar_consulta->fetch_assoc()){
		$cod_modalidad=utf8_decode($registro["cod_modalidad"]);
		$modalidad=utf8_decode($registro["modalidad"]);
		$tipo_modalidad=$registro["individual"];
		$name=$cod_modalidad."_rdo";
		$modalidad=isset($_POST[$name])?$_POST[$name]:NULL;
		if (isset($modalidad)) {
			if (!$tipo_modalidad==$tipo_categoria) {
				$mensaje="la modalidad ".$modalidad." no corresponde con la categoría ".$categoria;
				$ok=FALSE;
				break;
			}
			if (is_null($modalidades))
				$modalidades=$cod_modalidad;
			else
		 		$modalidades=$modalidades."-".$cod_modalidad;
		 } 
	}
	$_SESSION["modalidades"]=isset($modalidades)?$modalidades:NULL;

	if ($ok) {
		$consulta="UPDATE competenciapr set modalidades='$modalidades'  WHERE competencia=$cod_competencia AND categoria='$cod_categoria'";
		$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

		if ($ejecutar_consulta){
			$_SESSION["categoria"]=NULL;
			$_SESSION["modalidades"]=NULL;
			$mensaje="se actualizó la categoría <b>$categoria</b> en la competencia";
			$conexion->close();
			header("Location: ..?op=php/pruebas-competencia.php&com=$competencia&cco=$cod_competencia&mensaje=$mensaje");
			exit();
		}
		else{
			$conexion->close();
			$mensaje="No se pudo dactualizar la categoría <b>$categoria</b> en la competencia :(";
		}
	}
	else{
		$conexion->close();
		header("Location: ..?op=php/edita-prueba-competencia.php&mensaje=$mensaje");
 	}
 ?>