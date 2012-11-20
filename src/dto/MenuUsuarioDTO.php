<?php
class MenuUsuarioDTO {
	private $id_usuario;
	private $id_menu;
	private $nivel_acceso;
		
	/**
	 * @return the $nivel_acceso
	 */
	public function getNivel_acceso() {
		return $this->nivel_acceso;
	}

	/**
	 * @param field_type $nivel_acceso
	 */
	public function setNivel_acceso($nivel_acceso) {
		$this->nivel_acceso = $nivel_acceso;
	}

	/**
	 * @return the $id_usuario
	 */
	public function getId_usuario() {
		return $this->id_usuario;
	}

	/**
	 * @return the $id_menu
	 */
	public function getId_menu() {
		return $this->id_menu;
	}

	/**
	 * @param field_type $id_usuario
	 */
	public function setId_usuario($id_usuario) {
		$this->id_usuario = $id_usuario;
	}

	/**
	 * @param field_type $id_menu
	 */
	public function setId_menu($id_menu) {
		$this->id_menu = $id_menu;
	}

	
	
}
?>