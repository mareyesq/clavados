<?php 
	$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
	$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
	$categoria=isset($_POST["categoria_slc"]) ? $_POST["categoria_slc"] : null;
	$cod_categoria=substr($categoria, 0, 2);

	session_start();
	$_SESSION["competencia"]=$competencia;
	$_SESSION["cod_competencia"]=$cod_competencia;
	$_SESSION["categoria"]=isset($categoria)?$categoria:NULL;

	if (isset($_POST["regresar_sbm"])){
		$_SESSION["categoria"]=NULL;
		$_SESSION["modalidades"]=NULL;
		header("Location: ..?op=php/pruebas-competencia.php&com=$competencia&cco=$cod_competencia");
		exit();
	}
	include("funciones.php");
	$conexion=conectarse();
	$consulta="SELECT * FROM categorias WHERE cod_categoria='$cod_categoria'";
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	$registro=$ejecutar_consulta->fetch_assoc();
	$tipo_categoria=$registro["individual"];
	
	$modalidades=NULL;
	$men=TRUE;
	$mensaje=NULL;
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
	if (is_null($modalidades)) {
		$ok=false;
	}
	else
		$_SESSION["modalidades"]=isset($modalidades)?$modalidades:NULL;

	$cod_competencia=determina_competencia($competencia);
	if (substr($cod_competencia,0,5)=="Error") {
		$mensaje=$cod_competencia;
	}
	if (!$mensaje) {
		if (isset($_POST["registrar_sbm"])){
			$consulta="INSERT INTO competenciapr (competencia, categoria, modalidades) VALUES ($cod_competencia, '$cod_categoria', '$modalidades')";
			$ejecutar_consulta=$conexion->query(utf8_encode($consulta));

			if ($ejecutar_consulta){
				$_SESSION["categoria"]=NULL;
				$_SESSION["modalidades"]=NULL;
				$mensaje="se dió de alta la categoría <b>$categoria</b> en la competencia";
				$conexion->close();
				header("Location: ..?op=php/pruebas-competencia.php&com=$competencia&mensaje=$mensaje");
				exit();
			}
			else{
				$mensaje="No se pudo dar de alta la categoría <b>$categoria</b> en la competencia :(";
			}
		}
	}
	$conexion->close();
	header("Location: ..?op=php/alta-prueba-competencia.php&mensaje=$mensaje");
 ?>