<?php 
include("funciones.php");
$llamador="edita-clavadista-competencia.php";

if (isset($_GET["com"])){
	$cod_competencia=isset($_GET["cco"])?$_GET["cco"]:NULL;
	$logo=isset($_GET["lg"])?$_GET["lg"]:NULL;
	$cod_clavadista=isset($_GET["ccl"])?$_GET["ccl"]:NULL;
	$cod_clavadista2=isset($_GET["ccl2"])?$_GET["ccl2"]:NULL;
	$cod_clavadista3=isset($_GET["ccl3"])?$_GET["ccl3"]:NULL;
	$cod_clavadista4=isset($_GET["ccl4"])?$_GET["ccl4"]:NULL;
	$competencia=isset($_GET["com"])?$_GET["com"]:NULL;
	$equipo=isset($_GET["eq"])?$_GET["eq"]:NULL;
	$corto=isset($_GET["ceq"])?$_GET["ceq"]:NULL;
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	if (!is_null($llamados)) 
		if (array_key_exists($llamador, $llamados))
			$llamo=$llamados[$llamador];
	$conexion=conectarse();
	$consulta="SELECT DISTINCT 
		cla.nombre as nom_cla, 
		c.clavadista as cod_cla, 
		cl2.nombre as nom_cl2, 
		c.clavadista2 as cod_cl2, 
		cl3.nombre as nom_cl3, 
		c.clavadista3 as cod_cl3, 
		cl4.nombre as nom_cl4, 
		c.clavadista4 as cod_cl4, 
		ent.nombre as nom_ent, 
		c.entrenador as cod_ent, 
		c.equipo, c.categoria, c.modalidad, c.sexo, c.sexo2,
		cat.categoria as nom_cat, cat.edad_desde, 
		cat.edad_hasta, cla.imagen, cl2.imagen as imagen2
		from planillas as c 
		left join usuarios as cla on cla.cod_usuario=c.clavadista 
		left join usuarios as cl2 on cl2.cod_usuario=c.clavadista2
		left join usuarios as cl3 on cl3.cod_usuario=c.clavadista3
		left join usuarios as cl4 on cl4.cod_usuario=c.clavadista4
		left join usuarios as ent on ent.cod_usuario=c.entrenador 
		left join categorias as cat on cat.cod_categoria=c.categoria  
		WHERE (c.competencia=$cod_competencia 
			and c.clavadista=$cod_clavadista";
	if ($cod_clavadista2) {
		$consulta .= " AND c.clavadista2=$cod_clavadista2";
	}
	if ($cod_clavadista3) {
		$consulta .= " AND c.clavadista3=$cod_clavadista3";
	}
	if ($cod_clavadista4) {
		$consulta .= " AND c.clavadista4=$cod_clavadista4)";
	}
	else{
		$consulta .= ")";
	}

	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0)
		$mensaje="No está inscrito este clavadista en la competencia";
	else{
		$modalidades="";
		while($registro=$ejecutar_consulta->fetch_assoc()){
			$modalidad=$registro["modalidad"];
			if ($modalidades=="") {
				$entrenador=utf8_decode($registro["nom_ent"]);
				$clavadista=utf8_decode($registro["nom_cla"]);
				$clavadista2=isset($registro["nom_cl2"])?utf8_decode($registro["nom_cl2"]):NULL;
				$clavadista3=isset($registro["nom_cl3"])?utf8_decode($registro["nom_cl3"]):NULL;
				$clavadista4=isset($registro["nom_cl4"])?utf8_decode($registro["nom_cl4"]):NULL;
				$cod_entrenador=$registro["cod_ent"];
				$imagen=isset($registro["imagen"])?$registro["imagen"]:NULL;
				$imagen2=isset($registro["imagen2"])?$registro["imagen2"]:NULL;
				$imagen3=isset($registro["imagen3"])?$registro["imagen3"]:NULL;
				$imagen4=isset($registro["imagen4"])?$registro["imagen4"]:NULL;
				$sexo=isset($registro["sexo"])?utf8_decode($registro["sexo"]):NULL;
				$sexo2=isset($registro["sexo2"])?utf8_decode($registro["sexo2"]):NULL;
				$sexo3=isset($registro["sexo3"])?utf8_decode($registro["sexo3"]):NULL;
				$sexo4=isset($registro["sexo4"])?utf8_decode($registro["sexo4"]):NULL;
				$eq_juv=($registro["categoria"]=='Q1')?1:0;
				$categoria=	utf8_encode($registro["categoria"])."-".utf8_encode($registro["nom_cat"])." (de ".$registro["edad_desde"];
				if ($registro["edad_hasta"]==99)
					$categoria .= " años en adelante)";
				else
					if ($registro["edad_desde"]==$registro["edad_hasta"])
						$categoria .= " años)";
					else
						$categoria .= " a ".$registro["edad_hasta"]." años)";
				$modalidades=$modalidad;
			}
			else
				$modalidades.="-".$modalidad;
		}
	}
	$conexion->close();
}
include("toma-session-clavadista-competencia.php");

$tipo=(isset($_GET["tipo"])?$_GET["tipo"]:null);

$parael1=isset($_GET["parael1"])?$_GET["parael1"]:NULL;
$parael2=isset($_GET["parael2"])?$_GET["parael2"]:NULL;
$parael3=isset($_GET["parael3"])?$_GET["parael3"]:NULL;
$parael4=isset($_GET["parael4"])?$_GET["parael4"]:NULL;
if (isset($_SESSION["tipo"]))
	unset($_SESSION["tipo"]);
switch ($tipo) {
	case 'C':
		if ($parael4) {
			$clavadista4=$_GET["us"];
			$cod_clavadista4=$_GET["cod"];
			$imagen4=$_GET["img"];
			$sexo4=$_GET["sx"];
			unset($_SESSION["parael4"]);
		}
		if ($parael3) {
			$clavadista3=$_GET["us"];
			$cod_clavadista3=$_GET["cod"];
			$imagen3=$_GET["img"];
			$sexo3=$_GET["sx"];
			unset($_SESSION["parael3"]);
		}
		if ($parael2) {
			$clavadista2=$_GET["us"];
			$cod_clavadista2=$_GET["cod"];
			$imagen2=$_GET["img"];
			$sexo2=$_GET["sx"];
			unset($_SESSION["parael2"]);
		}
		if ($parael1) {
			$clavadista=(isset($_GET["us"])?$_GET["us"]:null);
			$cod_clavadista=(isset($_GET["cod"])?$_GET["cod"]:null);
			$imagen=(isset($_GET["img"])?$_GET["img"]:null);
			$sexo=$_GET["sx"];
			unset($_SESSION["parael1"]);
		}	
		break;
	case 'E':
		$entrenador=(isset($_GET["us"])?$_GET["us"]:null);
		$cod_entrenador=(isset($_GET["cod"])?$_GET["cod"]:null);
		break;
}
session_start();
	$individual=NULL;
	$pareja=NULL;
	$eq_juv=NULL;
	if ($cod_clavadista4 
		AND $cod_clavadista3 
		AND $cod_clavadista2
		AND $cod_clavadista)
		$eq_juv=1;
	elseif ($cod_clavadista2 
		AND $cod_clavadista){
		$pareja=1;
	}
	else{
		$individual=1;
	}

if ($eq_juv){
	$pareja=0;
	$individual=0;
}
if (!isset($individual)) 
	$individual=isset($_SESSION["individual"])?$_SESSION["individual"]:null;

if (!isset($pareja)) 
	$pareja=isset($_SESSION["pareja"])?$_SESSION["pareja"]:null;

if (!isset($equipo))
	$equipo=isset($_SESSION["equipo"])?$_SESSION["equipo"]:NULL;

if (!isset($eq_juv)) 
	$eq_juv=isset($_SESSION["eq_juv"])?$_SESSION["eq_juv"]:null;

if (!isset($mixto)) 
	$mixto=isset($_SESSION["mixto"])?$_SESSION["mixto"]:null;

if (!isset($llamo)) 
	$llamo=isset($_SESSION["llamo"])?$_SESSION["llamo"]:null;

if (!isset($competencia)) 
	$competencia=isset($_SESSION["competencia"])?$_SESSION["competencia"]:null;

if (!isset($cod_competencia)) 
	$cod_competencia=isset($_SESSION["cod_competencia"])?$_SESSION["cod_competencia"]:null;
if (!isset($logo)) 
	$logo=isset($_SESSION["logo"])?$_SESSION["logo"]:null;

if (!isset($corto)) 
	$corto=(isset($_SESSION["corto"])?$_SESSION["corto"]:null);

if (!isset($clave)) 
	$clave=(isset($_SESSION["clave"])?$_SESSION["clave"]:null);

if (!isset($entrenador)) 
	$entrenador=(isset($_SESSION["entrenador"])?$_SESSION["entrenador"]:null);

if (!isset($cod_entrenador)) 
	$cod_entrenador=(isset($_SESSION["cod_entrenador"])?$_SESSION["cod_entrenador"]:null);

if (!isset($clavadista)) 
	$clavadista=(isset($_SESSION["clavadista"])?$_SESSION["clavadista"]:null);

if (!isset($cod_clavadista)) 
	$cod_clavadista=(isset($_SESSION["cod_clavadista"])?$_SESSION["cod_clavadista"]:null);

if (!isset($sexo)) 
	$sexo=(isset($_SESSION["sexo"])?$_SESSION["sexo"]:null);

if (!isset($imagen)) 
	$imagen=(isset($_SESSION["imagen"])?$_SESSION["imagen"]:null);

if (!isset($clavadista2)) 
	$clavadista2=(isset($_SESSION["clavadista2"])?$_SESSION["clavadista2"]:null);
if (!isset($cod_clavadista2)) 
	$cod_clavadista2=(isset($_SESSION["cod_clavadista2"])?$_SESSION["cod_clavadista2"]:null);

if (!isset($imagen2)) 
	$imagen2=(isset($_SESSION["imagen2"])?$_SESSION["imagen2"]:null);

if (!isset($sexo2)) 
	$sexo2=(isset($_SESSION["sexo2"])?$_SESSION["sexo2"]:null);

if (!isset($clavadista3)) 
	$clavadista3=(isset($_SESSION["clavadista3"])?$_SESSION["clavadista3"]:null);
if (!isset($cod_clavadista3)) 
	$cod_clavadista3=(isset($_SESSION["cod_clavadista3"])?$_SESSION["cod_clavadista3"]:null);

if (!isset($imagen3)) 
	$imagen3=(isset($_SESSION["imagen3"])?$_SESSION["imagen3"]:null);

if (!isset($sexo3)) 
	$sexo3=(isset($_SESSION["sexo3"])?$_SESSION["sexo3"]:null);

if (!isset($clavadista4)) 
	$clavadista4=(isset($_SESSION["clavadista4"])?$_SESSION["clavadista4"]:null);
if (!isset($cod_clavadista4)) 
	$cod_clavadista4=(isset($_SESSION["cod_clavadista4"])?$_SESSION["cod_clavadista4"]:null);

if (!isset($imagen4)) 
	$imagen4=(isset($_SESSION["imagen4"])?$_SESSION["imagen4"]:null);

if (!isset($sexo4)) 
	$sexo4=(isset($_SESSION["sexo4"])?$_SESSION["sexo4"]:null);

if (!isset($categoria)) 
	$categoria=(isset($_SESSION["categoria"])?$_SESSION["categoria"]:null);
	
if (!isset($edad)) 
	$edad=(isset($_SESSION["edad"])?$_SESSION["edad"]:null);
	
if (!isset($modalidades)) 
	$modalidades=(isset($_SESSION["modalidades"])?$_SESSION["modalidades"]:null);
$alta=0;

?>
<form id="edita-clavadista-competencia" name="edita-frm" action="php/actualiza-clavadista-competencia.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend align="center" class="rotulo">
		<?php 
			if ($logo) 
				echo "<img class='textwrap' src='img/fotos/$logo' width='6%'/>";
				echo $competencia; 
		?>
		</legend>
		<?php include ("formulario-clavadista-competencia.php") ?>
		<div>
			<br>
			<input type="submit" id="enviar-act" title="Actualiza esta Inscripción" class="cambio" name="actualiza_sbm" value="Actualizar Inscripción" />
			&nbsp;
			<input type="submit" id="regresar" title="Regresa a clavadistas en competencia" class="cambio" name="regresar_sbm" value="Regresar" />
		</div>
	</fieldset>
</form>