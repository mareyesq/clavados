<?php 
$origen=(isset($_POST["origen_txt"])?$_POST["origen_txt"]:null);
$llamo=(isset($_POST["llamo_hdn"])?$_POST["llamo_hdn"]:null);
$cod_entrenador=isset($_POST["cod_entrenador_hdn"])?$_POST["cod_entrenador_hdn"]:NULL;
$cod_clavadista=isset($_POST["cod_clavadista_hdn"])?$_POST["cod_clavadista_hdn"]:NULL;
$cod_clavadista2=isset($_POST["cod_clavadista2_hdn"])?$_POST["cod_clavadista2_hdn"]:NULL;
$cod_clavadista3=isset($_POST["cod_clavadista3_hdn"])?$_POST["cod_clavadista3_hdn"]:NULL;
$cod_clavadista4=isset($_POST["cod_clavadista4_hdn"])?$_POST["cod_clavadista4_hdn"]:NULL;
$competencia=(isset($_POST["competencia_hdn"])?$_POST["competencia_hdn"]:null);
$cod_competencia=(isset($_POST["cod_competencia_hdn"])?$_POST["cod_competencia_hdn"]:null);
$logo=(isset($_POST["logo_hdn"])?$_POST["logo_hdn"]:null);
$equipo=(isset($_POST["equipo_txt"])?trim($_POST["equipo_txt"]):null);
$modalidades=isset($_POST["modalidades_hdn"])?$_POST["modalidades_hdn"]:NULL;
session_start();
if (isset($_POST["regresar_sbm"])){
	unset($_SESSION["llamo"]);
	unset($_SESSION["competencia"]);
	unset($_SESSION["equipo"]); 
	unset($_SESSION["clave"]);
	unset($_SESSION["entrenador"]);
	unset($_SESSION["clavadista"]);
	unset($_SESSION["imagen"]);
	unset($_SESSION["cod_clavadista"]);
	unset($_SESSION["modalidades"]);
	unset($_SESSION["edad"]);
	unset($_SESSION["sexo"]);
	unset($_SESSION["mixto"]);
	unset($_SESSION["cod_clavadista2"]);
	unset($_SESSION["clavadista2"]);
	unset($_SESSION["edad2"]);
	unset($_SESSION["sexo2"]);
	unset($_SESSION["imagen2"]);
	unset($_SESSION["cod_clavadista3"]);
	unset($_SESSION["clavadista3"]);
	unset($_SESSION["edad3"]);
	unset($_SESSION["sexo3"]);
	unset($_SESSION["imagen3"]);
	unset($_SESSION["cod_clavadista4"]);
	unset($_SESSION["clavadista4"]);
	unset($_SESSION["edad4"]);
	unset($_SESSION["sexo4"]);
	unset($_SESSION["imagen4"]);
	unset($_SESSION["categoria"]);
	unset($_SESSION["individual"]);
	unset($_SESSION["pareja"]);
	unset($_SESSION["eq_juv"]);
	unset($_SESSION["parael1"]);
	unset($_SESSION["parael2"]);
	unset($_SESSION["parael3"]);
	unset($_SESSION["parael4"]);
	unset($_SESSION["tipo"]);
	header("Location: ..?op=php/clavadistas-competencia.php&com=$competencia&eq=$equipo&cco=$cod_competencia");
	exit();
}
$busca_clavadista=isset($_POST["busca_clavadista_sbm"])?$_POST["busca_clavadista_sbm"]:null;
$nuevo_clavadista=isset($_POST["nuevo_clavadista_sbm"])?$_POST["nuevo_clavadista_sbm"]:null;
$busca_entrenador=isset($_POST["busca_entrenador_sbm"])?$_POST["busca_entrenador_sbm"]:null;
$nuevo_entrenador=isset($_POST["nuevo_entrenador_sbm"])?$_POST["nuevo_entrenador_sbm"]:null;
$busca_clavadista2=isset($_POST["busca_clavadista2_sbm"])?$_POST["busca_clavadista2_sbm"]:null;
$nuevo_clavadista2=isset($_POST["nuevo_clavadista2_sbm"])?$_POST["nuevo_clavadista2_sbm"]:null;
$busca_clavadista3=isset($_POST["busca_clavadista3_sbm"])?$_POST["busca_clavadista3_sbm"]:null;
$nuevo_clavadista3=isset($_POST["nuevo_clavadista3_sbm"])?$_POST["nuevo_clavadista3_sbm"]:null;
$busca_clavadista4=isset($_POST["busca_clavadista4_sbm"])?$_POST["busca_clavadista4_sbm"]:null;
$nuevo_clavadista4=isset($_POST["nuevo_clavadista4_sbm"])?$_POST["nuevo_clavadista4_sbm"]:null;

$clave=isset($_POST["clave_txt"])?$_POST["clave_txt"]:null;
$entrenador=isset($_POST["entrenador_txt"])?$_POST["entrenador_txt"]:null;
$clavadista=isset($_POST["clavadista_txt"])?$_POST["clavadista_txt"]:null;
$imagen=isset($_POST["imagen_hdn"])?$_POST["imagen_hdn"]:null;
$imagen2=isset($_POST["imagen2_hdn"])?$_POST["imagen2_hdn"]:null;
$imagen3=isset($_POST["imagen3_hdn"])?$_POST["imagen3_hdn"]:null;
$imagen4=isset($_POST["imagen4_hdn"])?$_POST["imagen4_hdn"]:null;
$clavadista2=isset($_POST["clavadista2_txt"])?$_POST["clavadista2_txt"]:null;
$clavadista3=isset($_POST["clavadista3_txt"])?$_POST["clavadista3_txt"]:null;
$clavadista4=isset($_POST["clavadista4_txt"])?$_POST["clavadista4_txt"]:null;
$categoria=isset($_POST["categoria_slc"])?$_POST["categoria_slc"]:null;
$individual=isset($_POST["individual_hdn"])?$_POST["individual_hdn"]:NULL;
$pareja=isset($_POST["pareja_hdn"])?$_POST["pareja_hdn"]:NULL;
$eq_juv=isset($_POST["eq_juv_hdn"])?$_POST["eq_juv_hdn"]:NULL;
$sexo=isset($_POST["sexo_hdn"])?$_POST["sexo_hdn"]:NULL;
$sexo2=isset($_POST["sexo2_hdn"])?$_POST["sexo2_hdn"]:NULL;
$sexo3=isset($_POST["sexo3_hdn"])?$_POST["sexo3_hdn"]:NULL;
$sexo4=isset($_POST["sexo4_hdn"])?$_POST["sexo4_hdn"]:NULL;

$_SESSION["llamo"]=isset($llamo)?$llamo:NULL;
$_SESSION["competencia"]=$competencia;
$_SESSION["cod_competencia"]=$cod_competencia;
$_SESSION["logo"]=$logo;
$_SESSION["equipo"]=$equipo; 
$_SESSION["clave"]=$clave;
$_SESSION["entrenador"]=$entrenador;
$_SESSION["cod_entrenador"]=$cod_entrenador;
$_SESSION["clavadista"]=$clavadista;
$_SESSION["imagen"]=$imagen;
$_SESSION["cod_clavadista"]=$cod_clavadista;
$_SESSION["clavadista2"]=$clavadista2;
$_SESSION["cod_clavadista2"]=$cod_clavadista2;
$_SESSION["imagen2"]=$imagen2;
$_SESSION["clavadista3"]=$clavadista3;
$_SESSION["cod_clavadista3"]=$cod_clavadista3;
$_SESSION["imagen3"]=$imagen3;
$_SESSION["clavadista4"]=$clavadista4;
$_SESSION["cod_clavadista4"]=$cod_clavadista4;
$_SESSION["imagen4"]=$imagen4;
$_SESSION["categoria"]=$categoria;
$_SESSION["modalidades"]=$modalidades;
$_SESSION["individual"]=$individual;
$_SESSION["pareja"]=$pareja;
$_SESSION["eq_juv"]=$eq_juv;
$_SESSION["sexo"]=$sexo;
$_SESSION["sexo2"]=$sexo2;
$_SESSION["sexo3"]=$sexo3;
$_SESSION["sexo4"]=$sexo4;

if (isset($busca_clavadista)) {
	header("Location: ../?op=php/busca-usuario.php&ori=$origen&tipo=C&parael1=1&bus=$clavadista");
	exit();	
}
	
if (isset($nuevo_clavadista)) {
	header("Location: ../?op=php/alta-usuario.php&ori=$origen&tipo=C&parael1=1");
	exit();	
}

if (isset($busca_clavadista2)) {
	header("Location: ../?op=php/busca-usuario.php&ori=$origen&tipo=C&parael2=1&bus=$clavadista2");
	exit();	
}
	
if (isset($nuevo_clavadista2)) {
	header("Location: ../?op=php/alta-usuario.php&ori=$origen&tipo=C&parael2=1");
	exit();	
}

if (isset($busca_clavadista3)) {
	header("Location: ../?op=php/busca-usuario.php&ori=$origen&tipo=C&parael3=1&bus=$clavadista3");
	exit();	
}
	
if (isset($nuevo_clavadista3)) {
	header("Location: ../?op=php/alta-usuario.php&ori=$origen&tipo=C&parael3=1");
	exit();	
}

if (isset($busca_clavadista4)) {
	header("Location: ../?op=php/busca-usuario.php&ori=$origen&tipo=C&parael4=1&bus=$clavadista4");
	exit();	
}
	
if (isset($nuevo_clavadista4)) {
	header("Location: ../?op=php/alta-usuario.php&ori=$origen&tipo=C&parael4=1");
	exit();	
}

if (isset($busca_entrenador)) {
	header("Location: ../?op=php/busca-usuario.php&ori=$origen&tipo=E&bus=$entrenador");
	exit();	
}
	
if (isset($nuevo_entrenador)) {
	header("Location: ../?op=php/alta-usuario.php&ori=$origen&tipo=E");
	exit();	
}
	
include("funciones.php");

$mensaje=NULL;

$cod_usuario=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
if (is_null($cod_usuario) or $cod_usuario=="")
	$mensaje= "Error: Debes iniciar sesión para poder hacer inscripciones";

if (is_null($mensaje)){
	if (is_null($competencia) or $competencia=="")
		$mensaje= "Error: No definiste la Competencia a participar";
}

if (is_null($mensaje)){
	$administrador=administrador_competencia($cod_usuario,$cod_competencia);
	if (substr($administrador, 0, 5)=="Error")
		$administrador=FALSE;
}

if (is_null($mensaje))
	if (!$administrador) 
		if (limite_inscripcion($cod_competencia)==FALSE)
			$mensaje="Error: Esta competencia ya no recibe inscripciones :(";

$conexion=conectarse();
if (is_null($mensaje) AND !$administrador and $cod_clavadista){
	$consulta="SELECT DISTINCT 
		c.clavadista as cod_cla, 
		c.clavadista2 as cod_cl2, 
		c.clavadista3 as cod_cl3, 
		c.clavadista4 as cod_cl4, 
		c.equipo, c.categoria, c.modalidad
	from planillas as c 
	WHERE (c.competencia=$cod_competencia 
		and c.clavadista=$cod_clavadista 
		and c.total IS NOT NULL";
	if ($cod_clavadista2) 
		$consulta .= " AND c.clavadista2=$cod_clavadista2";
	if ($cod_clavadista3) 
		$consulta .= " AND c.clavadista3=$cod_clavadista3";
	if ($cod_clavadista4) 
		$consulta .= " AND c.clavadista4=$cod_clavadista4";
	
	$consulta .= ")";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs>0)
		$mensaje="No puedes modificar la inscripción, pero puedes retirar o incluir planillas.";
}

if (is_null($mensaje)){
	$cod_equipo=determina_equipo($equipo,$cod_competencia);
	if (substr($cod_equipo, 0, 5)=="Error")
		$mensaje=$cod_equipo;
}

if (is_null($mensaje)){
	if (!$administrador) {
		$p=equivalencia($clave);
		$ret=verifica_clave_inscripcion($p,$cod_equipo,$cod_competencia);
		if (substr($ret, 0, 5)=="Error")
			$mensaje=$ret;
	}
}

/*if (is_null($mensaje)){  // si ya está inscrito en esta competencia
	if ($alta==1){
		$ret=ver_clavadista_competencia($cod_clavadista,$cod_competencia);
		if (substr($ret, 0, 5)=="Error")
			$mensaje=$ret;
	}
}
*/
if (is_null($mensaje)){
	$sexo=sexo_usuario($cod_clavadista);
	if (substr($sexo, 0, 5)=="Error")
		$mensaje=$sexo;
	else
		$_SESSION["sexo"]=$sexo;
}

if (is_null($mensaje)){
	$edad=edad_usuario($cod_clavadista);
	if (substr($edad, 0, 5)=="Error")
		$mensaje=$edad;
	else
		$_SESSION["edad"]=$edad;
}

if ($cod_clavadista2) {
	if (is_null($mensaje)){
		$sexo2=sexo_usuario($cod_clavadista2);
		if (substr($sexo2, 0, 5)=="Error")
			$mensaje=$sexo2;
		else
			$_SESSION["sexo2"]=$sexo2;
	}
	if (is_null($mensaje)){
		$edad2=edad_usuario($cod_clavadista2);
		if (substr($edad2, 0, 5)=="Error")
			$mensaje=$edad2;
		else
			$_SESSION["edad2"]=$edad2;
	}
}

if ($cod_clavadista3) {
	if (is_null($mensaje)){
		$sexo3=sexo_usuario($cod_clavadista3);
		if (substr($sexo3, 0, 5)=="Error")
			$mensaje=$sexo3;
		else
			$_SESSION["sexo3"]=$sexo3;
	}
	if (is_null($mensaje)){
		$edad3=edad_usuario($cod_clavadista3);
		if (substr($edad3, 0, 5)=="Error")
			$mensaje=$edad3;
		else
			$_SESSION["edad3"]=$edad3;
	}
}
if ($cod_clavadista4) {
	if (is_null($mensaje)){
		$sexo4=sexo_usuario($cod_clavadista4);
		if (substr($sexo4, 0, 5)=="Error")
			$mensaje=$sexo4;
		else
			$_SESSION["sexo4"]=$sexo4;
	}
	if (is_null($mensaje)){
		$edad4=edad_usuario($cod_clavadista4);
		if (substr($edad4, 0, 5)=="Error")
			$mensaje=$edad4;
		else
			$_SESSION["edad4"]=$edad4;
	}
}

if (is_null($mensaje)){
	$cod_categoria=substr($categoria, 0, 2);
	$ret=valida_edad_categoria($cod_categoria,$edad);
	if (substr($ret, 0, 5)=="Error")
		$mensaje=$ret;
}

if (is_null($mensaje)){
	if ($entrenador){
		$cod_entrenador=determina_usuario_nombre($entrenador);
		if (substr($cod_entrenador, 0, 5)=="Error")
			$mensaje=$cod_entrenador." Usa el enlace Buscar entrenador ;) " ;
	}
	else{
		$mensaje="Error: No haz definido el entrenador :(";
	}
}



if (is_null($mensaje)){
	if (!$cod_entrenador)
		$mensaje="Error: No haz definido el entrenador. Usa el enlace buscar entrenador :(";
}

if (is_null($mensaje)){
	$modalidades=NULL;
	$modalidades_sel=NULL;
	$ok=TRUE;
	$consulta="SELECT * FROM modalidades ORDER BY modalidad";
	$ejecutar_consulta=$conexion->query($consulta);
	while($registro=$ejecutar_consulta->fetch_assoc()){
		$cod_modalidad=utf8_decode($registro["cod_modalidad"]);
		$modalidad=utf8_decode($registro["modalidad"]);
		$name=$cod_modalidad."_rdo";
		$modalidad=isset($_POST[$name])?$_POST[$name]:NULL;
		if (isset($modalidad)) 
			if (is_null($modalidades))
				$modalidades=$cod_modalidad;
			else
				$modalidades=$modalidades."-".$cod_modalidad;

	 	if (isset($_POST[$name]))
			if (is_null($modalidades_sel))
				$modalidades_sel=$cod_modalidad;
			else
				$modalidades_sel=$modalidades_sel."-".$cod_modalidad;
	}
}

if (isset($mensaje1)) 
	$mensaje=$mensaje1;

$_SESSION["modalidades"]=isset($modalidades)?$modalidades:NULL;

if (is_null($mensaje)) {
	if (is_null($modalidades_sel)) {
		$mensaje="Error: No seleccionó las modalidades a competir  :(";
	}
}
if (is_null($mensaje)){
	$sel=explode("-", $modalidades_sel);
	$n=count($sel)-1;
	for ($i=0; $i <=$n ; $i++) { 
		$modalidad=$sel[$i];
		if (isset($modalidad)){
			$consulta="SELECT * FROM competenciapr WHERE (competencia=$cod_competencia AND categoria='$cod_categoria')";
			$ejecutar_consulta=$conexion->query($consulta);
			$num_regs=$ejecutar_consulta->num_rows;
			
			if ($num_regs==0){
				$mensaje="Error: No está programada la categoría <b>$categoria<b> en la competencia :(";
				break;				
			}
			$registro=$ejecutar_consulta->fetch_assoc();
			$modalidades_competencia=$registro["modalidades"];
			$pos = strpos($modalidades_competencia, $modalidad);
			if (is_null($pos)) {
				$mensaje="Error: No está programada la modalidad $modalidad para esta categoría :(";
				break;
			}
		}
	}
}
$conexion->close();
?>