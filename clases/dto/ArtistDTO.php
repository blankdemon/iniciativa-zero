<?php
	class ArtistDTO {
		private id_artista;
		private tipo_artista;
		private nombre;
		private biografia;
		
		public setId_artista($id_artista) {
			$this->id_artista = $id_artista;
		}
		
		public getId_artista() {
			return $this->id_artista;
		}
		
		public setTipo_artista($tipo_artista) {
			$this->tipo_artista = $tipo_artista;
		}
		
		public getTipo_artista() {
			return $this->tipo_artista;
		}
		
		public setNombre($nombre) {
			$this->nombre = $nombre;
		}
		
		public getNombre() {
			return $this->nombre;
		}
		
		public setBiografia($biografia) {
			$this->biografia = $biografia;
		}
		
		public getBiografia() {
			return $this->biografia;
		}
	}
?>
