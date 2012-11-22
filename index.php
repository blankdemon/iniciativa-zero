<?php
	session_start();

	// archivo de configuracion
	require_once("configuracion.php");
	
	// class with configuration functions
	require_once("clases/Configuracion.php");
	
	include("funciones/conexion.php");
	conectar();
	
	require_once("funciones/registro.php");
	require_once("funciones/recuperarPassword.php");
	require_once("funciones/login.php");
	
	require_once("clases/General.php");
	require_once("clases/CerrarSession.php");
	require_once("clases/Header.php");
	require_once("clases/Menu.php");
	
	$menu = new Menu();		
	$configuracion = new Configuracion();
	$general = new General();
	
	$_SESSION["ultimoAcceso"]= date("Y-n-j H:i:s");
	
	// iniciar sesion
	if (isset($_REQUEST[login])) iniciarSesion(mysql_real_escape_string(trim($_POST["usuario"])), mysql_real_escape_string(trim($_POST["password"])));
	if ($_SESSION["usuario"] != "") verificarSesion();
			
	// cerrar sesion del usuario
	if(eregi("^salir$", $_REQUEST[action])) {
		$sesion = new Session();
		$sesion->cerrar();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=7">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title><?=$configuracion->nombreSitio ?></title>
	<link href="css/estilos.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/fancybox/jquery.fancybox-1.3.4.css" media="screen" />	

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="js/slider.js"></script>
	<script type="text/javascript" src="js/funciones.js"></script>
</head>
<body onload="<?php
	// Para cargar los enlaces de las peliculas solo para usuarios registrados
	echo ($general->checkIfUserHaveGroupsEnabled()) ? 'displayLastMovies(true);' : 'displayLastCelebrities();displayLastMovies();';
	echo implode(';', $menu->javascriptOnloadFunctions);
?>">
	<div id="contenedor">
<?php
		$header = new Header();
		$header->generarHeader();
		
		echo '<div id="menu">' , $menu->crearEnlaces() , '</div>';
		
		/* despliega mensajes de rechazo y aceptacion */
		$general->desplegarMensajes();
		
		/* despliega los titulos de cada pagina */
		$general->desplegarTitulos();
?>
		<div id="cpaginas" 
<?php
	if($_REQUEST[id_pagina] > 1) echo ' style="padding: 10px;"';
?>
>
<?php
		require_once("paginas/" . $menu->paginaRequerida() . ".php");
?>
		</div>
        <div class="sep"></div>
		<div id="footer">
			<div align="center"><?=date("Y") ?> - <span id="result_box" lang="en">All rights reserved</span> - Wiki-Global.com<a href="mailto:info@wiki-global.com"> </a>            </div>
			<div align="center">
				<a href="?id_pagina=7">Terms</a>, 
				<a href="?id_pagina=8"> Security</a>, 
				<a href="?id_pagina=9"> Privacy</a>
			</div>
		</div>
	
	<script type="text/javascript">
		$("#featured").easySlider();
	</script>
    </div>
    </div>
</body>
</html>
