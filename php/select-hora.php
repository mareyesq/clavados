<?php 
	$i=0;			
	while ($i <= 12) {
		echo "<option value='$i' ";
		if ($i==$hh) 
			echo " selected ";
		echo ">$i</option>";	
		$i++;
	}

 ?>