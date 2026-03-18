<?php 
$row=lee_evento_competencia($cod_competencia,$fechahora);
$competencia=isset($row['nom_competencia'])?utf8_decode($row['nom_competencia']):NULL;
$sorteada=isset($row['sorteada'])?$row['sorteada']:NULL;
$modalidad=isset($row['modalidad'])?$row['modalidad']:NULL;
$nom_modalidad=isset($row['nom_modalidad'])?utf8_decode($row['nom_modalidad']):NULL;
$cat=isset($row['categorias'])?$row['categorias']:NULL;
$sx=isset($row['sexos'])?$row['sexos']:NULL;
$tipo=isset($row['tipo'])?$row['tipo']:NULL;
$evento=isset($row["evento"])?$row["evento"]:NULL;
$numero_evento=isset($row["numero_evento"])?$row["numero_evento"]:NULL;
$primero_libres=isset($row["primero_libres"])?$row["primero_libres"]:NULL;
$tiempo_estimado=isset($row["tiempo_estimado"])?$row["tiempo_estimado"]:NULL;
$tiempo_estimado_inicial=isset($row["tiempo_estimado_inicial"])?$row["tiempo_estimado_inicial"]:NULL;
$fecha=hora_coloquial($fechahora);
$descripcion=describe_evento($nom_modalidad,$sx,$cat,$tipo);

 ?>