<?php 
$conexion=conectarse();
$consulta="SELECT email FROM competidores ORDER BY email";
$ejecutar_consulta=$conexion->query($consulta);
while ($registro=$ejecutar_consulta->fetch_assoc())
{
	echo "<option value='".utf8_encode($registro["email"])."'";
	if ($_GET["competidor_slc"]==$registro["email"])
		echo " selected";

	echo ">".utf8_encode($registro["email"])."</option>";	
}
$conexion->close();
?>