<?php 
session_start();
$indice=isset($_GET["ind"])?$_GET["ind"]:NULL;
$item_baja=isset($_GET["it"])?$_GET["it"]:NULL;
$origen=isset($_GET["ori"])?$_GET["ori"]:NULL;

if ($item_baja) {
	$items_ser=isset($_SESSION["items_ser"])?$_SESSION["items_ser"]:NULL;
	if ($items_ser) {
		unset($items_ser[$indice]);
		$_SESSION["items_ser"]=$items_ser;
	}
}
header("Location: ?op=$origen");
exit();
?>