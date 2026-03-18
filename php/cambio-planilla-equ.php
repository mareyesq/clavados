<?php 
include("funciones.php");
session_start();
if (isset($_GET["pl"])){
	$cod_planilla=$_GET["pl"];
	$llamo=isset($_GET["ori"])?$_GET["ori"]:NULL;
	$competencia=$_GET["com"];
	$cod_competencia=$_GET["cco"];
	$logo=$_GET["lg"];
	$cod_usuario=(isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:null);
	if (!isset($cod_usuario)){
		$mensaje="Debe iniciar sesión para poder registrar planillas";
		header("Location: ?op=php/inicia-sesion.php&mensaje=$mensaje&op1=php/cambio-planilla.php&pl=$cod_planilla&ori=$llamo");
		exit();
	}
	$conexion=conectarse();
	$consulta="SELECT DISTINCT
		com.competencia as competencia, 
		pla.equipo as cod_equipo,
		equ.equipo as equipo,
		ent.nombre as entrenador,
		cla.nombre as clavadista,
		cl2.nombre as clavadista2,
		cat.categoria as categoria,
		modalidades.modalidad as modalidad,
		pla.categoria as cod_categoria,
		cat.edad_desde as edad_desde,
		cat.edad_hasta as edad_hasta,
		pla.clavadista as cod_clavadista,
		pla.clavadista2 as cod_clavadista2,
		pla.entrenador as cod_entrenador,
		cla.nacimiento as nacimiento,
		cl2.nacimiento as nacimiento2,
		cla.imagen as imagen,
		cl2.imagen as imagen2,
		cla.sexo as cod_sexo,
		cl2.sexo as cod_sexo2,
		pla.participa_extraof
		FROM planillas as pla 
		LEFT JOIN competencias as com ON com.cod_competencia=pla.competencia
		LEFT JOIN competenciasq as equ ON (equ.competencia=pla.competencia AND equ.nombre_corto=pla.equipo)
		LEFT JOIN usuarios as ent ON ent.cod_usuario=pla.entrenador
		LEFT JOIN usuarios as cla ON cla.cod_usuario=pla.clavadista
		LEFT JOIN usuarios as cl2 ON cl2.cod_usuario=pla.clavadista2
		LEFT JOIN categorias as cat ON cat.cod_categoria=pla.categoria
		LEFT JOIN modalidades ON modalidades.cod_modalidad=pla.modalidad
		WHERE (pla.cod_planilla=$cod_planilla)";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0)
		$mensaje="No está registrada esta planilla";
	elseif ($num_regs==1){	
		$registro=$ejecutar_consulta->fetch_assoc();
		$cod_clavadista=$registro["cod_clavadista"];
		$cod_clavadista2=$registro["cod_clavadista2"];
		$cod_entrenador=$registro["cod_entrenador"];
		$nacimiento=$registro["nacimiento"];
		$cod_sexo=$registro["cod_sexo"];
		$nacimiento2=$registro["nacimiento2"];
		$cod_sexo2=isset($registro["cod_sexo2"])?$registro["cod_sexo2"]:NULL;
		$competencia=$registro["competencia"];
		$equipo=utf8_decode($registro["equipo"]);
		$cod_equipo=utf8_decode($registro["cod_equipo"]);
		$entrenador=utf8_decode($registro["entrenador"]);
		$clavadista=utf8_decode($registro["clavadista"]);
		$clavadista2=utf8_decode($registro["clavadista2"]);
		$imagen=$registro["imagen"];
		$imagen2=$registro["imagen2"];
		$modalidad=utf8_decode($registro["modalidad"]);
		$participa_extraof=$registro["participa_extraof"];
		$cod_categoria=utf8_encode($registro["cod_categoria"]);
		$categoria=$cod_categoria."-".utf8_encode($registro["categoria"])." (de ".$registro["edad_desde"];
		if ($registro["edad_hasta"]==99)
			$categoria.=" años en adelante)";
		else
			if ($registro["edad_desde"]==$registro["edad_hasta"])
				$categoria.=" años)";
			else
				$categoria.=" a ".$registro["edad_hasta"]." años)";
	}
	$saltos = array();
	$consulta="SELECT DISTINCT
		pla.ronda as ronda,
		pla.salto as codigo,
		sal.salto as salto, 
		pla.posicion as posicion,
		pla.altura as altura,
		pla.grado_dif as grado_dif,
		pla.obligatorio as obligatorio,
		pla.ejecutor as ejecutor
		FROM planillad as pla 
		LEFT JOIN saltos as sal ON sal.cod_salto=pla.salto
		WHERE pla.planilla=$cod_planilla";
	$ejecutar_consulta = $conexion->query($consulta);

	while ($row=$ejecutar_consulta->fetch_assoc()){
		$ejecutor=$row["ejecutor"];
		if ($ejecutor==1) 
			$nom_ejecutor=$clavadista;
		else
			$nom_ejecutor=$clavadista2;
		
		$saltos[$row["ronda"]]=array('cod_salto' => $row["codigo"], 'pos' => $row["posicion"], 'alt' => $row["altura"], 'obl' => $row["obligatorio"], 'sal' => utf8_encode($row["salto"]), 'gra_dif' => $row["grado_dif"], 'eje' => $ejecutor, 'nej' => $nom_ejecutor); 
	}	
	$dificultad=grado_total($cod_categoria);
	$conexion->close();
}
else{
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL;
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null;
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:NULL;
	$equipo=isset($_SESSION["equipo"])?$_SESSION["equipo"]:NULL;
	$cod_equipo=isset($_SESSION["cod_equipo"])?$_SESSION["cod_equipo"]:NULL;
	$clave=isset($_SESSION["clave"])?$_SESSION["clave"]:NULL;
	$entrenador=isset($_SESSION["entrenador"])?$_SESSION["entrenador"]:NULL;
	$cod_entrenador=isset($_SESSION["cod_entrenador"])?$_SESSION["cod_entrenador"]:NULL;
	$clavadista=isset($_SESSION["clavadista"])?$_SESSION["clavadista"]:NULL;
	$nacimiento=isset($_SESSION["nacimiento"])?$_SESSION["nacimiento"]:NULL;
	$cod_sexo=isset($_SESSION["cod_sexo"])?$_SESSION["cod_sexo"]:NULL;
	$clavadista2=isset($_SESSION["clavadista2"])?$_SESSION["clavadista2"]:NULL;
	$nacimiento2=isset($_SESSION["nacimiento2"])?$_SESSION["nacimiento2"]:NULL;
	$cod_sexo2=isset($_SESSION["cod_sexo2"])?$_SESSION["cod_sexo2"]:NULL;
	$cod_clavadista=isset($_SESSION["cod_clavadista"])?$_SESSION["cod_clavadista"]:NULL;
	$cod_clavadista2=isset($_SESSION["cod_clavadista2"])?$_SESSION["cod_clavadista2"]:NULL;
	$categoria=isset($_SESSION["categoria"])?$_SESSION["categoria"]:NULL;
	$imagen=isset($_SESSION["imagen"])?$_SESSION["imagen"]:NULL;
	$imagen2=isset($_SESSION["imagen2"])?$_SESSION["imagen2"]:NULL;
	$modalidad=isset($_SESSION["modalidad"])?$_SESSION["modalidad"]:NULL;
	$participa_extraof=isset($_SESSION["participa_extraof"])?$_SESSION["participa_extraof"]:NULL;
	$dificultad=isset($_SESSION["dificultad"])?$_SESSION["dificultad"]:NULL;
	$saltos=isset($_SESSION["saltos"])?$_SESSION["saltos"]:NULL;
}

$ok=isset($_GET["ok"])?$_GET["ok"]:NULL;

$mixto=($cod_sexo!==$cod_sexo2)?TRUE:FALSE;
$sx=$mixto ? "X":$cod_sexo;
$reglamento=reglamento(substr($categoria,0,2),$sx,$modalidad);

$sal=isset($_SESSION["copia_saltos"])?$_SESSION["copia_saltos"]:NULL;
$n=count($sal);
$linea=$sal[1];
$salto1=$linea["cod_salto"];
if ($salto1) {
	$copia_saltos=isset($sal)?$sal:NULL;
}

if ($_GET["co"]) {
	$ronda=$_GET["ron"];
	$cod_salto=$_GET["co"];
	$nom_salto=$_GET["sal"];
	$salto=$saltos[$ronda];
	$cod=$salto["cod_salto"];
	$pos=$salto["pos"];
	$alt=$salto["alt"];
	$obl=$salto["obl"];
	$sal=$salto["sal"];
	$dif=number_format($salto["gra_dif"],1);
	$eje=$salto["eje"];
	$nej=$salto["nej"];
	$saltos[$ronda] = array('cod_salto' => $cod_salto, 'pos' => $pos, 'alt' => $alt, 'obl' => $obl, 'sal' => $nom_salto, 'eje' => $eje, 'nej' => $nej); 
}

//include("saltos.php");

$_SESSION["competencia"]= (isset($competencia)?$competencia:null);
$_SESSION["cod_competencia"]= (isset($cod_competencia)?$cod_competencia:null);
$_SESSION["logo"]= (isset($logo)?$logo:null);
$_SESSION["equipo"]= (isset($equipo)?$equipo:null);
$_SESSION["cod_equipo"]= (isset($cod_equipo)?$cod_equipo:null);
$_SESSION["clave"]=isset($clave)?$clave:NULL;
$_SESSION["entrenador"]= (isset($entrenador)?$entrenador:null);
$_SESSION["cod_entrenador"]= (isset($cod_entrenador)?$cod_entrenador:null);
$_SESSION["clavadista"]= (isset($clavadista)?$clavadista:null);
$_SESSION["nacimiento"]= (isset($nacimiento)?$nacimiento:null);
$_SESSION["cod_sexo"]= (isset($cod_sexo)?$cod_sexo:null);
$_SESSION["clavadista2"]= isset($clavadista2)?$clavadista2:null;
$_SESSION["nacimiento2"]= (isset($nacimiento2)?$nacimiento2:null);
$_SESSION["cod_sexo2"]= (isset($cod_sexo2)?$cod_sexo2:null);
$_SESSION["cod_clavadista"]= (isset($cod_clavadista)?$cod_clavadista:null);
$_SESSION["cod_clavadista2"]= (isset($cod_clavadista2)?$cod_clavadista2:null);
$_SESSION["categoria"]= (isset($categoria)?$categoria:null);
$_SESSION["imagen"]= (isset($imagen)?$imagen:null);
$_SESSION["imagen2"]= (isset($imagen2)?$imagen2:null);
$_SESSION["modalidad"]= (isset($modalidad)?$modalidad:null);
$_SESSION["participa_extraof"]= (isset($participa_extraof)?$participa_extraof:null);
$_SESSION["dificultad"]= (isset($dificultad)?$dificultad:null);
$_SESSION["saltos"]= (isset($saltos)?$saltos:null);

if (!isset($cod_planilla))
	$cod_planilla=(isset($_SESSION["cod_planilla"])?$_SESSION["cod_planilla"]:NULL);
if (is_null($llamo)) 
	$llamo=isset($_SESSION["llamo"])?$_SESSION["llamo"]:NULL;

if (isset($_SESSION["llamados"])) {
	$llamados=$_SESSION["llamados"];
}
else
	$llamados=array();
$alta=0;
$llamados["php/busca-salto.php"]="php/cambio-planilla.php";
$_SESSION["llamados"]=$llamados;


?>
<form id="cambio-planilla" name="cambio-frm" action="php/modifica-planilla-equ.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
		<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
				echo $competencia; 
		?>
		</legend>
		<h3>Modificar Planilla (Equipo)</h3>

		<?php include ("php/formulario-planilla-equ.php"); ?>
		<div>
			<input type="hidden" id="cod-planilla" name="cod_planilla_hdn" value="<?php echo $cod_planilla; ?>" >	
			<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo; ?>">	
			<input type="hidden" id="cod-sexo" name="cod_sexo_hdn" value="<?php echo $cod_sexo; ?>" >	
			<input type="hidden" id="nacimiento" name="nacimiento_hdn" value="<?php echo $nacimiento; ?>" >	
			<input type="hidden" id="cod-sexo2" name="cod_sexo2_hdn" value="<?php echo $cod_sexo2; ?>" >	
			<input type="hidden" id="nacimiento2" name="nacimiento2_hdn" value="<?php echo $nacimiento2; ?>" >	
		</div>
	</fieldset>
</form>