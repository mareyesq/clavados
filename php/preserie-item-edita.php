<?php 
session_start();
$indice=isset($_GET["ind"])?$_GET["ind"]:NULL;
$item_modificar=isset($_GET["it"])?$_GET["it"]:NULL;
$origen=isset($_GET["ori"])?$_GET["ori"]:NULL;

if ($item_modificar) {
	$items_ser=isset($_SESSION["items_ser"])?$_SESSION["items_ser"]:NULL;
	if ($items_ser) {
		$row=$items_ser[$indice];
		$_SESSION["orden"]=$row["orden"];
		$_SESSION["salto"]=$row["salto"];
		$_SESSION["posicion"]=$row["posicion"];
		$_SESSION["altura"]=$row["altura"];
		$_SESSION["plataforma"]=$row["plataforma"];
		$_SESSION["grado"]=$row["grado"];
		$_SESSION["observacion"]=$row["observacion"];
		$_SESSION["libre"]=$row["libre"];
		$_SESSION["indice"]=$indice;
		$_SESSION["item_modificar"]=$item_modificar;
	}
}
header("Location: ?op=$origen");
exit();

 ?>