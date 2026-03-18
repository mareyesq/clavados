<?php 
set_time_limit(0);

$inicial=848;  // usuario a convertir
$final=884;		// usuario consolidado

include("funciones.php");
$conexion=conectarse();
$consulta="UPDATE planillad SET juez1=$final WHERE juez1=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez2=$final WHERE juez2=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez3=$final WHERE juez3=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez4=$final WHERE juez4=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez5=$final WHERE juez5=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez6=$final WHERE juez6=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez7=$final WHERE juez7=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez8=$final WHERE juez8=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez9=$final WHERE juez9=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez10=$final WHERE juez10=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillad SET juez11=$final WHERE juez11=$inicial";
$ejecutar_consulta=$conexion->query($consulta);

// jueces eventos competencia
$consulta="UPDATE competenciasz SET juez=$final WHERE juez=$inicial";
$ejecutar_consulta=$conexion->query($consulta);

// jueces competencia
$consulta="UPDATE competenciasjz SET juez=$final WHERE juez=$inicial";
$ejecutar_consulta=$conexion->query($consulta);

// Administradores de competencia
$consulta="UPDATE competenciasa SET administrador=$final WHERE administrador=$inicial";
$ejecutar_consulta=$conexion->query($consulta);

// clavadistas
$consulta="UPDATE planillas SET clavadista=$final WHERE clavadista=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillas SET clavadista2=$final WHERE clavadista2=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillas SET clavadista3=$final WHERE clavadista3=$inicial";
$ejecutar_consulta=$conexion->query($consulta);
$consulta="UPDATE planillas SET clavadista4=$final WHERE clavadista4=$inicial";
$ejecutar_consulta=$conexion->query($consulta);

$consulta="DELETE FROM usuarios WHERE cod_usuario=$inicial";
$ejecutar_consulta=$conexion->query($consulta);

?>