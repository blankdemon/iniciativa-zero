<?php
	function recuperarPassword() {
		global $error, $enviado, $MAIL_GLOBAL_PRUEBAS;
		if(empty($_REQUEST[email])) {
			$error = 'Correo el&eacute;ctronico no ingresado.';
			return;
		}
		
		
		$dest_enviados = array();
		$dest_noenviados = array();
		
		require("clases/PHPMailer/class.phpmailer.php");
		
		$query = "SELECT * FROM cuentas WHERE email LIKE '" . htmlspecialchars($_REQUEST[email]) . "'";
		$result = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($result) > 0) {				
			/* envio de correos electronicos */
			$mail = new phpmailer();
			$mail->From     = "registro@wikiglobal.com";
			$mail->FromName = "WikiGlobal";
			$mail->Host     = "mail.oppici.cl";
			$mail->Mailer   = "smtp";
			$mail->Username = "csolis@oppici.cl";
			$mail->Password = "1585602106kj6m";
			$mail->WordWrap = 50;
			$mail->SMTPAuth = true;
			$mail->IsHTML(true);
			$mail->SetLanguage("es", "clases/PHPMailer/language/");
			$mail->Subject = "Recuperacion de Password";
			
			/* asigno los datos de nombre y apellidos si no vienen vacios */
			$destinatario = $_REQUEST[correo];
			$nueva_password = substr(md5(mktime()), 0, 8);
			
			/* asigno el mail de la invitacion */
			$email = !empty($MAIL_GLOBAL_PRUEBAS) ? $MAIL_GLOBAL_PRUEBAS : trim($_REQUEST[correo]);
			
			$body .= "Estimado Usuario,<br><br>";
			$body .= "Adjuntamos tu nueva password. Debemos recordarte que tu nombre de usuario es tu email.<br>";
			$body .= "<br>";
			$body .= "Su nombre de usuario es <b>$_REQUEST[correo]</b><br>";
			$body .= "Su clave de acceso es <b>$nueva_password</b><br>";
			$body .= "<br>";
			
			$mail-> Body = $body;
			
			$mail->AddAddress($email, $destinatario);
			
			if(!$mail->Send()) {
			  $error = "Ha ocurrido un error enviando su nueva password.</b><br> " . $mail->ErrorInfo . "<br>";
			} else {
				$query = "UPDATE cuentas SET password='$nueva_password' WHERE usuario='$email'";
				$result = mysql_query($query) or die(mysql_error());
				$enviado = "Se ha generado una nueva clave de acceso y se te ha enviado un correo.";
			}
			
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		} else {
			$error = "Tu correo electronico no se encuentra en nuestros registros.";
		}
	}
	
	if(isset($_REQUEST[recuperarPassword])) recuperarPassword();
?>