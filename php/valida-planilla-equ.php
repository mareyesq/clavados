<?php 

$cod_usuario=(isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:null);
if (!isset($cod_usuario)){
	$llamados=$_SESSION["llamados"];
	if (isset($llamados)){
		$llamados["inicia-sesion.php"]=$llamo."&comp=$competencia&eq=$equipo&ent=$entrenador&cl=$clavadista&ccl=$cod_clavadista";
		$_SESSION["llamados"]=$llamados;
	}
	$mensaje="Debe iniciar sesión para poder registrar planillas";
	header("Location: ..?op=php/inicia-sesion.php&mensaje=$mensaje&ok=FALSE");
	exit();
}

$clave=isset($_POST["clave_txt"])?trim($_POST["clave_txt"]):NULL;
$entrenador=(isset($_POST["entrenador_txt"]) ? trim($_POST["entrenador_txt"]) :null);
$cod_equipo=(isset($_POST["cod_equipo_hdn"])? trim($_POST["cod_equipo_hdn"]):null);
$cod_entrenador=(isset($_POST["cod_entrenador_hdn"])? trim($_POST["cod_entrenador_hdn"]):null);
$clavadista=(isset($_POST["clavadista_txt"]) ? trim($_POST["clavadista_txt"]) :null);

$edad=(isset($_POST["edad_hdn"])?trim($_POST["edad_hdn"]):null);

$clavadista2=isset($_POST["clavadista2_txt"])?trim($_POST["clavadista2_txt"]):null;

$categoria=(isset($_POST["categoria_slc"])?trim($_POST["categoria_slc"]):null);
$imagen=(isset($_POST["imagen_hdn"]) ? $_POST["imagen_hdn"] :null);
$imagen2=(isset($_POST["imagen2_hdn"]) ? $_POST["imagen2_hdn"] :null);

$clavadista=quitar_tildes($clavadista);
$clavadista2=quitar_tildes($clavadista2);
$entrenador=quitar_tildes($entrenador);
$cod_sexo=(isset($_POST["cod_sexo_hdn"])?$_POST["cod_sexo_hdn"]:null);
$cod_sexo2=(isset($_POST["cod_sexo2_hdn"])?$_POST["cod_sexo2_hdn"]:null);

$alta=isset($_POST["alta_hdn"])?$_POST["alta_hdn"]:NULL;

$modalidad=isset($_POST["modalidad_slc"])?trim($_POST["modalidad_slc"]):null;
$buscar=isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:NULL;

$categoria=(isset($_POST["categoria_slc"])?trim($_POST["categoria_slc"]):null);
$paricipa_extraof=(isset($_POST["participa_extraof_chk"])?$_POST["participa_extraof_chk"]:null);

$cod1=(isset($_POST["cod1_txt"])?trim($_POST["cod1_txt"]):null);
$pos1=(isset($_POST["pos1_slc"])?trim($_POST["pos1_slc"]):null);
$alt1=(isset($_POST["alt1_slc"])?trim($_POST["alt1_slc"]):null);
$obl1=(isset($_POST["obl1_rdo"])?"1":null);
$eje1=(isset($_POST["eje1_nbr"])?trim($_POST["eje1_nbr"]):null);

$cod2=(isset($_POST["cod2_txt"])?trim($_POST["cod2_txt"]):null);
$pos2=(isset($_POST["pos2_slc"])?trim($_POST["pos2_slc"]):null);
$alt2=(isset($_POST["alt2_slc"])?trim($_POST["alt2_slc"]):null);
$obl2=(isset($_POST["obl2_rdo"])?"1":null);
$eje2=(isset($_POST["eje2_nbr"])?trim($_POST["eje2_nbr"]):null);

$cod3=(isset($_POST["cod3_txt"])?trim($_POST["cod3_txt"]):null);
$pos3=(isset($_POST["pos3_slc"])?trim($_POST["pos3_slc"]):null);
$alt3=(isset($_POST["alt3_slc"])?trim($_POST["alt3_slc"]):null);
$obl3=(isset($_POST["obl3_rdo"])?"1":null);
$eje3=(isset($_POST["eje3_nbr"])?trim($_POST["eje3_nbr"]):null);

$cod4=(isset($_POST["cod4_txt"])?trim($_POST["cod4_txt"]):null);
$pos4=(isset($_POST["pos4_slc"])?trim($_POST["pos4_slc"]):null);
$alt4=(isset($_POST["alt4_slc"])?trim($_POST["alt4_slc"]):null);
$obl4=(isset($_POST["obl4_rdo"])?"1":null);
$eje4=(isset($_POST["eje4_nbr"])?trim($_POST["eje4_nbr"]):null);

$cod5=(isset($_POST["cod5_txt"])?trim($_POST["cod5_txt"]):null);
$pos5=(isset($_POST["pos5_slc"])?trim($_POST["pos5_slc"]):null);
$alt5=(isset($_POST["alt5_slc"])?trim($_POST["alt5_slc"]):null);
$obl5=(isset($_POST["obl5_rdo"])?"1":null);
$eje5=(isset($_POST["eje5_nbr"])?trim($_POST["eje5_nbr"]):null);

$cod6=(isset($_POST["cod6_txt"])?trim($_POST["cod6_txt"]):null);
$pos6=(isset($_POST["pos6_slc"])?trim($_POST["pos6_slc"]):null);
$alt6=(isset($_POST["alt6_slc"])?trim($_POST["alt6_slc"]):null);
$obl6=(isset($_POST["obl6_rdo"])?"1":null);
$eje6=(isset($_POST["eje6_nbr"])?trim($_POST["eje6_nbr"]):null);

$cod7=(isset($_POST["cod7_txt"])?trim($_POST["cod7_txt"]):null);
$pos7=(isset($_POST["pos7_slc"])?trim($_POST["pos7_slc"]):null);
$alt7=(isset($_POST["alt7_slc"])?trim($_POST["alt7_slc"]):null);
$obl7=(isset($_POST["obl7_rdo"])?"1":null);
$eje7=(isset($_POST["eje7_nbr"])?trim($_POST["eje7_nbr"]):null);

$cod8=(isset($_POST["cod8_txt"])?trim($_POST["cod8_txt"]):null);
$pos8=(isset($_POST["pos8_slc"])?trim($_POST["pos8_slc"]):null);
$alt8=(isset($_POST["alt8_slc"])?trim($_POST["alt8_slc"]):null);
$obl8=(isset($_POST["obl8_rdo"])?"1":null);
$eje8=(isset($_POST["eje8_nbr"])?trim($_POST["eje8_nbr"]):null);

$cod9=(isset($_POST["cod9_txt"])?trim($_POST["cod9_txt"]):null);
$pos9=(isset($_POST["pos9_slc"])?trim($_POST["pos9_slc"]):null);
$alt9=(isset($_POST["alt9_slc"])?trim($_POST["alt9_slc"]):null);
$obl9=(isset($_POST["obl9_rdo"])?"1":null);
$eje9=(isset($_POST["eje9_nbr"])?trim($_POST["eje9_nbr"]):null);

$cod10=(isset($_POST["cod10_txt"])?trim($_POST["cod10_txt"]):null);
$pos10=(isset($_POST["pos10_slc"])?trim($_POST["pos10_slc"]):null);
$alt10=(isset($_POST["alt10_slc"])?trim($_POST["alt10_slc"]):null);
$obl10=(isset($_POST["obl10_rdo"])?"1":null);
$eje10=(isset($_POST["eje10_nbr"])?trim($_POST["eje10_nbr"]):null);

$cod11=(isset($_POST["cod11_txt"])?trim($_POST["cod11_txt"]):null);
$pos11=(isset($_POST["pos11_slc"])?trim($_POST["pos11_slc"]):null);
$alt11=(isset($_POST["alt11_slc"])?trim($_POST["alt11_slc"]):null);
$obl11=(isset($_POST["obl11_rdo"])?"1":null);
$eje11=(isset($_POST["eje11_nbr"])?trim($_POST["eje11_nbr"]):null);

if (isset($modalidad)) {
	$row=lee_modalidad($modalidad);
	if (isset($row['error'])) {
		$mensaje=$row['error'];
		return;
	}
	$nom_modalidad=$row["nom_modalidad"];
	$fijo=$row["fijo"];
	if ($modalidad=="E") 
		$tipo_modalidad="equipo";
	else
		$tipo_modalidad=$row["individual"]?"individual":"sincronizado";
}

if ($fijo) {
	$alt1=$fijo;
	$alt2=$fijo;
	$alt3=$fijo;
	$alt4=$fijo;
	$alt5=$fijo;
	$alt6=$fijo;
	$alt7=$fijo;
	$alt8=$fijo;
	$alt9=$fijo;
	$alt10=$fijo;
	$alt11=$fijo;
}

$saltos = array();
if ($cod1){
	switch ($eje1) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[1] = array ("cod_salto" => $cod1, "pos" => $pos1, "alt" => $alt1, "obl" => $obl1, "eje" => $eje1, "nej" => $nej); 
}
if ($cod2){
	switch ($eje2) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[2] = array ("cod_salto" => $cod2, "pos" => $pos2, "alt" => $alt2, "obl" => $obl2, "eje" => $eje2, "nej" => $nej); 

}

if ($cod3){
	switch ($eje3) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[3] = array ("cod_salto" => $cod3, "pos" => $pos3, "alt" => $alt3, "obl" => $obl3, "eje" => $eje3, "nej" => $nej); 
}

if ($cod4){
	switch ($eje4) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[4] = array ("cod_salto" => $cod4, "pos" => $pos4, "alt" => $alt4, "obl" => $obl4, "eje" => $eje4, "nej" => $nej); 
}
if ($cod5){
	switch ($eje5) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[5] = array ("cod_salto" => $cod5, "pos" => $pos5, "alt" => $alt5, "obl" => $obl5, "eje" => $eje5, "nej" => $nej); 

}
if ($cod6){
	switch ($eje6) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[6] = array ("cod_salto" => $cod6, "pos" => $pos6, "alt" => $alt6, "obl" => $obl6, "eje" => $eje6, "nej" => $nej); 

}
if ($cod7){
	switch ($eje7) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[7] = array ("cod_salto" => $cod7, "pos" => $pos7, "alt" => $alt7, "obl" => $obl7, "eje" => $eje7, "nej" => $nej); 
}
if ($cod8){
	switch ($eje8) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[8] = array ("cod_salto" => $cod8, "pos" => $pos8, "alt" => $alt8, "obl" => $obl8, "eje" => $eje8, "nej" => $nej);

}
if ($cod9){
	switch ($eje9) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[9] = array ("cod_salto" => $cod9, "pos" => $pos9, "alt" => $alt9, "obl" => $obl9, "eje" => $eje9, "nej" => $nej);

}
if ($cod10){
	switch ($eje10) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[10] = array ("cod_salto" => $cod10, "pos" => $pos10, "alt" => $alt10, "obl" => $obl10, "eje" => $eje10, "nej" => $nej);

}
if ($cod11){
	switch ($eje11) {
		case '1':
			$nej=$clavadista;
			break;
		case '2':
			$nej=$clavadista2;
			break;
		
		default:
			$nej=NULL;
			break;
	}
	$saltos[11] = array ("cod_salto" => $cod11, "pos" => $pos11, "alt" => $alt11, "obl" => $obl11, "eje" => $eje11, "nej" => $nej);
}

if (isset($_POST["copiar_btn"])) {
	$n=count($saltos);
	if ($n>0) {
		$linea=$saltos[1];
		$salto1=$linea["cod_salto"];
		if ($salto1)
			$_SESSION["copia_saltos"]=$saltos;
	}
}

if (isset($_POST["pegar_btn"])) {
	$copia_saltos=isset($_SESSION["copia_saltos"])?$_SESSION["copia_saltos"]:NULL;
	$n=count($copia_saltos);
	if ($n>0) {
		$linea=$copia_saltos[1];
		$salto1=$linea["cod_salto"];
		if ($salto1) 
			$saltos=$copia_saltos;
	}
}

if (isset($_POST["cancela_copia_btn"])) {
	unset($_SESSION["copia_saltos"]);
}

$_SESSION["competencia"]= (isset($competencia)?$competencia:null);
$_SESSION["cod_competencia"]= (isset($cod_competencia)?$cod_competencia:null);
$_SESSION["logo"]= (isset($logo)?$logo:null);
$_SESSION["equipo"]= (isset($equipo)?$equipo:null);
$_SESSION["cod_equipo"]= (isset($cod_equipo)?$cod_equipo:null);
$_SESSION["clave"]=isset($clave)?$clave:NULL;
$_SESSION["entrenador"]= (isset($entrenador)?$entrenador:null);
$_SESSION["cod_entrenador"]= (isset($cod_entrenador)?$cod_entrenador:null);
$_SESSION["clavadista"]= (isset($clavadista)?$clavadista:null);
$_SESSION["clavadista2"]= isset($clavadista2)?$clavadista2:null;
$_SESSION["cod_clavadista"]= (isset($cod_clavadista)?$cod_clavadista:null);
$_SESSION["cod_clavadista2"]= (isset($cod_clavadista2)?$cod_clavadista2:null);
$_SESSION["cod_sexo"]= (isset($cod_sexo)?$cod_sexo:null);
$_SESSION["cod_sexo2"]= (isset($cod_sexo2)?$cod_sexo2:null);
$_SESSION["categoria"]= (isset($categoria)?$categoria:null);
$_SESSION["imagen"]= (isset($imagen)?$imagen:null);
$_SESSION["imagen2"]= (isset($imagen2)?$imagen2:null);
$_SESSION["modalidad"]= (isset($modalidad)?$modalidad:null);
$_SESSION["participa_extraof"]= (isset($participa_extraof)?$participa_extraof:null);
$_SESSION["saltos"]= (isset($saltos)?$saltos:null);

if (isset($_POST["busca_clavadista_sbm"])) {
	header("Location: ../?op=php/busca-usuario.php&bus=$clavadista2&ori=php/$llamo&tipo=C&parael2=si");
	exit();	
}

if (!is_null($buscar)){
	header("Location: ..?op=php/busca-salto.php&ori=$llamo");
	exit();
}

// validando
$mensaje=NULL;

if (is_null($competencia) or $competencia=="")
	$mensaje="Error: No definió la Competencia";

$cod_competencia=determina_competencia($competencia);
$administrador=administrador_competencia($cod_usuario,$cod_competencia);
if (substr($administrador, 0, 5)=="Error")
	$administrador=FALSE;

if (is_null($mensaje))
	if ($alta)
		if (!$administrador) 
			if (limite_inscripcion($cod_competencia)==FALSE)
				$mensaje="Error: Esta competencia ya no recibe planillas";

if (is_null($mensaje))
	if (!$alta)
		if (!$administrador) 
			if (!limite_cambios($cod_planilla))
				$mensaje="Error: Se venció el plazo para cambios en planillas :(";

if (is_null($mensaje))
	if (is_null($equipo) or $equipo=="")
		$mensaje="Error: No definió el Equipo";

if (is_null($mensaje)){
	if (!$administrador) {
		$p=equivalencia($clave);
		$ret=verifica_clave_inscripcion($p,$cod_equipo,$cod_competencia,$equipo);
		if (substr($ret, 0, 5)=="Error")
			$mensaje=$ret;
	}
}

if (is_null($mensaje))
	if (is_null($entrenador) or $entrenador=="")
		$mensaje="Error: No definió el nombre del Entrenador";

if (is_null($mensaje)){
	$cod_entrenador=determina_usuario_nombre($entrenador);
	if (substr($cod_entrenador, 0, 5)=="Error") 
		$mensaje=$cod_entrenador;
}

if (is_null($mensaje)){
	$n=strlen($clavadista);
	if ($n==0)
		$mensaje="Error: No definió el nombre del Clavadista";
}

if (is_null($mensaje)){
	$cod_clavadista=determina_usuario_nombre($clavadista);
	if (substr($cod_clavadista, 0, 5)=="Error") 
		$mensaje=$cod_clavadista;
}

if (is_null($mensaje)){
	if ($clavadista2){
		$cod_clavadista2=determina_usuario_nombre($clavadista2);
		if (substr($cod_clavadista2, 0, 5)=="Error") 
			$mensaje=$cod_clavadista2;
		else{
			$_SESSION["cod_clavadista2"]=$cod_clavadista2;
			$_SESSION["clavadista2"]=$clavadista2;
		}
	}
	else
		$mensaje="Error: No definió el clavadista 2";
}

if (is_null($mensaje))
	if (strlen($categoria)==0)
		$mensaje="Error: No definió la Categoría";

if (is_null($mensaje))
	if (strlen($modalidad)==0)
		$mensaje="Error: No definió la Modalidad";

if (is_null($mensaje)){
	$tipo_planilla=isset($cod_clavadista2)?"equipo":"individual";
	if (substr($categoria, 0, 2)=="EQ" or substr($categoria, 0, 2)=="Q1") 
		$tipo_categoria="equipo";
	else
		$tipo_categoria=tipo_categoria(substr($categoria,0,2));

	if ($tipo_planilla!=$tipo_categoria)
		$mensaje="Error: la planilla es $tipo_planilla y la categoría $tipo_categoria :(";
}

if (is_null($mensaje)) 
	if ($tipo_planilla!==$tipo_modalidad) 
		$mensaje="Error: la planilla es $tipo_planilla y la modalidad $tipo_modalidad :(";

if (is_null($mensaje)) 
	if ($tipo_categoria!==$tipo_modalidad)
		$mensaje="Error: la categoría es $tipo_categoria y la modalidad $tipo_modalidad :(";
		
if (is_null($mensaje)){
	if ($cod_sexo==$cod_sexo2)
		$mensaje="Error: La pareja debe estar compuesta por clavadistas de diferente sexo :(";
	else{
		$mixto=TRUE;
		$sx="X";
	}
}

if (is_null($mensaje)){
	$cod_categoria=substr($categoria,0,2);
	$reglamento=reglamento($cod_categoria,$sx,$modalidad);
	if (substr($reglamento, 0,5)=="Error") {
		$mensaje=$reglamento;
		$reglamento=NULL;
	}else
		$_SESSION["reglamento"]=$reglamento;
}

if (is_null($mensaje))
	if ($alta)
		$mensaje=planilla_registrada($cod_competencia,$cod_clavadista,substr($categoria, 0, 2),$modalidad);

if (is_null($mensaje))
	if ($alta and $cod_clavadista2)
		$mensaje=planilla_registrada($cod_competencia,$cod_clavadista2,substr($categoria, 0, 2),$modalidad);

if (is_null($mensaje)){
	$mensaje=valida_saltos_equ($modalidad,$categoria,$edad);
	$_SESSION["saltos"]= isset($saltos)?$saltos:null;

	$n=strpos($mensaje,"ejecutando menos saltos de los exigidos");
	if ($n) {
		$menos_saltos=$mensaje;
		$mensaje=NULL;
	}
	else{
		$n=strpos($mensaje,"No ha registrado los saltos en la planilla");
		if ($n) {
			$menos_saltos=$mensaje;
			$mensaje=NULL;
		}
		else
			$menos_saltos=NULL;

	}
}

if (is_null($mensaje)){
	$dificultad=grado_total($cod_categoria);
	$_SESSION["dificultad"]= isset($dificultad)?$dificultad:NULL;
}

if (!(substr($mensaje, 0,5)=="Error"))
	$mensaje=NULL;


?>
