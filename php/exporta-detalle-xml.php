<?php
$data=array();
$conexion=conectarse();
$consulta="SELECT DISTINCT p.cod_planilla, p.orden_salida, p.categoria, c.nombre as nom_cla, c2.nombre as nom_cla2, q.nombre_corto, q.equipo as nombre_equipo, m.mixto
    FROM planillas as p
    LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
    LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
    LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
    LEFT JOIN competenciasq as q on (q.competencia=p.competencia and q.nombre_corto=p.equipo)";
if ($sorteada) 
    $consulta.=" WHERE (p.competencia=$cod_competencia AND p.evento=$numero_evento and p.usuario_retiro IS NULL";
else
    $consulta .= $criterio.$criterio_sexos.$criterio_categorias;

if ($sorteada) 
    $consulta.=") ORDER BY p.orden_salida, nom_cla";
else
    $consulta.=") ORDER BY nombre_equipo, nom_cla";

//$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")
//      ORDER BY p.orden_salida";
$ejecutar_consulta = $conexion->query($consulta);
while ($row=$ejecutar_consulta->fetch_assoc()){
    $clavadista = array();
    $cod_planilla=$row["cod_planilla"];
    $clavadista[0]=$row["orden_salida"];
    $nom_cla=utf8_decode($row["nom_cla"]);
    $categoria=utf8_decode($row["categoria"]);
    $mixto=isset($row["mixto"])?$row["mixto"]:NULL;
    if (strlen($nom_cla)>30) {
        $cadenas=explode(" ", $nom_cla);
        $n=count($cadenas);
        if ($n>3) 
            $nom_cla=$cadenas[0]." ".$cadenas[2];
        else
            $nom_cla=$cadenas[0]." ".$cadenas[1];
    }
    $nom_cla2=isset($row['nom_cla2'])?utf8_decode($row["nom_cla2"]):NULL;
    if (strlen($nom_cla2)>0){
        if (strlen($nom_cla2)>30) {
            $rondas=8;
            $cadenas=explode(" ", $nom_cla2);
            $n=count($cadenas);
            if ($n>3) 
                $nom_cla2=$cadenas[0]." ".$cadenas[2];
            else
                $nom_cla2=$cadenas[0]." ".$cadenas[1];
        }
        $nom_cla = "(1) ".$nom_cla."- (2) ".$nom_cla2;
    } 
    $nom_equ=substr($row["nombre_equipo"],0,35);
    $nom_cla=substr($nom_cla,0,70)." - ".$nom_equ;
    $clavadista[1]=utf8_decode($nom_cla);
    if ($mezcla and $primero_libres and ($categoria=="GB" or $categoria="GA")) {
        $consulta="SELECT DISTINCT ronda, ejecutor, salto, posicion, altura, grado_dif, abierto, salto2, posicion2
            FROM planillad
            WHERE planilla=$cod_planilla
            ORDER BY abierto DESC, ronda";
    }
    else{
        $consulta="SELECT DISTINCT ronda, ejecutor, salto, posicion, altura, grado_dif, abierto, salto2, posicion2
            FROM planillad
            WHERE planilla=$cod_planilla
            ORDER BY ronda";
    }
    $ejecutar_rondas = $conexion->query(utf8_decode($consulta));
    $rondas=$ejecutar_rondas->num_rows;
    $i=1;
    if ($categoria=="AB") 
        if ($ronda_mayores)
            $i=$ronda_mayores;
    $ron_mx=NULL;
    while ($ronda=$ejecutar_rondas->fetch_assoc()){
        $ron=isset($ronda["ronda"])?$ronda["ronda"]:NULL;
        if ($ron>$ron_mx)
            $ron_mx=$ron;
        $i++;
        $tot_saltos++;
        if ($modalidad=="E") 
            $clavadista[$i]="(".$ronda["ejecutor"].")   ".$ronda["abierto"]." ".$ronda["salto"].$ronda["posicion"]." ".number_format($ronda["altura"],1)."  ".number_format($ronda["grado_dif"],1);

        elseif ($mixto) {
            if ($ronda["salto2"])
                $sal2=$ronda["salto2"].$ronda["posicion2"];
            else
                $sal2="       ";
            
            $clavadista[$i]=$ronda["abierto"]." ".$ronda["salto"].$ronda["posicion"].number_format($ronda["altura"],1)."  ".number_format($ronda["grado_dif"],1);       }
        else
            $clavadista[$i]=$ronda["abierto"]." ".$ronda["salto"].$ronda["posicion"]." ".number_format($ronda["altura"],1)." ".number_format($ronda["grado_dif"],1);
        }
    $data[]=$clavadista;
}
$conexion->close();

/* creando un objeto de la clase XMLWriter.*/
$objetoXML = new XMLWriter();
 
    // Estructura básica del XML
$archivo='../xml/cartera.xml';
//$archivo='cartera.xml';
$objetoXML->openURI($archivo);
$objetoXML->setIndent(true);
$objetoXML->setIndentString("\t");
$objetoXML->startDocument('1.0', 'utf-8');
    // Inicio del nodo raíz
$objetoXML->startElement("fact");
$k=NULL;
foreach($data as $row){
    $k++;
    $orden_salida=isset($row[0])?$row[0]:NULL;
    $nombre=isset($row[1])?$row[1]:NULL;
    $ronda_1=isset($row[2])?$row[2]:NULL;
    $ronda_2=isset($row[3])?$row[3]:NULL;
    $ronda_3=isset($row[4])?$row[4]:NULL;
    $ronda_4=isset($row[5])?$row[5]:NULL;
    $ronda_5=isset($row[6])?$row[6]:NULL;
    $ronda_6=isset($row[7])?$row[7]:NULL;
    $ronda_7=isset($row[8])?$row[8]:NULL;
    $ronda_8=isset($row[9])?$row[9]:NULL;
    $ronda_9=isset($row[10])?$row[10]:NULL;
    $ronda_10=isset($row[11])?$row[11]:NULL;
    $ronda_11=isset($row[12])?$row[12]:NULL;
    $objetoXML->startElement("orden1"); // Se inicia un elemento para cada salto.
// Atributos del elemento factura
    $objetoXML->writeAttribute("No.", $orden_salida);
    $objetoXML->writeAttribute("Nombre", $nombre);
    $objetoXML->writeAttribute("ronda_1", $ronda_1);
    $objetoXML->writeAttribute("ronda_2", $ronda_2);
    $objetoXML->writeAttribute("ronda_3", $ronda_3);
    $objetoXML->writeAttribute("ronda_4", $ronda_4);
    if ($ronda_5) {
        $objetoXML->writeAttribute("ronda_5", $ronda_5);
    }
    if ($ronda_6) {
        $objetoXML->writeAttribute("ronda_6", $ronda_6);
    }
    if ($ronda_7) {
        $objetoXML->writeAttribute("ronda_7", $ronda_7);
    }
    if ($ronda_8) {
        $objetoXML->writeAttribute("ronda_8", $ronda_8);
    }
    if ($ronda_9) {
        $objetoXML->writeAttribute("ronda_9", $ronda_9);
    }
    if ($ronda_10) {
        $objetoXML->writeAttribute("ronda_10", $ronda_10);
    }
    if ($ronda_11) {
        $objetoXML->writeAttribute("ronda_11", $ronda_11);
    }
  
    $objetoXML->endElement(); // Final del elemento 
}
$objetoXML->endElement(); // Final del nodo raíz, "fact"
$objetoXML->endDocument(); // Final del documento
header ("Content-Disposition: attachment; filename=cartera.xml");
header ("Content-Type: application/force-download");
header ("Content-Length: ".filesize($archivo));
readfile($archivo);
exit();
?>
