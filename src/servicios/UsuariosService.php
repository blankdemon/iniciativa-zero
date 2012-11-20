<?php
/*
 * Created on 18-11-2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 interface UsuariosService {
 	public function doIniciarSesion($usuario, $password);
 	public function doRegistrarUsuario($usuario);
 	public function doEliminarUsuario($id_usuario);
 	public function doActualizarUsuario($usuario);
 	public function getListadoUsuariosByCriterio($usuario);
 	public function getUsuarioById($id_usuario);
 	public function getUsuarioByLogin($login); 	 
};
?>
