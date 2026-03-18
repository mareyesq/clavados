<?php 
	$conexion=conectarse();
	$cal=array();
	$qry="SELECT juez, calificacion
		 FROM planillar
		  WHERE planilla=$cod_planilla and ronda=$ronda";
	$ejecutar_qry=$conexion->query($qry);
	if ($ejecutar_qry) {
		while ($row=$ejecutar_qry->fetch_assoc()) {
			$juez=isset($row['juez'])?$row['juez']:NULL;
			$calificacion=isset($row['calificacion'])?$row['calificacion']:NULL;
			$cal[$juez]=$calificacion;
		}
	}

	$qry="SELECT juez, calificacion
		 FROM calificaciones 
		 WHERE evento=$numero_evento AND sombra IS NOT NULL";  
	$ejecutar_qry=$conexion->query($qry);
	if ($ejecutar_qry){
		$val=NULL;
		while ($row=$ejecutar_qry->fetch_assoc()) {
			$juez=isset($row['juez'])?$row['juez']:NULL;
			$calificacion=isset($row['calificacion'])?$row['calificacion']:NULL;
			if (isset($cal[$juez])) {
				$cal[$juez]=$calificacion;
			}
			else
				if ($val)
					$val.=",";
				$val.=" ($cod_planilla, $ronda, $juez, $calificacion)";
		}
		if ($val){
			$consulta="INSERT INTO planillar VALUES".$val;
			$ejecutar_consulta=$conexion->query($consulta);
		}
	}

 ?>