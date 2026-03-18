<?php 
	$nav=$_SERVER['HTTP_USER_AGENT'];
	echo($nav);
	//Demilitador 'i' para no diferenciar mayus y minus
if (preg_match("/chrome/i", $nav)) 
    {
    echo "<br>Compatible Chrome";
    }else 
        {
        echo "<br>NO Compatible Chrome";
        }
 ?>