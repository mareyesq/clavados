<?php 
if (isset($_GET["ori"])) {
	$buscar=(isset($_GET["bus"])?trim($_GET["bus"]):NULL);
	$llamo=(isset($_GET["ori"])?trim($_GET["ori"]):NULL);
	$llamo=str_replace("*", "&", $llamo);
	$competencia=(isset($_GET["comp"])?$_GET["comp"]:NULL);
	$cod_competencia=(isset($_GET["cco"])?$_GET["cco"]:NULL);
	$parael1=(isset($_GET["parael1"])?$_GET["parael1"]:NULL);
	$parael2=(isset($_GET["parael2"])?$_GET["parael2"]:NULL);
	$parael3=(isset($_GET["parael3"])?$_GET["parael3"]:NULL);
	$parael4=(isset($_GET["parael4"])?$_GET["parael4"]:NULL);
	$tipo=isset($_GET["tipo"])?$_GET["tipo"]:NULL;
	if ($parael1)
		$_SESSION["parael1"]=$parael1;
	if ($parael2)
		$_SESSION["parael2"]=$parael2;
	if ($parael3)
		$_SESSION["parael3"]=$parael3;
	if ($parael4)
		$_SESSION["parael4"]=$parael4;
	if ($tipo)
		$_SESSION["tipo"]=$tipo;
}
if (!$tipo) 
	$tipo=isset($_GET["tipo"])?$_GET["tipo"]:NULL;

$llamador="php/busca-usuario.php%tipo=$tipo";

session_start();
if (!isset($llamo)) {
	$llamo=isset($_POST["origen_hdn"])?trim($_POST["origen_hdn"]):NULL;
	if (isset($llamo))
		$llamo=str_replace("*", "&", $llamo);
}

if (!isset($parael1)) 
	$parael1=isset($_SESSION["parael1"])?$_SESSION["parael1"]:NULL;

if (!isset($parael2)) 
	$parael2=isset($_SESSION["parael2"])?$_SESSION["parael2"]:NULL;

if (!isset($parael3)) 
	$parael3=isset($_SESSION["parael3"])?$_SESSION["parael3"]:NULL;

if (!isset($parael4)) 
	$parael4=isset($_SESSION["parael4"])?$_SESSION["parael4"]:NULL;
if (!isset($tipo)) 
	$tipo=isset($_SESSION["tipo"])?$_SESSION["tipo"]:NULL;

if (isset($_GET["ubi"])) {
	$ubi=$_GET["ubi"];
	$_SESSION["ubi"]=$ubi;
}

if (is_null($ubi)) 
	$ubi=isset($_SESSION["ubi"])?$_SESSION["ubi"]:NULL;

if (!isset($buscar)) 
	$buscar=(isset($_SESSION["buscar"])?$_SESSION["buscar"]:NULL);

if (!isset($competencia))
	$competencia=(isset($_SESSION["competencia"])?$_SESSION["competencia"]:NULL);

if (isset($tipo)) 
	$tipo_sel=$tipo;

if (!isset($tipo_sel)) 
	$tipo_sel=(isset($_SESSION["tipo_sel"])?$_SESSION["tipo_sel"]:NULL);

if (!isset($pais_sel)) 
	$pais_sel=(isset($_SESSION["pais_sel"])?$_SESSION["pais_sel"]:NULL);

$consulta="SELECT DISTINCT u.cod_usuario as cod_us, u.nombre as nombre, u.email as email, u.administrador as administrador, u.clavadista as clavadista, u.entrenador as entrenador, u.juez as juez, u.sexo, countries.Country as pais, u.imagen, u.nacimiento
	FROM usuarios as u
	left join countries on countries.CountryId=u.pais ";

/*if ($buscar){
	if (isset($criterio)) 
		$criterio.=" AND (u.nombre LIKE '%$buscar%' or u.email LIKE '%$buscar%' or countries.Country LIKE '%$buscar%')";
	else
		$criterio=" WHERE (u.nombre LIKE '%$buscar%' or u.email LIKE '%$buscar%' or countries.Country LIKE '%$buscar%')";
}
*/
if ($buscar){
	$cad=explode(' ', $buscar);
	$n=count($cad);
	for ($i=0; $i < $n ; $i++) { 
		if (is_null($criterio))
			$criterio=" WHERE";
		else
			$criterio .= " AND";
		$criterio .= " (u.nombre LIKE '%".$cad[$i]."%'
				OR u.email LIKE '%".$cad[$i]."%')";
	}			
}

if ($pais_sel) {
	$cadena = " pais=$pais_sel";
	if ($criterio) 
		$criterio .= " AND ".$cadena;
	else
		$criterio = " WHERE ".$cadena;
}
	
if ($tipo_sel) {
	switch ($tipo_sel) {
		case 'A':
			$cadena = " u.administrador='A'";
			$tipo_us="Administrador";
			break;
		case 'C':
			$cadena = " u.clavadista='C'";
			$tipo_us="Clavadista";
			break;
		case 'E':
			$cadena = " u.entrenador='E'";
			$tipo_us="Entrenador";
			break;
		case 'J':
			$cadena = " u.juez='J'";
			$tipo_us="Juez";
			break;
		default:
			$cadena=NULL;
			$tipo_us=null;
			break;
	}
	if ($cadena) 
		if ($criterio) 
			$criterio .= " AND ".$cadena;
		else
			$criterio = " WHERE ".$cadena;
}

if (isset($criterio)) 
	$consulta.=$criterio;

$consulta .= " ORDER BY nombre";

include("funciones.php");
$conexion=conectarse();
$ejecutar_consulta = $conexion->query($consulta);
$num_regs=$ejecutar_consulta->num_rows;
?>

<form id="buscar-usuarios" name="buscar-usuarios-frm" action="?op=php/busca-usuario-1.php" method="post" enctype="multipart/form-data">
<fieldset> 
<legend align='center' class="rotulo" >Buscar usuarios <?php echo "$tipo_us"; ?></legend>
	<div>
		<label for="buscar" >Buscar: </label>
		<input type="search" class="cambio" id="buscar" name="buscar_src" value="<?php echo $buscar; ?>">
		<input type="submit" class="cambio" name="buscar_sbm" value="Buscar" title="Busca en todos los usuarios registrados teniendo en cuenta los filtros">
		<input type="submit" id="nuevo" class="cambio" name="nuevo_sbm" value="Nuevo" title="Regístrate como nuevo Usuario del Sistema">
		<input type="submit" id="regresar" title="Regresa" class="cambio" name="regresar_sbm" value="Regresar" />
		<label class="rotulo">Registros:</label>
		<span><?php echo number_format($num_regs); ?></span>&nbsp;
		<label class="rotulo">Tipo:</label>
		<select id='tipo-sel' class='cambio' name='tipo_sel_slc' title='Filtra usuarios por Tipo' onChange="this.form.submit()" >
			<option value="" >- - -</option>
			<option value="A" <?php if ($tipo_sel=="A") echo " selected"; ?>>Administrador</option>
			<option value="C" <?php if ($tipo_sel=="C") echo " selected"; ?>>Clavadista</option>
			<option value="E" <?php if ($tipo_sel=="E") echo " selected"; ?>>Entrenador</option>
			<option value="J" <?php if ($tipo_sel=="J") echo " selected"; ?>>Juez</option>
		</select>
		<label class="rotulo">País:</label>
		<select id='pais-sel' class='cambio' name='pais_sel_slc' title='Filtra usuarios por País' onChange="this.form.submit()" >
			<option value="" >- - -</option>
			<?php include("select-pais-registrado.php"); ?> 
		</select>
	</div>
	<div id="div1">
	<table width='100%' border='1' bordercolor='#0000FF' cellspacing='0.5em' cellpadding='0.5em' class="tablas">
	<thead>
		<tr align='center' >
		<th>Usuario</th>
		<th>email</th>
		<th title="administrador">Adm.</th>
		<th title="clavadista">Clav.</th>
		<th title="entrenador">Entr.</th>
		<th>Juez</th>
		<th>Pais</th>	  
		<th colspan="6">Opciones</th>	  
		</tr> 
	</thead>
	<tbody>
	<?php 

		if ($ejecutar_consulta)
			$num_regs=$ejecutar_consulta->num_rows;

		if (!$num_regs){
			$mensaje="No hay registros ";
			if ($buscar) $mensaje=$mensaje." con nombre $buscar";
			$mensaje=$mensaje." :(";
		}
		else
			$linea=FALSE;
			while ($row=$ejecutar_consulta->fetch_assoc()){
				$cod_us=$row["cod_us"];
				$nombre=utf8_decode($row["nombre"]);
/*				$nombre=strtolower($nombre);
				$nombre=utf8_decode(ucwords($nombre));
*/				$imagen=utf8_encode($row["imagen"]);
				$email=utf8_encode($row["email"]);
				$sexo=$row["sexo"];
				$administrador=($row["administrador"]=="A"?"*":NULL);
				$clavadista=($row["clavadista"]=="C"?"*":NULL);
				$entrenador=($row["entrenador"]=="E"?"*":NULL);
				$juez=($row["juez"]=="J"?"*":NULL);
				$pais=utf8_decode($row["pais"]);
				if ($clavadista){
					$edad=edad_deportiva($row["nacimiento"]);
					$nom_usu=$nombre." (".$edad." años)";
				}
				else{
					$nom_usu=$nombre;
					$edad=NULL;
				}


/*				if ($linea)
					echo "<tr class='linea1'";
				else
*/					echo "<tr";
				$linea=!$linea;

    			echo " align='center'>";
    			if ($llamo) {
	    			echo "<td align='left'><a class='enlaces' href='?op=".$llamo."&us=$nombre&cod=$cod_us&comp=$competencia&img=$imagen&ubi=$ubi&sx=$sexo&ed=$edad&tipo=$tipo&parael1=$parael1&parael2=$parael2&parael3=$parael3&parael4=$parael4'>";
    			}
    			else
    				echo "<td align='left'><a class='enlaces' href='?op=php/cambio-usuario.php&us=$cod_us&ori=busca-usuario.php'>";	
				if ($imagen) 
	    			echo "<img class='textwrap'src='img/fotos/$imagen' width='9%'/>&nbsp;&nbsp;";
				echo "$nom_usu</a></td>";
    			echo "<td>".$email."</td>";  
    			echo "<td>".$administrador."</td>";  
    			echo "<td>".$clavadista."</td>";  
    			echo "<td>".$entrenador."</td>";  
    			echo "<td>".$juez."</td>";  
    			echo "<td>".$pais."</td>";  
    			echo "<td><a class='enlaces_tab' href='?op=php/cambio-usuario.php&us=$cod_us&ori=busca-usuario.php'>Modificar</a></td>";
    			echo "</tr>"; 
		}	
		$conexion->close();
	?>
	</tbody>
	</table> 
	</div>
	<div>
		<input type="hidden" id="competencia" name="competencia_hdn" value="<?php echo $competencia; ?>" >
		<input type="hidden" id="llamo" name="llamo_hdn" value="<?php echo $llamo; ?>" >
		<input type="hidden" id="llamador" name="llamador_hdn" value="<?php echo $llamador; ?>" >
	</div>
</fieldset>

</form>