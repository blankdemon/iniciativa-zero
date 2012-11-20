<?php
	session_start();
	include("../configuracion.php");
	include("../funciones/conexion.php");
	conectar();

	function enviarCorreoDeRecuperacion() {
		require("../clases/PHPMailer/class.phpmailer.php");
		
		if(empty($_REQUEST[email])) $arr = array("rechazado" => "Please enter your email. The email recovery has not been sent.");
		
		if(!isset($arr)) {
			$q = "SELECT * FROM cuentas WHERE email LIKE '" . addslashes($_REQUEST[email]) . "'";
			$s = mysql_query($q);
			if(mysql_num_rows($s)) {
				/* envio de correos electronicos */
				$mail = new phpmailer();
				$mail->From     = "invitaciones@wiki-global.com";
				$mail->FromName = "Wiki-Global.com";
				$mail->Host     = "mail.wiki-global.com";
				$mail->Mailer   = "smtp";
				$mail->Username = "invitaciones@wiki-global.com";
				$mail->Password = "54712548";
				$mail->WordWrap = 50;
				$mail->SMTPAuth = true;
				$mail->IsHTML(true);
				$mail->SetLanguage("es", "clases/PHPMailer/language/");
				$mail->Subject = "Password notification from Wiki-Global.com";
		
				/* asigno el mail de la recuperacion */
				$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : trim($_REQUEST[email]);


				/* asigno los datos de nombre y apellidos si no vienen vacios */
				$destinatario = trim($_REQUEST[email]);
				$nueva_password = substr(md5(mktime()), 0, 8);
			
				/* asigno el mail de la invitacion */
				$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : trim($_REQUEST[email]);
			
				$body .= "Dear User,<br><br>";
				$body .= "Attached is your new password. Remember your username is your email.<br>";
				$body .= "<br>";
				$body .= "Your username is <b>$_REQUEST[email]</b><br>";
				$body .= "Your new password is <b>$nueva_password</b><br>";
				$body .= "<br>";
				$body .= "<br>";
				$body .= "Welcome back at our Comunity!<br>";
				$body .= "<b><a href=\"http://www.wiki-global.com\">Wiki-Global.com</a> Team</b><br>";
				$body .= "<a href=\"http://www.wiki-global.com\">www.wiki-global.com</a><br>";
				$body .= "<br>";
		
				$mail->Body = $body;
				$mail->AddAddress($email, $destinatario);
		
				if(!$mail->Send()) {
					$arr = array("rechazado" => "An error has been detected while trying to send you a mail recovery.</b><br> " . $mail->ErrorInfo . "<br>");
				} else {
					$query = "UPDATE cuentas SET password='$nueva_password' WHERE usuario='$email'";
					$result = mysql_query($query) or die(mysql_error());
					$arr = array("msg" => "The email recovery password has been sent you successfully with a new password generated automatically.");
				}
				$mail->ClearAddresses();
				$mail->ClearAttachments();
			} else {
				$arr = array("rechazado" => "Your e-mail is not registered with us. Try with another.");
			}
		}
		
		// codifica en envio
		echo json_encode($arr);
	}
	
	// envio de correo para recuperar password
	enviarCorreoDeRecuperacion();
?>
