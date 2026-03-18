<?php 
switch ($tipo) {
	case 'P':
		$desc="Preliminar";
		break;
	
	case 'S':
		$desc="Semifinal";
		break;
	case 'F':
		$desc="Final";
		break;
	default:
		$desc="Final";
		break;
}
echo "<br>&nbsp;&nbsp;<input type='radio' id='p' name='tipo_rdo' value='P' title='Preliminar'";
if ($tipo=="P") 
	echo " checked";
echo "/> <label for='s'>Preliminar</label>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' id='s' name='tipo_rdo' value='S' title='Semifinal'";
if ($tipo=="S") 
	echo " checked";
echo "/> <label for='s'>Semifinal</label>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' id='f' name='tipo_rdo' value='F' title='Final'";
if ($tipo=="F") 
	echo " checked";
echo "/> <label for='f'>Final</label>";
?>