<?php 
$cod_sexos=explode("-", $sexos);
echo "<br>&nbsp;&nbsp;<input type='checkbox' id='f' name='femenino_rdo' value='F' title='Damas'";
if (in_array("F", $cod_sexos)) 
	echo " checked";
echo "/> <label for='F'>Damas</label>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' id='m' name='masculino_rdo' value='M' title='Varones'";
if (in_array("M", $cod_sexos)) 
	echo " checked";
echo "/> <label for='M'>Varones</label>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' id='x' name='mixto_rdo' value='X' title='Mixto'";
if (in_array("X", $cod_sexos)) 
	echo " checked";
echo "/> <label for='x'>Mixto</label>";
?>