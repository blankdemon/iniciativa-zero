<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	include("../clases/redimimagen.php");
		
	conectar();
	
	

	$q02 = "SELECT CONCAT(directorio , nombre) AS imagen FROM imagen WHERE id_imagen = " . (int) $_REQUEST[id_imagen];
	$s02 = mysql_query($q02) or die(mysql_error());
	$rr = mysql_fetch_array($s02);

	$cut = true;	
	$nuevo_ancho = (!isset($_REQUEST[ancho]) || !($_REQUEST[ancho] > 0) || $_REQUEST[ancho] > 800) ? 150 : $_REQUEST[ancho];
	$nuevo_alto = (!isset($_REQUEST[alto]) || !($_REQUEST[alto] > 0) || $_REQUEST[alto] > 800) ? 150 : $_REQUEST[alto]; 
	$imagen = new Imagen("../" . $rr[imagen], $nuevo_ancho, $nuevo_alto);
	$imagen->resize($nuevo_ancho, $nuevo_alto, $cut);

	if(isset($_GET['gris'])) 
    		$imagen->grayscale(); 
	elseif(isset($_GET['recolor'])) { 
	    	$exact = (isset($_GET['exact'])) ? true : false;
	    	$color = urldecode($_GET['recolor']);
	    	$imagen->colorize($color,$exact); 
	} 

	if(isset($_GET['download'])) 
	    $imagen->doDownload(); 
	else 
	    $imagen->doPrint(); 
?> 
