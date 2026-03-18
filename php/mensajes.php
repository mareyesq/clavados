<?php 
if($_GET["mensaje"]){
	$mensaje=trim($_GET["mensaje"]);
	echo "<br><span class='mensajes'>$mensaje</span><br />";
}

 ?>