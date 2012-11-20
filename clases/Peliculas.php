<?php
	class Peliculas {
		
		function getGenerosByIdFilm($id_film=0) {
			$q = "SELECT 
				cg.nombre genero
			FROM cart_genero_pelicula cgp
			INNER JOIN cart_genero cg ON (cgp.id_genero = cg.id_genero)
			WHERE cgp.id_pelicula = " . (int) $id_film;
			$s = mysql_query($q) or die(mysql_error());
			$total = mysql_num_rows($s);
			if($total) {
				$i=0;
				while($r = mysql_fetch_array($s)) {
					$i++;
					$generos .= $r[genero];
					if($i < $total) $generos .= ', ';
				}
				
				return $generos;
			} else {
				return "";
			}
		}
		
		function getDirectorsByIdFilm($id_film=0) {
			$q = "SELECT 
				ca.nombre director
			FROM cart_peli_artista cpa
			INNER JOIN cart_artista ca ON (cpa.id_artista = ca.id_artista)
			WHERE cpa.id_tipo_artista =2 AND cpa.id_pelicula = " . (int) $id_film;
			$s = mysql_query($q);
			$total = mysql_num_rows($s);
			if($total) {
				$i=0;
				while($r = mysql_fetch_array($s)) {
					$i++;
					$directors .= $r[director];
					if($i < $total) $directors .= ', ';
				}
				
				return $directors;
			} else {
				return "";
			}
		}
		
	};
?>
S
