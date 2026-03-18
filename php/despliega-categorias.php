<?php 
include "conexion.php";
$consulta="SELECT * FROM categorias ORDER BY codcategoria";
$ejecutar_consulta=$conexion->query($consulta);
while($registro=$ejecutar_consulta->fetch_assoc()){
    $cod=$registro["codcategoria"];
    $des=$registro["descripcion"];
    $ede=$registro["edaddesde"];
    $eha=$registro["edadhasta"];
    $ved=($registro["verificaedad"]==0?"NO":"SI");
    $tca=$registro["tipocategoria"];
    $ind=($registro["individual"]==0?"Sincro":"Indiv.");
    echo "<tr>
        <td>$cod</td>
        <td>$des</td>
        <td>$ede</td>
        <td>$eha</td>
        <td>$ved</td>
        <td>$tca</td>
        <td>$ind<a href=\"php/cambios-categoria.php?op=$cod\">Actualizar</a></td>";
}
$conexion->close();

?>
