<?php 
$ar=array();
$ar[]='A';
$ar[]='B';
$ar[]='C';
$ar[]='D';
$ar[]='E';

$n=count($ar);
for ($i=0; $i < $n; $i++) { 
	$nombre_pos=$ar[$i];
	echo "<option value='$nombre_pos'";
	if(isset($pos)){
		if ($nombre_pos==$pos)
			echo " selected";
	}
	echo ">$nombre_pos</option>";
}

 ?>