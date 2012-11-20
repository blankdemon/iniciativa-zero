<?php
	@ini_set('session.bug_compat_warn', '0');
	@ini_set('error_reporting', '2039');
	@ini_set("session.use_only_cookies","1");
	@ini_set("session.use_trans_sid","0"); 

	$logout = $_POST["logout"];

	if (isset($logout)) {
		session_start;
		session_destroy();
		session_unset();
		$_SESSION = array();
		unset($_SESSION["usuario"]);
		unset($_SESSION["permisos"]);
		//header()
	}
?>
