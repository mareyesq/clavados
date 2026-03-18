<?php 
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Competencias de Clavados</title>
	<meta charset="utf-8"/>
	<meta name="description" content="Software para administrar competencias de clavados"/>
    <?php 
        $nav=$_SERVER['HTTP_USER_AGENT'];
        $cual="chrome";
        //Demilitador 'i' para no diferenciar mayus y minus
        if (preg_match("/".$cual."/i", $nav)) 
//            echo "<link rel='stylesheet' type='text/css' href='css/estilos.css'/>";
    ?>
     <link rel='stylesheet' type='text/css' href='css/estilos.css'/>
<!--     <script language="JavaScript">
        function Abrir_ventana (pagina) {
            var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
            window.open(pagina,"",opciones);
        }
    </script>	-->

    <script type="text/javascript">
        function abrir_ventana(pagina,planilla){
            var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, titlebar=no, scrollbars=no, resizable=yes, width=750, height=300, top=200, left=640";
            var url=pagina+"?pla="+planilla;
            window.open(url,"ventana",opciones);
        }
     </script>
</head>
<body>
	<h1>Competencias de Clavados</h1>
	<header>Administra tus Competencias</header>
<!--    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
<!-- publicidad1 -->
<!--     <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-1415396522207334"
         data-ad-slot="7007220206"
        data-ad-format="auto"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>    
 -->    <nav>
        <ul id="menu">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="#">Buscar</a>
                <ul>
                    <li><a href="?op=php/busca-usuario.php&tipo=C">Clavadistas</a></li>
                    <li><a href="?op=php/busca-usuario.php&tipo=E">Entrenadores</a></li>
                    <li><a href="?op=php/busca-usuario.php&tipo=J">Jueces</a></li>
                    <li><a href="?op=php/busca-usuario.php&tipo=A">Administradores</a></li>
                    <li><a href="?op=php/busca-salto.php">Saltos</a></li>
                </ul>
            </li>
            <li><a href="#">Hist&oacute;rico</a>
                <ul>
                    <li><a href="?op=php/historico-saltos.php">Saltos</a></li>
                    <li><a href="#">Clavadistas</a></li>
                    <li><a href="#">Entrenadores</a></li>
                    <li><a href="#">Jueces</a></li>
                </ul>
            </li>
            <?php 
                if ($_SESSION["autentificado"]){
                    echo "<li><a class='cambio' href='?op=php/cierra-sesion.php'>Cierra Sesión</a></li>";
                }
                else
                    echo "<li><a class='cambio' href='?op=php/inicia-sesion.php'>Inicia Sesión</a></li>";

             ?>
        </ul>
           <?php 
            $nombre_usuario=$_SESSION["nombre_usuario"];
            $imagen_usuario=$_SESSION["imagen_usuario"];
            $cod_us=isset($_SESSION["usuario_id"])?$_SESSION["usuario_id"]:NULL;
            if ($imagen_usuario) 
                echo "<img src='img/fotos/$imagen_usuario' width='3%' />";
            
            echo "<span><a class='usuario' href='?op=php/cambio-usuario.php&us=$cod_us'>$nombre_usuario<a></span>";
        ?>
        &nbsp;
            <a class="enlaces" href="?op=php/alta-usuario.php">Usuario Nuevo</a>
        &nbsp;
            <a class="enlaces" href="?op=php/ayuda.php" target="_blank">Ayuda</a>
    </nav>
    <section id="contenedor">
		<section id="principal" >
			<?php 
                $opcion=isset($_GET["op"])?$_GET["op"]:"php/todas-competencias.php";
                 include($opcion); 
                 include("php/mensajes.php");
/*                }
                else
                   echo "<img src='img/logo.jpg' align='top' height='100%' width='100%' />";
*/			?>
		</section>
<!-- 		<aside>
			&lt;aside&gt
		</aside>
 -->	</section>
	<footer>
      <p>Información de Contacto: <a href="mailto:soporte@softneosas.com">
  soporte@softneosas.com</a>&nbsp;&nbsp;&nbsp;<img  align='center' src='img/whats_app.jpg' width='1.8%' />&nbsp;+57 316 4761171</p><br>
	</footer>
<!--     <div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="http://h24.hostg.co/49212" target="_blank"><img style="border:0px" src="https://cdn.rawgit.com/hostinger/banners/master/affiliate-banners/en-h24/300x250.png" width="20%"></a>
</div>
 --></body>
</html>
