<?php
/*
 * Created on 18-11-2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

	require_once 'paginas/dto/UsuarioDTO.php';
 
 	class UsuariosServiceImpl implements UsuariosService {
 		public function doIniciarSesion($usuario, $password) {
 			
 		}
 		
	 	public function doRegistrarUsuario($usuario) {
	 		$usuario->getId_usuario();
	 		$usuario->getFcreacion();
	 		$usuario->getUsuario();
	 		$usuario->getPassword();
	 		$usuario->getId_nacionalidad();
	 		$usuario->getNombres();
	 		$usuario->getApellidos();
	 		$usuario->getFnacimiento();
	 	}
	 	
	 	public function doEliminarUsuario($usuario) {
	 		$usuario->getId_usuario();
	 		$usuario->getFcreacion();
	 		$usuario->getUsuario();	 		
	 	}
	 	
	 	public function doActualizarUsuario($usuario) {
	 		$usuario->getId_usuario();
	 		$usuario->getFcreacion();
	 		$usuario->getUsuario();
	 		$usuario->getPassword();
	 		$usuario->getId_nacionalidad();
	 		$usuario->getNombres();
	 		$usuario->getApellidos();
	 		$usuario->getFnacimiento();
	 	}
	 	
	 	public function getListadoUsuariosByCriterio($usuario) {
	 		$usuario->getId_usuario();
	 		$usuario->getFcreacion();
	 		$usuario->getUsuario();
	 		$usuario->getPassword();
	 		$usuario->getId_nacionalidad();
	 		$usuario->getNombres();
	 		$usuario->getApellidos();
	 		$usuario->getFnacimiento();
	 	}
	 	
	 	public function getUsuarioById($usuario) {
	 		$usuario->getId_usuario();
	 		$usuario->getFcreacion();
	 		$usuario->getUsuario();
	 		$usuario->getPassword();
	 		$usuario->getId_nacionalidad();
	 		$usuario->getNombres();
	 		$usuario->getApellidos();
	 		$usuario->getFnacimiento();
	 	}
	 	
	 	public function getUsuarioByLogin($usuario) {
	 		$usuario->getId_usuario();
	 		$usuario->getFcreacion();
	 		$usuario->getUsuario();
	 		$usuario->getPassword();
	 		$usuario->getId_nacionalidad();
	 		$usuario->getNombres();
	 		$usuario->getApellidos();
	 		$usuario->getFnacimiento();
	 	}
 	};
?>
