<?php
	session_start();

	require_once("../configuracion.php");
	require_once("../funciones/conexion.php");
	require_once("../clases/Configuracion.php");
	
	conectar();
	$configuracion = new Configuracion();

	function checkEmailSession() {
		$error = false;

		if(isset($_SESSION[id_cuenta]) || $_SESSION[id_cuenta] > 0) $error = true;	
		
		$q = "SELECT * FROM invitaciones WHERE correo LIKE '" . trim(addslashes($_REQUEST[emregister])). "'";
		$s = mysql_query($q) or die(mysql_error());
		if(!mysql_num_rows($s)) $error = true;

		return $error;
	}
?>
<div id="registro-form">
<?php
	if(!checkEmailSession() || $_SESSION[nivelAcceso]==2) {
?>
		<h1>Register User</h1>
		<form name="fregister" method="post" action="">
			<div id="rechazado-nb"></div>
			<span>Name:</span> <span><input type="text" name="nombre"></span>
			<span>Last Name:</span> <span><input type="text" name="apellidos"></span>
			<span>Birthdate:</span> <span><?=$configuracion->inputsFecha('', '', '', $name="fecha_nac", true) ?></span>
			<span>Country</span> <span><?=$configuracion->retornarPaises($id_pais); ?></span>
			<span>E-mail:</span> <span><input type="text" name="email" value="<?=htmlentities($_REQUEST[emregister]) ?>"></span>
			<span>Retype E-mail:</span> <span><input type="text" name="email2"></span>
			<span>Password:</span> <span><input type="password" name="password"></span>
<?php
		if($_SESSION[nivelAcceso]!=2) {
?>
			<span>Terms of Use:</span> <span><input type="checkbox" name="conditions"> <a href="javascript:displayTermsAndContitions();" title="see details">see details...</a></span>
<?php
		}
?>
			<div id="bottom">
				<input type="button" value="Register..." onclick="registerUserAcceptInvitation(document.fregister);">
				<input type="button" value="Not now, later..." onclick="window.location='?id_pagina=1'">			
			</div>
		</form>
<?php
	} else {
		echo '<div id="rechazado-nb">
			<h1>Register</h1>
			You can not do this operation. Your e-mail is not the correct or you are trying a forbidden operation.
			<div style="text-align:center;margin-top:20px;"><input type="button" value="OK" onclick="window.location=\'?id_pagina=1\'"></div>		
		</div>';
	}
?>

	
</div>
