<?php 
$a=isset($fec[0])?$fec[0]:NULL;

if ($ord=="d") {
	for ($i=$hasta_anio; $i>=$desde_anio; $i--) { 
   			echo "<option value='$i' ";
   			if ($a){
   				if ($a==$i and $a>0) 
   					echo " selected " ;
   			}
   			echo ">$i</option>";	
	}
}
if ($ord=="a") {
	$i=$d;			
	while ($i <= $hasta_anio) {
		echo "<option value='$i'";
		if ($a==$i) 
			echo " selected";
		echo " >$i</option>";	
   		$i++;
	}
}					
 ?>