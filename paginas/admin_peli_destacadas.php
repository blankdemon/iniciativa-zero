<?php
	$cantidad = 0;
	$base = 0;
	$limite = 0;
	$total = 0;
	
	function guardarPeliculaDestacada() {
		global $aceptado, $rechazado, $general;
		
		$id_last_img = 0;
		$id_imagenes = array();
		$dir_img = "imagenes/peliculas/destacadas/";
			
		if(!empty($_FILES['imagen']['name'])) {
			$timagenes = explode(".", $_FILES['imagen']['name']);
			$nombre_img = mktime() . $i . "." . strtolower($timagenes[(sizeof($timagenes)-1)]);
			$nombre_dir_img = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir_img . $nombre_img;
			$nombre_dir_temp =  $_FILES['imagen']['tmp_name'];
		
			if (@move_uploaded_file($nombre_dir_temp, $nombre_dir_img)){
				$general->redimensionar_jpeg($dir_img . $nombre_img, $dir_img . $nombre_img, 90, 970);
								
				$q01 = "SELECT id_pelicula, nombre FROM cart_pelicula WHERE id_pelicula=" . (int) $_REQUEST[id_pelicula];	
				$s01 = mysql_query($q01);
				$r = mysql_fetch_array($s01);
			   
				$q = "INSERT INTO cart_pelicula_destacada ";
				$q .= "(id_pelicula, titulo, descripcion, imagen) ";
				$q .= "VALUES ";
				$q .= "('$r[id_pelicula]', '$r[nombre]', '$_REQUEST[descripcion]', '" . $dir_img . $nombre_img . "')";
				mysql_query($q);
				
				$aceptado = 'La pelicula ha sido marcada como destacada exitosamente.';
			} else {
				$rechazado = 'Ha ocurrido un error subiendo la imagen del artista.';
			}
		}		
	}
	
	function eliminarPeliculaDestacada() {
		global $rechazado;
		$q = "SELECT imagen FROM cart_pelicula_destacada WHERE id_pelicula = " . (int) $_REQUEST[id_destacado_eliminar];
		$ss = mysql_query($q);
		if(mysql_num_rows($ss)) {		
			$q = "DELETE FROM cart_pelicula_destacada WHERE id_pelicula = " . (int) $_REQUEST[id_destacado_eliminar];
			if(mysql_query($q)) {
				$r = mysql_fetch_array($ss);
				$dir_img = $_SERVER['DOCUMENT_ROOT'] . "/imagenes/peliculas/destacadas/" . $r[imagen];
				@unlink($dir_img);
				$rechazado = 'Pelicula eliminada desde banner de la página principal.';
			}
		}
	}
	
	/* guarda las imagenes cuando hay un upload masivo de imagenes, desde un listado */
	function guardarImagenesDePeliculas() {
		global $rechazado, $aceptado, $general;
		
		for($i=0;$i<(int)$_REQUEST[total_peliculas];$i++) {
			if(!empty($_FILES['imagen']['name'][$i])) {
				$id_pelicula = $_REQUEST[id_pelicula][$i];
				if(empty($rechazado)) {
					$timagenes = explode(".", $_FILES['imagen']['name'][$i]);
					$nombre_img = mktime() . "." . strtolower($timagenes[(sizeof($timagenes)-1)]);
					$dir_img = "imagenes/peliculas/cartelera/";
					$nombre_dir_img = $_SERVER['DOCUMENT_ROOT'] . "/" . $dir_img . $nombre_img;
					$nombre_dir_temp =  $_FILES['imagen']['tmp_name'][$i];
				
					if (@move_uploaded_file($nombre_dir_temp, $nombre_dir_img)){
						$general->redimensionar_jpeg($dir_img . $nombre_img, $dir_img . $nombre_img, 90, 800);
						
						$q01 = "INSERT INTO imagen (directorio, nombre) VALUES ('$dir_img', '$nombre_img')";	
						mysql_query($q01);
						$id_imagen = mysql_insert_id();
					} else {
						$rechazado .= 'Ha ocurrido un error subiendo la imagen de la pelicula.<br>';
					}
					
					if(empty($rechazado)) {
						$q = "UPDATE cart_pelicula SET date_updated='" . mktime() . "', id_imagen = '$id_imagen' WHERE id_pelicula = $id_pelicula";
						if(mysql_query($q)) $aceptado = 'La imagen ha sido asignada correctamente a la pelicula.';
					}	
				}
			} else {
				$faltan_imagenes = true;
			}
		}
		
		if($faltan_imagenes) $rechazado .= 'Algunas imagenes no han sido subidas.';
	}
	
	function showPaginado() {
		global $cantidad, $limite, $total, $base;

		// se realiza el paginado...
		if($total > $cantidad) {
			$limit_mi_max = 5;
			if((int)$_GET[base] >= $limit_mi_max && (int)$_GET[base] <= ($limite-$limit_mi_max)) {
				$binicio = $_GET[base]-$limit_mi_max;
				$bfinal = $_GET[base]+$limit_mi_max;
			} else if((int)$_GET[base] == 0) {
				$binicio = 0;
				$bfinal = $limit_mi_max;
			} else if((int)$_GET[base] == $limite) {
				$binicio = ($limite-$limit_mi_max);
				$bfinal = $limite;
			} else if((int)$_GET[base] < $limit_mi_max && (int)$_GET[base]>0) {
				$binicio = 0;
				$bfinal = $_GET[base]+$limit_mi_max;;
			} else {
				$binicio = (int)$_GET[base]-$limit_mi_max;
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
	
	if(eregi("^subir_imagenes$", $_REQUEST[action])) guardarImagenesDePeliculas();
	if(eregi("^guardar_destacada$", $_REQUEST[action])) guardarPeliculaDestacada();
	if(eregi("^eliminar$", $_REQUEST[action])) eliminarPeliculaDestacada();
?>
<h1>Administraci&oacute;n de pel&iacute;culas destacadas</h1>
<form name="form1" id="buscador" method="post" action="">
    <input type="text" name="words" id="words" />
    <input type="submit" name="action" id="button" value="buscar" />
    <div id="orden">
    	<a href="?id_pagina=35">Ingresar nueva pel&iacute;cula</a>
	</div>
</form>
<?php
	if(!empty($aceptado)) echo '<div id="aceptado">' . $aceptado . '</div>';
	if(!empty($rechazado) && !isset($_REQUEST[id_invitacion])) echo '<div id="rechazado">' . $rechazado . '</div>';

	$cantidad = 30;
	$base = ((int) $_REQUEST[base] > 0) ? ((int)$_REQUEST[base] * $cantidad) : 0;
	$q = "SELECT 
		p.id_pelicula, p.nombre, p.trama, p.year, p.duracion, p.subtitulo, p.puntaje, p.id_imagen,
		g.nombre genero, IF(p.id_pelicula IN (SELECT id_pelicula FROM cart_pelicula_destacada WHERE id_pelicula = p.id_pelicula), 1, 0) AS es_destacada 
	FROM cart_pelicula p
	LEFT JOIN cart_genero g ON (p.id_genero = g.id_genero) ";
	
	if(eregi("^buscar$", $_REQUEST[action])) {
		$q .= " WHERE ";
		$q .= " p.nombre LIKE '%$_REQUEST[words]%' OR ";
		$q .= " p.trama LIKE '%$_REQUEST[words]%' OR ";
		$q .= " g.nombre LIKE '%$_REQUEST[words]%'";
	}
	
	$q .= " GROUP BY p.id_pelicula 
	ORDER BY p.year DESC ";
	
	//if(!eregi("^buscar$", $_REQUEST[action])) $q .= " LIMIT 0, 30";
	
	$ss = mysql_query($q) or die(mysql_error());	
	$total = mysql_num_rows($ss);
	$limite = intval($total / $cantidad);
	
	$q .= " LIMIT $base, $cantidad";
	
	//echo $q;
	
	$s = mysql_query($q) or die(mysql_error());
	if(mysql_num_rows($s)) {
?>
		<form name="lpelis" action="" method="post">
		<ul id="peli_destacadas">        
            <li id="tpeli_destacadas">
                <span id="num">N&ordm;</span>
                <span id="nombre">Nombre</span>
                <span id="director">Director(es)</span>
                <span id="genero">G&eacute;nero</span>
                <span id="accion">Acci&oacute;n</span>
            </li>
<?php
			while($r = mysql_fetch_array($s)) {
				$c++;
?>
				<li class="lpeli_destacadas" onmouseover="uno(this, 'DFEFFF')" onmouseout="dos(this, 'ffffff')">
					<span id="num"><?=$c ?></span>
					<span id="nombre"><?=$r[nombre] ?></span>
					<span id="director"><?php $general->obtenerDirectores($r[id_pelicula]); ?></span>
					<span id="genero"><?php $general->obtenerGeneros($r[id_pelicula]); ?></span>
					<span id="accion">
<?php						
						if(!$r[es_destacada]) {
							echo '<a href="paginas/admin_ingreso_peli_destacadas.php?id_pelicula=' . $r[id_pelicula] . '" class="destacar" title="Poner pelicula en la p&aacute;gina principal">&nbsp;</a>';
						} else {
							echo '<a href="javascript:confirmarEliminacionDestacada(' . $r[id_pelicula] . ')" class="eliminar"  title="Eliminar de la p&aacute;gina principal">&nbsp;</a>';
						}
						
						// despliega la edicion de la pelicula
						echo '<a href="?id_pagina=35&id_pelicula=' . $r[id_pelicula] . '&action=update" class="actualizar"  title="modificar pelicula">&nbsp;</a>';
						
						echo '<input type="checkbox" class="upimg" value="' . $r[id_pelicula] . '||' . $r[nombre] . '" name="id_pelicula_upload_file"';
						if($r[id_imagen] > 0) echo ' checked disabled';						
						echo ' />';
?>
					</span>
				</li>
<?php
		}
?>
		</ul>
        <!-- div align="right"><input type="button" value="subir imagenes" onclick="subirImagenesPeliculas(document.lpelis);" /></div -->
        </form>
<?php
		/* desplegar el paginado de resultados */		
		showPaginado();
	} else {
		echo '<div  id="rechazado">No se han encontrado resultados de busqueda.</div>';
	}
?>
