<?php
	if($_SESSION[id_cuenta] > 0 && !empty($_SESSION[usuario])) {
		require_once("inicio_logeado.php");
	} else {
		require_once("inicio_deslogeado.php");
	}
?>
