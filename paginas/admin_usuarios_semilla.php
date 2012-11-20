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

	
	if(isset($_POST[guardarNoticia])) guardarNew();
	if(eregi("^actualizar$", $_POST[action])) actualizarPelicula();
?>
<h1>Administraci&oacute;n de Usuarios y Compras semilla</h1>
<form name="form1" id="buscador" method="post" action="">
	<input type="text" name="words" id="words" />
	<input type="submit" name="action" id="button" value="buscar" />
	<div id="new">
		<a href="javascript:displayRegisterForm(true);">Nuevo usuario</a>
	</div>
</form>
<?php
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado)) echo '<div id="rechazado">' . $rechazado . '</div>';
		
	$q = "SELECT 
		c.id_cuenta, c.usuario, CONCAT(c.nombres , ' ' , c.apellidos) AS nombre
	FROM cuentas c ";
	
	if(eregi("^buscar$", $_REQUEST[action])) {
		$q .= " WHERE c.nombres LIKE '%$_REQUEST[words]%' OR ";
		$q .= " c.apellidos LIKE '%$_REQUEST[words]%' ";
	} else {
		$q .= " WHERE c.usuario_semilla = 1";
	}
	
	$q .= " ORDER BY c.id_cuenta ";
	$s = mysql_query($q) or die(mysql_error());

	if(mysql_num_rows($s)) {
?>
		<ul id="usemillas">        
		    <li id="tusemillas">
		        <span id="num">N&ordm;</span>
		        <span id="usuario">Usuario</span>
		        <span id="nombre">Nombre</span>
		        <span id="nsemillas">Cant. Semillas</span>
		        <span id="accion">Acci&oacute;n</span>
		    </li>
<?php
			while($r = mysql_fetch_array($s)) {
?>
				<li class="lusemillas" onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
				    <span id="num"><?=$r[id_cuenta] ?></span>
				    <span id="usuario"><?=$r[usuario] ?></span>
				    <span id="nombre"><?=$r[nombre] ?></span>
				    <span id="nsemillas">desconocido</span>
				    <span id="accion">
					<a href="javascript:;" title="agregar compra gratis">agregar</a>
				    </span>
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
