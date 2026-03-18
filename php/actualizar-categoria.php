<?php
include ("conexion.php");

$codigo=$_GET["op"];
if (!isset($accion)){
  echo "$codigo";
  $consulta="SELECT * FROM categorias WHERE codcategoria='$codigo'";
  $ejecutar_consulta=$conexion->query($consulta);
  $num_regs=$ejecutar_consulta->num_rows;
  if ($num_regs==1) {
    $registro=$ejecutar_consulta->fetch_assoc();
    $des=$registro["descripcion"];
    $ede=$registro["edaddesde"];
    $eha=$registro["edadhasta"];
    $ved=($registro["verificaedad"]==0?"NO":"SI");
    $tca=$registro["tipocategoria"];
    $ind=($registro["individual"]==0?"Sincro":"Indiv.");
  echo"<html>
    <head><title>Actualizar Categorías</title></head>
    <body>
    <form action='actualizar-categoria.php?op=$codigo&accion=guardar' method='POST'>
    Descripci&oacute;n:<br>
    <input type='text' value='$des' name='descripcion_txt'><br>
    Edad Desde:<br>
    <input type='text' value='$ede' name='edaddesde_txt'><br>
    Edad Hasta:<br>
    <input type='text' value='$eha' name='edadhasta_txt'><br>
    Verifica Edad:<br>
    <input type='text' value='$ved' name='verificaedad_txt'><br>
    Tipo Categor&iacute;a:<br>
    <input type='text' value='$tca' name='tipocategoria_txt'><br>
    Individual:<br>
    <input type='text' value='$ind' name='individual_txt'><br>
    <input type='hidden' name='codigo_txt' value='$codigo'>
    <input type='submit' value='guardar'>
    </form>
    </body>
    </html>";
}elseif($accion=="guardar"){
  echo "$accion   $codigo";
  $descripcion=$_POST["descripcion_txt"];
  $edaddesde=$_POST["edaddesde_txt"];
  $edadhasta=$_POST["edadhasta_txt"];
  $verificaedad=($_POST["verificaedad_txt"]=="NO"?0:1);
  $tipocategoria=$_POST["tipocategoria_txt"];
  $individual=($_POST["individual_txt"]=="Sincro"?1:0);
  $consulta="UPDATE categorias SET  descripcion='$descripcion', edaddesde='$edaddesde', edadhasta='$edadhasta', verificaedad='$verificaedad', tipocategoria='$tipocategoria', individual='$individual'  WHERE codcategoria=$codigo";
  echo "$consulta";
  $ejecutar_consulta=$conexion->query($consulta);
  echo"
  <html>
  <body>
  <h3>Los registros han sido actualizados</h3>
  </body>
  </html>";
  }
}
$conexion->close();
?>