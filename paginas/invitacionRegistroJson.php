<?php
	session_start();

	include("../configuracion.php");
	include("../clases/General.php");
	include("../funciones/conexion.php");
	conectar();

	function checkEmailSession() {
		$error = false;

		if(isset($_SESSION[id_cuenta]) || $_SESSION[id_cuenta] > 0) $error = true;	
		
		$q = "SELECT * FROM invitaciones WHERE correo LIKE '" . trim(addslashes($_REQUEST[email])). "'";
		$s = mysql_query($q) or die(mysql_error());
		if(!mysql_num_rows($s)) $error = true;

		return $error;
	}

	/* envio de correo electronico de registro */
	function enviarCorreoDeRegistro() {
		global $aceptado, $rechazado, $MAIL_GLOBAL_PRUEBAS;
		
		require("../clases/PHPMailer/class.phpmailer.php");
		
		$mail = new phpmailer();
		$mail->From     = "noreply@wiki-global.com";
		$mail->FromName = "Wiki-Global";
		$mail->Host     = "mail.wiki-global.com";
		$mail->Mailer   = "smtp";
		$mail->Username = "noreply@wiki-global.com";
		$mail->Password = "1585602106";
		$mail->WordWrap = 50;
		$mail->SMTPAuth = true;
		$mail->IsHTML(true);
		$mail->SetLanguage("es", "clases/PHPMailer/language/");
		$mail->Subject = "Register Confirmation";
				
		/* asigno los datos de nombre y apellidos si no vienen vacios */
		$destinatario = $_REQUEST[email];
				
		/* asigno el mail de la invitacion */
		$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : $_REQUEST[email];
				
		$body .= "Dear $_REQUEST[nombre] $_REQUEST[apellidos],<br><br>";
		$body .= "Welcome to Wiki-Global.com. All your information about inscription was processed successfully.<br>";
		$body .= "<br>";
		$body .= "Look the Terms of Use of the Website:<br>";
		$body .= "<a href=\"http://www.wiki-global.com/?id_pagina=7\">http://www.wiki-global.com/?id_pagina=7</a><br>";
		$body .= "<br>";
		$body .= "Login in Wiki-global.com:<br>";
		$body .= "<a href=\"http://www.wiki-global.com\">http://www.wiki-global.com</a><br>";
		$body .= "<br>";
		$body .= "Your login information:<br>";
		$body .= "User name is <b>$_REQUEST[email]</b><br>";
		$body .= "Password is <b>$_REQUEST[password]</b><br>";
		$body .= "<br>";
		$body .= "Welcome to our Community!<br>";
		$body .= "<b>The <a href=\"http://www.wiki-global.com\">wiki-global.com</a> Team</b><br>";
		$body .= "<a href=\"http://www.wiki-global.com\">www.wiki-global.com</a><br>";
				
		$mail-> Body = $body;
		
		$mail->AddAddress($email, $destinatario);
		
		if(!$mail->Send()) {
			echo "An error wass ocurred registering your user. " . $mail->ErrorInfo . "<br>";
			return false;
		} else {
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			return true;
		}
	}

	function doRegisterUser() {
		global $rechazado;

		$error = '';
		$arr = array();

		$q = "SELECT * FROM cuentas WHERE email LIKE '" . trim($_REQUEST[email]). "'";
		$s = mysql_query($q) or die(mysql_error());
		if(mysql_num_rows($s)) {
			$arr = array("error" => 'The e-mail account exist. Please try with other account or try recovering your password.');
		} else {
			if(!checkEmailSession() || $_SESSION[nivelAcceso]==2) {
				if(enviarCorreoDeRegistro()) {
					$usuario_semilla = ($_SESSION[nivelAcceso]==2) ? 1 : 0;
					$q = "INSERT INTO cuentas (fecha_registro, usuario_semilla, usuario, nombres, apellidos, fnacimiento, email, password, id_pais) VALUES ('" . mktime() . "', '$usuario_semilla', '$_REQUEST[email]', '" . htmlentities(addslashes($_REQUEST[nombre]))."', '" . htmlentities(addslashes($_REQUEST[apellidos])) . "', '$_REQUEST[fecha_nac]', '$_REQUEST[email]', '$_REQUEST[password]', '$_REQUEST[id_pais]')";
					if(mysql_query($q)) {
						if($_SESSION[nivelAcceso]==2) {
							$id_usuario_registrado = mysql_insert_id();
							$q = "INSERT INTO compras (fecha_compra, id_persona, fecha_inicio, fecha_vencimiento, fecha_pago, id_producto, estado) VALUES ('" . date('Y-m-d') . "', '$id_usuario_registrado', '" . date('Y-m-d') . "', '" . (date('Y')+1) . "-" . date('m-d') . "', '" . date('Y-m-d') . "', '1', 'habilitada');";
							
							if(mysql_query($q)) {
								$arr = array("msga" => 'An e-mail was sent to the user. The information has been stored successful. And a Purchase has been created.');
							}							
						} else {
							$arr = array("msg" => 'An e-mail was sent you. Your information has been stored successful. Now you can sign up...');
						}					
					}
				} else {
					$arr = array("error" => 'An error wass ocurred registering your user. E-mail of register was not send. Please, try again in a few minutes.');
				}
			} else {
				$arr = array("error" => 'Your email account have not invitation or operation that you are trying is forbidden.');
			}
		}

		echo json_encode($arr);
	}

	if(eregi("^register$", $_REQUEST[action])) doRegisterUser();
?>
