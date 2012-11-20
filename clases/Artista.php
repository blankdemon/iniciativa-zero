<?php
	require("classes/dto/ArtistDTO.php");

	class Artista {
		
		function getArtistById($id_artista=0) {
			ArtistDTO artist = new ArtistDTO();
			
			$q = "SELECT 
				id_artista, tipo_artista, nombre, biografia 
			FROM cart_artista
			WHERE id_artista = " . (int) $id_artista;
			$s = mysql_query($q);
			$r = mysql_fetch_array($s);
			
			return $artist;
		}
		
		function getArtistsFromFilmByIdFilm($id_artista=0) {
			ArtistDTO artist = new ArtistDTO();
			
			$q = "SELECT
				ca.id_artista, ca.tipo_artista, ca.nombre, ca.biografia 
			FROM cart_artista ca
			INNER JOIN cart_peli_artista cpa ON (ca.id_artista = cpa.id_artista)
			WHERE id_pelicula = " . (int) $id_pelicula;
			$s = mysql_query($q);
			while($r = mysql_fetch_array($s)) {
				
			}
			
			return $artist;
		}
		
		function getArtistAll() {
			$s = mysql_query();
			
			return tipoArtista;
		}
	};
?>
