<?php 
$mensaje=NULL;

$cod_usuario=(isset($_SESSION["usuario_id"])?trim($_SESSION["usuario_id"]):null);
$entrenador=(isset($_POST["entrenador_txt"]) ? trim($_POST["entrenador_txt"]) :null);
$cod_equipo=(isset($_POST["cod_equipo_hdn"])?trim($_POST["cod_equipo_hdn"]):null);
$cod_entrenador=(isset($_POST["cod_entrenador_hdn"])?trim($_POST["cod_entrenador_hdn"]):null);
$btn_autorizar=isset($_POST["autorizar_sbm"])?$_POST["autorizar_sbm"]:null;
if (!isset($cod_usuario)){
	if (isset($_SESSION["llamados"]))
		$llamados=$_SESSION["llamados"];
	$llamados["inicia-sesion.php"]=$llamo."&comp=$competencia&eq=$equipo&ent=$entrenador&cl=$clavadista&ccl=$cod_clavadista";
	$_SESSION["llamados"]=$llamados;
	$mensaje="Debe iniciar sesión para poder registrar planillas";
	header("Location: ..?op=php/inicia-sesion.php&mensaje=$mensaje&ok=FALSE");
	exit();
}

$clave=isset($_POST["clave_txt"])?trim($_POST["clave_txt"]):NULL;
$clavadista=(isset($_POST["clavadista_txt"]) ? trim($_POST["clavadista_txt"]) :null);

$edad=(isset($_POST["edad_hdn"])?$_POST["edad_hdn"]:null);

$clavadista2=isset($_POST["clavadista2_txt"])?trim($_POST["clavadista2_txt"]):null;

$categoria=(isset($_POST["categoria_slc"])?trim($_POST["categoria_slc"]):null);
$imagen=(isset($_POST["imagen_hdn"]) ? $_POST["imagen_hdn"] :null);
$imagen2=(isset($_POST["imagen2_hdn"]) ? $_POST["imagen2_hdn"] :null);

$clavadista=quitar_tildes($clavadista);
$clavadista2=quitar_tildes($clavadista2);
$entrenador=quitar_tildes($entrenador);
$sexo=(isset($_POST["sexo_hdn"])?trim($_POST["sexo_hdn"]):null);
if ($sexo=="Masculino") 
	$cod_sexo="M";
else
	$cod_sexo="F";

$sexo2=(isset($_POST["sexo2_hdn"])?trim($_POST["sexo2_hdn"]):null);

$alta=isset($_POST["alta_hdn"])?trim($_POST["alta_hdn"]):NULL;

$modalidad=isset($_POST["modalidad_slc"])?trim($_POST["modalidad_slc"]):null;
if (!$modalidad) {
	$modalidad=isset($_POST["modalidad_hdn"])?trim($_POST["modalidad_hdn"]):null;
}
$buscar=isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:NULL;

$categoria=(isset($_POST["categoria_slc"])?trim($_POST["categoria_slc"]):null);
$paricipa_extraof=(isset($_POST["participa_extraof_chk"])?trim($_POST["participa_extraof_chk"]):null);

$cod1=(isset($_POST["cod1_txt"])?trim($_POST["cod1_txt"]):null);
$pos1=(isset($_POST["pos1_slc"])?trim($_POST["pos1_slc"]):null);
$alt1=(isset($_POST["alt1_slc"])?trim($_POST["alt1_slc"]):null);
$abi1=(isset($_POST["abi1_rdo"])?"*":null);

$cod2=(isset($_POST["cod2_txt"])?trim($_POST["cod2_txt"]):null);
$pos2=(isset($_POST["pos2_slc"])?trim($_POST["pos2_slc"]):null);
$alt2=(isset($_POST["alt2_slc"])?trim($_POST["alt2_slc"]):null);
$abi2=(isset($_POST["abi2_rdo"])?"*":null);

$cod3=(isset($_POST["cod3_txt"])?trim($_POST["cod3_txt"]):null);
$pos3=(isset($_POST["pos3_slc"])?trim($_POST["pos3_slc"]):null);
$alt3=(isset($_POST["alt3_slc"])?trim($_POST["alt3_slc"]):null);
$abi3=(isset($_POST["abi3_rdo"])?"*":null);

$cod4=(isset($_POST["cod4_txt"])?trim($_POST["cod4_txt"]):null);
$pos4=(isset($_POST["pos4_slc"])?trim($_POST["pos4_slc"]):null);
$alt4=(isset($_POST["alt4_slc"])?trim($_POST["alt4_slc"]):null);
$abi4=(isset($_POST["abi4_rdo"])?"*":null);

$cod5=(isset($_POST["cod5_txt"])?trim($_POST["cod5_txt"]):null);
$pos5=(isset($_POST["pos5_slc"])?trim($_POST["pos5_slc"]):null);
$alt5=(isset($_POST["alt5_slc"])?trim($_POST["alt5_slc"]):null);
$abi5=(isset($_POST["abi5_rdo"])?"*":null);

$cod6=(isset($_POST["cod6_txt"])?trim($_POST["cod6_txt"]):null);
$pos6=(isset($_POST["pos6_slc"])?trim($_POST["pos6_slc"]):null);
$alt6=(isset($_POST["alt6_slc"])?trim($_POST["alt6_slc"]):null);
$abi6=(isset($_POST["abi6_rdo"])?"*":null);

$cod7=(isset($_POST["cod7_txt"])?trim($_POST["cod7_txt"]):null);
$pos7=(isset($_POST["pos7_slc"])?trim($_POST["pos7_slc"]):null);
$alt7=(isset($_POST["alt7_slc"])?trim($_POST["alt7_slc"]):null);
$abi7=(isset($_POST["abi7_rdo"])?"*":null);

$cod8=(isset($_POST["cod8_txt"])?trim($_POST["cod8_txt"]):null);
$pos8=(isset($_POST["pos8_slc"])?trim($_POST["pos8_slc"]):null);
$alt8=(isset($_POST["alt8_slc"])?trim($_POST["alt8_slc"]):null);
$abi8=(isset($_POST["abi8_rdo"])?"*":null);

$cod9=(isset($_POST["cod9_txt"])?trim($_POST["cod9_txt"]):null);
$pos9=(isset($_POST["pos9_slc"])?trim($_POST["pos9_slc"]):null);
$alt9=(isset($_POST["alt9_slc"])?trim($_POST["alt9_slc"]):null);
$abi9=(isset($_POST["abi9_rdo"])?"*":null);

$cod10=(isset($_POST["cod10_txt"])?trim($_POST["cod10_txt"]):null);
$pos10=(isset($_POST["pos10_slc"])?trim($_POST["pos10_slc"]):null);
$alt10=(isset($_POST["alt10_slc"])?trim($_POST["alt10_slc"]):null);
$abi10=(isset($_POST["abi10_rdo"])?"*":null);

$cod11=(isset($_POST["cod11_txt"])?trim($_POST["cod11_txt"]):null);
$pos11=(isset($_POST["pos11_slc"])?trim($_POST["pos11_slc"]):null);
$alt11=(isset($_POST["alt11_slc"])?trim($_POST["alt11_slc"]):null);
$abi11=(isset($_POST["abi11_rdo"])?"*":null);

$fijo=NULL;
if ($modalidad) {
	$row=lee_modalidad($modalidad);
	if (isset($row['error'])) {
		$mensaje=$row['error'];
	}

	$nom_modalidad=isset($row["modalidad"])?$row["modalidad"]:NULL;
	$fijo=isset($row["fijo"])?$row["fijo"]:NULL;
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

$preserie=isset($_SESSION["preserie"])?$_SESSION["preserie"]:null;
$serie_btn=isset($_POST["serie_btn"])?trim($_POST["serie_btn"]):NULL;
$saltos = array();
if ($serie_btn) {
	foreach ($preserie as $row) {
		$orden=isset($row["orden"])?$row["orden"]:NULL;
		$salto=isset($row["salto"])?$row["salto"]:NULL;
		$posicion=isset($row["posicion"])?$row["posicion"]:NULL;
		$altura=isset($row["altura"])?$row["altura"]:NULL;
		$grado=isset($row["grado"])?$row["grado"]:NULL;
		$libre=isset($row["libre"])?$row["libre"]:NULL;
		$saltos[$orden]=array ("cod_salto" => $salto, "pos" => $posicion, "alt" => $altura, "abi" => NULL); 
	}
	$_SESSION["preserie"]= (isset($preserie)?$preserie:null);
}

if ($cod1)
	$saltos[1] = array ("cod_salto" => $cod1, "pos" => $pos1, "alt" => $alt1, "abi" => $abi1); 
if ($cod2)
	$saltos[2] = array ("cod_salto" => $cod2, "pos" => $pos2, "alt" => $alt2, "abi" => $abi2); 
if ($cod3)
	$saltos[3] = array ("cod_salto" => $cod3, "pos" => $pos3, "alt" => $alt3, "abi" => $abi3); 
if ($cod4)
	$saltos[4] = array ("cod_salto" => $cod4, "pos" => $pos4, "alt" => $alt4, "abi" => $abi4); 
if ($cod5)
	$saltos[5] = array ("cod_salto" => $cod5, "pos" => $pos5, "alt" => $alt5, "abi" => $abi5); 
if ($cod6)
	$saltos[6] = array ("cod_salto" => $cod6, "pos" => $pos6, "alt" => $alt6, "abi" => $abi6); 
if ($cod7)
	$saltos[7] = array ("cod_salto" => $cod7, "pos" => $pos7, "alt" => $alt7, "abi" => $abi7); 
if ($cod8)
	$saltos[8] = array ("cod_salto" => $cod8, "pos" => $pos8, "alt" => $alt8, "abi" => $abi8); 
if ($cod9)
	$saltos[9] = array ("cod_salto" => $cod9, "pos" => $pos9, "alt" => $alt9, "abi" => $abi9); 
if ($cod10)
	$saltos[10] = array ("cod_salto" => $cod10, "pos" => $pos10, "alt" => $alt10, "abi" => $abi10); 
if ($cod11)
	$saltos[11] = array ("cod_salto" => $cod11, "pos" => $pos11, "alt" => $alt11, "abi" => $abi11); 

if (isset($_POST["copiar_btn"])) {
	$n=count($saltos);
	if ($n>0) {
		$linea=$saltos[1];
		$salto1=$linea["cod_salto"];
		if ($salto1){
			$_SESSION["copia_saltos"]=$saltos;
		}
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

$es_miguel_o_sebas=($cod_usuario==5 OR $cod_usuario==16)?TRUE:FALSE;
if (is_null($mensaje)){
	if ($alta){
		if (!$administrador) {
			if (limite_inscripcion($cod_competencia)==FALSE){
// se suspende temporalmente agosto 4 2023
//				$mensaje="Error: Esta competencia ya no recibe planillas";
			}

		}
	}
}
if (is_null($mensaje))
	if (!$alta)
		if (!$administrador) 
			if (!limite_cambios($cod_planilla))
				$mensaje="Error: Se venció el plazo para cambios en planillas :(";

if (is_null($mensaje))
	if (!$equipo)
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

/*if (is_null($mensaje)){
	$cod_entrenador=determina_usuario_nombre($entrenador);
	if (substr($cod_entrenador, 0, 5)=="Error") 
		$mensaje=$cod_entrenador;
}
*/
if (is_null($mensaje)){
	if (!$clavadista)
		$mensaje="Error: No definió el nombre del Clavadista";
}

/*if (is_null($mensaje)){
	$cod_clavadista=determina_usuario_nombre($clavadista);
	if (substr($cod_clavadista, 0, 5)=="Error") 
		$mensaje=$cod_clavadista;
}

if (is_null($mensaje)){
	$n=strlen($clavadista2);
	if (!$n==0){
		$cod_clavadista2=determina_usuario_nombre($clavadista2);
		if (substr($cod_clavadista2, 0, 5)=="Error") 
			$mensaje=$cod_clavadista2;
		else{
			$_SESSION["cod_clavadista2"]=$cod_clavadista2;
			$_SESSION["clavadista2"]=$clavadista2;
		}
	}
	else{
		$clavadista2=NULL;
		$cod_clavadista2=NULL;
		$_SESSION["clavadista2"]=NULL;
	}
}
*/
if (is_null($mensaje))
	if (strlen($categoria)==0)
		$mensaje="Error: No definió la Categoría";

if (is_null($mensaje))
	if (strlen($modalidad)==0)
		$mensaje="Error: No definió la Modalidad";

if (is_null($mensaje)){
	$tipo_planilla=isset($cod_clavadista2)?"sincronizado":"individual";
	$tipo_categoria=tipo_categoria(substr($categoria,0,2));
	if (!($tipo_planilla==$tipo_categoria))
		$mensaje="Error: la planilla es $tipo_planilla y la categoría $tipo_categoria :(";
}

if (is_null($mensaje)) 
	if (!($tipo_planilla==$tipo_modalidad)) 
		$mensaje="Error: la planilla es $tipo_planilla y la modalidad $tipo_modalidad :(";

if (is_null($mensaje)) 
	if (!($tipo_categoria==$tipo_modalidad)) 
		$mensaje="Error: la categoría es $tipo_categoria y la modalidad $tipo_modalidad :(";
		

$cod_categoria=substr($categoria,0,2);

//$validar=($cod_categoria=='GC' or $cod_categoria=='GB' or $cod_categoria=='GA' or $cod_categoria=='AB');

if (is_null($mensaje)){
	$reglamento=reglamento($cod_categoria,$cod_sexo,$modalidad);
	if (substr($reglamento, 0,5)=="Error") {
		$mensaje=$reglamento;
		$reglamento=NULL;
	}else
		$_SESSION["reglamento"]=$reglamento;
}

if (is_null($mensaje))
	if ($alta)
		$mensaje=planilla_registrada($cod_competencia,$cod_clavadista,substr($categoria, 0, 2),$modalidad);

if (is_null($mensaje)){
	$mensaje=valida_saltos($modalidad,$categoria,$sexo,$edad);
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
	if ($mensaje AND $es_miguel_o_sebas)
		$_SESSION['autoriza']=TRUE;
}

if (is_null($mensaje)){
	$dificultad=grado_total($cod_categoria);
	$_SESSION["dificultad"]= isset($dificultad)?$dificultad:NULL;
}

if (!(substr($mensaje, 0,5)=="Error") OR $btn_autorizar)
	$mensaje=NULL;

?>
