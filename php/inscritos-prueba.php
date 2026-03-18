<?php 
$cod_competencia=36;
$modalidad="1";
$sexos = array();
$sexos[0]="F";
$categorias=array();
$categorias[0]="AB";
$categorias[1]="GA";
$categorias[2]="GB";

$consulta="SELECT c.clavadista, c.clavadista2, c.categoria, c.modalidades, c.equipo, l1.nombre as nom-cla, l2.nombre as nom_cla2, l1,sexo, q.nombre_corto as corto, q.equipo as nom_equipo
	FROM competenciasc as c 
	LEFT JOIN usuarios as l1 on l1.cod_usuario=c.clavadista
	LEFT JOIN usuarios as l2 on l2.cod_usuario=c.clavadista2
	LEFT JOIN competenciasq as q on q.competencia=c.competencia and q.nombre_corto=c.equipo
	WHERE c.competencia=$cod_competencia and c.modalidades like '%j.modalidad%'";

$n=count($sexos);
$criterio_sexos="";
for ($i=0; $i < $n ; $i++) { 
	if ($criterio_sexos=="") 
		$criterio_sexos=" AND (l1.sexo='$sexos[$i]'";
	else
		$criterio_sexos .= " OR l1.sexo='$sexos[$i]'";
}
if (!($criterio_sexos=="")) 
	$criterio_sexos .= ")"; 

$n=count($categorias);
$criterio_categorias="";
for ($i=0; $i < $n ; $i++) { 
	if ($criterio_categorias=="") 
		$criterio_categorias=" AND (c.categoria='$categorias[$i]'";
	else
		$criterio_categorias .= " OR c.categoria='$categorias[$i]'";
}
if (!($criterio_categorias=="")) 
	$criterio_categorias .= ")"; 

$consulta .= $criterio.$criterio_sexos.$criterio_categorias;
$ejecutar_consulta = $conexion->query($consulta);
while ($row=$ejecutar_consulta->fetch_assoc()){


 ?>