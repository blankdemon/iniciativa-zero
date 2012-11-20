<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	conectar();

	$q = "SELECT * FROM new WHERE id_new=" . (int) $_REQUEST[id_new];	
	$s = mysql_query($q);
	
	if(mysql_num_rows($s)) {
		$r = mysql_fetch_array($s);
		
		echo '<div id="newContent">
			<img src="paginas/imagen.php?id_imagen=' . $r[id_imagen] . '&ancho=300&alto=300&cut=false" align="left" /><b>' . stripslashes($r[title]) . '</b><br><br>'
			. stripslashes(str_replace("\r\n", "<br>", $r[text]))
		. '</div>';
	}
?>

