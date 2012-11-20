<?php
	// guarda las noticias
	function guardarNew() {
		global $rechazado, $aceptado, $general;
		
		if(empty($_REQUEST[tipo_noticia])) $rechazado .= 'Tipo de Noticia no Indicado<br>';
		if(empty($_REQUEST[titulo])) $rechazado .= 'Titulo no ingresado<br>';
		if(empty($_REQUEST[noticia])) $rechazado .= 'Contenido de la Noticia no ingresado<br>';
		
		if(empty($rechazado)) {
			$timagenes = explode(".", $_FILES['imagen']['name']);
			$nombre_img = mktime() . "." . strtolower($timagenes[(sizeof($timagenes)-1)]);
			$dir_img = "imagenes/news/";
			$nombre_dir_img = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir_img . $nombre_img;
			$nombre_dir_temp =  $_FILES['imagen']['tmp_name'];
		
			if (move_uploaded_file($nombre_dir_temp, $nombre_dir_img)){
				$general->redimensionar_jpeg($dir_img . $nombre_img, $dir_img . $nombre_img, 90, 800);
				
				$q01 = "INSERT INTO imagen (directorio, nombre) VALUES ('$dir_img', '$nombre_img')";	
				mysql_query($q01);
				$id_imagen = mysql_insert_id();
			} else {
				$rechazado = 'Ha ocurrido un error subiendo la imagen de la pelicula.';
			}
			
			if(empty($rechazado)) {
				$q = "INSERT INTO new ";
				$q .= "(date_inserted, new_type, title, text, id_imagen) VALUES  ";
				$q .= "('" . mktime() . "', '$_REQUEST[tipo_noticia]', '" . htmlentities(addslashes($_REQUEST[titulo])) . "', '" . htmlentities(addslashes($_REQUEST[noticia])) . "', '$id_imagen')";
				mysql_query($q);
								
				$aceptado = 'La noticia ha sido ingresada correctamente a la Base de Datos.';
			}	
		}
	}
	
	function eliminarNoticia() {
		global $rechazado;
		$q02 = "SELECT CONCAT(directorio , nombre) AS imagen FROM imagen WHERE id_imagen = " . (int) $_REQUEST[id_imagen];
		$s02 = mysql_query($q02) or die(mysql_error());
		if(mysql_num_rows($s02)) {
			$r = mysql_fetch_array($s02);
			$q = "DELETE FROM imagen WHERE id_imagen = " . (int) $_REQUEST[id_imagen];
			if(mysql_query($q)) {
				$q = "UPDATE new SET id_imagen = '0' WHERE id_new = " . (int) $_REQUEST[id_new];
				if(mysql_query($q)) {
						@unlink($r[imagen]);
						$rechazado = 'Imagen eliminada exitosamente.';
				} else {
					$rechazado = 'No se ha eliminado la imagen. No se ha actualizado en tabla <b>new</b>.';
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
		if(empty($_REQUEST[id_genero])) $rechazado .= 'Genero no seleccionado<br>';
		
		if(empty($rechazado)) {
			if(!empty($_FILES['imagen']['name'])) {
				$timagenes = explode(".", $_FILES['imagen']['name']);
				$nombre_img = mktime() . "." . strtolower($timagenes[(sizeof($timagenes)-1)]);
				$dir_img = "imagenes/news/";
				$nombre_dir_img = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir_img . $nombre_img;
				$nombre_dir_temp =  $_FILES['imagen']['tmp_name'];
			
				if (@move_uploaded_file($nombre_dir_temp, $nombre_dir_img)){
					$general->redimensionar_jpeg($dir_img . $nombre_img, $dir_img . $nombre_img, 90, 800);
					
					$q01 = "INSERT INTO imagen (directorio, nombre) VALUES ('$dir_img', '$nombre_img')";	
					mysql_query($q01);
					$id_imagen = mysql_insert_id();
				} else {
					$rechazado = 'Ha ocurrido un error subiendo la imagen de la pelicula.';
				}
			}
			
			if(empty($rechazado)) {
				$q = "UPDATE cart_pelicula SET nombre = '$_REQUEST[titulo]', trama = '$_REQUEST[descripcion]', id_genero = '$_REQUEST[id_genero]', idioma = '$_REQUEST[idioma]', duracion = '$_REQUEST[duracion]', year = '$_REQUEST[year]', subtitulo = '$_REQUEST[subtitulo]'";
				if($id_imagen > 0) $q .= ", id_imagen = '$id_imagen'";
				$q .= " WHERE id_new = " . (int) $_REQUEST[id_new];
				mysql_query($q);
				
				$aceptado = 'La pelicula ha sido actualizada correctamente en la Base de Datos.';
			}	
		}
	}

	// Funcion que elimina la noticia y la imagen que tenga asignada.
	function eliminarNew() {
		global $rechazado;
		$q = "SELECT * FROM new WHERE id_new = " . (int) $_REQUEST[id_eliminar];
		$ss = mysql_query($q);
		if(mysql_num_rows($ss)) {
			$r = mysql_fetch_array($ss);
			$q02 = "SELECT CONCAT(directorio , nombre) AS imagen FROM imagen WHERE id_imagen = " . (int) $r[id_imagen];
			$s02 = mysql_query($q02) or die(mysql_error());
			if(mysql_num_rows($s02)) {
				$r = mysql_fetch_array($s02);
				$q = "DELETE FROM imagen WHERE id_imagen = " . (int) $r[id_imagen];
				if(mysql_query($q)) {
					$q = "DELETE FROM new WHERE id_new = " . (int) $_REQUEST[id_eliminar];
					if(mysql_query($q)) {
							@unlink($r[imagen]);
							$rechazado = 'Noticia eliminada exitosamente.';
					} else {
						$rechazado = 'No se ha eliminado la noticia. No se ha actualizado en tabla <b>new</b>.';
					}
				} else {
					$rechazado = 'No se ha eliminado la noticia. No se ha eliminado de tabla imagen';
				}
			} else {
				$rechazado = 'No se ha eliminado la noticia. Imagen no encontrada en Base de Datos.';
			}
		}
	}
	
	if(eregi("^[0-9]{1,}$", $_REQUEST[id_eliminar])) eliminarNew();
	if(isset($_POST[guardarNoticia])) guardarNew();
	
	// Faltan estas dos funciones
	if(eregi("^actualizar$", $_POST[action])) actualizarPelicula();
	if(eregi("^eliminar$", $_GET[action]) && eregi("^[0-9]{1,}$", $_GET[id_imagen])) eliminarNoticia();
?>
<h1>Administraci&oacute;n de Noticias</h1>
<?php
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado)) echo '<div id="rechazado">' . $rechazado . '</div>';
	
	echo '<a href="paginas/ingresar_noticia.php?id_pagina=' . (int) $_REQUEST[id_pagina] . '" id="ing-new">Ingresar Noticia</a>';
	
	/*
	echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';
	*/

	$q = "SELECT n.*
	FROM new n
	ORDER BY n.id_new DESC
	LIMIT 0,30";
	
	$s = mysql_query($q) or die(mysql_error());	
	if(mysql_num_rows($s)) {
?>
		<ul id="admin-news">        
		    <li id="tpeli_destacadas">
		        <span id="num">N&ordm;</span>
		        <span id="fecha">Fecha</span>
		        <span id="tipo">Tipo Noticia</span>
		        <span id="titulo">Titulo</span>
		        <span id="accion">Acci&oacute;n</span>
		    </li>
<?php
			while($r = mysql_fetch_array($s)) {
				$c++;
?>
				<li class="ladmin-news" onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
                    <span id="num"><?=$c ?></span>
                    <span id="fecha"><?=date("d/m/Y", $r[date_inserted]) ?></span>
                    <span id="tipo"><?=$general->listarTipoNoticia($r[new_type], true) ?></span>
                    <span id="titulo"><?=$r[title] ?></span>
                    <span id="accion"><a href="javascript:;" onclick="if(confirm('Esta seguro(a) que desea eliminar esta noticia?')) window.location='?id_pagina=<?=(int)$_REQUEST[id_pagina] ?>&id_eliminar=<?=$r[id_new] ?>'; " class="eliminar"  title="Eliminar noticia">eliminar</a></span>
                </li>
<?php
		}
?>
		</ul>
<?php
	} else {
		echo '<div  id="rechazado">No se han encontrado resultados de busqueda.</div>';
	}
?>
