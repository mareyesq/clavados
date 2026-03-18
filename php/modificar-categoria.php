<?php 
// Asigno a variables php los valores que vienen en el formulario
$codcategoria_txt=$_POST["codcategoria_txt"];
$codcategoria_hdn=$_POST["codcategoria_hdn"];
$descripcion=$_POST["descripcion_txt"];
$edaddesde=$_POST["edaddesde_txt"];
$edadhasta=$_POST["edadhasta_txt"];
$verificaedad=($_POST["verificaedad_rdo"]=="no"?0:1);
$tipocategoria=$_POST["tipocategoria_txt"];
$individual=($_POST["categoria_ind_sin_rdo"]=="s"?0:1);

if($codcategoria_hdn==$codcategoria_txt){
	$cod_cat=$codcategoria_hdn;
	$consulta="UPDATE categorias SET descripcion='$descripcion', edaddesde=$edaddesde, edadhasta=$edadhasta, verificaedad=$verificaedad, tipocategoria='$tipocategoria', individual=$individual WHERE codcategoria='$cod_cat'";
	include("conexion.php");
	$ejecutar_consulta=$conexion->query(utf8_encode($consulta));
	if ($ejecutar_consulta)
		$mensaje="Se han hecho los cambios en los datos de la categoría <b>$cod_cat</b> :)";
	else
		$mensaje="No se hicieron los cambios en los datos de la categoría <b>$cod_cat</b> :(";
	$conexion->close();
}
header("Location: ../index.php?op=consultas&mensaje=$mensaje");

?>