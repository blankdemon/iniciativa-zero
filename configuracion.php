<?php
	/**
		Variables de configuracion del Sistema
	*/
	$config["url"] = "localhost";
	$config["dbname"] = 'captochi_wikiglobal';
	$config["dbuser"] = 'captochi_admin';
	$config["dbpass"] = '54712548';

	$config["remote_website_downloads"] = 'http://www.monsterdivx.ru';
	$config["company_name"] = 'Wiki-Global.com';
	
	/* do not show some information */
	$config["stay_beautiful"] = true;
	$config["id_imagen_movie_default_little"] = 999;
	$config["id_imagen_movie_default_big"] = 998;
	
	$MAIL_GLOBAL_PRUEBAS = 'programadorchile@hotmail.com';
	//  $MAIL_GLOBAL_PRUEBAS = 'blankdemon@gmail.com';

	$now=localtime();
	$ano = 1900+$now[5];
	$mes = 1+$now[4];
	$dia = $now[3];
	$hora = $now[2];
	$minuto = $now[1];
	$segundo = $now[0];
	$fecha_actual = "$ano-$mes-$dia";
	$hora_actual = "$hora:$minuto:$segundo";
?>
