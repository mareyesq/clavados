<?php 
	$qry="SELECT ubicacion, calificacion
		 FROM calificaciones";  
	$conexion=conectarse();
	$ejecutar_qry=$conexion->query($qry);
	if ($ejecutar_qry) {
		while ($row=$ejecutar_qry->fetch_assoc()) {
			$ubicacion=isset($row['ubicacion'])?$row['ubicacion']:NULL;
			$calificacion=isset($row['calificacion'])?$row['calificacion']:NULL;
			switch ($ubicacion) {
				case '1':
					$_POST["cal1"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '2':
					$_POST["cal2"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '3':
					$_POST["cal3"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '4':
					$_POST["cal4"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '5':
					$_POST["cal5"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '6':
					$_POST["cal6"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '7':
					$_POST["cal7"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '8':
					$_POST["cal8"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '9':
					$_POST["cal9"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '10':
					$_POST["cal10"]=isset($calificacion)?$calificacion:NULL;
					break;
				case '11':
					$_POST["cal11"]=isset($calificacion)?$calificacion:NULL;
					break;
			}
		};
	}
	$conexion->close();
?>