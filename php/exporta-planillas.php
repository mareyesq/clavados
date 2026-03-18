<?php
$conexion=conectarse();  
$csv_end = "  
";  
$csv_sep = "|";  
$csv_file = "planillas.csv";  
$csv="";  
$consulta=
	"SELECT  p.cod_planilla, p.clavadista, p.competencia, p.evento, p.sexo, p.equipo, p.categoria, p.clavadista2, p.orden_salida, p.modalidad, cl.nombre as nom_cla, cl.nacimiento as nac_cla, cl.email, cl.telefono, cl2.nombre as nom_cla2, cl2.nacimiento as nac_cla2, en.nombre as nom_ent, q.equipo as nom_equipo, m.modalidad as nom_modalidad, cat.categoria as nom_cat, d.ronda, d.salto, d.posicion, d.altura, d.grado_dif, d.abierto, d.total_salto, d.puntaje_salto, d.acumulado, d.penalizado
		FROM planillas as p
		LEFT JOIN competenciasj as j on (j.competencia=p.competencia AND j.numero_evento=p.evento)
		LEFT JOIN usuarios as cl on cl.cod_usuario=p.clavadista
		LEFT JOIN usuarios as cl2 on cl2.cod_usuario=p.clavadista2
		LEFT JOIN usuarios as en on en.cod_usuario=p.entrenador
        LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
        LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria
		LEFT JOIN competenciasq as q on q.competencia=p.competencia and q.nombre_corto=p.equipo
		LEFT JOIN planillad as d on d.planilla=p.cod_planilla
		WHERE p.competencia=$cod_competencia and p.usuario_retiro IS NULL
		ORDER BY competencia, evento, clavadista, cod_planilla,ronda";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$mensaje="No hay planillas para exportar :(";
		header($transfer);
		exit();
	}

while ($row=$ejecutar_consulta->fetch_assoc()){
    $csv.=$row['competencia'].
    	$csv_sep.$row['evento'].
    	$csv_sep.$row['cod_planilla'].
    	$csv_sep.$row['nom_ent'].
    	$csv_sep.$row['clavadista'].
    	$csv_sep.$row['nom_cla'].
    	$csv_sep.$row['sexo'].
    	$csv_sep.$row['equipo'].
    	$csv_sep.$row['nom_equipo'].
    	$csv_sep.$row['categoria'].
        $csv_sep.$row['nom_cat'].
    	$csv_sep.$row['clavadista2'].
    	$csv_sep.$row['nom_cla2'].
    	$csv_sep.$row['orden_salida'].
        $csv_sep.$row['modalidad'].
        $csv_sep.$row['nom_modalidad'].
    	$csv_sep.$row['ronda'].
    	$csv_sep.$row['salto'].
    	$csv_sep.$row['posicion'].
    	$csv_sep.$row['altura'].
    	$csv_sep.$row['grado_dif'].
    	$csv_sep.$row['abierto'].
    	$csv_sep.$row['nac_cla'].
        $csv_sep.$row['nac_cla2'].
        $csv_sep.$row['email'].
        $csv_sep.$row['telefono'].
        $csv_end;  
}  
$conexion->close();
//Generamos el csv de todos los datos  
if (!$handle = fopen($csv_file, "w")) 
    $mensaje="No puedo abrir el archivo $csv_file :(";  
elseif (fwrite($handle, utf8_decode($csv)) === FALSE) 
    	$mensaje="No puedo escribir el archivo $csv_file :(";  
    else{
		fclose($handle);  
		$mensaje="Se generó el archivo $csv_file :)";
    }		
?>  