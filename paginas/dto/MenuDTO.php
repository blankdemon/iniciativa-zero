<?php
class MenuDTO {
	private $id_menu;
	private $nivelAcceso;
	private $descripcion;
	private $nombrePagina;


	/**
	 * @return the $id_menu
	 */
	public function getId_menu() {
		return $this->id_menu;
	}

	/**
	 * @param field_type $id_menu
	 */
	public function setId_menu($id_menu) {
		$this->id_menu = $id_menu;
	}
	
	/**
	 * @return the $nivelAcceso
	 */
	public function getNivelAcceso() {
		return $this->nivelAcceso;
	}

	/**
	 * @return the $descripcion
	 */
	public function getDescripcion() {
		return $this->descripcion;
	}

	/**
	 * @return the $nombrePagina
	 */
	public function getNombrePagina() {
		return $this->nombrePagina;
	}

	/**
	 * @param field_type $nivelAcceso
	 */
	public function setNivelAcceso($nivelAcceso) {
		$this->nivelAcceso = $nivelAcceso;
	}

	/**
	 * @param field_type $descripcion
	 */
	public function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

	/**
	 * @param field_type $nombrePagina
	 */
	public function setNombrePagina($nombrePagina) {
		$this->nombrePagina = $nombrePagina;
	}


		
}
?>