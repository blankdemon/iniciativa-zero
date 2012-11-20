<?php
	session_start();

	require_once("../configuracion.php");
	require_once("../funciones/conexion.php");
	require_once("../clases/General.php");
	require_once("../clases/Configuracion.php");
	
	conectar();

	$cantidad = 0;
	$base = 0;
	$limite = 0;
	$total = 0;
	$general = new General();
	$configuracion = new Configuracion();

	function showPaginado() {
		global $cantidad, $limite, $total, $base;

		// se realiza el paginado...
		if($total > $cantidad) {
			$limit_mi_max = 5;
			
			//echo "Limite: " . $limite . '<br>';
			//echo "Base: " . $_GET[base] . '<br>';
			
			if((int)$_GET[base] >= $limit_mi_max && (int)$_GET[base] <= ($limite-$limit_mi_max)) {
				$binicio = $_GET[base]-$limit_mi_max;
				$bfinal = $_GET[base]+$limit_mi_max;
			} else if((int)$_GET[base] == 0 && $limite >= $limit_mi_max) {
				$binicio = 0;
				$bfinal = $limit_mi_max;
			} else if((int)$_GET[base] == $limite) {
				$binicio = ($limite-$limit_mi_max);
				$binicio = ($binicio<0) ? 0 : $binicio;
				$bfinal = $limite;
			} else if((int)$_GET[base] < $limit_mi_max && (int)$_GET[base]>0) {
				$binicio = 0;
				$bfinal = $_GET[base]+$limit_mi_max;;
			} else {
				$binicio = (int)$_GET[base]-$limit_mi_max;
				$binicio = ($binicio<0) ? 0 : $binicio;
				$bfinal = $limite;
			}
		
			echo '<div class="cartelera">';
				if((int)$_GET[base] > $limit_mi_max) echo '<a href="javascript:searchKeyWordsByPageNumber(0);" id="primero">&nbsp;</a>';			
				for($i=$binicio; $i<=$bfinal; $i++) {
					if($_GET[base]==$i) {
						echo '<b>'.($i+1).'</b>';
					} else {
						echo '<a href="javascript:searchKeyWordsByPageNumber('.$i.');">' . ($i+1). '</a>';
					}
				}
				if((int)$_GET[base] < $limite-$limit_mi_max) echo '<a href="javascript:searchKeyWordsByPageNumber(' . $limite . ');" id="ultimo">&nbsp;</a>';
			echo '</div>';
		}
	}


	function getMoviesList() {
		global $general, $cantidad, $base, $limite, $total, $configuracion, $config;

		$cantidad = 10;
		$base = ((int) $_REQUEST[base] > 0) ? ((int)$_REQUEST[base] * $cantidad) : 0;	
		$q = "SELECT 
			p.id_pelicula, p.nombre,
			p.trama, p.year, p.duracion, p.subtitulo, p.puntaje, p.idioma, p.id_imagen,
			g.nombre genero
		FROM cart_pelicula p
		LEFT JOIN cart_genero g ON (p.id_genero = g.id_genero) ";
	
		if(!empty($_REQUEST[words]) && empty($_REQUEST[searchOrder])) {
			$q .= " WHERE ";
			$q .= " p.nombre LIKE '%$_REQUEST[words]%' OR ";
			$q .= " p.trama LIKE '%$_REQUEST[words]%' OR ";
			$q .= " g.nombre LIKE '%$_REQUEST[words]%'";
		}
		
		/* determina el criterio orden de busqueda */
		switch($_REQUEST[searchOrder]) {
			case 'new-releases';
				$q .= " WHERE p.new_release = 1 ";
			break;
		}

		$ss = mysql_query($q) or die (mysql_error());
		$total = mysql_num_rows($ss);
		$limite = intval($total / $cantidad);
		
		/* determina el criterio orden de busqueda */
		switch($_REQUEST[searchOrder]) {
			case 'recents';
				$q .= " ORDER BY p.date_inserted DESC";
			break;
			case 'new-releases';
				$q .= " ORDER BY p.date_inserted";
			break;
			case 'top';
				$q .= " ORDER BY p.puntaje DESC";
			break;
			case 'genre';
				$q .= " ORDER BY g.nombre ASC";
			break;
			default:
				$q .= " ORDER BY p.id_pelicula DESC";
			break;
		}

		$q .= " LIMIT $base, $cantidad";
		$s = mysql_query($q) or die(mysql_error());
	
		/* despliegue de solo el resultado de busqueda, esto se usa para el orden de las peliculas */
		if(!$_REQUEST[searchOnlyResults]) {
?>
			<div class="menu" id="sel_1">
				<!-- span>Movies</span -->
				<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:searchKeyWordsByOrder('recents')"); ?>" class="sel" onclick="letCheckedOne(this, 1)">Recents</a>
				<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:searchKeyWordsByOrder('new-releases')"); ?>" onclick="letCheckedOne(this, 1)">New releases</a>
				<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:searchKeyWordsByOrder('top')"); ?>" onclick="letCheckedOne(this, 1)">Top</a>
				<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:searchKeyWordsByOrder('genre')"); ?>" onclick="letCheckedOne(this, 1)">Genre</a>
				<a href="<?=$general->displayRestrictContentByGroupsEnabled("javascript:searchKeyWordsByOrder('recents')"); ?>" title="display a list of all movies" onclick="letCheckedOne(this, 1)">All</a>
			</div>
			<div class="thumbs" id="onlySearchResults">
<?php
		}

		if(mysql_num_rows($s)) {
?>
			<ul id="peli_general">
<?php
				while($r = mysql_fetch_array($s)) {
					$c++;
?>
					<li class="lpeli_general" onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
                    				<span id="pelicula">
<?php							
		               				echo '<a href="' . $general->displayRestrictContentByGroupsEnabled('?id_pagina=36&id_pelicula=' . $r[id_pelicula]) . '"><img src="paginas/imagen.php?id_imagen=' . $general->getLastIdImgMovie($r[id_pelicula], $config["id_imagen_movie_default_little"]) . '&ancho=120&alto=160&default=999" style="margin-right:10px;" border="0" align="left"></a>';
?>
							<b><a href="<?=$general->displayRestrictContentByGroupsEnabled('?id_pagina=36&id_pelicula=' . $r[id_pelicula]) ?>"><?=htmlentities($r[nombre]) ?></a></b><br />
							<?=(strlen($r[trama]) > 600) ? htmlentities(substr(stripslashes($r[trama]), 0, 600)) . '...' : htmlentities(stripslashes($r[trama])) ?><br />
							<div class="sel"></div>
							Director(s): 
								<?php $general->obtenerDirectores($r[id_pelicula]); ?>
								<?='<br />Celebrities: ' , $general->obtenerActores($r[id_pelicula], 3); ?>
					    	</span>
						<span id="detalles">
							<b>Rating:</b> <?=$r[puntaje] ?><br />
							<b>Genre:</b> <?php $general->obtenerGeneros($r[id_pelicula]); ?><br />
							<b>Language:</b> <?=htmlentities($r[idioma]) ?><br />
<?php
							if(!empty($r[subtitulo])) echo '<b>Subtitles:</b> ' . $r[subtitulo] . '<br />';
?>
							<b>Time:</b> <?=($r[duracion]>0) ? $r[duracion] . ' min.' : '--' ?><br />
							<b>Year:</b> <?=$r[year] ?>
<?php
							if($_SESSION[nivelAcceso] > 1) echo '<b><a href="?id_pagina=35&id_pelicula=' . $r[id_pelicula] . '&action=update" target="_blank">Actualizar</a></b>';
?>
						</span>
					</li>
<?php
			}
?>
			</ul>
<?php
		/* desplegar el paginado de resultados */		
		showPaginado();
	} else {
		echo '<div  id="rechazado-nb">No search results found.</div>';
	}

		/* despliegue de solo el resultado de busqueda, esto se usa para el orden de las peliculas */
		if(!$_REQUEST[searchOnlyResults]) {
?>
			</div>
<?php
		}
	}

	function getCelebritiesList() {
		global $general, $cantidad, $base, $limite, $total;

		$cantidad = 20;
		$base = ((int) $_REQUEST[base] > 0) ? ((int)$_REQUEST[base] * $cantidad) : 0;
		$q = "SELECT 
			ca.id_artista, ca.nombre
		FROM cart_artista ca, imagen_artista ia
		WHERE ca.tipo_artista NOT IN(2) AND ca.id_artista IN (ia.id_artista) ";

		if(!empty($_REQUEST[words]) && empty($_REQUEST[searchOrder])) $q .= " AND ca.nombre LIKE '%$_REQUEST[words]%' ";

		$q .= "GROUP BY ca.id_artista
		ORDER BY ";
		
		/* limito a solo dos opciones de campos */
		$q .= (eregi("^(date_updated|date_inserted)$", $_REQUEST[searchOrder])) ? "ca." . $_REQUEST[searchOrder] : "ca.date_updated";
		$q .= " DESC";
		
		$ss = mysql_query($q) or die (mysql_error());
		$total = mysql_num_rows($ss);
		$limite = intval($total / $cantidad);
		
		/* configuracion para la query por paginacion */
		$q .= " LIMIT $base, $cantidad";
		$s = mysql_query($q) or die(mysql_error());

		/* despliegue de solo el resultado de busqueda, esto se usa para el orden de las peliculas */
		if(!$_REQUEST[searchOnlyResults]) {
			echo '<div class="menu" id="sel_1">
				<a href="javascript:' . $general->displayRestrictContentByGroupsEnabled("searchKeyWordsByOrder('date_updated');") . '" class="sel" onclick="letCheckedOne(this, 1)">Last updated</a>
				<a href="javascript:' . $general->displayRestrictContentByGroupsEnabled("searchKeyWordsByOrder('date_inserted');") . '" onclick="letCheckedOne(this, 1)">Last added</a>
				<a href="javascript:' . $general->displayRestrictContentByGroupsEnabled("searchKeyWordsByOrder();") . '" onclick="letCheckedOne(this, 1)">All</a>
			</div>
			<div class="thumbs" id="onlySearchResults">';
		}

		if(mysql_num_rows($s)) {
			echo '<div id="artistsSearchContainer">';

				while($r = mysql_fetch_array($s)) {
					echo '<div onmouseover="uno(this, \'DFEFFF\')" onmouseout="dos(this, \'ffffff\')">
					<a href="' . $general->displayRestrictContentByGroupsEnabled('?id_pagina=34&id_artista=' . $r[id_artista]) . '"><img src="paginas/imagen.php?id_imagen=' . $general->lastImgCelebrity($r[id_artista]) . '&ancho=140&alto=160" border="0"></a>
					<span><a href="' . $general->displayRestrictContentByGroupsEnabled('?id_pagina=34&id_artista=' . $r[id_artista]) . '">' . htmlentities($r[nombre]) . '</a></span>
					</div>';
				}

			echo '</div>';

			/* desplegar el paginado de resultados */		
			showPaginado();

		} else {
			echo '<div  id="rechazado-nb">No search results found.</div>';		
		}
		
		/* despliegue de solo el resultado de busqueda, esto se usa para el orden de las peliculas */
		if(!$_REQUEST[searchOnlyResults]) echo '</div>';
	}
	
	if(eregi("^2$", $_REQUEST[searchType])) {
		getCelebritiesList();
	} else {
		getMoviesList();
	}
?>
