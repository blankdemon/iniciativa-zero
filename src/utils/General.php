<?php
	class General {
		var $aceptado;
		var $rechazado;
		var $titulo;
		
		function desplegarMensajes() {
			if(!empty($this->rechazado)) echo '<div id="rechazado">' . $this->rechazado . '</div>';
			if(!empty($this->aceptado)) echo '<div id="aceptado">' . $this->aceptado . '</div>';
		}
		
		// despliega el titulo de cada uno de los menus
		function desplegarTitulos() {
			if(!empty($this->titulo)) echo '<h1>' . $this->titulo . '</h1>';
		}

		// despliega y verifica los privilegios de contenido restringido
		function checkIfUserHaveGroupsEnabled() {
			$query00 = "SELECT 
				c.*
			FROM compras c			
			INNER JOIN cuentas cu ON (c.id_persona = cu.id_cuenta)
			WHERE c.id_persona = " . (int) $_SESSION[id_cuenta] . " AND c.estado LIKE 'habilitada'
			ORDER BY c.id_compra DESC";

			//echo $query00;

			$s = mysql_query($query00) or die(mysql_error());
			if(mysql_num_rows($s)) {
				return true;
			} else {
				return false;
			}
		}

		// despliega y verifica los privilegios de contenido restringido
		function displayRestrictContentByGroupsEnabled($function) {
			if($this->checkIfUserHaveGroupsEnabled() || $_SESSION[nivelAcceso] > 1) {
				return $function;
			} else {
				return ($_SESSION[id_cuenta] > 0) ? 'javascript:displayMessageOfContentLoggedRestricted()' : 'javascript:displayMessageOfContentNotLoggedRestricted()';
			}
		}

		function listarPeliculasDestacadas($enlaces='') {
			$q = "SELECT cpd.id_pelicula, cpd.titulo, cpd.descripcion, cpd.imagen
			FROM cart_pelicula_destacada cpd
			ORDER BY id_peli_destacada DESC LIMIT 0, 6";
			$s = mysql_query($q) or die("Error listados peliculas destacadas: " . mysql_error());
			$npelis = mysql_num_rows($s);			
			if($npelis) {
				echo '<div id="featured">
					<ul>';
					while($r = mysql_fetch_array($s)) {
						echo '<li style="background-image: url(' . $r[imagen] . '); float: left;">
							<div class="feattxt">
								<div class="feattit">' . $r[titulo] . '</div>'
								. $r[descripcion];
								
								// para mostrar el enlace y ver informacion de la pelicula
								echo '<p class="featbut"><a href="' . $this->displayRestrictContentByGroupsEnabled('?id_pagina=36&id_pelicula=' . $r[id_pelicula]) . '" class="boton1">View</a></p>';

						echo '</div>
						</li>';
					}
					echo '</ul>
				</div>';
			}
			
			echo '<div id="featbot">';
					
				// despliegue de los enlaces de descarga y simulacion
				if(!empty($enlaces)) echo $enlaces;
				if($npelis > 1 && $_SESSION[id_cuenta] > 0) echo '<div id="prevBtn"></div><div id="nextBtn"></div>';
					
			echo '</div>';
		}

		function lastImgCelebrity($id_artista) {
			$q = "SELECT i.id_imagen
			FROM imagen i
			INNER JOIN imagen_artista ia ON (i.id_imagen = ia.id_imagen)
			WHERE ia.id_artista = " . (int) $id_artista . "
			ORDER BY i.id_imagen DESC 
			LIMIT 0, 1";
			$s = mysql_query($q) or die(mysql_error());
			if(mysql_num_rows($s)) {
				$r = mysql_fetch_array($s);
				return $r[id_imagen];
			} else {
				return '';	
			}
		}
		
		// lista las celebridades para retorno de contenido json para los home loggeado y desloggeado
		function listarCelebridadesThumbsHomeJson($display_link=false, $order = '') {
			$q = "SELECT 
				ca.id_artista, ca.nombre
			FROM cart_artista ca, imagen_artista ia
			WHERE ca.tipo_artista NOT IN(2) AND ca.id_artista IN (ia.id_artista)
			GROUP BY ca.id_artista
			ORDER BY ";
			$q .= (!empty($order)) ? "ca." . $order : "ca.date_updated";
			$q .= " DESC 
			LIMIT 0, 6";
			$s = mysql_query($q) or die(mysql_error());
			$npelis = mysql_num_rows($s);			
			if($npelis) {
				$arr = array();
				while($r = mysql_fetch_array($s)) {
					array_push($arr, array("id_celebrity" => $r[id_artista], "nombre" => htmlentities($r[nombre]), "imagen" => "paginas/imagen.php?id_imagen=" . $this->lastImgCelebrity($r[id_artista]) . "&ancho=120&alto=160"));
				}
			}

			echo json_encode($arr);
		}

		function listarMoviesThumbsHomeJson($order = '') {
			$q = "SELECT 
				cp.id_pelicula, cp.nombre, cp.id_imagen
			FROM cart_pelicula cp ";
			
			// condiciono para desplegar los estrenos
			if(eregi("^new_releases$", $order)) {
				$q .= "WHERE cp.new_release = 1
				ORDER BY cp.date_inserted";
			} else {
				$q .= "ORDER BY ";
				$q .= (!empty($order)) ? "cp." . $order : "cp.date_inserted";
			}
			
			$q .= " DESC 
			LIMIT 0, 6";

			$s = mysql_query($q) or die(mysql_error());
			$npelis = mysql_num_rows($s);			
			if($npelis) {
				$arr = array();
				while($r = mysql_fetch_array($s)) {
					array_push($arr, array("id_pelicula" => $r[id_pelicula], "nombre" => htmlentities($r[nombre]), "imagen" => "paginas/imagen.php?id_imagen=" . $this->getLastIdImgMovie($r[id_pelicula]) . "&ancho=120&alto=160"));
				}
			}

			echo json_encode($arr);
		}

		function listarNewsHomeJson($order = '') {
			$q = "SELECT n.id_new, n.date_inserted, n.title, n.text, n.id_imagen
			FROM new n ";
			
			// condiciono para desplegar los estrenos
			if(eregi("^company$", $order)) {
				$q .= "WHERE n.new_type = 2
				ORDER BY n.date_inserted";
			} else {
				$q .= "WHERE n.new_type = 1 
				ORDER BY ";
				$q .= (!empty($order)) ? "n." . $order : "n.date_inserted";
			}
			
			$q .= " DESC 
			LIMIT 0, 3";

			$s = mysql_query($q) or die(mysql_error());
			$npelis = mysql_num_rows($s);			
			if($npelis) {
				$arr = array();
				while($r = mysql_fetch_array($s)) {
					array_push($arr, array(
											"id_new" => $r[id_new],											
											"date_inserted" => date("Y/m/d", $r[date_inserted]),
											"title" => stripslashes($r[title]),
											"text" => substr(stripslashes($r[text]), 0, 120) . '...',
											"image" => "paginas/imagen.php?id_imagen=" . $r[id_imagen] . "&ancho=120&alto=170"
										  ));
				}
			}

			echo json_encode($arr);
		}
		
		function listarGeneros($id_genero=0, $return=false) {
			if(!$return) {
				$q = "SELECT * FROM cart_genero ORDER BY nombre";
				$s = mysql_query($q);
				if(mysql_num_rows($s)) {
					echo '<select name="id_genero[]">';
					if($id_genero=='') echo '<option value="">Seleccionar...';
					while($r = mysql_fetch_array($s)) {
						echo '<option value="' . $r[id_genero] . '"';
						if($r[id_genero]==$id_genero) echo ' selected';
						echo '>' . $r[nombre];
					}
					echo '</select>';
				}
			} else {
				$q = "SELECT nombre FROM cart_genero WHERE id_genero = " . (int) $id_genero;
				$s = mysql_query($q);
				$r = mysql_fetch_array($s);
				$genero = $r[nombre];
				return $genero;
			}
		}
		
		function listarYears($year=0) {
			echo '<select name="year">';
			if($year=='') echo '<option value="">Seleccionar...';
			for($i=date('Y');$i>(date('Y')-60);$i--) { 
				echo '<option value="' . $i . '"';
				if($year==$i) echo ' selected';
				echo '>' . $i;
			}
			echo '</select>';
		}
		
		function obtenerDirectores($id_pelicula) {
			$i = 0;
			$q = "SELECT 
				a.id_artista, a.nombre
			FROM cart_artista a
			INNER JOIN cart_peli_artista pa ON (a.id_artista = pa.id_artista)
			WHERE pa.id_tipo_artista IN (2) AND pa.id_pelicula = " . (int) $id_pelicula;
			$s = mysql_query($q);
			$nres = mysql_num_rows($s);
			if($nres) {
				while($r = mysql_fetch_array($s)) {
					$i++;
					echo '<a href="' . $this->displayRestrictContentByGroupsEnabled('?id_pagina=34&id_artista=' . $r[id_artista]) . '" title="">' . htmlentities($r[nombre]) . '</a>';
					if($i < $nres) echo ', ';
				}
			}
			mysql_free_result($s);
		}
		
		function obtenerActores($id_pelicula, $limit=0) {			
			$i = 0;
			$q = "SELECT 
				a.id_artista, a.nombre
			FROM cart_artista a
			INNER JOIN cart_peli_artista pa ON (a.id_artista = pa.id_artista)
			WHERE pa.id_tipo_artista IN (0,1) AND pa.id_pelicula = " . (int) $id_pelicula;
			
			$s = mysql_query($q);
			$tactores = mysql_num_rows($s);
			
			if($limit>0) $q .= " LIMIT 0, $limit";
			
			$s = mysql_query($q);
			$nres = mysql_num_rows($s);
			if($nres) {
				while($r = mysql_fetch_array($s)) {
					$i++;
					echo '<a href="' . $this->displayRestrictContentByGroupsEnabled('?id_pagina=34&id_artista=' . $r[id_artista]) . '" title="">' . htmlentities($r[nombre]) . '</a>';
					if($i < $nres) echo ', ';
				}
				
				if($tactores>$limite) echo '...';
			}
			mysql_free_result($s);
		}
		
		function obtenerActoresForMovieCard($id_pelicula) {			
			$i = 0;
			$q = "SELECT 
				a.id_artista, a.nombre
			FROM cart_artista a
			INNER JOIN cart_peli_artista pa ON (a.id_artista = pa.id_artista)
			WHERE pa.id_tipo_artista IN (0,1) AND pa.id_pelicula = " . (int) $id_pelicula;
			
			$s = mysql_query($q);
			$tactores = mysql_num_rows($s);
			
			if($limit>0) $q .= " LIMIT 0, $limit";
			
			$s = mysql_query($q);
			$nres = mysql_num_rows($s);
			if($nres) {
				while($r = mysql_fetch_array($s)) {
					$i++;
					echo '<span>
						<a href="' . $this->displayRestrictContentByGroupsEnabled('?id_pagina=34&id_artista=' . $r[id_artista]) . '" title=""><img src="paginas/imagen.php?id_imagen=' . $this->lastImgCelebrity($r[id_artista]) . '&ancho=35&alto=40" align="absmiddle">' . htmlentities($r[nombre]) . '</a>
					</span>';
				}
			}
			mysql_free_result($s);
		}
		
		function obtenerGeneros($id_pelicula, $limite=false) {
			$i = 0;
			$q = "SELECT 
				g.nombre
			FROM cart_genero g
			INNER JOIN cart_genero_pelicula cg ON (g.id_genero = cg.id_genero)
			WHERE cg.id_pelicula = " . (int) $id_pelicula;
			$s = mysql_query($q);
			$nres = mysql_num_rows($s);
			if($nres) {
				while($r = mysql_fetch_array($s)) {
					$i++;
					echo htmlentities($r[nombre]);
					
					if($limite==false) return;
					
					if($i < $nres) echo ', ';
				}
			}
			mysql_free_result($s);
		}
	
		function getMovieLinks($id_pelicula) {
			$i = 0;
			$return = '';
			$q = "SELECT direccion FROM cart_enlace WHERE id_pelicula = " . (int) $id_pelicula;
			$s = mysql_query($q);
			$nres = mysql_num_rows($s);
			if($nres) {
				while($r = mysql_fetch_array($s)) {
					$i++;
					$return .= '<a href="' . $r[direccion] . '" target="_blank">' . $r[direccion] . '</a>';
					if($i < $nres) $return .= '<br>';
				}
			}
			mysql_free_result($s);

			return $return;
		}

		function getSubtitlesFiles($id_pelicula) {
			$i = 0;
			$return = '';
			$q = "SELECT * FROM cart_subtitulo WHERE id_pelicula = " . (int) $id_pelicula;
			$s = mysql_query($q);
			$nres = mysql_num_rows($s);
			if($nres) {
				while($r = mysql_fetch_array($s)) {
					$i++;
					$return .= '<a href="files/subtitulos/' . $r[fichero] . '">' . $r[direccion] . '</a>';
					if($i < $nres) $return .= '<br>';
				}
			} else {
				$return .= "Subtitles not availables";
			}
			mysql_free_result($s);

			return $return;
		}
		
		function listarTipoArtista($id_tipo_artista=0, $return=false) {
			$tipos_artista = array(
				"" => 'Actor',
				0 => 'Director',
				3 => 'Actor / Director'
			);
			if(!$return) {
					echo '<select name="id_tipo_artista">';
					if($id_tipo_artista=='') echo '<option value="">Seleccionar...';
					foreach($tipos_artista as $id => $nombre) {
						echo '<option value="' . $id . '"';
						if($id==$id_tipo_artista) echo ' selected';
						echo '>' . $nombre;
					}
					echo '</select>';
			} else {
				foreach($tipos_artista as $id => $nombre) {
					if($id==$id_tipo_artista) return $nombre;
				}
				
			}
		}
		
		function getMpaaRating($rating, $return=false) {
			if(!$return) {
				$q = "SELECT id_mpaa_rating, nombre FROM cart_mpaa_rating ORDER BY nombre";
				$s = mysql_query($q);
				if(mysql_num_rows($s)) {
					echo '<select name="mpaa_rating">';
					if($rating=='') echo '<option value="">Seleccionar...';
					while($r = mysql_fetch_array($s)) {
						echo '<option value="' . $r[id_mpaa_rating] . '"';
						if($r[id_mpaa_rating]==$rating) echo ' selected';
						echo '>' . $r[nombre];
					}
					echo '</select>';
				}
			} else {
				$q = "SELECT nombre FROM cart_mpaa_rating WHERE id_mpaa_rating = " . (int) $rating;
				$s = mysql_query($q);
				if(mysql_num_rows($s)) {
					$r = mysql_fetch_array($s);
					return $r[nombre];
				}			
			}
		}
		
		/* obtiene el id de la ultima imagen ingresada a la pelicula */
		function getLastIdImgMovie($id, $id_default=0) {
			global $config;
			
			$q = "SELECT i.id_imagen
			FROM imagen i
			INNER JOIN imagen_pelicula ai ON (i.id_imagen = ai.id_imagen) 
			WHERE ai.id_pelicula = " . (int) $id . "
			ORDER BY i.id_imagen DESC
			LIMIT 0,1";
			$s = mysql_query($q);
			if(mysql_num_rows($s)) {
				$r = mysql_fetch_array($s);
				return $r[id_imagen];
			} else {
				if($id_default==$config["id_imagen_movie_default_little"])
					return $config["id_imagen_movie_default_little"];
				else
					return $config["id_imagen_movie_default_big"];
			}
		}
		
		function listarTipoNoticia($id_tipo_noticia=0, $return=false) {
			$tipos_noticia = array(1 => 'General', 2 => 'Nuestra' );
			if(!$return) {
					echo '<select name="id_tipo_noticia">';
					if($id_tipo_noticia=='') echo '<option value="">Seleccionar...';
					foreach($tipos_noticia as $id => $nombre) {
						echo '<option value="' . $id . '"';
						if($id==$id_tipo_noticia) echo ' selected';
						echo '>' . $nombre;
					}
					echo '</select>';
			} else {
				foreach($tipos_noticia as $id => $nombre) {
					if($id==$id_tipo_noticia) return $nombre;
				}
				
			}
		}
		
		function redimensionar_jpeg($img_original,$img_nueva,$img_nueva_calidad,$newwidth) {
			if(preg_match("/.jpg/i", "$img_original")){ $format = 'image/jpeg'; }
			if(preg_match("/.gif/i", "$img_original")){ $format = 'image/gif'; }
			if(preg_match("/.png/i", "$img_original")){ $format = 'image/png'; }
			if(!empty($format)){
			   list($width, $height) = getimagesize($img_original);
			   if($width > $newwidth){
					$newheight = ($height * $newwidth) / $width;
				}else{
					$newheight = $height;
					$newwidth = $width;
				}
			   switch($format){
				   case 'image/jpeg': $source = imagecreatefromjpeg($img_original); break;
				   case 'image/gif': $source = imagecreatefromgif($img_original); break;
				   case 'image/png': $source = imagecreatefrompng($img_original); break;
			   }
				$thumb = imagecreatetruecolor($newwidth,$newheight);		
				imagecopyresampled($thumb, $source,0,0,0,0,$newwidth,$newheight, $width, $height); 
				ImageJPEG($thumb,$img_nueva,$img_nueva_calidad);
			}
		}	
		
	};
?>
