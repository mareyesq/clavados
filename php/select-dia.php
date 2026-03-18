<?php 
	$i=1;			
	$d=$fec[2];

	while ($i <= 31) {
   		echo "<option value='$i' ";
   		if ($d==$i)
   			echo " selected";	
   		echo ">$i</option>";	
   		$i++;
	}
 ?>