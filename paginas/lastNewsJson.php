<?php
	session_start();
	include("../configuracion.php");
	include("../clases/General.php");
	include("../funciones/conexion.php");
	conectar();

	$general = new General();
	$general->listarNewsHomeJson($_REQUEST[order]);
?>