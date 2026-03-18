<?php 
/*	for ($i=1; $i <= 59; $i++) { 
		echo "<option value='$i' "
		if ($i==$mm) 
			echo " selected";
		echo ">$i</option>";	

	}
*/
	$i=0;			
	while ($i <= 59) {
//		$j=$i+100;
//		$j=substr($j,1,2);
		echo "<option value='$i' ";
		if ($i==$mm) 
			echo " selected ";
		echo ">$i</option>";	
		$i++;
	}

 ?>