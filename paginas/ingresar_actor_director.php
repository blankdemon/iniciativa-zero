<?php
	function retornarImagenes() {
		$q = "SELECT i.id_imagen, i.directorio, i.nombre
		FROM imagen i
		INNER JOIN imagen_artista ai ON (i.id_imagen = ai.id_imagen) 
		WHERE ai.id_artista = " . (int) $_REQUEST[id_artista];
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			echo '<div id="eliminar_imagenes">';
			while($r = mysql_fetch_array($s)) {
				echo '<span>
					<img src="paginas/imagen.php?id_imagen=' . $r[id_imagen] . '">
					<a href="?id_pagina=38&id_artista=' . (int) $_REQUEST[id_artista] . '&id_imagen=' . $r[id_imagen] . '&action=eliminar">eliminar</a>
				</span>';
			}
			echo '</div>';
		}
	}

	function guardarArtista() {
		global $rechazado, $aceptado, $general;
		
		//if(empty($_REQUEST[id_tipo_artista])) $rechazado .= 'Tipo de artista no seleccionado<br>';
		if(empty($_REQUEST[nombre])) $rechazado .= 'Nombre no ingresado<br>';
		
		if(empty($rechazado)) {
			$id_imagenes = array();
			$id_last_img = 0;			
			$dir_img = "imagenes/peliculas/artistas/";
			
			for($i=0;$i<=(int)$_REQUEST[cantidad_imagenes];$i++) {
				if(!empty($_FILES['imagen']['name'][$i])) {
					$timagenes = explode(".", $_FILES['imagen']['name'][$i]);
					$nombre_img = mktime() . $i . "." . strtolower($timagenes[(sizeof($timagenes)-1)]);
					$nombre_dir_img = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir_img . $nombre_img;
					$nombre_dir_temp =  $_FILES['imagen']['tmp_name'][$i];
				
					if (move_uploaded_file($nombre_dir_temp, $nombre_dir_img)){
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
			
			/* insercion de artistas en base de datos */
			if(empty($rechazado)) {
				$q = "INSERT INTO cart_artista ";
				$q .= "(date_inserted, tipo_artista, nombre, biografia, id_imagen) VALUES  ";
				$q .= "('" . mktime() . "', '$_REQUEST[id_tipo_artista]', '" . addslashes($_REQUEST[nombre]) . "', '" . addslashes($_REQUEST[biografia]) . "', '" . $id_last_img . "')";
				mysql_query($q);
				$id_artista = mysql_insert_id();
				
				$aceptado = 'El artista se ha ingresado correctamente a la Base de Datos.';
			}
			
			if(sizeof($id_imagenes)) {
				for($i=0;$i<sizeof($id_imagenes);$i++) {
					$q = "INSERT INTO imagen_artista (id_imagen, id_artista) VALUES ('$id_imagenes[$i]', '$id_artista')";
					mysql_query($q);
				}
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
				$q = "DELETE FROM imagen_artista WHERE id_artista = " . (int) $_REQUEST[id_artista] . " AND id_imagen = " . (int) $_REQUEST[id_imagen];
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
	
	function actualizarArtista() {
		global $rechazado, $aceptado, $general;
		
		//if(empty($_REQUEST[id_tipo_artista])) $rechazado .= 'Tipo de artista no seleccionado<br>';
		if(empty($_REQUEST[nombre])) $rechazado .= 'Titulo no ingresado<br>';
		
		if(empty($rechazado)) {
			$id_imagenes = array();
			$dir_img = "imagenes/peliculas/artistas/";
			
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
			
			/* insercion de artistas en base de datos */
			if(empty($rechazado)) {
				$q = "UPDATE cart_artista SET date_updated = '" . mktime() . "', tipo_artista = '$_REQUEST[id_tipo_artista]', nombre = '$_REQUEST[nombre]', biografia = '$_REQUEST[biografia]'";
				
				if(sizeof($id_imagenes)) $q .= ", id_imagen='" . $id_last_img . "' ";
				
				$q .= " WHERE id_artista = " . (int) $_REQUEST[id_artista];
				mysql_query($q);
								
				$aceptado = 'El artista se ha actualizado correctamente.';
			}
			
			if(sizeof($id_imagenes)) {
				for($i=0;$i<sizeof($id_imagenes);$i++) {
					$q = "INSERT INTO imagen_artista (id_imagen, id_artista) VALUES ('$id_imagenes[$i]', '" . (int) $_REQUEST[id_artista] . "')";
					mysql_query($q);
				}
			}
		}
	}

	if(eregi("^guardar$", $_POST[action])) guardarArtista();
	if(eregi("^actualizar$", $_POST[action])) actualizarArtista();
	if(eregi("^eliminar$", $_GET[action]) && eregi("^[0-9]{1,}$", $_GET[id_imagen])) eliminarImagenArtista();
	
	if(eregi("^update$", $_GET[action]) || (eregi("^eliminar$", $_GET[action]) && eregi("^[0-9]{1,}$", $_GET[id_imagen]))) {
		$q = "SELECT id_artista, tipo_artista, nombre, biografia FROM cart_artista WHERE id_artista = " . (int) $_REQUEST[id_artista];
		$s = mysql_query($q);
		$r = mysql_fetch_array($s);
		$id_artista_seleccionado = $r[id_artista];
		$tipo_artista_seleccionado = $r[tipo_artista];
		$nombre_seleccionado = $r[nombre];
		$biografia_seleccionado = $r[biografia];
		$actualizando = true;
	}
?>
<h1>Ingreso de Actores y Directores</h1>
<?php
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado)) echo '<div id="rechazado">' . $rechazado . '</div>';
?>
<form action="" method="post" enctype="multipart/form-data" name="form1">
  <table width="100%">
    <!-- tr>
      <td width="125">Tipo Artista</td>
      <td width="821">
        <?php $general->listarTipoArtista($tipo_artista_seleccionado); ?>
      </td>
    </tr -->
    <tr>
      <td width="125">Nombre Actor</td>
      <td width="821">
        <input type="text" name="nombre" value="<?=$nombre_seleccionado ?>" style="width:500px;">
      </td>
    </tr>
    <tr>
      <td valign="top">Biograf&iacute;a</td>
      <td>
        <textarea name="biografia" cols="45" rows="5" style="width:500px;"><?=$biografia_seleccionado ?></textarea>
      </td>
    </tr>
    <tr>
      <td valign="top">Imagenes</td>
      <td> 
	  	<?php retornarImagenes(); ?>
		<div id="nuevas_imagenes">
			<input type="file" name="imagen[0]"><br>
		</div>
		<a href="javascript:agregarInputDeImagen();">A&ntilde;adir otra imagen...</a>
		<input type="hidden" name="cantidad_imagenes" value="1">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right">
        <input type="button" name="action" value="volver" onclick="window.location='?id_pagina=37'">
        <input type="submit" name="action" value="<?=($actualizando == true) ? 'actualizar' : 'guardar' ?>">
      </td>
    </tr>
  </table>
</form>
