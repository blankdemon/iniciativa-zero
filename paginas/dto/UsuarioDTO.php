<?php
/*
 * Created on 18-11-2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class UsuarioDTO {
 		private $id_usuario;
		private $fcreacion;
		private $usuario;
		private $password;
		private $id_nacionalidad;
		private $nombres;
		private $apellidos;
		private $fnacimiento;
	
	/**
	 * @return the $id_usuario
	 */
	public function getId_usuario() {
		return $this->id_usuario;
	}

		/**
	 * @return the $fcreacion
	 */
	public function getFcreacion() {
		return $this->fcreacion;
	}

		/**
	 * @return the $usuario
	 */
	public function getUsuario() {
		return $this->usuario;
	}

		/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

		/**
	 * @return the $id_nacionalidad
	 */
	public function getId_nacionalidad() {
		return $this->id_nacionalidad;
	}

		/**
	 * @return the $nombres
	 */
	public function getNombres() {
		return $this->nombres;
	}

		/**
	 * @return the $apellidos
	 */
	public function getApellidos() {
		return $this->apellidos;
	}

		/**
	 * @return the $fnacimiento
	 */
	public function getFnacimiento() {
		return $this->fnacimiento;
	}

		/**
	 * @param field_type $id_usuario
	 */
	public function setId_usuario($id_usuario) {
		$this->id_usuario = $id_usuario;
	}

		/**
	 * @param field_type $fcreacion
	 */
	public function setFcreacion($fcreacion) {
		$this->fcreacion = $fcreacion;
	}

		/**
	 * @param field_type $usuario
	 */
	public function setUsuario($usuario) {
		$this->usuario = $usuario;
	}

		/**
	 * @param field_type $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

		/**
	 * @param field_type $id_nacionalidad
	 */
	public function setId_nacionalidad($id_nacionalidad) {
		$this->id_nacionalidad = $id_nacionalidad;
	}

		/**
	 * @param field_type $nombres
	 */
	public function setNombres($nombres) {
		$this->nombres = $nombres;
	}

		/**
	 * @param field_type $apellidos
	 */
	public function setApellidos($apellidos) {
		$this->apellidos = $apellidos;
	}

		/**
	 * @param field_type $fnacimiento
	 */
	public function setFnacimiento($fnacimiento) {
		$this->fnacimiento = $fnacimiento;
	}

		
		
 	};
?>
