<?php 
//El parametro de $extension determina qué tipo de imagen no se borrará, por ejemplo: si es jpg, significa que la imagen con extensión .jpg se queda en el servidor y si existen imágenes con el mismo nombre pero con extensión png o gif, se eliminarán. Con esta función evito tener imágenes duplicadas con didtintas extensiones para cada perfil. La función file_exists evalua si un archivo existe y la funcion unlink borra un archivo del servidor
	function borrar_imagenes($ruta,$extension){
		switch ($extension){
			case '.jpg':
				if (file_exists($ruta."png"))
					unlink($ruta."png");	
				if (file_exists($ruta."gif"))
					unlink($ruta."gif");	
			break;
			case '.gif':
				if (file_exists($ruta."png"))
					unlink($ruta."png");	
				if (file_exists($ruta."jpg"))
					unlink($ruta."jpg");	
			break;
			case '.png':
				if (file_exists($ruta."jpg"))
					unlink($ruta."jpg");	
				if (file_exists($ruta."gif"))
					unlink($ruta."gif");	
			break;
		}
	}

//Función para subir la imagen del perfil del usuario
	function subir_imagen($tipo,$imagen,$email){
	//strstr($cadena1,$cadena2)	sirve para evaluar si en la primera cadena de texto existe la segunda cadena de texto
	// Si dentro del tipo del archivo se encuentra la palabra image, significa que el archivo es una imagen
		if (strstr($tipo,"image")) {
		//El archivo si es una imagen
		//para saber de que tipo de extensión es la imagen
			if (strstr($tipo,"jpeg"))
				$extension=".jpg";
			else if (strstr($tipo, ".gif"))
				$extension=".gif";
			else if (strstr($tipo, "png"))
				$extension=".png";
		//para saber si la imagen tiene el ancho correcto que es de 420px	
			$tam_img = getimagesize($imagen);
			$ancho_img=$tam_img[0];
			$alto_img=$tam_img[1];
			$ancho_img_deseado=420;
		//Si la imagen tiene el ancho mayor a 420px su tamaño 
			if ($ancho_img>$ancho_img_deseado) {
			//reajustamos, por una regla de 3, obtengo el alto de la imagen de manera proporcional al ancho nuevo que será de 420
				$nuevo_ancho_img=$ancho_img_deseado;
				$nuevo_alto_img=($alto_img/$ancho_img)*$nuevo_ancho_img;
			//Creo una imagen en color real con las nuevas dimensiones
				$img_reajustada=imagecreatetruecolor($nuevo_ancho_img, $nuevo_alto_img);
			//Creo una imagen basada en la original, dependiendo de su extensión es el tipo que crearé
				switch ($extension){
					case ".jpg":
						$img_original=imagecreatefromjpeg($imagen);
					//Reajusto la imagen nueva con respecto a la original
						imagecopyresampled($img_reajustada, $img_original, 0, 0, 0, 0, $nuevo_ancho_img, $nuevo_alto_img, $ancho_img, $alto_img);
					//Guardo la imagen reescalada en el servidor
						$nombre_img_ext="../img/fotos/".$email.$extension;
						$nombre_img="../img/fotos/".$email;
						imagejpeg($img_reajustada,$nombre_img_ext,100);
						borrar_imagenes($nombre_img,".jpg");
						break;
					case ".gif":
						$img_original=imagecreatefromgif($imagen);
					//Reajusto la imagen nueva con respecto a la original
						imagecopyresampled($img_reajustada, $img_original, 0, 0, 0, 0, $nuevo_ancho_img, $nuevo_alto_img, $ancho_img, $alto_img);
					//Guardo la imagen reescalada en el servidor
						$nombre_img_ext="../img/fotos/".$email.$extension;
						$nombre_img="../img/fotos/".$email;
						imagegif($img_reajustada,$nombre_img_ext);
						borrar_imagenes($nombre_img,".gif");
						break;
					case ".png":
						$img_original=imagecreatefrompng($imagen);
					//Reajusto la imagen nueva con respecto a la original
						imagecopyresampled($img_reajustada, $img_original, 0, 0, 0, 0, $nuevo_ancho_img, $nuevo_alto_img, $ancho_img, $alto_img);
					//Guardo la imagen reescalada en el servidor
						$nombre_img_ext="../img/fotos/".$email.$extension;
						$nombre_img="../img/fotos/".$email;
						imagepng($img_reajustada,$nombre_img_ext,9);
						borrar_imagenes($nombre_img,".png");
						break;
				}
			} else {
			// no se reajusta y se sube
			// guardo la ruta que tendra en el servidor la imagen
				$destino="../img/fotos/".$email.$extension;
			// se sube la foto
				move_uploaded_file($imagen, $destino) or die ("no se pudo subir la imagen");
			// ejecuto la funcion para borrar posibles imágenes dobles para el perfil
				$nombre_img="../img/fotos/".$email;
				borrar_imagenes($nombre_img,$extension);
			}
			// Asigno el nombre de la foto que se guardará en la BD como cadena de texto
			$imagen=$email.$extension;
			return $imagen;
		} else {
			return false;
		}
	}

//Función para subir archivos
	function subir_archivo($tipo,$archivo,$competencia,$dest){
		if (strstr($tipo,"officedocument")) {
		//El archivo si es una imagen
		//para saber de que tipo de extensión es la imagen
			if (strstr($tipo,"word"))
				$extension=".docx";
			else if (strstr($tipo, "spreadsheet"))
				$extension=".xlsx";
		}
		if (strstr($tipo,"powerpoint"))
				$extension=".ppt";

		if (strstr($tipo,"pdf"))
				$extension=".pdf";

		if (isset($extension)) {
			$destino="../img/fotos/".$dest.$competencia.$extension;
				// se sube el archivo
			move_uploaded_file($archivo, $destino) or die ("no se pudo subir el archivo");
			// Asigno el nombre del archivo a guardar n la BD como cadena de texto
			$nombre=$dest.$competencia.$extension;
			return $nombre;
		}
		else
			return FALSE;

	}

	function determina_equipo($nom_equipo,$cod_competencia)
	{
		$conexion=conectarse();
		$consulta="SELECT nombre_corto FROM competenciasq WHERE (competencia=$cod_competencia AND equipo='$nom_equipo')";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==1){
			$row=$ejecutar_consulta->fetch_assoc();
			$conexion->close();
			return $row["nombre_corto"];
		}
		else{
			$conexion->close();
			return "Error: No está inscrito el Equipo <b>$nom_equipo<b> en la competencia :(";
		}
	}		
	function determina_pais($nom_pais)
	{
		/* verificar la conexión */
		$conexion=conectarse();
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT CountryId FROM countries WHERE Country='$nom_pais'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrado este País :(";
		}
		else 
		{	
			if ($num_regs==1){	
				$registro_pais=$ejecutar_consulta->fetch_assoc();
				$cod_pais=$registro_pais["CountryId"];
				$conexion->close();
				return $cod_pais;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para este Pais :/";
				}
		}
	}	
	function determina_categoria($categoria)
	{
		$conexion=conectarse();
		/* verificar la conexión */
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT cod_categoria FROM categorias WHERE categoria='$categoria'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada esta Categoría :(";
		}
		else 
		{	
			if ($num_regs==1){	
				$registro=$ejecutar_consulta->fetch_assoc();
				$cod_categoria=$registro["cod_categoria"];
				$conexion->close();
				return $cod_categoria;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para esta Categoría :/";
				}
		}
	}	

	function determina_ciudad($nom_ciudad,$cod_pais)
	{
		$conexion=conectarse();
		/* verificar la conexión */
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT CityId FROM cities WHERE City='$nom_ciudad' and CountryId=$cod_pais";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada la Ciudad <b>$nom_ciudad<b> :(";
		}
		else 
		{	
			if ($num_regs==1){
				$conexion->close();	
				$registro_ciudad=$ejecutar_consulta->fetch_assoc();
				$cod_ciudad=$registro_ciudad["CityId"];
				return $cod_ciudad;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para la Ciudad <b>$nom_ciudad<b> :/";
				}
		}
	}	

	function determina_usuario($email)	{
		$conexion=conectarse();
		/* verificar la conexión */
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT cod_usuario FROM usuarios WHERE email='$email'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrado el usuario con email <b>$email<b> :(";
		}
		else 
		{	
			if ($num_regs==1){	
				$registro_usuario=$ejecutar_consulta->fetch_assoc();
				$cod_usuario=$registro_usuario["cod_usuario"];
				$conexion->close();
				return $cod_usuario;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para el usuario con email <b>$email<b> :/";
				}
		}
	}

	function determina_usuario_nombre($nombre)	{
		$conexion=conectarse();
		/* verificar la conexión */
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT cod_usuario FROM usuarios WHERE nombre='$nombre'";
		$ejecutar_consulta = $conexion->query(utf8_encode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrado el usuario <b>$nombre<b> :(";
		}
		else 
		{	
			if ($num_regs==1){	
				$registro_usuario=$ejecutar_consulta->fetch_assoc();
				$cod_usuario=$registro_usuario["cod_usuario"];
				$conexion->close();
				return $cod_usuario;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para el usuario <b>$nombre<b> :/";
				}
		}
	}

	function determina_competencia($nom_competencia)
	{
		$conexion=conectarse();
		$consulta="SELECT cod_competencia FROM competencias WHERE competencia='$nom_competencia'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada la Competencia <b>$nom_competencia<b> :(";
		}
		else
		{ 
			if ($num_regs==1){	
				$registro_competencia=$ejecutar_consulta->fetch_assoc();
				$cod_competencia=$registro_competencia["cod_competencia"];
				$conexion->close();
				return $cod_competencia;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para la Competencia <b>$nom_competencia<b> :/";
				}
		}
	}		

	function determina_modalidad($modalidad)	{
		$conexion=conectarse();
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT cod_modalidad FROM modalidades WHERE modalidad='$modalidad'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada la modalidad <b>$modalidad<b> :(";
		}
		else 
		{	
			if ($num_regs==1){	
	
				$registro=$ejecutar_consulta->fetch_assoc();
				$cod_modalidad=$registro["cod_modalidad"];
				$conexion->close();
				return $cod_modalidad;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para la modalidad <b>$modalidad<b> :/";
				}
		}
	}

	function lee_modalidad($modalidad)	{
		$conexion=conectarse();
		if (mysqli_connect_errno()) {
			$row['error']="Error: Conexión fallida:  mysqli_connect_error() :/";
    		return $row;
		}
		$consulta="SELECT * FROM modalidades WHERE cod_modalidad='$modalidad'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			$row['error']="Error: No está registrada la modalidad <b>$modalidad<b> :/";
			return $row;
		}
		else 
		{	
			if ($num_regs==1){	
				$row=$ejecutar_consulta->fetch_assoc();
				$conexion->close();
				return $row;
			}
				else{
					$conexion->close();
					$row['error']="Error: Hay varios registros para la modalidad <b>$modalidad<b> :/";
					return $row; 
				}
		}
	}

	function lee_categoria($cod_cat)	{
		$conexion=conectarse();
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT DISTINCT * FROM categorias WHERE cod_categoria='$cod_cat'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada la categoría <b>$cod_cat<b> :(";
		}
		else 
		{	
			if ($num_regs==1){	
				$row=$ejecutar_consulta->fetch_assoc();
				$conexion->close();
				return $row;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para la categoría <b>$cod_cat<b> :/";
				}
		}
	}

	function descripcion_cat_sex($cat_sex){
		$arr=explode('-', $cat_sex);
		$row=lee_categoria($arr[0]);
		$cat=$row['categoria'];
		$sex=($arr[1]=='F')?" - Damas":" - Varones";
		$descripcion=$cat.$sex;

		return $descripcion;
	}

function amddma($fecha, $corto) {
	global $separador;
	$separador = isset($separador) ? $separador : "-";

	if (isset($fecha)) {
		$cad = explode("-", $fecha);
		$fecha = $cad[2] . $separador . $cad[1] . $separador;
		if ($corto) {
			$fecha .= substr($cad[0], 2, 2);
		} else {
			$fecha .= $cad["0"];
		}

	} else {
		$fecha = "Error: la fecha <b>$fecha</b> está errada :(";
	}

	return $fecha;
}

	function equipo_inscrito($competencia,$equipo){
		$conexion=conectarse();
		$cod_competencia=determina_competencia($competencia,$conexion);
		$cod_equipo=determina_equipo($equipo,$conexion);
		$consulta="SELECT DISTINCT competencia, equipo FROM competenciasq WHERE (competencia=$cod_competencia) and (equipo=$cod_equipo)";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		$conexion->close();
		if ($num_regs==0)
			return false;
		else
			return true;
	}

	function equivalencia($entra){
		$cad1= " abcdefghijklmnopqrstuvwxyz01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$cad2= " nopqrstuvwxyz01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklm";
		
		$sale="";
		$n=strlen($entra);
		for ($i=0;$i<=$n-1;$i++) {
			$car=$entra[$i];
			$p=strpos($cad1, $car);
			if (!$p=="") {
				$car=$cad2[$p];
			}
			$sale.=$car;
		}
		return $sale;
	}

	function inversa($entra){
		$cad1= " abcdefghijklmnopqrstuvwxyz01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$cad2= " nopqrstuvwxyz01234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklm";
		
		$sale="";
		$n=strlen($entra);
		for ($i=0;$i<=$n-1;$i++) {
			$car=$entra[$i];
			$p=strpos($cad2, $car);
			if (!$p=="") {
				$car=$cad1[$p];
			}
			$sale.=$car;
		}
		return $sale;
	}

	function navegador_compatible($cual) {
		$nav=$_SERVER['HTTP_USER_AGENT'];
		//Demilitador 'i' para no diferenciar mayus y minus
		if (preg_match("/".$cual."/i", $nav)) 
    		return true;
    	else 
       		return false;
	}
function mes_num($cual){
	switch ($cual) {
		case 'Enero':
		$m='01';
		break;
		case 'Febrero':
		$m='02';
		break;
		case 'Marzo':
		$m='03';
		break;
		case 'Abril':
		$m='04';
		break;
		case 'Mayo':
		$m='05';
		break;
		case 'Junio':
		$m='06';
		break;
		case 'Julio':
		$m='07';
		break;
		case 'Agosto':
		$m='08';
		break;
		case 'Septiembre':
		$m='09';
		break;
		case 'Octubre':
		$m='10';
		break;
		case 'Noviembre':
		$m='11';
		break;
		case 'Diciembre':
		$m='12';
		break;
		default:
		$m='0';
		break;
	}

	return $m;
}


	function valida_usuario($llave,$pass){
		$conexion=conectarse();
		$ok=false;
		$consulta="SELECT email, password FROM usuarios WHERE email='$llave'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==1){	
			$registro_usuario=$ejecutar_consulta->fetch_assoc();
			$p=$registro_usuario["password"];
			$preal=inversa($p);
			$conexion->close();
			if (strcmp($pass, $p) == 0) 
				$ok=true;
		}
		else
			$conexion->close();
		return $ok;
	}

	function recordar_passw($email){
		$conexion=conectarse();
		$consulta="SELECT password, nombre 
			FROM usuarios WHERE email='$email'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==1){	
			$row=$ejecutar_consulta->fetch_assoc();
			$p=$row["password"];
			$nombre=$row["nombre"];
			$passw=inversa($p);

			$para= trim($email);
			$titulo= utf8_decode("Recordar contraseña de competencias de clavados");
			$texto="Hola ".$nombre.", La contraseña de la página de competencias de clavados es: ".$passw;
			$texto = str_replace("\n.", "\n..", $texto);
			$texto=utf8_decode($texto) ;

	// Para enviar un correo HTML, debe establecerse la cabecera Content-type
//			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
//			$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

//			$cabeceras = "From: soporte@softneosas.com";
			$de = "soporte@softneosas.com";
			$conexion->close();


			$conexion_gral = conectar_gral();
			$ahora = ahora(NULL);
			$usuario = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : NUll;
			$consulta = "INSERT INTO emails_enviar (para, asunto, texto, de, envio, fecha_alta) VALUES ('$para', '$titulo', '$texto', '$de', '$usuario', '$ahora')";
			$ejecutar_consulta = $conexion_gral->query(utf8_encode($consulta));
			if (!$ejecutar_consulta) {
				$mensaje="No se pudo enviar la contraseña al email <b>$email</b> :(";
			}
			else{
				$mensaje=NULL;
			}
			$conexion_gral->close();
			if (!$mensaje)
				return "Se envió la contraseña al usuario con email <b>$email<b> :)";
			else
				return "No se pudo enviar la contraseña al email <b>$email</b> :(";


/*
			if (mail($para, $titulo, $texto, $cabeceras))
				return "Se envió la contraseña al usuario con email <b>$email<b> :)";
			else
				return "No se pudo enviar la contraseña al email <b>$email</b> :(";
*/		}
		$conexion->close();
		return "Error: no está registrado el usuario con email <b>$email<b> :(";
	}

function notifica_alta_competencia($competencia,$organizador,$email){
		$para= $email;
//		$para= "miguelreyes1@hotmail.com";
		$titulo= "Nueva competencia de clavados";
		$titulo=utf8_decode($titulo);
		$texto="<html><head><title>Nueva Competencia".$competencia."</title></head><body><p align='justify'>Buen d&iacute;a ".$organizador."</p><p>Muchas gracias por elegirnos para prestarles el servicio del software de administraci&oacute;n de competencias de clavados. Se registr&oacute; la competencia de clavados ".$competencia." en la p&aacute;gina <a href='http://www.softneosas.com/saltos'>www.softneosas.com/saltos</a>.</p><p>Para cancelar los derechos de uso del software para esta competencia, por favor hacer giro a nombre de Miguel Angel Reyes Quec&aacute;n, n&uacute;mero de c&eacute;dula; 2994966, celular: +573164761171 en Cali-Colombia, por valor de doscientos d&oacute;lares am&eacute;ricanos (US$200) y enviar copia del giro por WhatsApp o al correo electr&oacute;nico de soporte. Una vez recibida la copia del giro procedemos a activar su competencia y podr&aacute; configurar las pruebas y programaci&oacute;n de eventos de la misma, contando con nuestro soporte permanente a trav&eacute;s de WhatsApp y acceso remoto (AnyDesk) en caso que se requiera. También pueden hacer giro a través del  <a href='https://daviplata.com/wps/portal/daviplata/Home/ComoMeterlePlata/GirosInternacionales/GirosPorDaviPlata' target='_blank' >servicio Daviplata</a>, siga las instrucciones de los pasos 2 y 3, el numero de celular a cargar es 3164761171.</p><p>Quedamos atentos a resolver cualquier inquietud que tenga con nuestro servicio y le reiteramos nuestro agradecimiento por escogernos.</p><p>Contacto: <a href='mailto:soporte@softneosas.com'>soporte@softneosas.com</a></p></table></body></html>";

		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras .= "From: info@softneosas.com" . "\r\n";
		$cabeceras .= "Cc: gerencia@softneosas.com" . "\r\n";
		$cabeceras .= "Bcc: miguelreyes1@hotmail.com, acabadia@hotmail.com" . "\r\n";
		mail($para, $titulo, $texto, $cabeceras);

		return;
	}

function notifica_autorizacion_competencia($competencia,$organizador,$email){
	$para= $email;
	$titulo= "Nueva competencia de clavados";
	$titulo=utf8_decode($titulo);
	$texto="<html><head><title>Nueva Competencia".$competencia."</title></head><body><p align='justify'>Buen d&iacute;a ".$organizador."</p><p>La competencia de clavados ".$competencia." est&aacute; activada y desde ya pueden configurar las pruebas, categor&iacute;as y eventos de la competencia. Luego de hacer esto, cada equipo, que desee participar en la competencia podr&aacute; acceder a la p&aacute;gina para realizar su inscripci&oacute;n en la competencia y la de sus clavadistas, junto con sus planillas de saltos a trav&eacute;s de la direcci&oacute;n: <a href='http://www.softneosas.com/saltos'>www.softneosas.com/saltos</a>.&nbsp; Así mismo, cuenta con nuestro soporte permanente a través de WhatsApp y acceso remoto (AnyDesk) en caso que se requiera.</p><p>Quedamos atentos a resolver cualquier inquietud que tenga con nuestro servicio y le reiteramos nuestro agradecimiento por escogernos.</p><p>Contacto: <a href='mailto:soporte@softneosas.com'>soporte@softneosas.com</a></p></table></body></html>";

		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras .= "From: info@softneosas.com" . "\r\n";
		$cabeceras .= "Cc: gerencia@softneosas.com" . "\r\n";
		$cabeceras .= "Bcc: miguelreyes1@hotmail.com, acabadia@hotmail.com" . "\r\n";
		mail($para, $titulo, $texto, $cabeceras);

		return;

}

/*	function recordar_clave_inscripcion($cod_competencia,$corto){
		$conexion=conectarse();
		$consulta="SELECT equipo, email, clave_inscripciones, representante 
			FROM competenciasq WHERE competencia=$cod_competencia and nombre_corto='$corto'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==1){	
			$row=$ejecutar_consulta->fetch_assoc();
			$p=$row["clave_inscripciones"];
			$nombre=$row["representante"];
			$email=$row["email"];
			$equipo=$row["equipo"];
			$passw=inversa($p);
			$para= trim($email);
			$titulo= "Recordar clave de inscripciones de equipo";
			$texto=$nombre.", La clave de inscripciones de la página de competencias de clavados para el equipo ".$equipo." es: ".$passw;
			$texto="<html><head><title>Clave para equipo".$equipo."</title></head><body><p align='justify'>Buenos d&iacute;as</p><p>La clave del equipo ".$equipo." para la competencia de clavados es: $passw</p><p>Ingrese a la p&aacute;gina: <a href='http://www.softneosas.com/clavados'>www.softneosas.com/clavados</a></p><p>Contacto: <a href='mailto:soporte@softneosas.com'>soporte@softneosas.com</a></p></table></body></html>";

			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
			$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";			
			$cabeceras .= "From: soporte@softneosas.com";
			mail($para, $titulo, $texto, $cabeceras);
			$men="Se envió la clave de inscripciones al usuario con email <b>$email<b> :)";
		}
		else
			$men="Error: no está inscrio el equipo con nombre corto <b>$corto<b> :(";
		$conexion->close();
		return $men;
	}
*/
	function recordar_clave_inscripcion($cod_competencia,$corto){
		$conexion=conectarse();
		$consulta="SELECT equipo, email, clave_inscripciones, representante
			FROM competenciasq WHERE competencia=$cod_competencia and nombre_corto='$corto'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==1){	
			$row=$ejecutar_consulta->fetch_assoc();
			$p=$row["clave_inscripciones"];
			$nombre=$row["representante"];
			$email=$row["email"];
			$equipo=$row["equipo"];
			$passw=inversa($p);
			$para= trim($email);
			$titulo= "Recordar clave de inscripciones de equipo";
			$texto="hola ".$nombre.", <br>La clave de inscripciones de la página de competencias de clavados para el equipo ".$equipo." es: ".$passw;
			$texto=utf8_decode($texto) ;
			$texto = str_replace("\n.", "\n..", $texto);
	// Para enviar un correo HTML, debe establecerse la cabecera Content-type
			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
			$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			$cabeceras .= "From: soporte@softneosas.com" . "\r\n";
			mail($para, $titulo, $texto, $cabecera);
			$conexion->close();
			return "Se envió la clave de inscripciones al usuario con email <b>$email<b> :)";
		}
		$conexion->close();
		return "Error: no está inscrio el equipo con nombre corto <b>$corto<b> :(";
	}

	function defina_rol($llave){
		$conexion=conectarse();
		$consulta="SELECT administrador, entrenador, clavadista, juez FROM usuarios WHERE cod_usuario=$llave";

		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==1){	
			$registro_usuario=$ejecutar_consulta->fetch_assoc();
			$a=$registro_usuario["administrador"];
			$c=$registro_usuario["clavadista"];
			$e=$registro_usuario["entrenador"];
			$j=$registro_usuario["juez"];
			$roles = array(
				"administrador" => $a, 
				"clavadista" => $c, 
				"entrenador" => $e, 
				"juez" => $j);
		}
		$conexion->close();
		return $roles;
	}

	function pais_usuario(){
		$conexion=conectarse();
		$pais_us="";
		$us=$_SESSION["usuario_id"];
		$consulta="SELECT DISTINCT countries.Country"
			. " FROM usuarios LEFT JOIN countries"
			. " ON (countries.CountryId=usuarios.pais)"
			. " WHERE (usuarios.cod_usuario=$us)";

		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==1){	
			$registro=$ejecutar_consulta->fetch_assoc();
			$pais_us=$registro["Country"];
		}
		$conexion->close();
		return $pais_us;
	}
	function verifica_competencia($llave)
	{
		$conexion=conectarse();
		$nom="";
		$consulta="SELECT competencia FROM competencias WHERE cod_competencia=$llave";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada la competencia <b>$llave<b> :(";
		}
		else
		{ 
			if ($num_regs==1){
				$registro=$ejecutar_consulta->fetch_assoc();
				$nom=$registro["competencia"];
			}
		}		
		$conexion->close();
		return $nom;
	}	
	function verifica_pais($llave)
	{
		$conexion=conectarse();
		$nom="";
		$consulta="SELECT Country FROM countries WHERE CountryId=$llave";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrado el pais <b>$llave<b> :(";
		}
		else
		{ 
			if ($num_regs==1){	
				$registro=$ejecutar_consulta->fetch_assoc();
				$nom=$registro["Country"];
			}
		}		
		$conexion->close();
		return $nom;
	}	
	function verifica_ciudad($llave)
	{
		$conexion=conectarse();
		$nom="";
		$consulta="SELECT City FROM cities WHERE CityId=$llave";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada la Ciudad <b>$llave<b> :(";
		}
		else
		{ 
			if ($num_regs==1){	
				$registro=$ejecutar_consulta->fetch_assoc();
				$nom=$registro["City"];
			}
		}		
		$conexion->close();
		return $nom;
	}	
	function ciudad_sede($llave){
		$conexion=conectarse();
		$nom_ciudad="";
		$consulta="SELECT City,CountryId FROM cities WHERE CityId=$llave";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrada la Ciudad <b>$llave<b> :(";
		}
		else
		{ 
			if ($num_regs==1){	
				$registro=$ejecutar_consulta->fetch_assoc();
				$nom_ciudad=$registro["City"];
				$cod_pais=$registro["CountryId"];
				$nom_pais=verifica_pais($cod_pais,$conexion);
				$nom=$nom_ciudad."-".$nom_pais;
			}
		}		
		$conexion->close();
		return $nom;
	}
	function hora_coloquial($ahora){
	$dias = array(
		"Sunday" => "Domingo",
		"Monday" => "Lunes",
		"Tuesday" => "Martes",
		"Wednesday" => "Miércoles",
		"Thursday" => "Jueves",
		"Friday" => "Viernes",
		"Saturday" => "Sábado");
	$meses = array(
		"01" => "Enero" , 
		"02" => "Febrero" , 
		"03" => "Marzo" , 
		"04" => "Abril" , 
		"05" => "Mayo" , 
		"06" => "Junio" , 
		"07" => "Julio" , 
		"08" => "Agosto" , 
		"09" => "Septiembre" , 
		"10" => "Octubre" , 
		"11" => "Noviembre" , 
		"12" => "Diciembre" );

	$date = date_create($ahora);
	$day=date_format($date, 'l');
	$dia=$dias[$day];
	$m=date_format($date, 'm');
	$mes=$meses[$m];
	$ano=date_format($date, 'Y');
	$ndia=date_format($date, 'j');
	$hor=substr($ahora,11,2);
	if ($hor)
		$hora=date_format($date, 'g:i a');
	return  $dia." ".$ndia." de ".$mes." del ".$ano." ".$hora;
}
/*	function fecha_larga($fecha){
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$ano=substr($fecha, 0,4);
		$mes=substr($fecha, 5,2);
		$dia=substr($fecha, 8,2);
		$hor=substr($fecha,11,2);
		$min=substr($fecha,14,2);
		$fec="$mes/$dia/$ano";
		$w=strftime("%w", strtotime($fec));
 		$r=$dias[$w]." ".$dia." de ".$meses[$mes-1]. " del ".$ano ;
		if (!$hor=="") {
			if ($hor>12) {
				$hor=$hor-12;
				$am="p.m.";
			}
			else{
				$am="a.m.";
			}
			$r=$r." a las ".$hor.":".$min." ".$am;
		}
		return $r;
	}
*/
	function limite_inscripcion($cod_competencia){
		$conexion=conectarse();
		$consulta="SELECT ciudad, limite_inscripcion 
			FROM competencias
			WHERE cod_competencia=$cod_competencia";

		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$reg=$ejecutar_consulta->fetch_assoc();
		$limite_inscripcion=$reg["limite_inscripcion"];
		$cod_ciudad=$reg["ciudad"];
		if (!$limite_inscripcion){
			$conexion->close();
			return TRUE;
		}

		$ahora=ahora($cod_ciudad, $conexion);
		$conexion->close();
		if ($ahora>$limite_inscripcion) 
			return FALSE;
		else
			return TRUE;
	}
	function ahora($cod_ciudad){
		date_default_timezone_set('GMT');
		if ($cod_ciudad) {
			$conexion=conectarse();
			$consulta="SELECT TimeZone FROM cities WHERE CityId=$cod_ciudad";
			$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
			$registro=$ejecutar_consulta->fetch_assoc();
			$time_zone=$registro["TimeZone"];
			$conexion->close();
			$signo_zone=substr($time_zone, 0,1);
			$horas_zone=substr($time_zone, 1,2);
			$minutos_zone=substr($time_zone, 4,2);
		}
		else{
			$time_zone = "-05:00";
			$signo_zone = substr($time_zone, 0, 1);
			$horas_zone = substr($time_zone, 1, 2);
			$minutos_zone = substr($time_zone, 4, 2);

		}
		$fecha = date('Y-m-d H:i:s');
		if ($signo_zone=="+") {
			$nuevafecha = strtotime ('+'.$horas_zone.' hour' , strtotime ( $fecha ) ) ;
			if ($minutos_zone>0) {
				$nuevafecha = strtotime ('+'.$minutos_zone.' minute' , strtotime ( $nuevafecha)) ;
			}
		}
		else{
			$nuevafecha = strtotime ('-'.$horas_zone.' hour' , strtotime ( $fecha ) ) ;
			if ($minutos_zone>0) {
				$nuevafecha = strtotime ('-'.$minutos_zone.' minute' , strtotime ( $nuevafecha ));
			}
		}
		$nuevafecha=date('Y-m-d H:i:s',$nuevafecha );
		return $nuevafecha;
	}

	function edad_deportiva($nacimiento){
		if (!$nacimiento) {
			return NULL;
		}

		$fecha_corte = fecha_edad_deportiva();
		$fecha_nacimiento = date_create($nacimiento);
		$fecha_corte_dt = date_create($fecha_corte);

		if (!$fecha_nacimiento || !$fecha_corte_dt) {
			return NULL;
		}

		if ($fecha_nacimiento > $fecha_corte_dt) {
			return 0;
		}

		return (int)$fecha_nacimiento->diff($fecha_corte_dt)->y;
	}

	function fecha_edad_deportiva(){
		$cod_competencia = isset($_SESSION["cod_competencia"]) ? (int)$_SESSION["cod_competencia"] : 0;
		static $fechas_corte = array();

		if (isset($fechas_corte[$cod_competencia])) {
			return $fechas_corte[$cod_competencia];
		}

		$fecha_corte = date("Y") . "-12-31";

		if ($cod_competencia) {
			$consulta="SELECT COALESCE(NULLIF(fecha_edad_deportiva, '0000-00-00'), CONCAT(YEAR(fecha_inicia), '-12-31')) AS fecha_edad_deportiva
			FROM competencias
			WHERE cod_competencia=$cod_competencia";
			$conexion=conectarse();
			$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
			$row=$ejecutar_consulta ? $ejecutar_consulta->fetch_assoc() : NULL;

			if (!empty($row['fecha_edad_deportiva'])) {
				$fecha_corte = $row['fecha_edad_deportiva'];
			}

			$conexion->close();
		}

		$fechas_corte[$cod_competencia] = $fecha_corte;
		return $fecha_corte;

	}

	function busca_categoria($nacimiento){

		$conexion=conectarse();
		$nombre_categoria=null;
		$edad=edad_deportiva($nacimiento);
		$consulta="SELECT DISTINCT * FROM categorias WHERE edad_desde<=$edad and edad_hasta>=$edad and individual=1";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return;
		}
		else
		{ 
			$registro=$ejecutar_consulta->fetch_assoc();
			$nombre_categoria=utf8_encode($registro["cod_categoria"])."-".utf8_encode($registro["categoria"])." (de ".$registro["edad_desde"];
			if ($registro["edad_hasta"]==99)
				$nombre_categoria=$nombre_categoria." años en adelante)";
			else
				if ($registro["edad_desde"]==$registro["edad_hasta"])
					$nombre_categoria=$nombre_categoria." años)";
				else
					$nombre_categoria=$nombre_categoria." a ".$registro["edad_hasta"]." años)";
			
		}		
		$conexion->close();
		return $nombre_categoria;
	}

	function grupo_categoria($nacimiento){
		$conexion=conectarse();
		$nombre_categoria=null;
		$edad=edad_deportiva($nacimiento);
		$consulta="SELECT DISTINCT categoria FROM categorias WHERE edad_desde<=$edad and edad_hasta>=$edad and individual=1 AND cod_categoria IN ('GA', 'GB', 'GC', 'GD')";
		$ejecutar_consulta = $conexion->query($consulta);
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0)
			$conexion->close();
		else
		{ 
			$registro=$ejecutar_consulta->fetch_assoc();
			$nombre_categoria=utf8_decode($registro["categoria"]);
		}
		$conexion->close();
		return $nombre_categoria;
	}


	function limite_cambios($cod_planilla){
		return TRUE;
		$control_cambios=FALSE;
		$conexion=conectarse();
		$consulta="SELECT p.competencia, e.fechahora, e.numero_evento, e.modalidad, e.sexos, e.categorias, c.control_cambios, c.ciudad, e.fechahora
			FROM planillas as p 
			LEFT JOIN competencias as c on c.cod_competencia=p.competencia
			LEFT JOIN competenciaev as e on e.competencia=p.competencia and e.numero_evento=p.evento
			WHERE cod_planilla=$cod_planilla 
				AND p.evento IS NOT NULL";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs){
			$reg=$ejecutar_consulta->fetch_assoc();
			$hora_evento=isset($reg["fechahora"])?$reg["fechahora"]:NULL;
			$cod_ciudad=isset($reg["ciudad"])?$reg["ciudad"]:NULL;
			$control_cambios=isset($reg['control_cambios'])?$reg['control_cambios']:NULL;
		}
		else{
			$consulta="SELECT competencia, modalidad, categoria, sexo
			FROM planillas 
			WHERE cod_planilla=$cod_planilla";
			$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
			$num_regs=$ejecutar_consulta->num_rows;
			if ($num_regs==1) {
				$reg=$ejecutar_consulta->fetch_assoc();
				$competencia=$reg["competencia"];
				$modalidad=$reg["modalidad"];
				$sexo=$reg["sexo"];
				$categoria=$reg["categoria"];
				$consulta="SELECT DISTINCT e.fechahora, c.ciudad, c.control_cambios
					FROM competenciaev as e
					LEFT JOIN competencias as c on c.cod_competencia=e.competencia
					WHERE e.competencia=$competencia 
						AND modalidad='$modalidad'
						AND sexos LIKE '%$sexo%'
						AND categorias LIKE '%$categoria%'";
				$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
				$num_regs=$ejecutar_consulta->num_rows;

				if ($num_regs==1){
					$reg=$ejecutar_consulta->fetch_assoc();
					$hora_evento=isset($reg["fechahora"])?$reg["fechahora"]:NULL;
					$cod_ciudad=isset($reg["ciudad"])?$reg["ciudad"]:NULL;
					$control_cambios=isset($reg['control_cambios'])?$reg['control_cambios']:NULL;
				}
			}
		}
		if (isset($hora_evento) and isset($cod_ciudad)) {
			if ($hora_evento AND $cod_ciudad) {
				$hora_cambios= strtotime ('-24 hour' , strtotime ($hora_evento));
				$hora_cambios = date('Y-m-d H:i:s',$hora_cambios);
				$ahora=ahora($cod_ciudad);
			}
		}
		$conexion->close();

		if (!$control_cambios) // control de cambios desactivado
			return TRUE;

		if (isset($ahora) and isset($hora_cambios))
			if ($ahora>$hora_cambios)
				return FALSE;
			else
				return TRUE;
		else
			return FALSE;
	}

	function inscripciones($competencia){
		$conexion=conectarse();
		$consulta="SELECT cod_competencia, fecha_termina, ciudad FROM competencias WHERE competencia='$competencia'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			$mensaje="Error: No está registrada esta Competencia";
			return $mensaje;
		}
		elseif ($num_regs==1){	
			$registro_competencia=$ejecutar_consulta->fetch_assoc();
			$termina=$registro_competencia["fecha_termina"];
			$cod_ciudad=$registro_competencia["ciudad"];
			$ahora=ahora($cod_ciudad,$conexion);
			$hoy=substr($ahora, 0,10);
			$conexion->close();
			if ($hoy>$termina)
				return FALSE;
			else
				return TRUE;
			}
	}
	function lee_salto($cod){
		$conexion=conectarse();
		$consulta="SELECT DISTINCT salto FROM saltos WHERE cod_salto='$cod'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0){
			$conexion->close();
			return "Error: El salto ".$cod." no está definido";	
		} 
		if ($num_regs==1){
			$registro=$ejecutar_consulta->fetch_assoc();
			$salto=utf8_encode($registro["salto"]);
			$conexion->close();
			return $salto;
		}
		$conexion->close();
		return;
}

	function valida_salto($nom_modalidad,$cod,$pos,$alt,$fijo){

		if ($pos!=="A" and $pos!=="B" and $pos!=="C" and $pos!=="D" and $pos!=="E"){
			return "Error: La posición del salto ".$cod." debe ser A, B, C, D ó E";	
		} 

		if ($fijo) {
			if ($fijo != $alt) {
				return "Error: La competencia es de ".$nom_modalidad." y la altura es ".$alt;
			}
		}
		elseif (!$alt) {
			return "Error: La altura $alt no es válida";
		}

		return ;
	}
	function grado_dificultad($cod,$pos,$alt){
		$conexion=conectarse();
		$consulta="SELECT DISTINCT posicion_a, posicion_b, posicion_c, posicion_d  FROM dificult WHERE cod_salto='$cod' AND altura=$alt";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0) {
			$conexion->close();
			return "Error: ".$cod."-".$pos." de ".$alt." no tiene definido el grado de dificultad";
		}

		if ($num_regs==1){
			$registro=$ejecutar_consulta->fetch_assoc();
			if ($pos=="A") $dif=$registro["posicion_a"];
			if ($pos=="B") $dif=$registro["posicion_b"];
			if ($pos=="C") $dif=$registro["posicion_c"];
			if ($pos=="D") $dif=$registro["posicion_d"];
		}	
		if ($dif==0){
			$conexion->close();
			return "Error: ".$cod."-".$pos." de ".$alt." no tiene definido el grado de dificultad";
		} 
		$conexion->close();
		return $dif;
	}
function grado_total($cod_categoria){
	global $saltos;
	$saltos_digitados=count($saltos);
	$dificultad=array();
	$dificultad[0]=0;
	$dificultad[1]=0;
	
	for ($i=1; $i<=$saltos_digitados; $i++) { 
		$salto=$saltos[$i];
		$abi=isset($salto["abi"])?$salto["abi"]:null;
		$dif=isset($salto["gra_dif"])?$salto["gra_dif"]:null;
		if ($abi=="*") 
			$dificultad[1]+=$dif;
		else
			$dificultad[0]+=$dif;
	}
	$total="Grado dificultad Total ".$cod_categoria.": ".number_format($dificultad[0],1);
	if ($dificultad[1]>0)
		$total.="  AB: ".number_format($dificultad[1],1);

	return $total;
}

function valida_saltos($modalidad,$categoria,$sexo,$edad){
	
	global $saltos, $preserie;

	if ($sexo=="Masculino") 
		$cod_sexo="M";
	else
		$cod_sexo="F";

	$row=lee_modalidad($modalidad);
	if (isset($row['error']))
		return $row['error'];
	
	$nom_modalidad=isset($row["nom_modalidad"])?utf8_decode($row["nom_modalidad"]):NULL;
	$fijo=$row["fijo"];
	$individual=$row["individual"];

	$cod_categoria=substr($categoria, 0, 2);
	$row=lee_categoria($cod_categoria);
	if (isset($row[0]))
		if (substr($row,0,5)=="Error") 
			return $row;

	$verifica_edad=$row["verifica_edad"];
	$edad_desde=$row["edad_desde"];
	$edad_hasta=$row["edad_hasta"];

	if ($verifica_edad)
		if ($edad<$edad_desde or $edad>$edad_hasta)
			return "Error: Por la edad no puede competir en la categoría <b>$categoria<b> :(";

	$codigos=array();
	$saltos_digitados=count($saltos);

	if (!$saltos_digitados) 
		return "Error: No ha registrado los saltos en la planilla :(";
	
	for ($i=1; $i<=$saltos_digitados; $i++) { 
		$salto=$saltos[$i];
		$cod_salto=isset($salto["cod_salto"])?$salto["cod_salto"]:null;
		$pos=isset($salto["pos"])?$salto["pos"]:null;
		$alt=isset($salto["alt"])?$salto["alt"]:null;
		$abi=isset($salto["abi"])?$salto["abi"]:null;
		if ($cod_salto){
			$sal=lee_salto($cod_salto);
			if (substr($sal,0,5)=="Error") 
				return $sal;


			$salto["sal"]=$sal;
			$saltos[$i]=$salto;
			$mensaje=valida_salto($nom_modalidad,$cod_salto,$pos,$alt,$fijo);
			if (substr($mensaje,0,5)=="Error") 
				return $mensaje;

			if ($preserie){
				$ok=FALSE;
				$orden=TRUE;
				$mensaje=toma_grado_preserie($cod_salto,$pos,$alt,$orden);

				if (!$mensaje) {
					$orden=FALSE;
					$mensaje=toma_grado_preserie($cod_salto,$pos,$alt,$orden);
				}

				// Quito esta validación para Chile, diciembre 17 2024
/*				if (!$ok) {
					$mensaje="1.0";
				}
*/

			}

			if (is_null($mensaje)){
				if ($individual==0 and $i<3)
					$mensaje="2.0";
				else{
					if ($cod_categoria=='GD'
						AND ($cod_salto=='10' OR $cod_salto=='20'))
						$mensaje='0.8';
					else
						$mensaje=grado_dificultad($cod_salto,$pos,$alt);
				}
			}
			
			if (substr($mensaje,0,5)=="Error") {
				if ($cod_salto=='10' or $cod_salto=='20'){
					$mensaje='0.8';
				}
			}

			if (substr($mensaje,0,5)=="Error") 
				return $mensaje;

			$salto["gra_dif"]=$mensaje;
			$saltos[$i]=$salto;
			$codigo=$cod_salto."-".$pos."-".$alt;
			if ($alt != 3){  // temporal para permitir el mismo salto de trampolín y platforma 3 mts. para Chile MARQ  diciembre 17 2024
/*
				if (in_array($codigo, $codigos))
					return "Error: está repitiendo el salto <b>$codigo<b> :(";
*/
			}
			$codigos[]=$codigo;
		}
	}

	$conexion=conectarse();
	$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min FROM series WHERE categoria='$cod_categoria' and sexo='$cod_sexo' and modalidad='$modalidad'";

	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$conexion->close();
		return "Error: Reglamento no definido para categoría ".$categoria."/".$sexo."/".$modalidad;
	} 

	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();

		$grupos = array();
		$dificultad=0;
		if ($registro["saltos_obl"]>0){
			for ($i=1; $i<=$registro["saltos_obl"]; $i++) { 
				$salto=$saltos[$i];
				$cod_salto=$salto["cod_salto"];
				if (strlen($cod_salto)<3 
					OR $cod_salto=='100'
					OR $cod_salto=='200')
					continue;
				$dificultad+=$salto["gra_dif"];
				$grupo=substr($cod_salto, 0,1);
				if (in_array($grupo, $grupos)){
					$conexion->close();
					// Modificación para Chile, no validar repetición para novicios diciembre 17 2024
					
					if (substr($cod_categoría,0,1) != 'V') {
						return "Error: Está repitiendo el grupo <b>$grupo<b>  en los saltos obligatorios :(";
					}

				} 
				$grupos[] = $grupo;
			}
			if ($registro["limite_dif"]>0) {
				$ld=number_format($registro["limite_dif"],1);
				$gd=number_format($dificultad,1);
				if ($gd>$ld){
					$conexion->close();
					return "Error: Los saltos obligatorios con <b>$gd<b>, superan el límite de dificultad <b>$ld<b> :(";
				} 
			}
		}
		$total_saltos=$registro["saltos_obl"]+$registro["saltos_lib"];
		$saltos_ejecutar=0;
		$grupos = array();
		$repetidos=array();
		for ($i=1; $i<=$total_saltos; $i++) { 
			$salto=isset($saltos[$i])?$saltos[$i]:NULL;
			$cod_salto=isset($salto["cod_salto"])?$salto["cod_salto"]:NULL;
			if ($cod_salto) {
				$saltos_ejecutar++;
				if ($salto["abi"]=="*") 
					$abierto=TRUE;
				$grupo=substr($cod_salto, 0,1);
				if (in_array($grupo, $grupos))
					$repetidos[]=$grupo;
				else
					$grupos[] =$grupo;			
			}
		}
		if ($registro["grupos_min"]) {
			$grupos_min=$registro["grupos_min"];
			$can_gru=count($grupos);
			if ($can_gru<$grupos_min) 
				return "Error: Está haciendo sólo ".$can_gru." grupos, mínimo debe hacer ".$grupos_min." grupos";			
		}
		if (!isset($abierto)) 
			$abierto=FALSE;

		if ($saltos_ejecutar<$total_saltos) {
			$conexion->close();
			return "Error: está ejecutando menos saltos de los exigidos para la categoría :(";
		}
			

		if (!$abierto and $saltos_ejecutar>$total_saltos) {
			$conexion->close();
			return "Error: Está ejecutando más saltos de los requeridos en la Categoría :(";
		}
			
	}
	if ($abierto) {
		$cod_categoria="AB";
		$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min FROM series WHERE categoria='$cod_categoria' and sexo='$cod_sexo' and modalidad='$modalidad'";

		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0) {
			$conexion->close();
			return "Error: Reglamento no definido para categoría ".$cod_categoria."/".$sexo."/".$nom_modalidad." :(";
		}

		if ($num_regs==1){
			$registro=$ejecutar_consulta->fetch_assoc();
			$total_saltos=$registro["saltos_obl"]+$registro["saltos_lib"];
			$grupos = array();
			$repetidos=array();
			$saltos_abierto=0;
			for ($i=1; $i<=$saltos_digitados; $i++) {
				$salto=$saltos[$i];
				if ($salto["abi"]=="*"){
					$cod_salto=$salto["cod_salto"];
					$saltos_abierto++;
					$grupo=substr($cod_salto, 0,1);
					if (in_array($grupo, $grupos))
						$repetidos[]=$grupo;
					else
						$grupos[] =$grupo;
				}
			}
			$r=count($repetidos);
			if ($r>1) {
				$conexion->close();
				return "Error: en abierto, está repitiendo ".$r." grupos de saltos :(";
			}
				
			if ($saltos_abierto<$total_saltos) {
				$conexion->close();
				return "Error: en abierto, está haciendo menos saltos que los requeridos :(";
			}
			if ($registro["grupos_min"]) {
				$grupos_min=$registro["grupos_min"];
				$can_gru=count($grupos);
				if ($can_gru<$grupos_min)
					return "Error: Está haciendo sólo ".$can_gru." grupos, mínimo debe hacer ".$grupos_min." grupos en Abierto";			
			}
		}	
	}
	$conexion->close();
	return NULL;
}

function toma_grado_preserie($cod_salto,$pos,$alt,$orden){

	global $preserie;

	$grado=NULL;

	foreach ($preserie as $reg) {
		$gr=isset($reg["grado"])?$reg["grado"]:NULL;
		if (!$gr) {
			continue;
		}
		$lb=$reg["libre"];
		$sl=$reg["salto"];
		$ps=$reg["posicion"];
		$al=$reg["altura"];
		$ord=$reg["orden"];
		if ($lb) {
			break;
		}
		if ($cod_salto==$sl AND $pos==$ps AND $alt==$al){
			if ($orden) {
				if ($ord==$i) {
					$grado=$gr;
					break;
				}
			}
			else{
				$grado=$gr;
				break;
			}
		}

	}

	return $grado;
}

function valida_saltos_mix($modalidad,$categoria,$edad){
	global $saltos;

	$cod_sexo="X";
	$sexo="Mixto";

	$cod_categoria=substr($categoria, 0, 2);

	$row=lee_modalidad($modalidad);
	if (isset($row['error']))
		return $row['error'];
	
	$nom_modalidad=isset($row["nom_modalidad"])?utf8_decode($row["nom_modalidad"]):NULL;
	$fijo=$row["fijo"];
	$individual=$row["individual"];

	$conexion=conectarse();
	$consulta="SELECT DISTINCT * FROM categorias WHERE cod_categoria='$cod_categoria'";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$conexion->close();
		return "Error: No está definida la categoría <b>$cod_categoria<b> :(";
	} 

	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		if ($registro["verifica_edad"]){
			if ($edad<$registro["edad_desde"] or $edad>$registro["edad_hasta"]){
				$conexion->close();
				return "Error: Por la edad no puede competir en la categoría <b>$categoria<b> :(";
			}
//			if ($edad>$registro["edad_hasta"])
		}
	}		
	$codigos=array();
	$saltos_digitados=count($saltos);

	$conexion->close();
	
	if ($saltos_digitados==0) {
		return "Error: No ha registrado los saltos en la planilla";
	}
	

	for ($i=1; $i<=$saltos_digitados; $i++) { 
		$salto=$saltos[$i];
		$cod_salto=(isset($salto["cod_salto"])?trim($salto["cod_salto"]):null);
		$pos=(isset($salto["pos"])?$salto["pos"]:null);
		$alt=(isset($salto["alt"])?$salto["alt"]:null);
		$abi=(isset($salto["abi"])?$salto["abi"]:null);
		$cod_salto2=(isset($salto["cod_salto2"])?$salto["cod_salto2"]:null);
		$pos2=(isset($salto["pos2"])?$salto["pos2"]:null);
		if ($cod_salto){
			$sal=lee_salto($cod_salto);
			if (substr($sal,0,5)=="Error") {
				return $sal;
			}
			$salto["sal"]=$sal;
			$saltos[$i]=$salto;
			$mensaje=valida_salto($modalidad,$cod_salto,$pos,$alt,$fijo);

			if (substr($mensaje,0,5)=="Error") 
				return $mensaje;

			if ($individual==0 and $i<3)
				$mensaje="2.0";
			else
				$mensaje=grado_dificultad($cod_salto,$pos,$alt);

			if (substr($mensaje,0,5)=="Error") 
				return $mensaje;

			$gra_dif=$mensaje;
			if ($cod_salto2) {
				$sal2=lee_salto($cod_salto2);
				if (substr($sal2,0,5)=="Error") 
					return $sal2;
				if ($cod_salto!==$cod_salto2) {
					if ($individual==0 and $i<3)
						$mensaje="2.0";
					else
						$mensaje=grado_dificultad($cod_salto2,$pos2,$alt);
					if (substr($mensaje,0,5)=="Error") 
						return $mensaje;
					$gra_dif = ($gra_dif+$mensaje)/2;
				}
			}

			$salto["gra_dif"]=$gra_dif;
			if ($cod_salto2) {
				$salto["sal2"]=$sal2;
			}
			$saltos[$i]=$salto;
			$codigo=$cod_salto."-".$pos."-".$alt;

			if (in_array($codigo, $codigos))
				return "Error: está repitiendo el salto <b>$codigo<b> :(";
			$codigos[]=$codigo;

		}
	}

	$conexion=conectarse();
	$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min FROM series WHERE categoria='$cod_categoria' and sexo='$cod_sexo' and modalidad='$modalidad'";

	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$conexion->close();
		return "Error: Reglamento no definido para categoría ".$categoria."/".$sexo."/".$nom_modalidad;
	} 

	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		$grupos = array();
		$dificultad=0;
		if ($registro["saltos_obl"]>0){
			for ($i=1; $i<=$registro["saltos_obl"]; $i++) { 
				$salto=$saltos[$i];
				$cod_salto=$salto["cod_salto"];
				$dificultad += $salto["gra_dif"];
				$grupo=substr($cod_salto, 0,1);
				if (in_array($grupo, $grupos)){
					$conexion->close();
					return "Error: Está repitiendo el grupo <b>$grupo<b>  en los saltos obligatorios :(";
				} 
				$grupos[] = $grupo;
			}

			if ($registro["limite_dif"]>0) 
				$ld=number_format($registro["limite_dif"],1);
				$gd=number_format($dificultad,1);
				if ($gd>$ld){
					$conexion->close();
					return "Error: Los saltos obligatorios con <b>$gd<b>, superan el límite de dificultad <b>$ld<b> :(";
				} 
		}
		$total_saltos=$registro["saltos_obl"]+$registro["saltos_lib"];
		$saltos_ejecutar=0;
		$repetidos = array();
		$grupos = array();
		for ($i=1; $i<=$total_saltos; $i++) { 
			$salto=$saltos[$i];
			$cod_salto=$salto["cod_salto"];
			if ($cod_salto) {

				$grupo=substr($cod_salto, 0,1);
				if (in_array($grupo, $grupos)){
					$repetidos[]=$grupo;
				}
				else
					$grupos[] =$grupo;			

				$saltos_ejecutar++;

				if ($salto["abi"]=="*") 
					$abierto=TRUE;
			}
		}

		if ($registro["grupos_min"]) {
			$grupos_min=$registro["grupos_min"];
			$can_gru=count($grupos);
			if ($can_gru<$grupos_min) {
				return "Error: Está haciendo sólo ".$can_gru." grupos, mínimo debe hacer ".$grupos_min." grupos";			}
		}

		if (!isset($abierto)) 
			$abierto=FALSE;

		if ($saltos_ejecutar<$total_saltos) {
			$conexion->close();
			return "Error: está ejecutando menos saltos de los exigidos para la categoría :(";
		}
			

		if (!$abierto and $saltos_ejecutar>$total_saltos) {
			$conexion->close();
			return "Error: Está ejecutando más saltos de los requeridos en la Categoría :(";
		}
			
	}
	if ($abierto) {
		$cod_categoria="AB";
		$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min FROM series WHERE categoria='$cod_categoria' and sexo='$cod_sexo' and modalidad='$modalidad'";

		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;

		if ($num_regs==0) {
			$conexion->close();
			return "Error: Reglamento no definido para categoría ".$cod_categoria."/".$sexo."/".$nom_modalidad." :(";
		}

		if ($num_regs==1){
			$registro=$ejecutar_consulta->fetch_assoc();
			$total_saltos=$registro["saltos_obl"]+$registro["saltos_lib"];
			unset($grupos);
			$grupos = array();
			$repetidos=array();
			$g=0;
			$r=0;
			$saltos_abierto=0;
			for ($i=1; $i<=$saltos_digitados; $i++) {
				$salto=$saltos[$i];
				if ($salto["abi"]=="*"){
					$cod_salto=$salto["cod_salto"];
					$saltos_abierto++;
					$grupo=substr($cod_salto, 0,1);
					if (in_array($grupo, $grupos)){
						$repetidos[]=$grupo;
					}
					else
						$grupos[] =$grupo;
				}
			}
			$r=count($repetidos);
			if ($r>1) {
				$conexion->close();
				return "Error: en abierto, está repitiendo ".$r." grupos de saltos :(";
			}
				
			if ($saltos_abierto<$total_saltos) {
				$conexion->close();
				return "Error: en abierto, está haciendo menos saltos que los requeridos :(";
			}
			if ($registro["grupos_min"]) {
				$grupos_min=$registro["grupos_min"];
				$can_gru=count($grupos);
				if ($can_gru<$grupos_min) {
					return "Error: Está haciendo sólo ".$can_gru." grupos, mínimo debe hacer ".$grupos_min." grupos en Abierto";			
				}
			}
		}	
	}
	$conexion->close();
	return NULL;
}

function valida_saltos_equ($modalidad,$categoria,$edad){
	global $saltos;

	$cod_sexo="X";
	$sexo="Mixto";

	$cod_categoria=substr($categoria, 0, 2);

	$row=lee_modalidad($modalidad);
	if (isset($row['error']))
		return $row['error'];
	
	$nom_modalidad=isset($row["nom_modalidad"])?utf8_decode($row["nom_modalidad"]):NULL;
	$fijo=$row["fijo"];
	$individual=$row["individual"];

	$conexion=conectarse();

	$consulta="SELECT DISTINCT * FROM categorias WHERE cod_categoria='$cod_categoria'";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$conexion->close();
		return "Error: No está definida la categoría <b>$cod_categoria<b> :(";
	} 

	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		if ($registro["verifica_edad"]){
			if ($edad<$registro["edad_desde"] or $edad>$registro["edad_hasta"]){
				$conexion->close();
				return "Error: Por la edad no puede competir en la categoría <b>$categoria<b> :(";
			}
//			if ($edad>$registro["edad_hasta"])
		}
	}		
	$codigos=array();
	$saltos_digitados=count($saltos);

	$conexion->close();
	
	if ($saltos_digitados==0) {
		return "Error: No ha registrado los saltos en la planilla";
	}
	$obligatorios[1]=NULL;
	$obligatorios[2]=NULL;
	$eje1["3mt"]=NULL;
	$eje1["plt"]=NULL;
	$eje2["3mt"]=NULL;
	$eje2["plt"]=NULL;

	for ($i=1; $i<=$saltos_digitados; $i++) { 
		$salto=$saltos[$i];
		$cod_salto=(isset($salto["cod_salto"])?trim($salto["cod_salto"]):null);
		$pos=(isset($salto["pos"])?$salto["pos"]:null);
		$alt=(isset($salto["alt"])?number_format($salto["alt"],1):null);
		$obl=(isset($salto["obl"])?$salto["obl"]:null);
		$eje=(isset($salto["eje"])?$salto["eje"]:null);
		if ($cod_salto){
			$sal=lee_salto($cod_salto);
			if (substr($sal,0,5)=="Error") {
				return $sal." en salto No. ".$i;
			}
			$salto["sal"]=$sal;
			$saltos[$i]=$salto;
			$mensaje=valida_salto($modalidad,$cod_salto,$pos,$alt,$fijo);

			if (substr($mensaje,0,5)=="Error") 
				return $mensaje." en salto No. ".$i;

			if ($obl)
				$mensaje="2.0";
			else
				$mensaje=grado_dificultad($cod_salto,$pos,$alt);

			if (substr($mensaje,0,5)=="Error") 
				return $mensaje." en salto No. ".$i;

			$gra_dif=$mensaje;

			if (!$eje)
				return "Error: Debe ingresar cuál es el clavadista ejecutor del salto No. ".$i." :(";

			if ($eje==1) {
				if ($obl) 
					$obligatorios[1]++;
				if ($alt==3) 
					$eje1["3mt"]++;
				elseif ($alt > 3) 
					$eje1["plt"]++;
			}
			elseif ($eje==2){
				if ($obl) 
					$obligatorios[2]++;
				if ($alt==3) 
					$eje2["3mt"]++;
				elseif ($alt > 3)
					$eje2["plt"]++;
			} 
			else
				$nej=NULL;

			$salto["gra_dif"]=$gra_dif;
			$saltos[$i]=$salto;

			$codigo=$cod_salto."-".$pos."-".$alt;

			if (in_array($codigo, $codigos))
				return "Error: está repitiendo el salto <b>$codigo<b> :(";
			$codigos[]=$codigo;
		}
	}

	$conexion=conectarse();
	$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min FROM series WHERE categoria='$cod_categoria' and sexo='$cod_sexo' and modalidad='$modalidad'";

	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$conexion->close();
		return "Error: Reglamento no definido para categoría ".$categoria."/".$sexo."/".$nom_modalidad;
	} 

	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		$grupos = array();
		$dificultad=0;
		if ($registro["saltos_obl"]>0){
			$k_obl=0;
			for ($i=1; $i<=$saltos_digitados; $i++) { 
				$salto=$saltos[$i];
				$obl=$salto["obl"];
				if ($obl) {
					$k_obl++;
					
					$cod_salto=$salto["cod_salto"];
					$dificultad += $salto["gra_dif"];
					$grupo=substr($cod_salto, 0,1);
					if (in_array($grupo, $grupos)){
						$conexion->close();
						return "Error: Está repitiendo el grupo <b>$grupo<b>  en los saltos obligatorios :(";
					} 
					$grupos[] = $grupo;
				}
			}
			if ($k_obl>$registro["saltos_obl"]) {
				$conexion->close();
				return "Error: Señaló ".$k_obl." obligatorios y debe hacer ".$registro["saltos_obl"]." obligatorios :(";
			}

			if ($registro["limite_dif"]>0) 
				$ld=number_format($registro["limite_dif"],1);
				$gd=number_format($dificultad,1);
				if ($gd>$ld){
					$conexion->close();
					return "Error: Los saltos obligatorios con <b>$gd<b>, superan el límite de dificultad <b>$ld<b> :(";
				} 
		}
		$total_saltos=$registro["saltos_obl"]+$registro["saltos_lib"];
		$saltos_ejecutar=0;
		$repetidos = array();
		$grupos = array();
		for ($i=1; $i<=$total_saltos; $i++) { 
			$salto=$saltos[$i];
			$cod_salto=$salto["cod_salto"];
			if ($cod_salto) {

				$grupo=substr($cod_salto, 0,1);
				if (in_array($grupo, $grupos)){
					$repetidos[]=$grupo;
				}
				else
					$grupos[] =$grupo;			

				$saltos_ejecutar++;

			}
		}

		if ($registro["grupos_min"]) {
			$grupos_min=$registro["grupos_min"];
			$can_gru=count($grupos);
			if ($can_gru<$grupos_min) {
				$conexion->close();
				return "Error: Está haciendo sólo ".$can_gru." grupos, mínimo debe hacer ".$grupos_min." grupos";			}
		}


		if ($saltos_ejecutar<$total_saltos) {
			$conexion->close();
			return "Error: está ejecutando menos saltos de los exigidos para la categoría :(";
		}
			

		if ($saltos_ejecutar>$total_saltos) {
			$conexion->close();
			return "Error: Está ejecutando más saltos de los requeridos en la Categoría :(";
		}
			
	}
	$conexion->close();

	$m=$eje1["3mt"]+$eje1["plt"];
	if ($m!==3) 
		return "Error: El clavadista 1 está haciendo ".$m." saltos debiendo ser 3 :(";

	$m=$eje2["3mt"]+$eje2["plt"];
	if ($m!==3) 
		return "Error: El clavadista 2 está haciendo ".$m." saltos debiendo ser 3 :(";

	$m=$eje1["3mt"]+$eje2["3mt"];
	if ($m!==3) 
		return "Error: En 3 mts están haciendo ".$m." saltos debiendo ser 3 :(";
	
	$m=$eje1["plt"]+$eje2["plt"];
	if ($m!==3) 
		return "Error: En Plataforma están haciendo ".$m." saltos debiendo ser 3 :(";

	if (!$eje1["3mt"]) 
		return "Error: El clavadista 1 no está haciendo saltos en Trampolín de 3 metros :(";

	if (!$eje1["plt"]) 
		return "Error: El clavadista 1 no está haciendo saltos en Plataforma :(";

	if (!$eje2["3mt"]) 
		return "Error: El clavadista 2 no está haciendo saltos en Trampolín de 3 metros :(";

	if (!$eje2["plt"]) 
		return "Error: El clavadista 2 no está haciendo saltos en Plataforma :(";

	if (!$obligatorios[1]) 
		return "Error: El clavadista 1 NO está haciendo salto obligatorio :(";
	
	if (!$obligatorios[2]) 
		return "Error: El clavadista 2 NO está haciendo salto obligatorio :(";

	if ($obligatorios[1]>1) 
		return "Error: El clavadista 1 está haciendo más de 1 salto obligatorio :(";

	if ($obligatorios[2]>1) 
		return "Error: El clavadista 2 está haciendo más de 1 salto obligatorio :(";

	return NULL;
}

function valida_saltos_equ_ju($modalidad,$categoria,$edad,$edad2,$edad3,$edad4){
	global $saltos;

	$cod_sexo="X";
	$sexo="Mixto";

	$cod_categoria=substr($categoria, 0, 2);
	$row=lee_modalidad($modalidad);
	if (isset($row['error']))
		return $row['error'];
	
	$nom_modalidad=isset($row["nom_modalidad"])?utf8_decode($row["nom_modalidad"]):NULL;
	$fijo=$row["fijo"];
	$individual=$row["individual"];

	$conexion=conectarse();

	$consulta="SELECT DISTINCT * FROM categorias WHERE cod_categoria='$cod_categoria'";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$conexion->close();
		return "Error: No está definida la categoría <b>$cod_categoria<b> :(";
	} 

	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		if ($registro["verifica_edad"]){
			if ($edad<$registro["edad_desde"] or $edad>$registro["edad_hasta"]){
				$conexion->close();
				return "Error: Por la edad el competidor 1 no puede competir en la categoría <b>$categoria<b> :(";
			}
			if ($edad2) {
				if ($edad2<$registro["edad_desde"] or $edad2>$registro["edad_hasta"]){
					$conexion->close();
					return "Error: Por la edad el competidor 2 no puede competir en la categoría <b>$categoria<b> :(";
				}
			}
			if ($edad3) {
				if ($edad3<$registro["edad_desde"] or $edad3>$registro["edad_hasta"]){
					$conexion->close();
					return "Error: Por la edad el competidor 3 no puede competir en la categoría <b>$categoria<b> :(";
				}
			}
			if ($edad4) {
				if ($edad4<$registro["edad_desde"] or $edad4>$registro["edad_hasta"]){
					$conexion->close();
					return "Error: Por la edad el competidor 4 no puede competir en la categoría <b>$categoria<b> :(";
				}
			}
		}
	}		
	$codigos=array();
	$saltos_digitados=count($saltos);

	$conexion->close();
	
	if ($saltos_digitados==0) {
		return "Error: No ha registrado los saltos en la planilla";
	}

	for ($i=1; $i<=$saltos_digitados; $i++) { 
		$salto=$saltos[$i];
		$cod_salto=(isset($salto["cod_salto"])?trim($salto["cod_salto"]):null);
		$pos=(isset($salto["pos"])?$salto["pos"]:null);
		$alt=(isset($salto["alt"])?number_format($salto["alt"],1):null);
		$eje1=(isset($salto["eje1"])?$salto["eje1"]:null);
		$eje2=(isset($salto["eje2"])?$salto["eje2"]:null);
		if ($cod_salto){
			$sal=lee_salto($cod_salto);
			if (substr($sal,0,5)=="Error") {
				return $sal." en salto No. ".$i;
			}
			$salto["sal"]=$sal;
			$saltos[$i]=$salto;
			$mensaje=valida_salto($modalidad,$cod_salto,$pos,$alt,$fijo);

			if (substr($mensaje,0,5)=="Error") 
				return $mensaje." en salto No. ".$i;

			$mensaje=grado_dificultad($cod_salto,$pos,$alt);

			if (substr($mensaje,0,5)=="Error") 
				return $mensaje." en salto No. ".$i;

			$gra_dif=$mensaje;

			if (!$eje1)
				return "Error: Debe ingresar cuál es el clavadista ejecutor del salto No. ".$i." :(";
			if ($i>3)
			 	if (!$eje2)
				 return "Error: Debe ingresar el segundo clavadista que ejecuta el salto No. ".$i." :(";

			$salto["gra_dif"]=$gra_dif;
			$saltos[$i]=$salto;

			$codigo=$cod_salto."-".$pos."-".$alt;

			if (in_array($codigo, $codigos))
				return "Error: está repitiendo el salto <b>$codigo<b> :(";
			$codigos[]=$codigo;
		}
	}

	$conexion=conectarse();
	$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min FROM series WHERE categoria='$cod_categoria' and sexo='$cod_sexo' and modalidad='$modalidad'";

	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;

	if ($num_regs==0){
		$conexion->close();
		return "Error: Reglamento no definido para categoría ".$categoria."/".$sexo."/".$nom_modalidad;
	} 

	if ($num_regs==1){
		$registro=$ejecutar_consulta->fetch_assoc();
		$conexion->close();
		$total_saltos=$registro["saltos_obl"]+$registro["saltos_lib"];
		$saltos_ejecutar=0;
		$repetidos = array();
		$grupos = array();
		for ($i=1; $i<=$total_saltos; $i++) { 
			$salto=$saltos[$i];
			$cod_salto=$salto["cod_salto"];
			if ($cod_salto) {
				$grupo=substr($cod_salto, 0,1);
				if (in_array($grupo, $grupos)){
					$repetidos[]=$grupo;
				}
				else
					$grupos[] =$grupo;			
				$saltos_ejecutar++;
			}
		}

		$salto=$saltos[4];
		$cod_salto=$salto["cod_salto"];
		if ($cod_salto){
			$grupo=substr($cod_salto, 0,1);
			if ($grupo!=5){
				return "Error: El salto 4 debe ser del grupo de Giros :(";
			}
		}

		$salto=$saltos[5];
		$cod_salto=$salto["cod_salto"];
		if ($cod_salto){
			$grupo=substr($cod_salto, 0,1);
			if ($grupo!=2 and $grupo!=3){
				return "Error: El salto 5 debe ser del grupo Atrás o Inverso :(";	
			}
		}

		if ($registro["grupos_min"]) {
			$grupos_min=$registro["grupos_min"];
			$can_gru=count($grupos);
			if ($can_gru<$grupos_min) {
				return "Error: Está haciendo sólo ".$can_gru." grupos, mínimo debe hacer ".$grupos_min." grupos";			}
		}

		if ($saltos_ejecutar<$total_saltos) {
			return "Error: está ejecutando menos saltos de los exigidos para la categoría :(";
		}

		if ($saltos_ejecutar>$total_saltos) {
			return "Error: Está ejecutando más saltos de los requeridos en la Categoría :(";
		}
	}
	return;
}


function reglamento($cod_categoria,$cod_sexo,$modalidad){
	$conexion=conectarse();
	$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min FROM series WHERE categoria='$cod_categoria' and sexo='$cod_sexo' and modalidad='$modalidad'";

	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0) {
		$conexion->close();
		return "Error: Reglamento no definido para categoría ".$cod_categoria."/".$cod_sexo."/".$modalidad." :(";
	}

	$registro=$ejecutar_consulta->fetch_assoc();
	$regla="";
	if ($registro["saltos_obl"]>0) {
		$regla=$regla.$registro["saltos_obl"]." obligatorios";
		if ($registro["limite_dif"]>0)		
			$regla=$regla." con ".number_format($registro["limite_dif"],1)." de límite y ";
	}
	if ($registro["saltos_lib"]>0) 
		$regla=$regla.$registro["saltos_lib"]." libres";

	if ($registro["grupos_min"]>0)
		$regla=$regla.". Mínimo ".$registro["grupos_min"]." grupos.";

	$conexion->close();
	return $regla;
}

function planilla_registrada($cod_competencia,$cod_clavadista,$cod_categoria,$cod_modalidad){

	$conexion=conectarse();
	$consulta="SELECT * from planillas WHERE competencia=$cod_competencia AND clavadista=$cod_clavadista AND modalidad='$cod_modalidad' AND categoria='$cod_categoria'"
;

	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	$conexion->close();
	if ($num_regs==0){
		return null;
	} 
	
	return "Error: Ya registró una planilla con estas características en esta competencia :/";
}

function describe_modalidades($modalidades){
	$conexion=conectarse();
	$modalidades_arr = explode("-", $modalidades);
	$nom_modalidades=NULL;
	$n=count($modalidades_arr);
	for ($i=0; $i<=($n-1) ; $i++) {
		$cod_modalidad=$modalidades_arr[$i]; 
		$consulta="SELECT abreviado from modalidades WHERE cod_modalidad='$cod_modalidad'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$registro=$ejecutar_consulta->fetch_assoc();
		if (is_null($nom_modalidades)) {
			$nom_modalidades=$registro["abreviado"];
		}
		else
			$nom_modalidades.=", ".$registro["abreviado"];

	}
	$conexion->close();
	return $nom_modalidades;
}
function describe_evento($modalidad,$sexos,$categorias,$tipo){
	$conexion=conectarse();
	$categorias_arr=explode("-", $categorias);
	$n=count($categorias_arr);
	for ($i=0; $i <=$n-1 ; $i++) { 
		$cod_categoria=$categorias_arr[$i];
		$consulta="SELECT categoria FROM categorias WHERE cod_categoria='$cod_categoria'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==1){
			$registro=$ejecutar_consulta->fetch_assoc();
			if (is_null($descripcion))
				$descripcion=utf8_encode($registro["categoria"]);
			else
				$descripcion.=", ".utf8_encode($registro["categoria"]);
		}
	}	
	$sexos_arr=explode("-", $sexos);
	$n=count($sexos_arr);
	$desc_sexos=NULL;
	for ($i=0; $i <=$n-1 ; $i++) { 
		if ($sexos_arr[$i]=="F")
			if (is_null($desc_sexos))
				$desc_sexos=". Damas";
			else
				$desc_sexos=$desc_sexos." y Damas";
		if ($sexos_arr[$i]=="M") 
			if (is_null($desc_sexos))
				$desc_sexos=". Varones";
			else
				$desc_sexos=$desc_sexos." y Varones";
		if ($sexos_arr[$i]=="X") 
			if (is_null($desc_sexos))
				$desc_sexos=". Mixto";
			else
				$desc_sexos=$desc_sexos." y Mixto";
		
	}
	$descripcion=$modalidad.":  ".$descripcion.$desc_sexos;
	switch ($tipo) {
		case 'P':
			$descripcion=$descripcion." - Preliminar";
			break;
	
		case 'S':
			$descripcion=$descripcion." - Semifinal";
			break;
		case 'F':
			$descripcion=$descripcion." - Final";
			break;
		default:
			$descripcion=$descripcion." - Final";
			break;
	}
	$conexion->close();
	return $descripcion;
}

function valida_edad_categoria($cod_categoria,$edad){
	$conexion=conectarse();
		/* verificar la conexión */
	if (mysqli_connect_errno()) {
   		return "Error: Conexión fallida:  mysqli_connect_error() :/";
	}
	$consulta="SELECT * FROM categorias WHERE cod_categoria='$cod_categoria'";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0){
		$conexion->close();
		return "Error: No está registrada esta Categoría :(";
	}
	elseif ($num_regs==1){	
		$registro=$ejecutar_consulta->fetch_assoc();
		if ($registro["verifica_edad"]==0){
			$conexion->close();
			return "todo ok";
		}
		else{
			$desde=$registro["edad_desde"];
//			if ($edad<$desde)
//				return "Error: Tiene $edad años y debe tener mínimo $desde años para competir en esta Categoría :/";
			$hasta=$registro["edad_hasta"];
			$conexion->close();
			if ($edad>$hasta)
				return "Error: Tiene $edad años y debe tener máximo $hasta años para competir en esta Categoría :/";
			else
				return "todo ok";
		}
	}
}
function edad_usuario($cod_usuario)	{
	if (!isset($cod_usuario))
		return "Error: No está registrado el usuario :(";		
	if (!$cod_usuario)
		return "Error: No está registrado el usuario <b>$cod_usuario<b>:(";		
	$conexion=conectarse();
	if (mysqli_connect_errno()) {
   		return "Error: Conexión fallida:  mysqli_connect_error() :/";
	}
	$consulta="SELECT nacimiento FROM usuarios WHERE cod_usuario=$cod_usuario";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==0){
		$conexion->close();
		return "Error: No está registrado el usuario <b>$cod_usuario<b>:(";
	}
	elseif ($num_regs==1){	
		$registro_usuario=$ejecutar_consulta->fetch_assoc();
		$nacimiento=$registro_usuario["nacimiento"];
		$edad=edad_deportiva($nacimiento);				
		$conexion->close();
		return $edad;
	}
	else{
		$conexion->close();
		return "Error: Hay varios registros para el usuario con email <b>$email<b> :/";
	}
	
}

function sexo_usuario($cod_usuario)	{
	$conexion=conectarse();
	if (mysqli_connect_errno()) {
   		return "Error: Conexión fallida:  mysqli_connect_error() :/";
	}
	$consulta="SELECT sexo FROM usuarios WHERE cod_usuario=$cod_usuario";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	if ($ejecutar_consulta) {
		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrado el usuario <b>$cod_usuario<b>:(";
		}
		elseif ($num_regs==1){	
			$registro_usuario=$ejecutar_consulta->fetch_assoc();
			$sexo=$registro_usuario["sexo"];
			$conexion->close();
			return $sexo;
		}
		else{
			$conexion->close();
			return "Error: Hay varios registros para el usuario con email <b>$email<b> :/";
		}
	}
	$conexion->close();
	return "";
	
}
function ver_clavadista_competencia($cod_usuario,$cod_competencia){
	$conexion=conectarse();
	if (mysqli_connect_errno()) 
   		return "Error: Conexión fallida:  mysqli_connect_error() :/";
	
	$consulta="SELECT * FROM planillas WHERE (competencia=$cod_competencia AND clavadista=$cod_usuario AND usuario_retiro iS NULL )";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	$conexion->close();
	if ($num_regs==1)
		return "Error: El clavadista ya está inscrito en esta competencia :/";
	else
		return "todo ok";
}

function nombre_usuario($email)	{
		$conexion=conectarse();
		/* verificar la conexión */
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT nombre FROM usuarios WHERE email='$email'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrado el usuario con email <b>$email<b> :(";
		}
		else 
		{	
			if ($num_regs==1){	
				$registro_usuario=$ejecutar_consulta->fetch_assoc();
				$nombre_usuario=$registro_usuario["nombre"];
				$conexion->close();
				return $nombre_usuario;
			}
				else
					return "Error: Hay varios registros para el usuario con email <b>$email<b> :/";
		}
	}

function imagen_usuario($email)	{
		$conexion=conectarse();
		/* verificar la conexión */
		if (mysqli_connect_errno()) {
    		return "Error: Conexión fallida:  mysqli_connect_error() :/";
		}
		$consulta="SELECT imagen FROM usuarios WHERE email='$email'";
		$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

		$num_regs=$ejecutar_consulta->num_rows;
		if ($num_regs==0){
			$conexion->close();
			return "Error: No está registrado el usuario con email <b>$email<b> :(";
		}
		else 
		{	
			if ($num_regs==1){	
				$registro_usuario=$ejecutar_consulta->fetch_assoc();
				$imagen_usuario=$registro_usuario["imagen"];
				$conexion->close();
				return $imagen_usuario;
			}
				else{
					$conexion->close();
					return "Error: Hay varios registros para el usuario con email <b>$email<b> :/";
				}
		}
	}

function verifica_clave_inscripcion($pass,$cod_equipo,$cod_competencia){
	$conexion=conectarse();
	$consulta="SELECT clave_inscripciones FROM competenciasq WHERE (competencia=$cod_competencia AND ";
	if (isset($equipo)) 
		$consulta.="equipo='$equipo')";
	else
		$consulta.="nombre_corto='$cod_equipo')";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1){	
		$registro=$ejecutar_consulta->fetch_assoc();
		$p=trim($registro["clave_inscripciones"]);
		$conexion->close();
		if (strcmp($pass, $p) !== 0) 
			return "Error: la clave para inscripciones del equipo, es incorrecta :(";
		else
			return "todo OK";
	}
	else{
		$conexion->close();
		return "Error: no está inscrito este equipo en la competencia";
	}
}

function administrador_competencia($cod_usuario,$cod_competencia){
	if (!isset($cod_usuario) or !isset($cod_competencia))
		return "Error: no estás autorizado para administrar esta competencia :(";

	if (!$cod_usuario or !$cod_competencia) 
		return "Error: no estás autorizado para administrar esta competencia :(";
	
	$conexion=conectarse();
	$consulta="SELECT * FROM competenciasa WHERE (competencia=$cod_competencia AND administrador=$cod_usuario)";
	$ejecutar_consulta = $conexion->query($consulta);

	$num_regs=$ejecutar_consulta->num_rows;
//	echo "cod_usuario1: $cod_usuario<br>cod_competencia: $cod_competencia<br>num_regs: $num_regs<br>";
//	echo "consulta: $consulta<br>";print_r($conexion->error_list);exit();
	$conexion->close();
	if ($num_regs==1)	
		return TRUE;
	else
		return "Error: no estás autorizado para administrar esta competencia :(";
}

function administrador_ppal_competencia($cod_usuario,$cod_competencia){
	$conexion=conectarse();
	$consulta="SELECT * FROM competenciasa WHERE (competencia=$cod_competencia AND administrador=$cod_usuario)";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	$men=FALSE;
	if ($num_regs==1){
		$row=$ejecutar_consulta->fetch_assoc();
		if($row["principal"]==1)
			$men=TRUE;
		else
			$men="Error: debes ser Administrador Principal de la competencia para registrar esta información :(";
	}	
	else{
		$men="Error: no estás autorizado para administrar esta competencia :(";
	}
	$conexion->close();
	return $men;		
}

function tipo_categoria($cod_categoria){
	$conexion=conectarse();
	$consulta="SELECT individual FROM categorias WHERE (cod_categoria='$cod_categoria')";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	$men=FALSE;
	if ($num_regs==1){
		$row=$ejecutar_consulta->fetch_assoc();
		if($row["individual"]==1)
			$men="individual";
		else
			$men="sincronizado";
	}	
	else
		$men="Error: no está registrada la categoria <b>$cod_categoria<b> :(";

	$conexion=conectarse();
	return $men;
}

function tipo_modalidad($cod_modalidad){
	$conexion=conectarse();
	$consulta="SELECT individual FROM modalidades WHERE (cod_modalidad='$cod_modalidad')";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	$num_regs=$ejecutar_consulta->num_rows;
	$men=FALSE;
	if ($num_regs==1){
		$row=$ejecutar_consulta->fetch_assoc();
		if($row["individual"]==1)
			$men="individual";
		else
			$men="sincronizado";
	}	
	else
		$men="Error: no está registrada la modalidad <b>$cod_modalidad<b> :(";
	$conexion->close();
	return $men;
}

function quitar_tildes($cadena) {
	if (substr($cadena, 0, 4)=='Sol ') {
		var_dump($cadena);
	}
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","Á","É","Í","Ó","Ú");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}

function primera_mayuscula_palabra($cadena)
{
	$cadena=utf8_decode($cadena);
	$cadena=quitar_tildes($cadena);
	$cadena=strtolower($cadena);
	$cadena=ucwords($cadena);

	return $cadena;
}

function primera_mayuscula_frase($cadena)
{
	$cadena=utf8_decode($cadena);
	$cadena=quitar_tildes($cadena);
	$cadena=strtolower($cadena);
	$cadena=ucfirst($cadena);

	return $cadena;
}

function sorteo($criterio,$criterio_sexos,$criterio_categorias,$cod_competencia,$evento){

	global $cat_sex;
	
	$conexion=conectarse();
	$consulta="SELECT DISTINCT p.categoria, p.sexo, p.cod_planilla, p.clavadista, p.orden_salida
		FROM planillas as p";
	$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")
		ORDER BY p.categoria DESC, p.sexo";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	
	$com = array();
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$com[]=$row;
		$categoria=isset($row['categoria'])?$row['categoria']:NULL;
		$sexo=isset($row['sexo'])?$row['sexo']:NULL;
		$cat_sex[$categoria][$sexo]++;
	}

	krsort($cat_sex);

	$n_1=count($cat_sex);
	$n=count($com);
	$sal = array();
	$sal = array_fill(0, $n, 0);
	$x= (int) 0;
	$maximo= (int) 0;
	foreach ($cat_sex as $categoria => $row) {
		foreach ($row as $sexo => $cantidad) {
			$mx=(int) $maximo;
			$inicio=$mx+1;
			$tope=$mx+$cantidad;

			for ($i=$mx; $i < $tope; $i++) { 
				while (in_array($x, $sal)){
					$x=rand($inicio, $tope);

				}
				
				$sal[$i]=$x;
				if ($x>$maximo) {
					$maximo=(int) $x;
				}
			}
		}
	}

	for ($i=0; $i < $n; $i++) { 
		$clavadista=$com[$i]['clavadista'];
		$consulta="UPDATE planillas as p
			SET orden_salida=$sal[$i],
				evento=$evento ";
		$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")"." AND clavadista=$clavadista";
		$ejecutar_consulta = $conexion->query($consulta);
	}
	$consulta="UPDATE competenciaev 
		 SET sorteada=1
		 WHERE competencia=$cod_competencia AND numero_evento=$evento";
	$ejecutar_consulta = $conexion->query($consulta);
	
	$conexion->close();
	$_SESSION["sorteada"]=1;
	return;
}

function lee_evento_competencia($cod_competencia,$fechahora){
	$row=array();
	$conexion=conectarse();
	$consulta="SELECT e.*, m.modalidad AS nom_modalidad, c.competencia AS nom_competencia
		FROM competenciaev AS e 
		LEFT JOIN competencias AS c ON c.cod_competencia=e.competencia
		LEFT JOIN modalidades AS m ON m.cod_modalidad=e.modalidad
		WHERE e.competencia=$cod_competencia AND e.fechahora='$fechahora'";
	$ejecutar_consulta = $conexion->query($consulta);
	
	if (!$ejecutar_consulta) {
		$row['error']="No puedo leer el evento $fechahora de la competencia $cod_competencia";
	}
	else{
		$row=$ejecutar_consulta->fetch_assoc();
	}
	$conexion->close();

	return $row;
}

function limpia_sorteo($criterio,$criterio_sexos,$criterio_categorias,$cod_competencia,$evento){
	$conexion=conectarse();
	$consulta="UPDATE planillas as p
		SET orden_salida=NULL,
			evento=NULL ";
	$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")";
	$ejecutar_consulta = $conexion->query($consulta);

	$consulta="UPDATE competenciaev 
		 SET sorteada=NULL
		 WHERE competencia=$cod_competencia AND numero_evento=$evento";
	$ejecutar_consulta = $conexion->query($consulta);
	$conexion->close();
	$_SESSION["sorteada"]=0;
	return;
}

function guarda_sorteo($criterio,$criterio_sexos,$criterio_categorias,$clavadistas,$orden,$evento){

	$n=count($clavadistas);
	for ($i=0; $i < $n; $i++) { 
		if ($orden[$i]==0) 	return "Error: asignaste el orden de salida en cero :(";
		if ($orden[$i]>$n) 	return "Error: asignaste el orden de salida ".$orden[$i]." mayor a la cantidad de clavadistas :(";
		for ($j=0; $j < $i; $j++) { 
			if ($orden[$j]==$orden[$i]) return "Error: asignaste el orden de salida ".$orden[$j]." repetido :(";
		}
	}		
	$conexion=conectarse();
	for ($i=0; $i < $n; $i++) { 
		$consulta="UPDATE planillas as p
			SET orden_salida=$orden[$i],
				evento=$evento ";
		$consulta .= $criterio.$criterio_sexos.$criterio_categorias.")"." AND clavadista=$clavadistas[$i]";
		$ejecutar_consulta = $conexion->query($consulta);
	}
	$consulta="UPDATE competenciaev 
		 SET sorteada=1
		 WHERE competencia=$cod_competencia AND numero_evento=$evento";
	$ejecutar_consulta = $conexion->query($consulta);
	$conexion->close();
	return;
}
function minimo($cal,$d,$h){
	$m=99;
	$l=$d;
	for ($i=$d; $i <=$h ; $i++) { 
		if ($cal [$i] != 10000){
			if ($cal [$i] < $m){
				$m=$cal [$i];
				$l=$i;	
			}
		} 
	}

	return $l;
}
function maximo($cal,$d,$h){
	$m=0;
	$l=$d;
	for ($i=$d; $i <=$h ; $i++) { 
		if ($cal [$i] != 10000){
			if ($cal [$i] > $m){
				$m=$cal [$i];
				$l=$i;	
			}
		} 
	}

	return $l;
}

function suma($cal,$sincronizado,$num_jueces){
	if ($sincronizado) {
		switch ($num_jueces) {
			case '7':
				$k=minimo($cal,3,7);
				$cal[$k]=10000;
				$k=maximo($cal,3,7);
				$cal[$k]=10000;
				break;
			case '8':
				$k=minimo($cal,1,4);
				$cal[$k]=10000;
				$k=maximo($cal,1,4);
				$cal[$k]=10000;
				$k=minimo($cal,5,8);
				$cal[$k]=10000;
				$k=maximo($cal,5,8);
				$cal[$k]=10000;
				break;
			case '9':
				$k=minimo($cal,1,4);
				$cal[$k]=10000;
				$k=minimo($cal,5,9);
				$cal[$k]=10000;
				$k=maximo($cal,1,4);
				$cal[$k]=10000;
				$k=maximo($cal,5,9);
				$cal[$k]=10000;
				break;
			case '10':
				$k=minimo($cal,1,5);
				$cal[$k]=10000;
				$k=maximo($cal,1,5);
				$cal[$k]=10000;
				$k=minimo($cal,6,10);
				$cal[$k]=10000;
				$k=maximo($cal,6,10);
				$cal[$k]=10000;
				break;
			case '11':
				$k=minimo($cal,1,3);
				$cal[$k]=10000;
				$k=maximo($cal,1,3);
				$cal[$k]=10000;

				$k=minimo($cal,4,6);
				$cal[$k]=10000;
				$k=maximo($cal,4,6);
				$cal[$k]=10000;

				$k=minimo($cal,7,11);
				$cal[$k]=10000;
				$k=maximo($cal,7,11);
				$cal[$k]=10000;
				break;
		}
	}
	else{
		switch ($num_jueces) {
			case '5':
				$k=minimo($cal,1,5);
				$cal[$k]=10000;
				$k=maximo($cal,1,5);
				$cal[$k]=10000;
				break;
			case '6':
				$k=minimo($cal,1,6);
				$cal[$k]=10000;
				$k=maximo($cal,1,6);
				$cal[$k]=10000;
				break;
			case '7':
				$k=minimo($cal,1,7);
				$cal[$k]=10000;
				$k=minimo($cal,1,7);
				$cal[$k]=10000;
				$k=maximo($cal,1,7);
				$cal[$k]=10000;
				$k=maximo($cal,1,7);
				$cal[$k]=10000;
				break;
			case '8':
				$k=minimo($cal,1,8);
				$cal[$k]=10000;
				$k=minimo($cal,1,8);
				$cal[$k]=10000;
				$k=maximo($cal,1,8);
				$cal[$k]=10000;
				$k=maximo($cal,1,8);
				$cal[$k]=10000;
				break;
		}
	}

$s=0;
for ($i=1; $i < $num_jueces+1; $i++) { 
	if ($cal[$i] >0  
		and $cal[$i]<11)
		$s=$s+$cal[$i];
}
$suma=$s;

if ($sincronizado) {
	switch ($num_jueces) {
		case '5':
			$s=($s/5)*3;
			break;
		case '6':
			$s=($s/4)*3;
			break;
		case '7':
			$s=($s/5)*3;
			break;
		case '8':
			$s=($s/4)*3;
			break;
		case '9':
			$s=($s/5)*3;
			break;
		case '10':
			$s=($s/6)*3;
			break;
		case '11':
			$s=($s/5)*3;
			break;
	}
}
else{
	switch ($num_jueces) {
		case '6':
			$s=($s/4)*3;
			break;
		case '8':
			$s=($s/4)*3;
			break;
	}
}
$res = array('suma' => $suma, 'puntaje' => $s);

return $res;
}

function numero_jueces($cod_competencia,$numero_evento,$panel){

$num_jueces=0;
$conexion=conectarse();
$consulta="SELECT DISTINCT z.panel, z.ubicacion, z.juez
	FROM competenciasz as z
	WHERE z.competencia=$cod_competencia and z.evento=$numero_evento and z.panel=$panel
	ORDER BY ubicacion";
$ejecutar_consulta = $conexion->query($consulta);
if ($ejecutar_consulta){
	$num_regs=$ejecutar_consulta->num_rows;
	if (!$num_regs)
		$num_jueces="No hay jueces en Panel $panel registrados para este evento :(";
	else{
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$ub=$row["ubicacion"];
			if ($ub!=25)
				$num_jueces++;
		}
	}
}
$conexion->close();
return $num_jueces;
}

function actualiza_puntajes($cod_competencia,$numero_evento,$cod_planilla,$saltos_categoria){
	$k=0;
	$ka=0;
	$kc=0;
	$ns=0;
	$conexion=conectarse();
	$consulta="SELECT turno, total_salto, puntaje_salto, abierto
			FROM planillad
			WHERE planilla=$cod_planilla 
				and calificado=1";
	$ejecutar_consulta = $conexion->query($consulta);
	$participa_abierto="";
	if ($ejecutar_consulta){
		while ($row=$ejecutar_consulta->fetch_assoc()) {
			$k+=$row["puntaje_salto"];
			$turno=$row["turno"];
			if ($ronda<=$saltos_categoria) 
				$kc+=$row["puntaje_salto"];
			$abierto=$row["abierto"];
			if ($abierto=="*" 
				or $categoria=="AB")
				$ka+=$row["puntaje_salto"];
			if ($abierto=="*") 
				$participa_abierto="*";
			$actualiza="UPDATE planillad 
				SET acumulado=$k 
				WHERE planilla=$cod_planilla and turno=$turno";
			$ejecutar_actualiza = $conexion->query($actualiza);
		}
		$actualiza="UPDATE planillas 
			SET total=$kc";
		if ($ka>0)
			$actualiza .= ", total_abierta=$ka ";
		if ($participa_abierto=="*")
			$actualiza .= ", part_abierta='*' ";
		$actualiza .= " WHERE cod_planilla=$cod_planilla";
		$ejecutar_actualiza = $conexion->query($actualiza);
	}

	$separa_extraoficiales=0;
	$consulta="SELECT max_2_competidores 
		FROM competencias 
		WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);

	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1) {
		$row=$ejecutar_consulta->fetch_assoc();
		$separa_extraoficiales=$row["max_2_competidores"];
	}

	$criterio=" WHERE (p.competencia=$cod_competencia AND p.evento=$numero_evento AND p.usuario_retiro IS NULL) ";

	if ($separa_extraoficiales) {
		$actualiza="UPDATE planillas as p
			SET p.extraof=NULL ";
		$actualiza .= $criterio;
		$ejecutar_actualiza = $conexion->query($actualiza);
		$actualiza="UPDATE planillas as p
			SET p.extraof='*' ";
		$actualiza .= $criterio." AND p.participa_extraof='*') ";
		$ejecutar_actualiza = $conexion->query($actualiza);

		$consulta="SELECT p.cod_planilla, p.competencia, p.categoria, p.sexo, p.equipo, p.extraof, p.total, p.lugar
			FROM planillas as p"; 
		$consulta .= $criterio.
 			"ORDER BY p.categoria, p.sexo, p.equipo, p.total DESC";
		$ejecutar_consulta = $conexion->query($consulta);
		$cat_sex_equ="";
		$n=0;
		while ($row=$ejecutar_consulta->fetch_assoc()){
			$cod_planilla=$row["cod_planilla"];
			$leido=$row["categoria"].$row["sexo"].$row["equipo"];
			if (!($cat_sex_equ==$leido)) {
				$n=0;
				$cat_sex_equ=$leido;
			}
			$n++;
			if ($n>2){
				$actualiza="UPDATE planillas 
							SET extraof='*'
							WHERE cod_planilla=$cod_planilla";
				$ejecutar_actualiza = $conexion->query($actualiza);
			}
		}
	}

	$consulta="SELECT p.cod_planilla, p.competencia, p.categoria, p.sexo, p.equipo, p.extraof, p.total, p.lugar
		FROM planillas as p"; 
	$consulta .= $criterio.
 	"ORDER BY p.categoria, p.sexo, p.extraof, p.total DESC";
	$ejecutar_consulta = $conexion->query($consulta);
	$cat_sex="";
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$cod_planilla=$row["cod_planilla"];
		$extraof=$row["extraof"];
		$leido=$row["categoria"].$row["sexo"].$extraof;
		if (!($cat_sex==$leido)) {
			$pos=0;
			$ext=0;
			$cat_sex=$leido;
		}
		if (is_null($extraof) or 
			$extraof=="") 
			$pos++;
		else
			$ext++;
		
		$actualiza="UPDATE planillas ";
		if (is_null($extraof)
			or $extraof=="")
			$actualiza .= "SET lugar=$pos ";
		else
			$actualiza .= "SET 	lugar=$ext";
		$actualiza .= " WHERE cod_planilla=$cod_planilla";
		$ejecutar_actualiza = $conexion->query($actualiza);
	}

	$consulta="SELECT p.cod_planilla, p.competencia, p.categoria, p.sexo, p.extraof, p.total_abierta, p.lugar_abierta
		FROM planillas as p"; 
	$consulta .= $criterio." AND (p.part_abierta='*' or p.categoria='AB'))";
 		"ORDER BY p.sexo, p.total_abierta DESC";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

	$cat_sex_ext="";
	while ($row=$ejecutar_consulta->fetch_assoc()){
		$cod_planilla=$row["cod_planilla"];
		$extraof=$row["extraof_abierto"];
		$leido=$row["sexo"].$extraof;		
		if (is_null($extraof)) 
			$pos++;
		else
			$ext++;
		$actualiza="UPDATE planillas ";
		if (is_null($extraof))
			$actualiza .= "SET lugar_abierta=$pos ";
		else
			$actualiza .= "SET lugar_abierta=$ext ";
		$actualiza .= "WHERE cod_planilla=$cod_planilla";
		$ejecutar_actualiza = $conexion->query($actualiza);
	}
	$conexion->close();
	return;
}
function nom_mes($mes){
	switch ($mes) {
		case '1': $nom="Enero"; break;
		case '2': $nom="Febrero"; break;
		case '3': $nom="Marzo"; break;
		case '4': $nom="Abril"; break;
		case '5': $nom="Mayo"; break;
		case '6': $nom="Junio"; break;
		case '7': $nom="Julio"; break;
		case '8': $nom="Agosto"; break;
		case '9': $nom="Septiembre"; break;
		case '10': $nom="Octubre"; break;
		case '11': $nom="Noviembre"; break;
		case '12': $nom="Diciembre"; break;
	}
	return $nom;
}
function fecha_desde_hasta($desde,$hasta){
	$date = date_create($desde);
	$dd=date_format($date, 'd');
	$md=date_format($date, 'm');
	$ad=date_format($date, 'Y');
	$date = date_create($hasta);
	$dh=date_format($date, 'd');
	$mh=date_format($date, 'm');
	$ah=date_format($date, 'Y');
	if ($ad==$ah)
		if ($md==$mh)
			$fecha="del ".$dd." al ".$dh." de ".nom_mes($md)." del ".$ad;
		else
			$fecha="del ".$dd." de ".nom_mes($md)." al ".$dh." de ".nom_mes($mh)." del ".$ad;
	else
		$fecha="del ".$dd." de ".nom_mes($md)." del ".$ad." al ".$dh." de ".nom_mes($mh)." de ".$ah;


	return  $fecha;
}

function elimina_calificaciones($cal_cop,$sincronizado,$num_jueces){
	if ($sincronizado) {
		switch ($num_jueces) {
			case '7':
				$k=minimo($cal_cop,3,7);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,3,7);
				$cal_cop[$k]=10000;
				break;
			case '8':
				$k=minimo($cal_cop,1,4);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,4);
				$cal_cop[$k]=10000;
				$k=minimo($cal_cop,5,8);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,5,8);
				$cal_cop[$k]=10000;
				break;
			case '9':
				$k=minimo($cal_cop,1,4);
				$cal_cop[$k]=10000;
				$k=minimo($cal_cop,5,9);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,4);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,5,9);
				$cal_cop[$k]=10000;
				break;
			case '10':
				$k=minimo($cal_cop,1,5);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,5);
				$cal_cop[$k]=10000;
				$k=minimo($cal_cop,6,10);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,6,10);
				$cal_cop[$k]=10000;
				break;
			case '11':
				$k=minimo($cal_cop,1,3);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,3);
				$cal_cop[$k]=10000;
				$k=minimo($cal_cop,4,6);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,4,6);
				$cal_cop[$k]=10000;
				$k=minimo($cal_cop,7,11);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,7,11);
				$cal_cop[$k]=10000;
				break;
		}
	}
	else{
		switch ($num_jueces) {
			case '5':
				$k=minimo($cal_cop,1,5);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,5);
				$cal_cop[$k]=10000;
				break;
			case '6':
				$k=minimo($cal_cop,1,6);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,6);
				$cal_cop[$k]=10000;
				break;
			case '7':
				$k=minimo($cal_cop,1,7);
				$cal_cop[$k]=10000;
				$k=minimo($cal_cop,1,7);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,7);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,7);
				$cal_cop[$k]=10000;
				break;
			case '8':
				$k=minimo($cal_cop,1,8);
				$cal_cop[$k]=10000;
				$k=minimo($cal_cop,1,8);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,8);
				$cal_cop[$k]=10000;
				$k=maximo($cal_cop,1,8);
				$cal_cop[$k]=10000;
				break;
		}
	}

	return $cal_cop;
}

function acumula_juez($juez,$puntaje,$cal_juez,$gd,$penal,$nom_juez){
	global $jueces, $nombre, $real, $calif, $dif, $difn, $difp, $difa, $saltos;

	$cal=($cal_juez-$penal)*3*$gd;
	$k = array_search($juez, $jueces); 
	if (!is_numeric($k)) {
		$jueces[]=$juez;
		$nombre[]=$nom_juez;
		$real[]=0;
		$calif[]=0;
		$dif[]=0;
		$difn[]=0;
		$difp[]=0;
		$difa[]=0;
		$saltos[]=0;
	}
	$k = array_search($juez, $jueces); 
	if (is_numeric($k)) {
		$difer=$cal-$puntaje;
		if ($difer<0) {
			$dif_neg=$difer;
			$dif_pos=0;
		}
		else{
			$dif_neg=0;
			$dif_pos=$difer;
		}
		$real[$k]=$real[$k]+$puntaje;
		$calif[$k]=$calif[$k]+$cal;
		$dif[$k]=$dif[$k]+$difer;
		$difa[$k]=$difa[$k]+abs($difer);
		$difn[$k]=$difn[$k]+$dif_neg;
		$difp[$k]=$difp[$k]+$dif_pos;
		$saltos[$k]++;
	}
	return;
}

function puntajes_juez($competencia,$evento,$juez){
	global $mat;
	$conexion=conectarse();
	$consulta="SELECT p.categoria, p.sexo, p.clavadista, c.nombre AS nom_clavadista, juez";
	$consulta.=$juez.", p".$juez." AS puntaje
		FROM puntajes_jueces AS p
		LEFT JOIN usuarios AS c on c.cod_usuario=p.clavadista
		WHERE competencia=$competencia AND evento=$evento
		ORDER BY p.categoria, p.sexo, p".$juez." DESC";
	$ejecutar_consulta=$conexion->query($consulta);
	$i=0;
	if ($ejecutar_consulta) {
		$cat_sex_ant=NULL;
		while ($row=$ejecutar_consulta->fetch_assoc()) {
			$categoria=isset($row['categoria'])?$row['categoria']:NULL;
			$sexo=isset($row['sexo'])?$row['sexo']:NULL;
			$cat_sex=$categoria.'-'.$sexo;
			if ($cat_sex!=$cat_sex_ant){
				$k=0;
				$cat_sex_ant=$cat_sex;
			}
			$clavadista=isset($row['clavadista'])?$row['clavadista']:NULL;
			$nom_clavadista=isset($row['nom_clavadista'])?utf8_decode($row['nom_clavadista']):NULL;
			$puntaje=isset($row['puntaje'])?$row['puntaje']:NULL;
			$k++;
			$lin=$mat[$i];
			$lin['lugar'.$juez]=$k;
			$lin['clavadista'.$juez]=$nom_clavadista;
			$lin['puntaje'.$juez]=$puntaje;
			$mat[$i]=$lin;
			$i++;
		}
	}
	return;
}

function evaluacion_jueces($competencia,$evento){
	global $mat, $jueces;

	$mat=array();
	$jueces=array();
	$conexion=conectarse();
	$cat_sex_ant=NULL;
	$consulta="SELECT p.categoria, p.sexo, p.clavadista, c.nombre AS nom_clavadista, p.total, j1.nombre AS juez1, j2.nombre AS juez2, j3.nombre AS juez3, j4.nombre AS  juez4, j5.nombre AS juez5, j6.nombre AS juez6, j7.nombre AS juez7
		FROM puntajes_jueces AS p
		LEFT JOIN usuarios AS c on c.cod_usuario=p.clavadista
		LEFT JOIN usuarios as j1 on j1.cod_usuario=p.juez1
		LEFT JOIN usuarios as j2 on j2.cod_usuario=p.juez2
		LEFT JOIN usuarios as j3 on j3.cod_usuario=p.juez3
		LEFT JOIN usuarios as j4 on j4.cod_usuario=p.juez4
		LEFT JOIN usuarios as j5 on j5.cod_usuario=p.juez5
		LEFT JOIN usuarios as j6 on j6.cod_usuario=p.juez6
		LEFT JOIN usuarios as j7 on j7.cod_usuario=p.juez7
		WHERE competencia=$competencia AND evento=$evento
		ORDER BY p.categoria, p.sexo, total DESC";
	$ejecutar_consulta=$conexion->query($consulta);
	if ($ejecutar_consulta) {
		$k=NULL;
		while ($row=$ejecutar_consulta->fetch_assoc()) {
			if (is_null($k)){
				$jueces[1]=isset($row['juez1'])?$row['juez1']:NULL;
				$jueces[2]=isset($row['juez2'])?$row['juez2']:NULL;
				$jueces[3]=isset($row['juez3'])?$row['juez3']:NULL;
				$jueces[4]=isset($row['juez4'])?$row['juez4']:NULL;
				$jueces[5]=isset($row['juez5'])?$row['juez5']:NULL;
				$jueces[6]=isset($row['juez6'])?$row['juez6']:NULL;
				$jueces[7]=isset($row['juez7'])?$row['juez7']:NULL;
			}
			$categoria=isset($row['categoria'])?$row['categoria']:NULL;
			$sexo=isset($row['sexo'])?$row['sexo']:NULL;
			$cat_sex=$categoria.'-'.$sexo;
			if ($cat_sex!=$cat_sex_ant){
				$k=0;
				$cat_sex_ant=$cat_sex;
			}
			$clavadista=isset($row['clavadista'])?$row['clavadista']:NULL;
			$nom_clavadista=isset($row['nom_clavadista'])?utf8_decode($row['nom_clavadista']):NULL;
			$total=isset($row['total'])?$row['total']:NULL;
			$k++;
			$lin=array(
				'cat_sex'			=>	$cat_sex,
				'lugar'				=>	$k,
				'nom_clavadista'	=>	$nom_clavadista,
				'total'				=>	$total);
			$mat[]=$lin;
		}
	}

	foreach ($jueces as $key => $juez) {
		if (isset($juez))
			puntajes_juez($competencia,$evento,$key);
	}
	
	return $mat;
}

function acumula_medallas_cat_sexo($categoria,$nom_cat,$sexo,$lugar,$equipo,$nom_equipo){
	global $llave,$cat_sx,$categorias,$sexos,$equipos,$oro,$plata,$bronce;
	$key=$categoria.$sexo.$equipo;
	if (!in_array($key, $llave)) {
		$llave[]=$key;
		$cat_sx[]=$categoria.$sexo;
		$categorias[]=$nom_cat;
		$sex=(($sexo=="m" or $sexo=="M")?"Varones":(($sexo=="x" or $sexo=="X")?"Mixto": "Damas"));
		$sexos[]=$sex;
		$equipos[]=$nom_equipo;
		$oro[]=0;
		$plata[]=0;
		$bronce[]=0;
	}
	if (in_array($key, $llave)) {
		$k = array_search($key, $llave); 
		switch ($lugar) {
			case 1:
				$oro[$k]++;
				break;
			case 2:
				$plata[$k]++;
				break;
			case 3:
				$bronce[$k]++;
				break;
		}
	}

	return;

}

function acumula_medallas_categoria($categoria,$nom_cat,$lugar,$equipo,$nom_equipo){
	global $llave,$cat,$categorias,$equipos,$oro,$plata,$bronce;
	$key=$categoria.$equipo;
	if (!in_array($key, $llave)) {
		$llave[]=$key;
		$cat[]=$categoria;
		$categorias[]=$nom_cat;
		$equipos[]=$nom_equipo;
		$oro[]=0;
		$plata[]=0;
		$bronce[]=0;
	}
	if (in_array($key, $llave)) {
		$k = array_search($key, $llave); 
		switch ($lugar) {
			case 1:
				$oro[$k]++;
				break;
			case 2:
				$plata[$k]++;
				break;
			case 3:
				$bronce[$k]++;
				break;
		}
	}

	return;

}
function acumula_medallas($lugar,$equipo,$nom_equipo){
	global $llave,$equipos,$oro,$plata,$bronce;
	$key=strtoupper($equipo);
	$esta=in_array($key, $llave, TRUE);
	if (!$esta) {
		$llave[]=$key;
		$equipos[]=$nom_equipo;
		$oro[]=0;
		$plata[]=0;
		$bronce[]=0;
	}

	if (in_array($key, $llave)) {
		$k = array_search($key, $llave); 
		switch ($lugar) {
			case 1:
				$oro[$k]++;
				break;
			case 2:
				$plata[$k]++;
				break;
			case 3:
				$bronce[$k]++;
				break;
		}
	}

	return;
}

function decimales($cod_competencia){
	$decimales=2;
/*	$conexion=conectarse();
	$consulta="SELECT decimales FROM competencias WHERE cod_competencia=$cod_competencia";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1){
		$row=$ejecutar_consulta->fetch_assoc();
		$decimales=isset($row["decimales"])?$row["decimales"]:NULL;
	}	

	$conexion->close();
*/
	return $decimales;
}

function esta_calificando($cod_planilla,$ronda){
	if ($cod_planilla and $ronda) {
		$conexion=conectarse();
		$consulta="SELECT calificando FROM planillad WHERE planilla=$cod_planilla AND ronda=$ronda";
		$ejecutar_consulta = $conexion->query($consulta);
		$row=$ejecutar_consulta->fetch_assoc();
		$conexion->close();
		$calificando=isset($row["calificando"])?$row["calificando"]:NULL;
	}
	else
		$calificando=FALSE;

	return $calificando;
}

function esta_calificado($cod_planilla,$ronda){
	if ($cod_planilla and $ronda) {
		$conexion=conectarse();
		$consulta="SELECT calificado FROM planillad WHERE planilla=$cod_planilla AND ronda=$ronda";
		$ejecutar_consulta = $conexion->query($consulta);
		$row=$ejecutar_consulta->fetch_assoc();
		$conexion->close();
		$calificado=isset($row["calificado"])?$row["calificado"]:NULL;
	}
	else
		$calificado=FALSE;

	return $calificado;
}

function administrador_sistema($cod_usuario){
	$conexion=conectarse();
	$consulta="SELECT admin_sist FROM usuarios WHERE cod_usuario=$cod_usuario";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1){
		$row=$ejecutar_consulta->fetch_assoc();
		$admin=isset($row["admin_sist"])?$row["admin_sist"]:NULL;
		if ($admin==1) {
			$men= TRUE;
		}
		else
			$men= "Error: no estás autorizado para administrar el Sistema :(";
	}	
	else
		$men= "Error: no estás registrado como usuario :(";

	$conexion->close();
	return $men;
}
function lee_salto_planilla($planilla,$ronda){
	global $row;
	$conexion=conectarse();
	$consulta="SELECT * FROM planillad WHERE planilla=$planilla AND ronda=$ronda";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if ($num_regs==1){
		$row=$ejecutar_consulta->fetch_assoc();
	}	
	$conexion->close();
	return ;

}

function cantidad_jueces($competencia,$evento){
	$conexion=conectarse();
	$consulta="SELECT * FROM competenciasz WHERE competencia=$competencia AND evento=$evento and panel=1";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	$conexion->close();
	return $num_regs ;
}

function select_entrenador($entrenador,$competencia,$corto){
	$criterio=NULL;
	if ($competencia) {
		$criterio=" WHERE p.competencia=$competencia";
	}
	if ($corto) {
		if ($criterio) {
			$criterio.=" AND p.equipo='$corto'";
		} else {
			$criterio=" WHERE p.equipo='$corto'";
		}
	}

	$conexion=conectarse();
	$consulta="SELECT DISTINCT p.entrenador, u.nombre AS nom_entrenador
		FROM planillas AS p 
		LEFT JOIN usuarios AS u ON u.cod_usuario=p.entrenador";
	if ($criterio) {
		$consulta.=$criterio;
	}
	$consulta.=" ORDER BY nom_entrenador";

	$ejecutar_consulta=$conexion->query($consulta);

	while($row=$ejecutar_consulta->fetch_assoc()){
		$nom_entrenador=utf8_decode($row["nom_entrenador"]);
		$cod_entrenador=$row["entrenador"];
		echo "<option value='$cod_entrenador'";
		if(isset($entrenador)){
			if($cod_entrenador==$entrenador)
				echo " selected";
		}
		echo ">$nom_entrenador</option>";
	}
	$conexion->close();

}

function busca_preserie($competencia,$categoria,$modalidad){
	$conexion=conectarse();
	$consulta="SELECT * 
		FROM preseries 
		WHERE competencia=$competencia 
			AND modalidad='$modalidad' 
			and categoria='$categoria' ";
	$ejecutar_consulta = $conexion->query($consulta);
	$num_regs=$ejecutar_consulta->num_rows;
	if (!$num_regs) {
		$serie=NULL;
	}
	else{
		while ($row=$ejecutar_consulta->fetch_assoc()) {
			$orden=isset($row["orden"])?$row["orden"]:NULL;
			$salto=isset($row["salto"])?$row["salto"]:NULL;
			$posicion=isset($row["posicion"])?$row["posicion"]:NULL;
			$altura=isset($row["altura"])?$row["altura"]:NULL;
			$grado=isset($row["grado"])?$row["grado"]:NULL;
			$libre=isset($row["libre"])?$row["libre"]:NULL;
			$serie[]=array('orden' => $orden, 'salto' => $salto, 'posicion' => $posicion, 'altura' => $altura, 'grado' => $grado, 'libre' => $libre);
		}
	}
	$conexion->close();

	return $serie ;
}

function calificacion_promedio($clavadista,$salto,$posicion,$altura){
	$promedio=NULL;
	$consulta="SELECT d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, d.cal8, d.cal9, d.cal10, d.cal11
		FROM planillad as d 
		LEFT JOIN planillas as p on p.cod_planilla=d.planilla
		WHERE p.clavadista=$clavadista 
			AND d.salto='$salto'
			AND d.posicion='$posicion'
			AND d.altura=$altura";
	$conexion=conectarse();
	$ejecutar_consulta=$conexion->query($consulta);
	if ($ejecutar_consulta) {
		$suma=0;
		$cant=0;
		while ($row=$ejecutar_consulta->fetch_assoc()) {
			$cal1=isset($row['cal1'])?$row['cal1']:NULL;
			$cal2=isset($row['cal2'])?$row['cal2']:NULL;
			$cal3=isset($row['cal3'])?$row['cal3']:NULL;
			$cal4=isset($row['cal4'])?$row['cal4']:NULL;
			$cal5=isset($row['cal5'])?$row['cal5']:NULL;
			$cal6=isset($row['cal6'])?$row['cal6']:NULL;
			$cal7=isset($row['cal7'])?$row['cal7']:NULL;
			$cal8=isset($row['cal8'])?$row['cal8']:NULL;
			$cal9=isset($row['cal9'])?$row['cal9']:NULL;
			$cal10=isset($row['cal10'])?$row['cal10']:NULL;
			$cal11=isset($row['cal11'])?$row['cal11']:NULL;
			if ($cal1) {
				$suma+=$cal1;
				$cant++;
			}
			if ($cal2) {
				$suma+=$cal2;
				$cant++;
			}
			if ($cal3) {
				$suma+=$cal3;
				$cant++;
			}
			if ($cal4) {
				$suma+=$cal4;
				$cant++;
			}
			if ($cal5) {
				$suma+=$cal5;
				$cant++;
			}
			if ($cal6) {
				$suma+=$cal6;
				$cant++;
			}
			if ($cal7) {
				$suma+=$cal7;
				$cant++;
			}
			if ($cal8) {
				$suma+=$cal8;
				$cant++;
			}
			if ($cal9) {
				$suma+=$cal9;
				$cant++;
			}
			if ($cal10) {
				$suma+=$cal10;
				$cant++;
			}
			if ($cal11) {
				$suma+=$cal11;
				$cant++;
			}

		}
		if ($cant)
			$promedio=$suma/$cant;
	}
	$conexion->close();
	if ($promedio)
		$promedio=ajuste_fina($promedio);
	return $promedio;
}

function puntaje_primero($cod_competencia,$numero_evento,$sexo,$categoria){
	$consulta = "SELECT lugar, lugar_abierta, extraof, total, total_abierta 
	FROM planillas 
	WHERE (competencia=$cod_competencia 
		AND evento=$numero_evento
		AND extraof IS NULL ";

	if ($sexo) 
		$consulta.=" AND sexo='$sexo'";

	if ($categoria) 
		$consulta.=" AND categoria='$categoria'";

	if ($categoria=="AB")
		$consulta.=" AND lugar_abierta=1)";
	else
		$consulta.=" AND lugar=1)";

	if ($categoria=="AB")
		$consulta.=" ORDER BY lugar_abierta";
	else
		$consulta.=" ORDER BY lugar";
	$conexion=conectarse();
	$ejecutar_consulta = $conexion->query($consulta);

	if ($ejecutar_consulta) {
		$reg=$ejecutar_consulta->fetch_assoc();
		if ($categoria=="AB")
			$total1=$reg["total_abierta"];
		else
			$total1=$reg["total"];
	}
	else
		$total1=0;

	$conexion->close();
	return $total1;
}

function ajuste_calificacion($valor){
	$val=number_format($valor,1);
	$arr=explode('.', $val);
	$ent=isset($arr[0])?$arr[0]:NULL;
	$dec=isset($arr[1])?$arr[1]:NULL;
	if ($dec) {
		if ($dec>5){
			$dec=0;
			$ent++;
		}
		else{
			$dec=5;
		}
	}
	$num=$ent.'.'.$dec;
	return $num;
}

function ajuste_fina($valor){
	$val=number_format($valor,2);
	if ($val>10)
		return "Más de 10";
	$arr=explode('.', $val);
	$ent=isset($arr[0])?$arr[0]:NULL;
	$dec=isset($arr[1])?$arr[1]:NULL;
	if ($dec) {
		if ($dec>74){
			$dec=0;
			$ent++;
		}
		elseif ($dec>50) {
			$dec=5;
		}
		elseif ($dec>24) {
			$dec=5;
		}
		else{
			$dec=0;
		}
	}
	$num=$ent.'.'.$dec;
	return $num;
}

function agrega_llamados($destino,$origen){
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:array();
	$llamados[$destino]=$origen;
	$_SESSION["llamados"]=$llamados;
	return;
}

function llamo_a($programa,$default){
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	$llamado=NULL;
	if ($llamados) {
		$llamado=isset($llamados[$programa])?$llamados[$programa]:NULL;
		if ($llamado) {
			unset($llamados[$programa]);
			$_SESSION["llamados"]=$llamados;
		}
	}
	if (!$llamado)
		$llamado=$default;
	return $llamado;
}

function cual_llamo_a($programa){
	$llamados=isset($_SESSION["llamados"])?$_SESSION["llamados"]:NULL;
	if ($llamados) {
		$llamado=isset($llamados[$programa])?$llamados[$programa]:NULL;
	}
	else
		$llamado=NULL;

	return $llamado;
}

function renumera_eventos($cod_competencia){
	$conexion=conectarse();
	$consulta="SELECT fechahora, numero_evento 
		FROM competenciaev 
		WHERE competencia=$cod_competencia 
		ORDER BY fechahora";
	$ejecutar_consulta=$conexion->query($consulta);
	$arr=array();
	while ($row=$ejecutar_consulta->fetch_assoc()) {
		$fechahora=isset($row['fechahora'])?$row['fechahora']:NULL;
		$arr[]=$fechahora;
	}
	$k=0;
	foreach ($arr as $key => $fechahora) {
		$k++;
		$consulta="UPDATE competenciaev 
			SET evento=$k 
			WHERE competencia=$cod_competencia 
				AND fechahora='$fechahora'";
		$ejecutar_consulta=$conexion->query($consulta);
	}
	$conexion->close();

	return;
}
function panel_activo($cod_competencia,$numero_evento){
	$panel=1;
	$consulta="SELECT panel_activo
	FROM competenciasev
	WHERE competencia=$cod_competencia AND evento=$numero_evento
	LIMIT 1";
	$conexion=conectarse();
	$ejecutar_consulta = $conexion->query($consulta);
	if ($ejecutar_consulta){
		$row=$ejecutar_consulta->fetch_assoc();
		$panel=isset($row['panel_activo'])?$row['panel_activo']:NULL;
	}
	$conexion->close();
	return $panel;
}
function esta_en_evento($juez){
	global $cod_competencia, $numero_evento;

	$esta=FALSE;
	$consulta="SELECT juez 
	FROM competenciass
	WHERE competencia=$cod_competencia AND evento=$numero_evento AND juez=$juez
	LIMIT 1";
	$conexion=conectarse();
	$ejecutar_consulta = $conexion->query($consulta);
	if ($ejecutar_consulta) {
		$esta=$ejecutar_consulta->num_rows;
	}

	if (!$esta) {
		$consulta="SELECT juez 
		FROM competenciasz
		WHERE competencia=$cod_competencia AND evento=$numero_evento AND juez=$juez";
		$conexion=conectarse();
		$ejecutar_consulta = $conexion->query($consulta);
		if ($ejecutar_consulta) {
			$esta=$ejecutar_consulta->num_rows;
		}
	}

	$conexion->close();
	return $esta;
}

function tiempo_estimado($n){
	$tiempo_salto=50;
	$tiempo_estimado=number_format(($n*$tiempo_salto/60)+2);
	return $tiempo_estimado;
}

function tiempo_estimado_inicial($cod_competencia){
	$tiempo_salto=50;
	$consulta="SELECT e.fechahora, e.modalidad, e.categorias, e.sexos, e.participantes_estimado
	FROM competenciaev AS e 
	WHERE competencia=$cod_competencia 
		AND tiempo_estimado IS NULL";
	$conexion=conectarse();
	$ejecutar_consulta = $conexion->query($consulta);

	$arr=array();
	while ($row=$ejecutar_consulta->fetch_assoc()) {
		$fechahora = (isset($row['fechahora'])) ? $row['fechahora'] : NULL ;
		$modalidad = (isset($row['modalidad'])) ? $row['modalidad'] : NULL ;
		$sexos = (isset($row['sexos'])) ? $row['sexos'] : NULL ;
		$categorias = (isset($row['categorias'])) ? $row['categorias'] : NULL ;
		$participantes_estimado = (isset($row['participantes_estimado'])) ? $row['participantes_estimado'] : NULL ;
		$saltos_participante=cantidad_saltos($modalidad,$sexos,$categorias);
		$n=$saltos_participante*$participantes_estimado;
		$tiempo_estimado_inicial=tiempo_estimado($n);
		$arr[]=array(
			'fechahora'	=> $fechahora,
			'tiempo_estimado_inicial' => $tiempo_estimado_inicial);
	}

	foreach ($arr as $row) {
		$fechahora = (isset($row['fechahora'])) ? $row['fechahora'] : NULL ;
		$tiempo_estimado_inicial = (isset($row['tiempo_estimado_inicial'])) ? $row['tiempo_estimado_inicial'] : NULL ;

		$consulta="UPDATE competenciaev 
			SET tiempo_estimado_inicial= $tiempo_estimado_inicial 
			WHERE competencia=$cod_competencia 
				AND fechahora='$fechahora'";
		$ejecutar_consulta = $conexion->query($consulta);
	}
	$conexion->close();
}

function cantidad_saltos($modalidad,$sexos,$categorias){
	$arr=explode(',', $sexos);
	if (count($arr)>1) {
		$sexo='M';
	}
	else{
		$sexo=$arr[0];
	}
	$arr=explode(',', $categorias);
	$saltos=0;
	foreach ($arr as $key => $categoria) {
		$n=cantidad_saltos_modalidad($modalidad,$sexo,$categoria);
		if ($n>$saltos) {
			$saltos=$n;
		}
	}

	return $saltos;
}


function cantidad_saltos_modalidad($modalidad,$sexo,$categoria){
	$n=NULL;	
	if (!$modalidad OR !$sexo OR !$categoria) {
		return $n;
	}
	$conexion=conectarse();
	$consulta="SELECT DISTINCT saltos_obl, limite_dif, saltos_lib, grupos_min 
		FROM series WHERE categoria='$categoria' and sexo='$sexo' and modalidad='$modalidad'";

	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));

	if ($ejecutar_consulta) {
		$row=$ejecutar_consulta->fetch_assoc();
		$saltos_obl = (isset($row['saltos_obl'])) ? $row['saltos_obl'] : NULL ;
		$saltos_lib = (isset($row['saltos_lib'])) ? $row['saltos_lib'] : NULL ;
		$n=$saltos_obl+$saltos_lib;
	}
	$conexion->close();
	return $n;
}


function asigna_hora_estimada($competencia){
	$conexion=conectarse();
	$consulta="SELECT fechahora, tiempo_estimado, calentamiento, participantes_estimado, evento, tiempo_estimado_inicial
		FROM competenciaev
		WHERE competencia=$competencia";
	$ejecutar_consulta = $conexion->query(utf8_decode($consulta));
	if ($ejecutar_consulta) {
		$primera_hora=NULL;
		$hora_siguiente=NULL;
		$dia_anterior=NULL;
		$arr=array();
		while ($row=$ejecutar_consulta->fetch_assoc()) {
			$fechahora=isset($row['fechahora']) ? $row['fechahora']: NULL;
			$dia=substr($fechahora, 0, 10);
			if (!$primera_hora) {
				$primera_hora=substr($fechahora, 11,5);
			}
			$evento=isset($row['evento']) ? $row['evento']: NULL;
			$tiempo_estimado=isset($row['tiempo_estimado']) ? $row['tiempo_estimado']: NULL;
			$tiempo_estimado_inicial=isset($row['tiempo_estimado_inicial']) ? $row['tiempo_estimado_inicial']: NULL;
			$calentamiento=isset($row['calentamiento']) ? $row['calentamiento']: NULL;
			$participantes_estimado=isset($row['participantes_estimado']) ? $row['participantes_estimado']: NULL;
			$tiempo_evento=isset($tiempo_estimado)?$tiempo_estimado:$tiempo_estimado_inicial;

			if ($dia != $dia_anterior) {
				$hora=$dia.' '.$primera_hora;
				$dia_anterior=$dia;
			}
			else{
				$hora=$hora_siguiente;
			}
			$mifecha = new DateTime($hora); 
			$mifecha->modify('+'.$tiempo_evento+$calentamiento.' minute'); 
			$hora_siguiente=$mifecha->format('Y-m-d H:i:s');
			$arr[]=array(
				'fechahora'						=>	$fechahora,
				'hora'							=> 	$hora,
				'evento'						=> 	$evento,
				'tiempo_estimado_inicial'		=> 	$tiempo_estimado_inicial,
				'participantes_estimado'		=> 	$participantes_estimado,
				'tiempo_evento'					=> 	$tiempo_evento);
		}
		foreach ($arr as $key => $row) {
			$fechahora=isset($row['fechahora'])?$row['fechahora']:NULL;
			$hora=isset($row['hora'])?$row['hora']:NULL;
			$consulta="UPDATE competenciaev 
				SET fechahora='$hora' 
				WHERE competencia=$competencia
					AND fechahora='$fechahora' ";
			$ejecutar_consulta=$conexion->query($consulta);
		}
	}

	$conexion->close();
	return;
}

function marcas($competencia,$categoria,$modalidad)
{

	$marcas=array();
	$consulta="SELECT *  
		FROM competenciasm 
		WHERE competencia = $competencia 
			AND categoria LIKE '$categoria' 
			AND modalidad LIKE '$modalidad'
		LIMIT 1";
	$conexion=conectarse();
	$ejecutar_consulta=$conexion->query($consulta);

	if ($ejecutar_consulta) {
		$marcas =$ejecutar_consulta->fetch_assoc();
	}

	$conexion->close();

	return $marcas;

}

function conectar_gral() {

	include "db-gral-info.php";

	$conectar = new mysqli($servidor, $usuario, $password, $bd);

	return $conectar;
}

function conectarse()
	{

		include("db-info.php");

		$conectar= new mysqli($servidor,$usuario,$password,$bd);
		return $conectar;

	}
?>