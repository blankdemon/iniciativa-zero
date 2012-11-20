<?php
	// fichero para obtener las diversas funcionalidades crud, para organizacion de DB
	// require_once("paginas/operaciones_crud.php");

	// despliega mensaje para el primer inicio de sesion
	if($_REQUEST[primerInicioSesion]==true) {
		echo '<div id="aceptado">
			<b>Bienvenido a Wiki-Global!</b><br>
			Te hemos enviado un correo electronico con el nombre de usuario y contrase�a que has elegido para tu cuenta.
		</div>';
		unset($_SESSION[registrado]);
	}

	//despliegue del banner con las imagenes de peliculas y los enlaces de simulacion
	// $general->listarPeliculasDestacadas($enlaces);

?>
	<div class="sep"></div>
	<div id="thumbs_main_page">
		<div class="menu" id="sel_1">
			<span>Movies</span>
			<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:displayLastMovies(true, 'date_inserted');"); ?>" class="sel" onclick="letCheckedOne(this, 1)">Recents</a>
			<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:displayLastMovies(true, 'new_releases');"); ?>" onclick="letCheckedOne(this, 1)">New releases</a>
			<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:displayLastMovies(true, 'puntaje');"); ?>" onclick="letCheckedOne(this, 1)">Top</a>
			<a href="<?=$general->displayRestrictContentByGroupsEnabled("?id_pagina=31"); ?>" title="display a list of all movies" onclick="letCheckedOne(this, 1)">All</a>
		</div>
		<div class="thumbs" id="moviesThumbs"></div>
	</div>

	<div class="sep"></div>
	<div id="thumbs_main_page">
		<div class="menu" id="sel_2">
			<span>News</span>
			<a href="javascript:displayLastNews();" onclick="letCheckedOne(this, 2)" class="sel">Movies & Celebrities</a>
			<a href="javascript:displayLastNews('company');" onclick="letCheckedOne(this, 2)">Our Company</a>
		</div>
		<div class="thumbs" id="newsThumbs"></div>
	</div>
