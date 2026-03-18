<?php 
	if($_GET["op"]!==null){
		include ("conexion.php");
		$conexion2=conectarse();
		$cod_categoria=$_GET["op"];
		$consulta_categoria="SELECT * FROM categorias WHERE codcategoria='$cod_categoria' ";
		$ejecutar_consulta_categoria=$conexion2->query($consulta_categoria);
		$registro_principal=$ejecutar_consulta_categoria->fetch_assoc();
		include("cambio-categoria.php");
		}
		include("mensajes.php");
?>	
