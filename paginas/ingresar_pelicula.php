<?php
	function retornarImagenes() {
		$id_pelicula = (int) $_REQUEST[id_pelicula];
		
		if($id_pelicula > 0) {
			$q = "SELECT i.id_imagen, i.directorio, i.nombre
			FROM imagen i
			INNER JOIN imagen_pelicula ai ON (i.id_imagen = ai.id_imagen) 
			WHERE ai.id_pelicula = $id_pelicula";
			$s = mysql_query($q);
			if(mysql_num_rows($s)) {
				echo '<div id="eliminar_imagenes">';
				while($r = mysql_fetch_array($s)) {
					echo '<span style="display:block;float:left;margin-right:10px;">
						<img src="paginas/imagen.php?id_imagen=' . $r[id_imagen] . '&ancho=100&alto=100"><br >
						<a href="?id_pagina=35&id_pelicula=' . (int) $_REQUEST[id_pelicula] . '&id_imagen=' . $r[id_imagen] . '&action=eliminar">eliminar</a>
					</span>';
				}
				echo '</div>';
			}
		}
	}
	
	function eliminarImagenArtista() {
		global $rechazado;
		$q02 = "SELECT CONCAT(directorio , nombre) AS imagen FROM imagen WHERE id_imagen = " . (int) $_REQUEST[id_imagen];
		$s02 = mysql_query($q02) or die(mysql_error());
		if(mysql_num_rows($s02)) {
			$r = mysql_fetch_array($s02);
			$q = "DELETE FROM imagen WHERE id_imagen = " . (int) $_REQUEST[id_imagen];
			if(mysql_query($q)) {
				$q = "DELETE FROM imagen_pelicula WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_imagen = " . (int) $_REQUEST[id_imagen];
				if(mysql_query($q)) {
					@unlink($r[imagen]);
					$rechazado = 'Imagen eliminada exitosamente.';
				} else {
					$rechazado = 'No se ha eliminado la imagen. No se ha eliminado de tabla imagen_artista';
				}
			} else {
				$rechazado = 'No se ha eliminado la imagen. No se ha eliminado de tabla imagen';
			}
		} else {
			$rechazado = 'No se ha eliminado la imagen. Imagen no encontrada en Base de Datos.';
		}
	}
	
	function ingresarActoresDirectores($anuevos, $id_pelicula, $tipo = 1) {
		$nuevos = explode(",", $anuevos);
					
		// ingreso de actores nuevos ingresados
		if(sizeof($nuevos) > 0) {
			for($i=0;$i<sizeof($nuevos);$i++) {
				$id_actor_nuevo = 0;
				$nombre_actor = addslashes(trim($nuevos[$i]));
				
				if(!empty($nombre_actor)) {	
					$q = "SELECT id_artista FROM cart_artista WHERE nombre LIKE '$nombre_actor'";
					$s = mysql_query($q);
					
					if(!mysql_num_rows($s)) {
						$qq = "INSERT INTO cart_artista (date_inserted, nombre) VALUES ('" . mktime() . "', '$nombre_actor')";
						$ss = mysql_query($qq);
						$id_actor_nuevo = mysql_insert_id();
					} else {
						$rr = mysql_fetch_array($s);
						$id_actor_nuevo = $rr[id_artista];
					}
					
					$qqq = "SELECT * FROM cart_peli_artista WHERE id_pelicula = $id_pelicula AND id_artista = $id_actor_nuevo AND id_tipo_artista = $tipo";
					$sss = mysql_query($qqq);
					if(!mysql_num_rows($sss)) {
						$qqqq = "INSERT INTO cart_peli_artista (id_pelicula, id_artista, id_tipo_artista) VALUES ('$id_pelicula', '$id_actor_nuevo', '$tipo')";
						mysql_query($qqqq);
					}
				}		
			}
		}
	}
	
	function guardarPelicula() {
		global $rechazado, $aceptado, $general, $config;
		
		if(empty($_REQUEST[titulo])) $rechazado .= 'Titulo no ingresado<br>';
		if(empty($_REQUEST[descripcion])) $rechazado .= 'Descripcion no ingresada<br>';
		if(empty($_REQUEST[duracion])) $rechazado .= 'Duracion en minutos no ingresada<br>';
		if(empty($_REQUEST[idioma])) $rechazado .= 'Idioma no ingresado<br>';
		if(empty($_REQUEST[year])) $rechazado .= 'Año no seleccionado<br>';
		
		/* verificacion de directores y reparto */
		$directores = explode(",",  $_REQUEST[id_director]);
		if(sizeof($directores)<1) $rechazado .= 'No has seleccionado un director.<br>';
		
		$reparto = explode(",",  $_REQUEST[id_reparto]);
		if(sizeof($reparto)<1) $rechazado .= 'No has seleccionado actores para el reparto.<br>';
		
		$q = "SELECT * FROM cart_pelicula WHERE nombre LIKE '" . $_REQUEST[titulo] . "'";
		$s = mysql_query($q);
		if(mysql_num_rows($s) > 0)  $rechazado .= 'This movie was uploaded before, it can be uploaded again.<br>';
		
		if(empty($rechazado)) {
			$q = "INSERT INTO cart_pelicula ";
			$q .= "(date_inserted, new_release, nombre, trama, idioma, duracion, year, mpaa_rating, audience_that_like_it, critica) VALUES  ";
			$q .= "('" . mktime() . "', '$_REQUEST[new_release]', '$_REQUEST[titulo]', '" . addslashes($_REQUEST[descripcion]) . "', '$_REQUEST[idioma]', '$_REQUEST[duracion]', '$_REQUEST[year]', '$_REQUEST[mpaa_rating]', '$_REQUEST[audience_that_like_it]', '" . addslashes($_REQUEST[critica]) . "')";
			mysql_query($q);
			$id_pelicula = mysql_insert_id();
			
			// ingreso de imagenes de la pelicula
			$id_imagenes = array();
			$id_last_img = 0;			
			$dir_img = "imagenes/peliculas/cartelera/";
			
			for($i=0;$i<=(int)$_REQUEST[cantidad_imagenes];$i++) {
				if(!empty($_FILES['imagen']['name'][$i])) {
					$timagenes = explode(".", $_FILES['imagen']['name'][$i]);
					$nombre_img = mktime() . $i . "." . strtolower($timagenes[(sizeof($timagenes)-1)]);
					$nombre_dir_img = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir_img . $nombre_img;
					$nombre_dir_temp =  $_FILES['imagen']['tmp_name'][$i];
				
					if (@move_uploaded_file($nombre_dir_temp, $nombre_dir_img)){
						$general->redimensionar_jpeg($dir_img . $nombre_img, $dir_img . $nombre_img, 90, 800);
						
						$q01 = "INSERT INTO imagen (directorio, nombre) VALUES ('$dir_img', '$nombre_img')";	
						mysql_query($q01);

						$id_last_img = mysql_insert_id();
						
						array_push($id_imagenes, $id_last_img);
					} else {
						$rechazado = 'Ha ocurrido un error subiendo la imagen del artista.';
					}
				}
			}
			
			// ingreso de directores seleccionados
			for($i=0;$i<sizeof($directores);$i++) {
				$q = "INSERT INTO cart_peli_artista (id_pelicula, id_artista, id_tipo_artista) VALUES ('$id_pelicula', '$directores[$i]', '2')";
				mysql_query($q) or die(mysql_error());
			}
			
			// ingresa actores y directores obtenidos desde un input
			ingresarActoresDirectores($_REQUEST[actores_input], $id_pelicula, 1);
			ingresarActoresDirectores($_REQUEST[directores_input], $id_pelicula, 2);
			
			for($i=0;$i<sizeof($reparto);$i++) {
				$q = "INSERT INTO cart_peli_artista (id_pelicula, id_artista, id_tipo_artista) VALUES ('$id_pelicula', '$reparto[$i]', '1')";
				mysql_query($q);
			}
			
			// insercion de las imagenes subidas
			if(sizeof($id_imagenes)) {
				for($i=0;$i<sizeof($id_imagenes);$i++) {
					$q = "INSERT INTO imagen_pelicula (id_imagen, id_pelicula) VALUES ('$id_imagenes[$i]', '$id_pelicula')";
					mysql_query($q);
				}
			}
			
			// ingreso de los generos seleccionados
			for($i=0;$i<=(int)$_REQUEST[tgeneros];$i++) {
				if(!empty($_REQUEST[id_genero][$i])) {
					$q = "INSERT INTO cart_genero_pelicula (id_pelicula, id_genero) VALUES ('$id_pelicula', '" . $_REQUEST[id_genero][$i] . "')";
					mysql_query($q);
				}
			}
			
			$aceptado = 'La pelicula con ID ' . $id_pelicula . ' se ha ingresado correctamente a la Base de Datos. <a href="' . $config["remote_website_downloads"] . '/admMovie.php?idMovie=' . $id_pelicula . '&name=' . $_REQUEST[titulo] . '" id="admRussia">Ingresar Enlaces y Subtitulos</a>';
		}
	}
	
	function eliminarImagenPelicula() {
		global $rechazado;
		$q02 = "SELECT CONCAT(directorio , nombre) AS imagen FROM imagen WHERE id_imagen = " . (int) $_REQUEST[id_imagen];
		$s02 = mysql_query($q02) or die(mysql_error());
		if(mysql_num_rows($s02)) {
			$r = mysql_fetch_array($s02);
			$q = "DELETE FROM imagen WHERE id_imagen = " . (int) $_REQUEST[id_imagen];
			if(mysql_query($q)) {
				$q = "UPDATE cart_pelicula SET id_imagen = '0' WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_imagen = " . (int) $_REQUEST[id_imagen];
				if(mysql_query($q)) {
					$q = "DELETE FROM imagen_pelicula WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_imagen = " . (int) $_REQUEST[id_imagen];
					if(mysql_query($q)) {
						@unlink($r[imagen]);
						$rechazado = 'Imagen eliminada exitosamente.';
					} else {
						$rechazado = 'No se ha eliminado la imagen. No se ha eliminado de tabla imagen_pelicula';
					}
				} else {
					$rechazado = 'No se ha eliminado la imagen. No se ha actualizado en tabla cart_pelicula.';
				}
			} else {
				$rechazado = 'No se ha eliminado la imagen. No se ha eliminado de tabla imagen';
			}
		} else {
			$rechazado = 'No se ha eliminado la imagen. Imagen no encontrada en Base de Datos.';
		}
	}
	
	function actualizarPelicula() {
		global $rechazado, $aceptado, $general;
		
		if(empty($_REQUEST[titulo])) $rechazado .= 'Titulo no ingresado<br>';
		if(empty($_REQUEST[descripcion])) $rechazado .= 'Descripcion no ingresada<br>';
		//if(empty($_REQUEST[duracion])) $rechazado .= 'Duracion en minutos no ingresada<br>';
		if(empty($_REQUEST[idioma])) $rechazado .= 'Idioma no ingresado<br>';
		if(empty($_REQUEST[year])) $rechazado .= 'Año no seleccionado<br>';
		
		/* verificacion de directores y reparto */
		$directores = explode(",",  $_REQUEST[id_director]);
		if(sizeof($directores)<1) $rechazado .= 'No has seleccionado un director.<br>';
		
		$reparto = explode(",",  $_REQUEST[id_reparto]);
		if(sizeof($reparto)<1) $rechazado .= 'No has seleccionado actores para el reparto.<br>';
		
		if(empty($rechazado)) {
			$id_imagenes = array();
			$dir_img = "imagenes/peliculas/cartelera/";
			
			for($i=0;$i<=(int)$_REQUEST[cantidad_imagenes];$i++) {
				if(!empty($_FILES['imagen']['name'][$i])) {
					$timagenes = explode(".", $_FILES['imagen']['name'][$i]);
					$nombre_img = mktime() . $i . "." . strtolower($timagenes[(sizeof($timagenes)-1)]);
					$nombre_dir_img = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir_img . $nombre_img;
					$nombre_dir_temp =  $_FILES['imagen']['tmp_name'][$i];
				
					if (@move_uploaded_file($nombre_dir_temp, $nombre_dir_img)){
						$general->redimensionar_jpeg($dir_img . $nombre_img, $dir_img . $nombre_img, 90, 800);
						
						$q01 = "INSERT INTO imagen (directorio, nombre) VALUES ('$dir_img', '$nombre_img')";	
						mysql_query($q01);
						
						$id_last_img = mysql_insert_id();

						array_push($id_imagenes, $id_last_img);
					} else {
						$rechazado = 'Ha ocurrido un error subiendo la imagen del artista.';
					}
				}
			}
			
			if(empty($rechazado)) {
				$q = "UPDATE cart_pelicula SET new_release='$_REQUEST[new_release]', identificador_id_movie='$_REQUEST[id_movie]', nombre='$_REQUEST[titulo]', trama='" . addslashes($_REQUEST[descripcion]) . "', idioma='$_REQUEST[idioma]', duracion='$_REQUEST[duracion]', year='$_REQUEST[year]', mpaa_rating='$_REQUEST[mpaa_rating]', audience_that_like_it='$_REQUEST[audience_that_like_it]', critica='" . addslashes($_REQUEST[critica]) . "' WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula];
				//echo $q;
				mysql_query($q) or die(mysql_error());
				
				// ingresa actores y directores obtenidos desde un input
				ingresarActoresDirectores($_REQUEST[actores_input], $_REQUEST[id_pelicula], 1);
				ingresarActoresDirectores($_REQUEST[directores_input], $_REQUEST[id_pelicula], 2);
			
				if(sizeof($directores) > 0) {
					for($i=0;$i<sizeof($directores);$i++) {
						if((int)$directores[$i] > 0) {
							$q = "SELECT * FROM cart_peli_artista WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_artista = " . (int)$directores[$i] . " AND id_tipo_artista = 2";
							$s = mysql_query($q);
							if(!mysql_num_rows($s)) {
								$q = "INSERT INTO cart_peli_artista (id_pelicula, id_artista, id_tipo_artista) VALUES ('" . (int) $_REQUEST[id_pelicula] . "', '$directores[$i]', '2')";
								mysql_query($q);
							}
						}
					}
				}
				
				if(sizeof($reparto) > 0) {
					for($i=0;$i<sizeof($reparto);$i++) {
						if((int) $reparto[$i] > 0) {
							$q = "SELECT * FROM cart_peli_artista WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_artista = " . (int) $reparto[$i] . " AND id_tipo_artista != 2";
							$s = mysql_query($q);
							if(!mysql_num_rows($s)) {
								$q = "INSERT INTO cart_peli_artista (id_pelicula, id_artista, id_tipo_artista) VALUES ('" . (int) $_REQUEST[id_pelicula] . "', '$reparto[$i]', '1')";
								mysql_query($q);
							}
						}
					}
				}
								
				if(sizeof($id_imagenes)) {
					for($i=0;$i<sizeof($id_imagenes);$i++) {
						$q = "INSERT INTO imagen_pelicula (id_imagen, id_pelicula) VALUES ('$id_imagenes[$i]', '" . (int) $_REQUEST[id_pelicula] . "')";
						mysql_query($q);
					}
				}
				
				$aceptado = 'La pelicula ha sido actualizada correctamente en la Base de Datos.';
			}	
		}
	}

	function eliminarDirectorPelicula() {
		global $rechazado;
		$q = "SELECT * FROM cart_peli_artista WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_artista = " . (int) $_GET[id_director_eliminar]. " AND id_tipo_artista = 2";
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			$q = "DELETE FROM cart_peli_artista WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_artista = " . (int) $_GET[id_director_eliminar]. " AND id_tipo_artista = 2";
			if(mysql_query($q)) {
				$rechazado = 'Director eliminado correctamente.';
			}
		}
	}

	function eliminarRepartoPelicula() {
		global $rechazado;
		$q = "SELECT * FROM cart_peli_artista WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_artista = " . (int) $_GET[id_artista_eliminar]. " AND id_tipo_artista != 2";
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			$q = "DELETE FROM cart_peli_artista WHERE id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND id_artista = " . (int) $_GET[id_artista_eliminar]. " AND id_tipo_artista != 2";
			if(mysql_query($q)) {
				$rechazado = 'Artista eliminado correctamente.';
			}
		}
	}
	
	function desplegarDirectores() {
		$id_directores_stored = array();
		$directores_stored = array();
		
		$q = "SELECT 
			ca.id_artista, ca.nombre
		FROM cart_artista ca
		INNER JOIN cart_peli_artista cpa ON (ca.id_artista = cpa.id_artista)
		WHERE cpa.id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND cpa.id_tipo_artista = 2";
		$s = mysql_query($q);
		while($r = mysql_fetch_array($s)) {
			array_push($id_directores_stored, $r[id_artista]);
			array_push($directores_stored, $r[nombre]);
		}
	
		echo '<input type="hidden" value="';
			if(sizeof($id_directores_stored)) echo implode(",", $id_directores_stored);
		echo '" name="id_director" />
      	<span id="director">';
			if(sizeof($directores_stored)) {
				for($i = 0; $i<sizeof($directores_stored); $i++) {
					echo $directores_stored[$i] . ' <a href="?id_pagina=' . (int) $_REQUEST[id_pagina] . '&id_pelicula=' . (int) $_REQUEST[id_pelicula] . '&id_director_eliminar=' . $id_directores_stored[$i] . '&action=update" title="eliminar artista"><img src="imagenes/cancelar.png" border="0"></a>';
					if(($i+1)<sizeof($directores_stored)) echo ', ';
				}
			}
		echo '</span>';
	}
	
	function desplegarReparto() {
		$id_reparto_stored = array();
		$reparto_stored = array();
		
		$q = "SELECT 
			ca.id_artista, ca.nombre
		FROM cart_artista ca
		INNER JOIN cart_peli_artista cpa ON (ca.id_artista = cpa.id_artista)
		WHERE cpa.id_pelicula = " . (int) $_REQUEST[id_pelicula] . " AND cpa.id_tipo_artista != 2";
		$s = mysql_query($q);
		while($r = mysql_fetch_array($s)) {
			array_push($id_reparto_stored, $r[id_artista]);
			array_push($reparto_stored, $r[nombre]);
		}
	
		echo '<input type="hidden" value="';
			if(sizeof($id_reparto_stored)) echo implode(",", $id_reparto_stored);
		echo '" name="id_reparto" />
      	<span id="reparto">';
			if(sizeof($reparto_stored)) {
				for($i = 0; $i<sizeof($reparto_stored); $i++) {
					echo $reparto_stored[$i] . ' <a href="?id_pagina=' . (int) $_REQUEST[id_pagina] . '&id_pelicula=' . (int) $_REQUEST[id_pelicula] . '&id_artista_eliminar=' . $id_reparto_stored[$i] . '&action=update" title="eliminar artista"><img src="imagenes/cancelar.png" border="0"></a>';
					if(($i+1)<sizeof($reparto_stored)) echo ', ';
				}
			}
		echo '</span>';
	}
	
	if(eregi("^[0-9]{1,}$", $_GET[id_director_eliminar])) eliminarDirectorPelicula();
	if(eregi("^[0-9]{1,}$", $_GET[id_artista_eliminar])) eliminarRepartoPelicula();

	if(eregi("^guardar$", $_POST[action])) guardarPelicula();
	if(eregi("^actualizar$", $_POST[action])) actualizarPelicula();
	if(eregi("^eliminar$", $_GET[action]) && eregi("^[0-9]{1,}$", $_GET[id_imagen])) eliminarImagenPelicula();
	
	if(!isset($_GET[action]) || eregi("^update$", $_GET[action]) || (eregi("^eliminar$", $_GET[action]) && eregi("^[0-9]{1,}$", $_GET[id_imagen]))) {
		$q = "SELECT * FROM cart_pelicula p WHERE p.id_pelicula = " . (int) $_REQUEST[id_pelicula];
		$s = mysql_query($q);
		$r = mysql_fetch_array($s);
		$id_pelicula = $r[id_pelicula];
		$titulo = $r[nombre];
		$descripcion = $r[trama];
		$duracion = $r[duracion];
		$idioma = $r[idioma];
		$year = $r[year];
		$mpaa_rating = $r[mpaa_rating];
		$id_movie_download = $r[identificador_id_movie];
		$new_release = $r[new_release];
		$audience_that_like_it = $r[audience_that_like_it];
		$critica = $r[critica];
		$actualizando = true;
	}
?>
<h1>Ingreso de Peliculas</h1>
<?php
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado)) echo '<div id="rechazado">' . $rechazado . '</div>';
?>
<form action="?id_pagina=<?php
	echo (int) $_REQUEST[id_pagina];
	
	if($id_pelicula > 0)
		echo '&id_pelicula=' . (int) $_REQUEST[id_pelicula];
        
?>" method="post" enctype="multipart/form-data" name="form1">
<fieldset>
  <legend>Movie Information</legend>
  <table width="100%" cellpadding="5">
    <tr>
      <td width="279" valign="top"><strong>Title</strong></td>
      <td width="985"><input name="titulo" type="text" style="width:810px;" value="<?=$titulo ?>"></td>
    </tr>
    <tr>
      <td valign="top"><strong>Description</strong></td>
      <td>
        
        <textarea name="descripcion" cols="45" rows="5" style="width:810px;"><?=stripslashes($descripcion) ?></textarea>      </td>
    </tr>
    <tr>
      <td valign="top"><strong>Critica</strong></td>
      <td><textarea name="critica" cols="45" rows="5" style="width:810px;" id="critica"><?=stripslashes($critica) ?></textarea></td>
    </tr>
    <tr>
      <td valign="top"><strong> Genero</strong></td>
      <td>
        <div id="ngeneros" style="float:left">
			<?php $general->listarGeneros($id_genero); ?>
        </div>
		<a href="javascript:agregarGenero();"> Añadir...</a>
		<input type="hidden" name="tgeneros" value="1">      </td>
    </tr>
    <tr>
      <td valign="top"><strong>Time</strong></td>
      <td><strong>
        <input type="text" name="duracion" value="<?=$duracion ?>"> 
      min.</strong></td>
    </tr>
    <tr>
      <td valign="top"><strong>Language</strong></td>
      <td><input name="idioma" type="text" value="<?=$idioma ?>"></td>
    </tr>
    <tr>
      <td valign="top"><strong>Year</strong></td>
      <td><strong>
        <?php $general->listarYears($year);  ?>
      </strong></td>
    </tr>
    <tr>
    	<td valign="top"><strong>MPAA Rating</strong></td>
      <td>
        <strong>
        <?php
		$general->getMpaaRating($mpaa_rating);
?>
        </strong> </td>
    </tr>
    <tr>
	<td valign="top"><strong>Audience that like it</strong></td>
	<td><input name="audience_that_like_it" type="text" value="<?=$audience_that_like_it ?>"> Ej: 0.00</td>
    </tr>
    <tr>
      <td valign="top"><strong>New Release</strong></td>
      <td><input type="checkbox" name="new_release" value="1" <?=($new_release > 0) ? ' checked' : '' ?> /></td>
    </tr>
    <tr>
      <td valign="top"><strong>ID Movie</strong></td>
      <td><input name="id_movie" type="text" value="<?=$id_movie_download ?>"></td>
    </tr>
  </table>
</fieldset>
<br />
<br />
<fieldset>
  <legend>Directors and movie cast information</legend>
  <table cellpadding="5" cellspacing="0">
    <tr>
      <td><strong>Directores</strong></td>
      <td><strong>Actores</strong></td>
    </tr>
    <tr>
      <td valign="top"><?php if($id_pelicula > 0) desplegarDirectores(); ?></td>
      <td valign="top"><?php if($id_pelicula > 0) desplegarReparto(); ?></td>
    </tr>
    <tr>
      <td><strong>Nuevos:</strong> <i>(Ingresar separados por comas. Ej: Dir1, Dir2[, ... DirN])</i><input name="directores_input" type="text"  style="width:440px" /></td>
      <td><strong>Nuevos:</strong> <i>(Ingresar separados por comas. Ej: Act1, Act2[, ... ActN])</i><input name="actores_input" type="text" style="width:440px" /></td>
    </tr>
  </table>
</fieldset>
<br />
<br />


    <fieldset>
        <legend>Movie images and external information</legend>
        <table width="100%" cellpadding="5">
<?php
	if($config["stay_beautiful"]) {
        	if($_REQUEST[id_pelicula] > 0) {
?>
        <tr>
          <td width="15%" valign="top"><strong>Links and Subtitles:</strong></td>
          <td width="85%"><a href="<?=$config["remote_website_downloads"] ?>/admMovie.php?idMovie=<?=$_REQUEST[id_pelicula] ?>&amp;name=<?=$titulo ?>" id="admRussia">Admin external links, images and subtitles...</a></td>
        </tr>
<?php
        	}
        }
?>
        <tr>
          <td valign="top"><strong>Images:</strong></td>
          <td><?php retornarImagenes(); ?>
          	<div id="nuevas_imagenes" style="clear:both;margin-top:20px;"><input type="file" name="imagen[0]" /> </div>
            <a href="javascript:agregarInputDeImagen();">A&ntilde;adir otra imagen...</a>
			<input type="hidden" name="cantidad_imagenes" value="1" />
		  </td>
        </tr>
      </table>
    </fieldset>
    <br />
    <br />
    
    
	<div align="right">
    	<input type="button" name="action" value="volver" onclick="window.location='?id_pagina=24'">
        <input type="submit" name="action" value="<?=($_REQUEST[id_pelicula] > 0) ? 'actualizar' : 'guardar' ?>">
    </div>
</form>
