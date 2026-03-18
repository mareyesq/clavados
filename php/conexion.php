<?php 
	function conectarse()
	{
		$servidor="localhost";
		$usuario="root";
		$password="";
		$bd="clavados";
		

/*		$servidor="http://sql9.000webhost.com/phpMyAdmin";
		$usuario="a3266813_root";
		$password="clavados0305";
		$bd="a3266813_clav";
*/

/*		$servidor="localhost";
		$usuario="softneos_dives";
		$password="clavados0305";
		$bd="softneos_clav";*/

		$conectar= new mysqli($servidor,$usuario,$password,$bd);
		return $conectar;

	}
//	$conexion=conectarse();
 ?>