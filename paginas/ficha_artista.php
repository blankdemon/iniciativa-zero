<?php
	function retornarImagenes() {
		global $img_restantes;
		
		$img_restantes = array();
		
		$q = "SELECT i.id_imagen, CONCAT(i.directorio , i.nombre) imagen
		FROM imagen i
		INNER JOIN imagen_artista ai ON (i.id_imagen = ai.id_imagen) 
		WHERE ai.id_artista = " . (int) $_REQUEST[id_artista];
		$s = mysql_query($q);
		if(mysql_num_rows($s)) {
			$i = 0;
			while($r = mysql_fetch_array($s)) {
				array_push($img_restantes, '<a href="' . $r[imagen] . '" rel="example_group"><img src="paginas/imagen.php?id_imagen=' . $r[id_imagen] . '&ancho=180&alto=170"></a> ');
			}
		} else {
			array_push($img_restantes, 'Artista sin imagenes asociadas.');
		}
	}
	
	function listarFilmografia() {
		$q = "SELECT p.id_pelicula, p.nombre, p.year
		FROM cart_peli_artista pa
		INNER JOIN cart_pelicula p ON (pa.id_pelicula = p.id_pelicula)
		WHERE pa.id_artista = " . (int) $_REQUEST[id_artista];
		$s = mysql_query($q);
		$numreg = mysql_num_rows($s);
		if($numreg) {
			while($r = mysql_fetch_array($s)) {
				$i++;
				echo '<a href="?id_pagina=36&id_pelicula=' . $r[id_pelicula] . '">' . $r[nombre] . ' (' . $r[year] . ')</a>';
				if($i < $numreg) echo ', ';
			}
		} else {
			echo 'Artist without movies related.';
		}
	}

	$q = "SELECT nombre, biografia, id_imagen FROM cart_artista WHERE id_artista = " . (int) $_REQUEST[id_artista];
	$s = mysql_query($q);
	
	if(mysql_num_rows($s)) {
		$r = mysql_fetch_array($s);
		
		retornarImagenes();
?>
	<div id="artista">
		<div id="left">
			<img src="paginas/imagen.php?id_imagen=<?=$general->lastImgCelebrity((int)$_REQUEST[id_artista]) ?>&ancho=180&alto=267">
<?php
				if($_SESSION[nivelAcceso] > 1) echo '<span><b><a href="/?id_pagina=38&id_artista=' . (int) $_REQUEST[id_artista] . '&action=update" target="_blank">Actualizar</a></b></span>';
?>
		</div>
		<div id="right">
			<h1><?=$r[nombre] ?></h1>
			<div>
				<h3>Biography</h3>
				<?=(!empty($r[biografia])) ? str_replace("\r\n", "<br>", $r[biografia]) : 'Not available yet.' ?>
			</div>
			<div>
				<h3>Filmography</h3>
				<?php listarFilmografia(); ?>
			</div>
			<div>
				<h3>Gallery</h3>
				<?php if(sizeof($img_restantes)) echo implode(" ", $img_restantes); ?>		
			</div>
			<div>
				<h3>News Related</h3>
				No news related...		
			</div>
		</div>
	</div>
<?php
	} else {
		echo '<h1>Not information available</h1>
		<div id="rechazado">Not information available</div>';
	}
?>
