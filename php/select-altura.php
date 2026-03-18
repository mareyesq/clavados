<?php 
$ar=array();
$ar[]='0';
$ar[]='0.75';
$ar[]='1';
$ar[]='2';
$ar[]='3';
$ar[]='5';
$ar[]='7.5';
$ar[]='10';

$n=count($ar);
for ($i=0; $i < $n; $i++) { 
	$nombre_alt=$ar[$i];
	echo "<option value='$nombre_alt'";
	if(isset($alt)){
		if ($nombre_alt==$alt)
			echo " selected";
	}
	echo ">$nombre_alt</option>";
}

 ?>