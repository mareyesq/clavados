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
$edad2=(isset($_POST["edad2_hdn"])?trim($_POST["edad2_hdn"]):null);
$edad3=(isset($_POST["edad3_hdn"])?trim($_POST["edad3_hdn"]):null);
$edad4=(isset($_POST["edad4_hdn"])?trim($_POST["edad4_hdn"]):null);

$clavadista2=isset($_POST["clavadista2_txt"])?trim($_POST["clavadista2_txt"]):null;
$clavadista3=isset($_POST["clavadista3_txt"])?trim($_POST["clavadista3_txt"]):null;
$clavadista4=isset($_POST["clavadista4_txt"])?trim($_POST["clavadista4_txt"]):null;

$categoria=(isset($_POST["categoria_slc"])?trim($_POST["categoria_slc"]):null);
$imagen=(isset($_POST["imagen_hdn"]) ? $_POST["imagen_hdn"] :null);
$imagen2=(isset($_POST["imagen2_hdn"]) ? $_POST["imagen2_hdn"] :null);
$imagen3=(isset($_POST["imagen3_hdn"]) ? $_POST["imagen3_hdn"] :null);
$imagen4=(isset($_POST["imagen4_hdn"]) ? $_POST["imagen4_hdn"] :null);

$clavadista=quitar_tildes($clavadista);
$clavadista2=quitar_tildes($clavadista2);
$clavadista3=quitar_tildes($clavadista3);
$clavadista4=quitar_tildes($clavadista4);
$entrenador=quitar_tildes($entrenador);
$cod_sexo=(isset($_POST["cod_sexo_hdn"])?$_POST["cod_sexo_hdn"]:null);
$cod_sexo2=(isset($_POST["cod_sexo2_hdn"])?$_POST["cod_sexo2_hdn"]:null);
$cod_sexo3=(isset($_POST["cod_sexo3_hdn"])?$_POST["cod_sexo3_hdn"]:null);
$cod_sexo4=(isset($_POST["cod_sexo4_hdn"])?$_POST["cod_sexo4_hdn"]:null);

$alta=isset($_POST["alta_hdn"])?$_POST["alta_hdn"]:NULL;

$modalidad=isset($_POST["modalidad_slc"])?trim($_POST["modalidad_slc"]):null;
$buscar=isset($_POST["buscar_sbm"])?$_POST["buscar_sbm"]:NULL;

$categoria=(isset($_POST["categoria_slc"])?trim($_POST["categoria_slc"]):null);
$paricipa_extraof=(isset($_POST["participa_extraof_chk"])?$_POST["participa_extraof_chk"]:null);

$cod1=(isset($_POST["cod1_txt"])?trim($_POST["cod1_txt"]):null);
$pos1=(isset($_POST["pos1_slc"])?trim($_POST["pos1_slc"]):null);
$alt1=(isset($_POST["alt1_slc"])?trim($_POST["alt1_slc"]):null);
$eje1_1=(isset($_POST["eje1_1_slc"])?$_POST["eje1_1_slc"]:null);
$eje2_1=null;

$cod2=(isset($_POST["cod2_txt"])?trim($_POST["cod2_txt"]):null);
$pos2=(isset($_POST["pos2_slc"])?trim($_POST["pos2_slc"]):null);
$alt2=(isset($_POST["alt2_slc"])?trim($_POST["alt2_slc"]):null);
$eje1_2=(isset($_POST["eje1_2_slc"])?$_POST["eje1_2_slc"]:null);
$eje2_2=null;

$cod3=(isset($_POST["cod3_txt"])?trim($_POST["cod3_txt"]):null);
$pos3=(isset($_POST["pos3_slc"])?trim($_POST["pos3_slc"]):null);
$alt3=(isset($_POST["alt3_slc"])?trim($_POST["alt3_slc"]):null);
$eje1_3=(isset($_POST["eje1_3_slc"])?$_POST["eje1_3_slc"]:null);
$eje2_3=null;

$cod4=(isset($_POST["cod4_txt"])?trim($_POST["cod4_txt"]):null);
$pos4=(isset($_POST["pos4_slc"])?trim($_POST["pos4_slc"]):null);
$alt4=(isset($_POST["alt4_slc"])?trim($_POST["alt4_slc"]):null);
$eje1_4=(isset($_POST["eje1_4_slc"])?$_POST["eje1_4_slc"]:null);
$eje2_4=(isset($_POST["eje2_4_slc"])?$_POST["eje2_4_slc"]:null);

$cod5=(isset($_POST["cod5_txt"])?trim($_POST["cod5_txt"]):null);
$pos5=(isset($_POST["pos5_slc"])?trim($_POST["pos5_slc"]):null);
$alt5=(isset($_POST["alt5_slc"])?trim($_POST["alt5_slc"]):null);
$eje1_5=(isset($_POST["eje1_5_slc"])?$_POST["eje1_5_slc"]:null);
$eje2_5=(isset($_POST["eje2_5_slc"])?$_POST["eje2_5_slc"]:null);

if (isset($modalidad)) {
	$row=lee_modalidad($modalidad);
	if (isset($row['error'])){
		$mensaje=$row['error'];
		return;
	}
	if ($modalidad=="E") 
		$tipo_modalidad="equipo";
	else
		$tipo_modalidad=$row["individual"]?"individual":"sincronizado";
}

$saltos = array();
if ($cod1){
	$saltos[1] = array ("cod_salto" => $cod1, "pos" => $pos1, "alt" => $alt1, "eje1" => $eje1_1, "eje2" => $eje2_1); 
}
if ($cod2){
	$saltos[2] = array ("cod_salto" => $cod2, "pos" => $pos2, "alt" => $alt2, "eje1" => $eje1_2, "eje2" => $eje2_2); 

}

if ($cod3){
	$saltos[3] = array ("cod_salto" => $cod3, "pos" => $pos3, "alt" => $alt3, "eje1" => $eje1_3, "eje2" => $eje2_3); 
}

if ($cod4){
	$saltos[4] = array ("cod_salto" => $cod4, "pos" => $pos4, "alt" => $alt4, "eje1" => $eje1_4, "eje2" => $eje2_4); 
}
if ($cod5){
	$saltos[5] = array ("cod_salto" => $cod5, "pos" => $pos5, "alt" => $alt5, "eje1" => $eje1_5, "eje2" => $eje2_5); 

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
$_SESSION["clavadista3"]= isset($clavadista3)?$clavadista3:null;
$_SESSION["clavadista4"]= isset($clavadista4)?$clavadista4:null;
$_SESSION["cod_clavadista"]= (isset($cod_clavadista)?$cod_clavadista:null);
$_SESSION["cod_clavadista2"]= (isset($cod_clavadista2)?$cod_clavadista2:null);
$_SESSION["cod_clavadista3"]= (isset($cod_clavadista3)?$cod_clavadista3:null);
$_SESSION["cod_clavadista4"]= (isset($cod_clavadista4)?$cod_clavadista4:null);
$_SESSION["cod_sexo"]= (isset($cod_sexo)?$cod_sexo:null);
$_SESSION["cod_sexo2"]= (isset($cod_sexo2)?$cod_sexo2:null);
$_SESSION["cod_sexo3"]= (isset($cod_sexo3)?$cod_sexo3:null);
$_SESSION["cod_sexo4"]= (isset($cod_sexo4)?$cod_sexo4:null);
$_SESSION["categoria"]= (isset($categoria)?$categoria:null);
$_SESSION["imagen"]= (isset($imagen)?$imagen:null);
$_SESSION["imagen2"]= (isset($imagen2)?$imagen2:null);
$_SESSION["imagen3"]= (isset($imagen3)?$imagen3:null);
$_SESSION["imagen4"]= (isset($imagen4)?$imagen4:null);
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
if (is_null($mensaje)){
	if ($clavadista3){
		$cod_clavadista3=determina_usuario_nombre($clavadista3);
		if (substr($cod_clavadista3, 0, 5)=="Error") 
			$mensaje=$cod_clavadista3;
		else{
			$_SESSION["cod_clavadista3"]=$cod_clavadista3;
			$_SESSION["clavadista3"]=$clavadista3;
		}
	}
}
if (is_null($mensaje)){
	if ($clavadista4){
		$cod_clavadista4=determina_usuario_nombre($clavadista4);
		if (substr($cod_clavadista4, 0, 5)=="Error") 
			$mensaje=$cod_clavadista4;
		else{
			$_SESSION["cod_clavadista4"]=$cod_clavadista4;
			$_SESSION["clavadista4"]=$clavadista4;
		}
	}
}

if (is_null($mensaje))
	if (strlen($categoria)==0)
		$mensaje="Error: No definió la Categoría";

if (is_null($mensaje))
	if (strlen($modalidad)==0)
		$mensaje="Error: No definió la Modalidad";

if (is_null($mensaje)){
	$tipo_planilla=isset($cod_clavadista2)?"equipo":NULL;
	$tipo_categoria="equipo";
}

if (is_null($mensaje)){
	if ($cod_sexo==$cod_sexo2 and $cod_sexo==$cod_sexo3 and $cod_sexo==$cod_sexo4)
		$mensaje="Error: El equipo debe estar compuesto por clavadistas de diferente sexo :(";
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
	$mensaje=valida_saltos_equ_ju($modalidad,$categoria,$edad,$edad2,$edad3,$edad4);
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

if (substr($mensaje, 0,5)!="Error")
	$mensaje=NULL;
?>
